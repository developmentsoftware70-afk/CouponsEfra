@php
    $languages = App\Models\Language::all();
    $user = auth()->user();
@endphp
<div class="row ">
    <div class="col-lg-12">
        <div class="dashboard-header-wrap d-flex justify-content-between">
            <div class="header-left d-flex align-items-center">
                <button> <i class="fas fa-bars dashboard-show-hide"></i></button>
                <h4 class="title-three mb-0"> {{__($pageTitle)}}</h4>
            </div>
            <div class="header-right">
                {{-- 
                <div class="item">
                    <div class="input-group">
                        <select class="form--control form-select f-control f-dropdown langSel"
                            aria-label="Default select example">
                            @foreach ($languages as $language)
                            <option value="{{ $language->code }}" @if (Session::get('lang')===$language->
                                code) selected @endif>
                                {{ __($language->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                 --}}
                <div class="item">
                    <div class="profile">
                        <a href="{{ route('user.profile.setting') }}">
                            <img src="{{ getImage(getFilePath('userProfile').'/'. @$user->image)  }}" alt="agent">
                        </a>
                    </div>
                </div>
                <div class="item">
                    <a class="btn btn--base btn--sm" href="{{ route('home') }}"><i class="fas fa-house-user"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ====== Responsive breadcrumb start ====== -->
<div class="row ">
    <div class="col-lg-12">
        <div class="dashboard-header-wrap breadcrumb">
            <div class="d-flex justify-content-between">
                <div class="header-left d-flex align-items-center">
                    <h4 class="title-three mb-0"> {{__($pageTitle)}}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ====== Responsive breadcrumb end ====== -->