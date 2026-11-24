@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">

    {{-- SECTION 1: Interactive Postback URL Generator --}}
    <div class="col-12">
        <div class="card b-radius--10 shadow-sm border-0">
            <div class="card-header bg--primary text-white d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
                <h5 class="card-title text-white mb-0">
                    <i class="las la-link"></i> @lang('Postback URL Generator (Network Presets)')
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn--dark" data-bs-toggle="modal" data-bs-target="#testSimulatorModal">
                        <i class="las la-vial"></i> @lang('Test Postback Simulator')
                    </button>
                    <button type="button" class="btn btn-sm btn--info text-white" data-bs-toggle="modal" data-bs-target="#testApiSyncModal">
                        <i class="las la-sync"></i> @lang('Test API Sync Simulator')
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-end gy-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">@lang('Select Affiliate / Merchant Network')</label>
                        <select id="networkSelect" class="form-select form-control">
                            @foreach($presets as $key => $preset)
                                <option value="{{ $key }}" data-query="{{ $preset['query'] }}" data-desc="{{ $preset['description'] }}">
                                    {{ $preset['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <div id="networkDesc" class="form-text text-muted small mt-1">
                            {{ $presets['trackier']['description'] ?? '' }}
                        </div>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-bold">@lang('Generated Postback URL for Network')</label>
                        <div class="input-group">
                            <input type="text" id="generatedPostbackUrl" class="form-control bg--white font-monospace fs-13" readonly 
                                value="{{ $basePostbackUrl . $presets['trackier']['query'] }}">
                            <button type="button" class="btn btn--primary" id="btnCopyUrl" title="@lang('Copy to clipboard')">
                                <i class="las la-copy"></i> @lang('Copy URL')
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Macro Reference Guide --}}
                <div class="mt-4 p-3 bg-light rounded border">
                    <div class="fw-bold mb-2 text-dark fs-13">
                        <i class="las la-info-circle text--primary"></i> @lang('Supported Dynamic Macro Parameters & Automated Sync'):
                    </div>
                    <div class="row g-2 text-muted small">
                        <div class="col-md-4 col-sm-6">
                            <code>click_id / sub1 / subid</code> : @lang('Click tracking identifier')
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <code>amount / sale_amount</code> : @lang('Cart or purchase value')
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <code>payout / commission</code> : @lang('Publisher commission')
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <code>txn_id / order_id</code> : @lang('Merchant order ID')
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <code>status</code> : @lang('confirmed, pending, or cancelled')
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <code>token / secret</code> : @lang('Security validation key')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2: Saved Postbacks Configuration Table --}}
    <div class="col-12">
        <div class="card b-radius--10 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap gap-2">
                <h5 class="card-title mb-0 text--dark">
                    <i class="las la-list-ul"></i> @lang('Configured Postbacks & API Sync Settings')
                </h5>
                <button type="button" class="btn btn-sm btn--success" data-bs-toggle="modal" data-bs-target="#createPostbackModal">
                    <i class="las la-plus"></i> @lang('Create New Postback')
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table--light style--two text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>@lang('Sr. No.')</th>
                                <th>@lang('Campaign / Name')</th>
                                <th>@lang('Network')</th>
                                <th>@lang('Associated Store')</th>
                                <th>@lang('Postback URL')</th>
                                <th>@lang('API Sync')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($postbacks as $pb)
                            <tr>
                                <td>{{ $postbacks->firstItem() + $loop->index }}</td>
                                <td class="fw-bold text--dark">{{ $pb->name }}</td>
                                <td>
                                    <span class="badge badge--dark">{{ ucfirst($pb->network) }}</span>
                                </td>
                                <td>
                                    @if($pb->store)
                                        <span class="text--primary fw-semibold">{{ $pb->store->name }}</span>
                                    @else
                                        <span class="text-muted small">@lang('Global (All Stores)')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control form-control-sm font-monospace fs-11" style="width: 250px;" value="{{ $pb->postback_url }}" readonly>
                                        <button type="button" class="btn btn-sm btn-outline--primary py-1 px-2 copy-single-btn" data-url="{{ $pb->postback_url }}" title="@lang('Copy')">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    @if($pb->sync_enabled)
                                        <span class="badge badge--success"><i class="las la-sync"></i> @lang('Enabled')</span>
                                        @if($pb->last_sync_at)
                                            <div class="text-muted small fs-11 mt-1">{{ diffForHumans($pb->last_sync_at) }}</div>
                                        @endif
                                    @else
                                        <span class="badge badge--secondary">@lang('Disabled')</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pb->status == 1)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        {{-- Manual Sync Form Button --}}
                                        <form action="{{ route('admin.postback.sync', $pb->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn--info text-white" title="@lang('Trigger API Sync Now')">
                                                <i class="las la-sync"></i>
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn--primary editBtn" 
                                            data-id="{{ $pb->id }}"
                                            data-name="{{ $pb->name }}"
                                            data-network="{{ $pb->network }}"
                                            data-store_id="{{ $pb->store_id }}"
                                            data-secret_key="{{ $pb->secret_key }}"
                                            data-api_key="{{ $pb->api_key }}"
                                            data-api_endpoint="{{ $pb->api_endpoint }}"
                                            data-sync_enabled="{{ $pb->sync_enabled }}"
                                            data-status="{{ $pb->status }}"
                                            data-notes="{{ $pb->notes }}">
                                            <i class="la la-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn--danger deleteBtn" data-id="{{ $pb->id }}">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center py-4" colspan="100%">
                                    <i class="las la-info-circle fs-18"></i> @lang('No custom postback configured yet. Click "Create New Postback" to create one.')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($postbacks->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($postbacks) }}
            </div>
            @endif
        </div>
    </div>

    {{-- SECTION 3: Live Postback Conversions Feed --}}
    <div class="col-12">
        <div class="card b-radius--10 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                <h5 class="card-title mb-0 text--dark">
                    <i class="las la-history"></i> @lang('Recent Live Postback Conversions (Incoming Feed)')
                </h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn--outline-primary">
                    @lang('View All Orders') <i class="las la-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table--light style--two text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>@lang('Order ID')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Product / Campaign')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Payout')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Source')</th>
                                <th>@lang('Time')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <span class="fw-bold text--primary">#{{ $order->id }}</span>
                                    @if($order->order_number)
                                        <div class="text-muted small fs-11">{{ $order->order_number }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($order->user)
                                        <span class="fw-semibold">{{ $order->user->username }}</span>
                                    @else
                                        <span class="text-muted">#{{ $order->user_id }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;">
                                        {{ $order->product_name ?? 'Merchant Purchase' }}
                                    </div>
                                </td>
                                <td class="fw-bold">₹{{ number_format($order->amount, 2) }}</td>
                                <td class="text-success fw-bold">₹{{ number_format($order->payout_amount, 2) }}</td>
                                <td>
                                    @if($order->status == 'confirmed')
                                        <span class="badge badge--success">@lang('Confirmed')</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge badge--primary">@lang('Processing')</span>
                                    @elseif($order->status == 'pending')
                                        <span class="badge badge--warning">@lang('Pending')</span>
                                    @else
                                        <span class="badge badge--danger">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge--light border">{{ $order->source ?? 'affiliate' }}</span>
                                </td>
                                <td class="text-muted small">{{ showDateTime($order->created_at, 'd M Y, h:i A') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center py-4" colspan="100%">
                                    @lang('No live postback conversions recorded yet.')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 4: Automated API Sync Logs (Cron & Manual) --}}
    <div class="col-12">
        <div class="card b-radius--10 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                <h5 class="card-title mb-0 text--dark">
                    <i class="las la-sync text--info"></i> @lang('Automated Network API Sync Logs (Cron & Backup Recovery)')
                </h5>
                <span class="badge badge--info py-1 px-2 text-white">@lang('Hourly Cron Scheduled')</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table--light style--two text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>@lang('Network')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Total Synced')</th>
                                <th>@lang('Updated (Pending ➔ Confirmed)')</th>
                                <th>@lang('Recovered (Backup Sync)')</th>
                                <th>@lang('Sync Message')</th>
                                <th>@lang('Time')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($syncLogs as $log)
                            <tr>
                                <td class="fw-bold">{{ ucfirst($log->network) }}</td>
                                <td>
                                    @if($log->status === 'success')
                                        <span class="badge badge--success">@lang('Success')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('Failed')</span>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $log->synced_count }}</td>
                                <td><span class="badge badge--primary">{{ $log->updated_count }}</span></td>
                                <td><span class="badge badge--warning text-dark">{{ $log->created_count }}</span></td>
                                <td>
                                    <div class="text-truncate" style="max-width: 320px;" title="{{ $log->message }}">
                                        {{ $log->message }}
                                    </div>
                                </td>
                                <td class="text-muted small">{{ showDateTime($log->created_at, 'd M Y, h:i A') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center py-4" colspan="100%">
                                    @lang('No API sync runs recorded yet. Click "Test API Sync Simulator" or "Sync Now" to run.')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL 1: Create Postback Modal --}}
