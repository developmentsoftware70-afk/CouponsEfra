<style>
    /* SweetAlert2 Toast Global Styles & Header Overlap Fix */
    .swal2-container {
        z-index: 2147483647 !important; /* Force on top of header (which has z-index 9999999) */
        pointer-events: none;
    }
    .swal2-container .swal2-popup {
        pointer-events: auto;
    }
    .swal2-container.swal2-top-right,
    .swal2-container.swal2-top-end,
    .swal2-container.swal2-top {
        top: 25px !important;
        right: 25px !important;
    }
    .colored-toast {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.28), 0 4px 10px rgba(0, 0, 0, 0.12) !important;
        border-radius: 12px !important;
        padding: 14px 18px !important;
        min-width: 300px !important;
        max-width: 440px !important;
    }
    .colored-toast.swal2-icon-success {
        background-color: #059669 !important;
    }
    .colored-toast.swal2-icon-error {
        background-color: #dc2626 !important;
    }
    .colored-toast.swal2-icon-warning {
        background-color: #d97706 !important;
    }
    .colored-toast.swal2-icon-info {
        background-color: #2563eb !important;
    }
    .colored-toast .swal2-title {
        color: #ffffff !important;
        font-size: 14.5px !important;
        font-weight: 600 !important;
        margin: 0 !important;
        line-height: 1.4 !important;
    }
    .colored-toast .swal2-html-container {
        color: #ffffff !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        margin: 4px 0 0 0 !important;
        text-align: left !important;
        line-height: 1.5 !important;
    }
    .colored-toast .swal2-icon {
        border-color: #ffffff !important;
        color: #ffffff !important;
    }
    .colored-toast .swal2-close {
        color: #ffffff !important;
    }

    /* Bootstrap Modal z-index above header fix */
    .modal {
        z-index: 10000005 !important;
    }
    .modal-backdrop {
        z-index: 10000000 !important;
    }
</style>

<script src="{{ asset('assets/common/js/sweetalert2.min.js') }}"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        customClass: {
            popup: 'colored-toast'
        },
        showConfirmButton: false,
        timer: 6000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
</script>

@if(session()->has('notify'))
@foreach(session('notify') as $msg)
<script>
    Toast.fire({
        icon: '{{ $msg[0] }}',
        title: '{{ __($msg[1]) }}'
    })
</script>
@endforeach
@endif

@if (isset($errors) && $errors->any())
@php
$collection = collect($errors->all());
$errors = $collection->unique();
@endphp

<script>
    "use strict";
    @if($errors->count() > 1)
        Toast.fire({
            icon: 'error',
            html: '{!! addslashes(implode("<br>", $errors->map(function($e) { return "• " . __($e); })->toArray())) !!}'
        });
    @else
        Toast.fire({
            icon: 'error',
            title: '{{ __($errors->first()) }}'
        });
    @endif
</script>
@endif