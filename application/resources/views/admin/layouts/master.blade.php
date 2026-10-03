<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->siteName($pageTitle ?? '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{getImage(getFilePath('logoIcon') .'/favicon.png')}}">
    <link href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/common/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-toggle.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/common/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/common/css/line-awesome.min.css')}}">

    @stack('style-lib')

    <link rel="stylesheet" href="{{asset('assets/admin/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/admin.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/custom-style.css')}}">


    @stack('style')
</head>

<body>
    @yield('content')




    <script src="{{asset('assets/common/js/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/common/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/bootstrap-toggle.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/jquery.slimscroll.min.js')}}"></script>



    @include('includes.notify')
    @stack('script-lib')


    <script src="{{asset('assets/admin/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/admin.js')}}"></script>
    <script src="{{ asset('assets/common/js/ckeditor.js') }}"></script>
    {{-- LOAD EDITOR --}}
    <script>
    "use strict";

    // 1) A tiny upload adapter that POSTs file + CSRF to your endpoint
    class LaravelUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file.then(file => {
        const data = new FormData();
        data.append('upload', file);
        data.append('_token', '{{ csrf_token() }}'); // important

        return fetch("{{ route('admin.ckeditor.upload') }}", {
            method: "POST",
            body: data
        })
        .then(resp => resp.json())
        .then(res => {
            // Your controller returns { url: "..." }
            // CKEditor expects { default: "..." }
            if (res && res.url) {
            return { default: res.url };
            }
            // If your controller returns an error shape:
            const msg = (res && res.error && res.error.message) ? res.error.message : 'Upload failed';
            return Promise.reject(msg);
        });
        });
    }

    abort() { /* optional */ }
    }

    // 2) Plugin wrapper that registers the adapter with FileRepository
    function LaravelUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new LaravelUploadAdapter(loader);
    };
    }

    // 3) Create the editor and enable the plugin
    if (document.querySelector('.trumEdit')) {
    ClassicEditor
        .create(document.querySelector('.trumEdit'), {
        extraPlugins: [ LaravelUploadAdapterPlugin ],
        // (optional) limit accepted file types in the UI
        image: { upload: { types: [ 'jpeg', 'jpg', 'png', 'gif', 'webp', 'svg' ] } }
        })
        .then(editor => window.editor = editor)
        .catch(console.error);
    }
    </script>
    @stack('script')


</body>

</html>