<div class="modal fade" id="createPostbackModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form action="{{ route('admin.postback.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">@lang('Create New Postback & API Sync Configuration')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Configuration / Campaign Name') <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Trackier Main Postback" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Affiliate Network') <span class="text-danger">*</span></label>
                        <select name="network" class="form-select form-control" required>
                            @foreach($presets as $key => $preset)
                                <option value="{{ $key }}">{{ $preset['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Associated Store (Optional)')</label>
                        <select name="store_id" class="form-select form-control">
                            <option value="">@lang('-- Global (Applicable to all stores) --')</option>
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Postback Secret Key / Token (Optional)')</label>
                        <input type="text" name="secret_key" class="form-control" placeholder="For postback security validation">
                    </div>

                    {{-- API Sync Configuration --}}
                    <div class="col-12 border-top pt-3 mt-3">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="sync_enabled" value="1" id="create_sync_enabled" checked>
                            <label class="form-check-label fw-bold text-dark" for="create_sync_enabled">
                                @lang('Enable Background API Sync (Periodic Status Updates & Backup Recovery)')
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Network API Key / Bearer Token')</label>
                        <input type="text" name="api_key" class="form-control" placeholder="Optional for automated API sync">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Custom API Endpoint URL (Optional)')</label>
                        <input type="text" name="api_endpoint" class="form-control" placeholder="Leave empty for official network endpoint">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">@lang('Notes / Description')</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Optional notes for this network setup"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                <button type="submit" class="btn btn--primary">@lang('Save Configuration')</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: Edit Postback Modal --}}
<div class="modal fade" id="editPostbackModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form id="editPostbackForm" action="" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">@lang('Edit Postback & API Sync Configuration')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Configuration / Campaign Name') <span class="text-danger">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Affiliate Network') <span class="text-danger">*</span></label>
                        <select id="edit_network" name="network" class="form-select form-control" required>
                            @foreach($presets as $key => $preset)
                                <option value="{{ $key }}">{{ $preset['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Associated Store')</label>
                        <select id="edit_store_id" name="store_id" class="form-select form-control">
                            <option value="">@lang('-- Global (Applicable to all stores) --')</option>
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Postback Secret Key / Token')</label>
                        <input type="text" id="edit_secret_key" name="secret_key" class="form-control">
                    </div>

                    {{-- API Sync Configuration --}}
                    <div class="col-12 border-top pt-3 mt-3">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="sync_enabled" value="1" id="edit_sync_enabled">
                            <label class="form-check-label fw-bold text-dark" for="edit_sync_enabled">
                                @lang('Enable Background API Sync (Periodic Status Updates & Backup Recovery)')
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Network API Key / Bearer Token')</label>
                        <input type="text" id="edit_api_key" name="api_key" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Custom API Endpoint URL')</label>
                        <input type="text" id="edit_api_endpoint" name="api_endpoint" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Status')</label>
                        <select id="edit_status" name="status" class="form-select form-control" required>
                            <option value="1">@lang('Active')</option>
                            <option value="0">@lang('Inactive')</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">@lang('Notes')</label>
                        <input type="text" id="edit_notes" name="notes" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                <button type="submit" class="btn btn--primary">@lang('Update Configuration')</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 3: Delete Confirmation Modal --}}
<div class="modal fade" id="deletePostbackModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="deletePostbackForm" action="" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">@lang('Delete Confirmation')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="las la-exclamation-triangle text-warning display-4"></i>
                <p class="mt-3 fs-15 text-dark">@lang('Are you sure you want to delete this postback configuration?')</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                <button type="submit" class="btn btn--danger">@lang('Yes, Delete')</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 4: Test Postback Simulator Modal --}}
