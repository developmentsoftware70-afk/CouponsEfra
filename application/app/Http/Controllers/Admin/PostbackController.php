<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WebhookController;
use App\Models\PostbackSetting;
use App\Models\NetworkSyncLog;
use App\Models\Store;
use App\Models\Order;
use App\Models\Click;
use App\Services\AffiliateSyncService;
use Illuminate\Http\Request;

class PostbackController extends Controller
{
    /**
     * Display the Postback Manager page.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Postback Manager & API Sync Engine';
        $postbacks = PostbackSetting::with('store')->latest()->paginate(getPaginate());
        $stores    = Store::where('status', 1)->orderBy('name')->get();

        // Recent live postback conversions
        $recentOrders = Order::with('user')
            ->whereNotNull('click_id')
            ->latest()
            ->take(15)
            ->get();

        // Recent API Sync Logs
        $syncLogs = NetworkSyncLog::with('setting')->latest()->take(10)->get();

        // Network macro presets
        $presets = $this->getNetworkPresets();

        $basePostbackUrl = url('/affiliate/postback');

        return view('admin.postback.index', compact('pageTitle', 'postbacks', 'stores', 'recentOrders', 'syncLogs', 'presets', 'basePostbackUrl'));
    }

    /**
     * Store a new postback configuration.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'network'  => 'required|string|max:100',
            'store_id' => 'nullable|exists:stores,id',
            'notes'    => 'nullable|string|max:500',
        ]);

        $presets = $this->getNetworkPresets();
        $networkKey = strtolower($request->network);
        $macros = $presets[$networkKey]['query'] ?? '?click_id={click_id}&amount={sale_amount}&payout={payout}&status={status}&txn_id={order_id}';

        $postbackUrl = url('/affiliate/postback') . $macros;

        $postback = new PostbackSetting();
        $postback->name         = $request->name;
        $postback->network      = $request->network;
        $postback->store_id     = $request->store_id;
        $postback->secret_key   = $request->secret_key ?: null;
        $postback->api_key      = $request->api_key ?: null;
        $postback->api_secret   = $request->api_secret ?: null;
        $postback->api_endpoint = $request->api_endpoint ?: null;
        $postback->sync_enabled = $request->has('sync_enabled') ? 1 : 0;
        $postback->postback_url = $postbackUrl;
        $postback->status       = 1;
        $postback->notes        = $request->notes;
        $postback->save();

        $notify[] = ['success', 'Postback configuration created successfully!'];
        return back()->withNotify($notify);
    }

    /**
     * Update an existing postback configuration.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'network'  => 'required|string|max:100',
            'store_id' => 'nullable|exists:stores,id',
            'status'   => 'required|in:0,1',
            'notes'    => 'nullable|string|max:500',
        ]);

        $postback = PostbackSetting::findOrFail($id);

        $presets = $this->getNetworkPresets();
        $networkKey = strtolower($request->network);
        $macros = $presets[$networkKey]['query'] ?? '?click_id={click_id}&amount={sale_amount}&payout={payout}&status={status}&txn_id={order_id}';

        $postback->name         = $request->name;
        $postback->network      = $request->network;
        $postback->store_id     = $request->store_id;
        $postback->secret_key   = $request->secret_key ?: null;
        $postback->api_key      = $request->api_key ?: null;
        $postback->api_secret   = $request->api_secret ?: null;
        $postback->api_endpoint = $request->api_endpoint ?: null;
        $postback->sync_enabled = $request->has('sync_enabled') ? 1 : 0;
        $postback->postback_url = url('/affiliate/postback') . $macros;
        $postback->status       = (int) $request->status;
        $postback->notes        = $request->notes;
        $postback->save();

        $notify[] = ['success', 'Postback configuration updated successfully!'];
        return back()->withNotify($notify);
    }

    /**
     * Delete a postback configuration.
     */
    public function delete($id)
    {
        $postback = PostbackSetting::findOrFail($id);
        $postback->delete();

        $notify[] = ['success', 'Postback configuration deleted successfully!'];
        return back()->withNotify($notify);
    }

    /**
     * Manually trigger API Sync for a specific network.
     */
    public function manualSync(Request $request, $id, AffiliateSyncService $syncService)
    {
        $setting = PostbackSetting::findOrFail($id);
        $res = $syncService->syncNetwork($setting);

        $type = ($res['status'] === 'success') ? 'success' : 'error';
        $notify[] = [$type, $res['message']];
        return back()->withNotify($notify);
    }

    /**
     * Test simulator for API Sync (conversions status update & backup sync recovery).
     */
    public function simulateSync(Request $request, AffiliateSyncService $syncService)
    {
        $request->validate([
            'setting_id' => 'required|exists:postback_settings,id',
            'click_id'   => 'required|string',
            'status'     => 'required|string',
            'amount'     => 'required|numeric',
            'payout'     => 'required|numeric',
        ]);

        $setting = PostbackSetting::findOrFail($request->setting_id);
        $mockData = [[
            'click_id'     => $request->click_id,
            'txn_id'       => $request->txn_id ?: ('TXN_' . rand(100000, 999999)),
            'status'       => $request->status,
            'amount'       => (float) $request->amount,
            'payout'       => (float) $request->payout,
            'product_name' => $request->product_name ?: 'Simulated API Conversion',
        ]];

        $res = $syncService->syncNetwork($setting, $mockData);

        return response()->json([
            'success' => ($res['status'] === 'success'),
            'result'  => $res,
        ]);
    }

