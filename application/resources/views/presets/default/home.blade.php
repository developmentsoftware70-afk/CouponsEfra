@extends($activeTemplate.'layouts.frontend')
@section('content')
    @php
        $referByHomePage = session()->get('reference');
    @endphp

    {{--@if(($referByHomePage && $referByHomePage == 1) || (!$referByHomePage && gs()->homepage == 1 && $slider->isNotEmpty()))
    @endif
    --}}
    <section class="full-width-slider">
        @foreach($slider as $item)
        <div class="slider-item">
            <a href="{{ $item->link }}" target="_blank">
                <img src="{{ getImage(getFilePath('slider') . '/' . $item->image) }}" alt="{{ $item->title ?? 'Slider Image' }}" class="img-fluid">
            </a>
        </div>
        @endforeach
    </section>
    
    
    @if($sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif

@endsection