<div class="modal fade" id="testSimulatorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg--primary text-white">
                <h5 class="modal-title text-white fw-bold">
                    <i class="las la-vial"></i> @lang('Test Postback Simulator')
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small">
                    @lang('Simulate an incoming real-time postback call right now to verify that your system records orders and updates accurately.')
                </p>
                <form id="simulatorForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">@lang('Click ID') <span class="text-danger">*</span></label>
                            <input type="text" id="sim_click_id" name="click_id" class="form-control" value="TEST_CLICK_{{ time() }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">@lang('Merchant Order / Txn ID') <span class="text-danger">*</span></label>
                            <input type="text" id="sim_order_id" name="order_id" class="form-control" value="TXN_{{ rand(100000, 999999) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">@lang('Sale Amount (INR)') <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="sim_sale_amount" name="sale_amount" class="form-control" value="1499.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">@lang('Payout / Commission (INR)') <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="sim_payout" name="payout" class="form-control" value="120.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">@lang('Status') <span class="text-danger">*</span></label>
                            <select id="sim_status" name="status" class="form-select form-control" required>
                                <option value="confirmed">@lang('Confirmed / Approved')</option>
                                <option value="pending">@lang('Pending / Processing')</option>
                                <option value="cancelled">@lang('Cancelled / Rejected')</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">@lang('Product / Campaign Name')</label>
                            <input type="text" id="sim_product_name" name="product_name" class="form-control" value="Simulator Test Purchase">
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" id="btnRunSimulator" class="btn btn--primary">
                            <i class="las la-paper-plane"></i> @lang('Send Test Postback')
                        </button>
                    </div>
                </form>

                {{-- Live Response Box --}}
                <div id="simResultBox" class="mt-4" style="display: none;">
                    <label class="fw-bold text-dark fs-13">@lang('Server Response'):</label>
                    <pre id="simResponseOutput" class="bg-dark text-success p-3 rounded font-monospace fs-12 mb-0" style="max-height: 200px; overflow-y: auto;"></pre>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 5: Test API Sync Simulator Modal --}}