    /**
     * Test simulator endpoint for admin to trigger a test postback.
     */
    public function testSimulator(Request $request)
    {
        $request->validate([
            'click_id'     => 'required|string|max:100',
            'sale_amount'  => 'required|numeric|min:0',
            'payout'       => 'required|numeric|min:0',
            'order_id'     => 'required|string|max:100',
            'status'       => 'required|string|in:confirmed,pending,cancelled',
            'product_name' => 'nullable|string|max:255',
        ]);

        // Forward to WebhookController directly
        $webhookRequest = new Request([
            'click_id'      => $request->click_id,
            'amount'        => $request->sale_amount,
            'payout'        => $request->payout,
            'txn_id'        => $request->order_id,
            'status'        => $request->status,
            'product_name'  => $request->product_name ?: 'Test Postback Product',
            'detail'        => 'Simulated test postback fired from Admin Panel',
        ]);

        $webhookController = app(WebhookController::class);
        $response = $webhookController->handleMerchantCallback($webhookRequest);

        return response()->json([
            'success'  => true,
            'message'  => 'Test postback executed successfully!',
            'response' => json_decode($response->getContent(), true),
        ]);
    }

    /**
     * Network presets with exact macro parameter syntax.
     */
    public function getNetworkPresets(): array
    {
        return [
            'trackier' => [
                'name'        => 'Trackier',
                'description' => 'India\'s leading performance marketing platform',
                'query'       => '?click_id={click_id}&amount={sale_amount}&payout={payout}&status={status}&txn_id={txn_id}',
                'macros'      => [
                    'click_id' => '{click_id}',
                    'amount'   => '{sale_amount}',
                    'payout'   => '{payout}',
                    'status'   => '{status}',
                    'txn_id'   => '{txn_id}',
                ],
            ],
            'admitad' => [
                'name'        => 'Admitad',
                'description' => 'Global affiliate and publisher network',
                'query'       => '?click_id={subid}&amount={payment}&payout={tariff_rate}&status={status}&txn_id={order_id}',
                'macros'      => [
                    'click_id' => '{subid}',
                    'amount'   => '{payment}',
                    'payout'   => '{tariff_rate}',
                    'status'   => '{status}',
                    'txn_id'   => '{order_id}',
                ],
            ],
            'cuelinks' => [
                'name'        => 'Cuelinks',
                'description' => 'Leading 2-way content monetization network',
                'query'       => '?click_id={subid}&amount={sale_amount}&payout={commission}&status={status}&txn_id={transaction_id}',
                'macros'      => [
                    'click_id' => '{subid}',
                    'amount'   => '{sale_amount}',
                    'payout'   => '{commission}',
                    'status'   => '{status}',
                    'txn_id'   => '{transaction_id}',
                ],
            ],
            'impact' => [
                'name'        => 'Impact.com',
                'description' => 'Global partnership and affiliate management platform',
                'query'       => '?click_id={subid1}&amount={Amount}&payout={Payout}&status={State}&txn_id={ActionId}',
                'macros'      => [
                    'click_id' => '{subid1}',
                    'amount'   => '{Amount}',
                    'payout'   => '{Payout}',
                    'status'   => '{State}',
                    'txn_id'   => '{ActionId}',
                ],
            ],
            'vcommission' => [
                'name'        => 'vCommission',
                'description' => 'Top Indian affiliate marketing network',
                'query'       => '?click_id={aff_sub}&amount={sale_amount}&payout={payout}&status={status}&txn_id={order_id}',
                'macros'      => [
                    'click_id' => '{aff_sub}',
                    'amount'   => '{sale_amount}',
                    'payout'   => '{payout}',
                    'status'   => '{status}',
                    'txn_id'   => '{order_id}',
                ],
            ],
            'involve_asia' => [
                'name'        => 'Involve Asia',
                'description' => 'Southeast Asia e-commerce affiliate platform',
                'query'       => '?click_id={sub_id}&amount={sale_amount}&payout={payout}&status={status}&txn_id={order_id}',
                'macros'      => [
                    'click_id' => '{sub_id}',
                    'amount'   => '{sale_amount}',
                    'payout'   => '{payout}',
                    'status'   => '{status}',
                    'txn_id'   => '{order_id}',
                ],
            ],
            'custom' => [
                'name'        => 'Custom / Other Network',
                'description' => 'Custom postback parameters for any merchant or network',
                'query'       => '?click_id={click_id}&amount={sale_amount}&payout={payout}&status={status}&txn_id={order_id}',
                'macros'      => [
                    'click_id' => '{click_id}',
                    'amount'   => '{sale_amount}',
                    'payout'   => '{payout}',
                    'status'   => '{status}',
                    'txn_id'   => '{order_id}',
                ],
            ],
        ];
    }
}
