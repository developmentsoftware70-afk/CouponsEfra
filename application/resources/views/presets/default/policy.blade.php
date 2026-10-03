@extends($activeTemplate.'layouts.frontend')
@section('content')


<section class="policy-area py-20 bg-white">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col">
                <div class="policy-wrap">
                    <div class="wyg">
                        @php
                            echo $policy->data_values->details
                        @endphp
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('style')
<style>
    .wyg h1, h2, h3, h4{
        color:#383838;
    }
    .wyg strong{
        color:#383838
    }
    .wyg p{
        color: #666666
    }
    .wyg ul{
        margin-left: 40px
    }
    .wyg ul li{
        list-style-type: disc;
        color: #666666
    }
    .section-title{
        font-size: 30px;
        margin-bottom: 0;
    }
</style>
@endpush