<div class="modal fade" id="testApiSyncModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg--info text-white">
                <h5 class="modal-title text-white fw-bold">
                    <i class="las la-sync"></i> @lang('Test Affiliate API Sync Simulator (Conversions & Recovery)')
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small">
                    @lang('Simulate a periodic API fetch from an affiliate network. Test both cases: (1) Updating an existing Pending order to Confirmed, or (2) Recovering a missed order via Backup Sync!')
                </p>
                <form id="apiSyncSimulatorForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">@lang('Target Network Setting') <span class="text-danger">*</span></label>
                            <select name="setting_id" class="form-select form-control" required>
                                @foreach($postbacks as $pb)
                                    <option value="{{ $pb->id }}">{{ $pb->name }} ({{ ucfirst($pb->network) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">@lang('Click ID (Existing to update, or New to recover)') <span class="text-danger">*</span></label>
                            <input type="text" name="click_id" class="form-control" value="SYNC_CLICK_{{ time() }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">@lang('Synced Status') <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-control" required>
                                <option value="confirmed">@lang('Confirmed / Approved (Pending ➔ Confirmed)')</option>
                                <option value="cancelled">@lang('Cancelled / Rejected')</option>
                                <option value="pending">@lang('Pending')</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">@lang('Sale Amount (INR)') <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control" value="2499.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">@lang('Commission / Payout (INR)') <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="payout" class="form-control" value="180.00" required>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" id="btnRunApiSyncSimulator" class="btn btn--info text-white">
                            <i class="las la-sync"></i> @lang('Run Simulated API Sync')
                        </button>
                    </div>
                </form>

                {{-- Sync Simulator Response Box --}}
                <div id="apiSyncResultBox" class="mt-4" style="display: none;">
                    <label class="fw-bold text-dark fs-13">@lang('API Sync Engine Response'):</label>
                    <pre id="apiSyncResponseOutput" class="bg-dark text-info p-3 rounded font-monospace fs-12 mb-0" style="max-height: 200px; overflow-y: auto;"></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";

        var baseUrl = "{{ $basePostbackUrl }}";

        // Dynamic Network Preset URL change
        $('#networkSelect').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var query = selectedOption.data('query');
            var desc = selectedOption.data('desc');

            $('#generatedPostbackUrl').val(baseUrl + query);
            $('#networkDesc').text(desc);
        });

        function showNotification(type, message) {
            if (typeof Toast !== 'undefined' && Toast.fire) {
                Toast.fire({ icon: type, title: message });
            } else if (typeof notify === 'function') {
                notify(type, message);
            } else {
                alert(message);
            }
        }

        // Copy button in Generator
        $('#btnCopyUrl').on('click', function () {
            var input = document.getElementById('generatedPostbackUrl');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value).then(function () {
                showNotification('success', '@lang("Postback URL copied to clipboard!")');
            });
        });

        // Copy single postback URL
        $('.copy-single-btn').on('click', function () {
            var url = $(this).data('url');
            navigator.clipboard.writeText(url).then(function () {
                showNotification('success', '@lang("Postback URL copied to clipboard!")');
            });
        });

        // Edit button
        $('.editBtn').on('click', function () {
            var id = $(this).data('id');
            var modal = $('#editPostbackModal');
            modal.find('form').attr('action', "{{ url('admin/postbacks/update') }}/" + id);
            $('#edit_name').val($(this).data('name'));
            $('#edit_network').val($(this).data('network'));
            $('#edit_store_id').val($(this).data('store_id') || '');
            $('#edit_secret_key').val($(this).data('secret_key') || '');
            $('#edit_api_key').val($(this).data('api_key') || '');
            $('#edit_api_endpoint').val($(this).data('api_endpoint') || '');
            $('#edit_sync_enabled').prop('checked', $(this).data('sync_enabled') == 1);
            $('#edit_status').val($(this).data('status'));
            $('#edit_notes').val($(this).data('notes') || '');
            modal.modal('show');
        });

        // Delete button
        $('.deleteBtn').on('click', function () {
            var id = $(this).data('id');
            var modal = $('#deletePostbackModal');
            modal.find('form').attr('action', "{{ url('admin/postbacks/delete') }}/" + id);
            modal.modal('show');
        });

        // Test Simulator AJAX Form
        $('#simulatorForm').on('submit', function (e) {
            e.preventDefault();
            var btn = $('#btnRunSimulator');
            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Sending...');

            $.ajax({
                url: "{{ route('admin.postback.test') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    $('#simResultBox').show();
                    $('#simResponseOutput').text(JSON.stringify(res, null, 2));
                    showNotification('success', '@lang("Test postback executed successfully!")');
                },
                error: function (xhr) {
                    $('#simResultBox').show();
                    $('#simResponseOutput').text(xhr.responseText || 'Error occurred');
                    showNotification('error', '@lang("Test postback failed!")');
                },
                complete: function () {
                    btn.prop('disabled', false).html('<i class="las la-paper-plane"></i> @lang("Send Test Postback")');
                }
            });
        });

        // Test API Sync Simulator AJAX Form
        $('#apiSyncSimulatorForm').on('submit', function (e) {
            e.preventDefault();
            var btn = $('#btnRunApiSyncSimulator');
            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Syncing...');

            $.ajax({
                url: "{{ route('admin.postback.simulate.sync') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    $('#apiSyncResultBox').show();
                    $('#apiSyncResponseOutput').text(JSON.stringify(res, null, 2));
                    showNotification('success', res.result.message || '@lang("API Sync executed successfully!")');
                },
                error: function (xhr) {
                    $('#apiSyncResultBox').show();
                    $('#apiSyncResponseOutput').text(xhr.responseText || 'Error occurred during API sync');
                    showNotification('error', '@lang("API Sync simulation failed!")');
                },
                complete: function () {
                    btn.prop('disabled', false).html('<i class="las la-sync"></i> @lang("Run Simulated API Sync")');
                }
            });
        });

    })(jQuery);
</script>
@endpush
