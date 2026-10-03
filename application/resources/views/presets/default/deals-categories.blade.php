@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="top-collections top-stores second py-20">
    <div class="container-fluid container-custom">
        <div class="row justify-content-center justify-content-sm-start cus-row mx-0 g-3">
            @foreach($dealCategories as $index => $item)
            <div class="col-7 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="single-box h-100 transition position-relative text-center gap-3 gap-md-4 p1-2nd-bg-color cus-border border b-eighth p-3 p-md-4 rounded-3  wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                    <div class="d-center thumb-area rounded-circle deal_store_area">
                        <img class="rounded-circle" src="{{ getImage(getFilePath('dealCategory') . '/' . $item->image) }}" alt="Image">
                    </div>
                    <div class="d-grid gap-2 text-center">
                        <h4 class="n15-color text-nowrap fw-bold">
                            <a href="{{route("category.deals", $item->id)}}" class="title-cat">
                                {{ Str::limit(__($item->title), 20) }}
                            </a>
                        </h4>
                        <span class="n15-color fs-eight">{{$item->deals->count()}} @lang('deals')</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row py-4">
            @if ($dealCategories->hasPages())
                <div class="py-4">
                    {{ paginateLinks($dealCategories) }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
