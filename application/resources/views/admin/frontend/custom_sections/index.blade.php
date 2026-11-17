@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-0">@lang('Custom Homepage Sections')</h5>
                    <small class="text-muted">@lang('Create dynamic sections from here and drag-and-drop them into your Home Page via Page Builder')</small>
                </div>
                <button type="button" class="btn btn-sm btn--primary" data-bs-toggle="modal" data-bs-target="#createSectionModal">
                    <i class="las la-plus"></i> @lang('Create New Section')
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Section Name')</th>
                                <th>@lang('Key / Slug')</th>
                                <th>@lang('Layout Type')</th>
                                <th>@lang('Heading')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customSections as $item)
                                <tr>
                                    <td>
                                        <strong class="text-dark">{{ $item->name }}</strong>
                                    </td>
                                    <td>
                                        <code>{{ $item->key }}</code>
                                    </td>
                                    <td>
                                        @if($item->layout_type == 'card_grid')
                                            <span class="badge badge--primary">@lang('Card Grid (Deals/Brands)')</span>
                                        @elseif($item->layout_type == 'banner_slider')
                                            <span class="badge badge--info">@lang('Banner Slider')</span>
                                        @else
                                            <span class="badge badge--warning">@lang('Promo Box')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span>{{ $item->heading }}</span>
                                        @if($item->subheading)
                                            <br><small class="text-muted">{{ $item->subheading }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 1)
                                            <span class="badge badge--success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            {{-- Manage Content & Items --}}
                                            <a href="{{ route('admin.frontend.sections', $item->key) }}" class="btn btn-sm btn--success" title="@lang('Add / Manage Items & Images')">
                                                <i class="la la-cog"></i> @lang('Manage Content')
                                            </a>

                                            {{-- Edit Section Info --}}
                                            <button type="button" class="btn btn-sm btn--primary editBtn"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                data-layout="{{ $item->layout_type }}"
                                                data-heading="{{ $item->heading }}"
                                                data-subheading="{{ $item->subheading }}"
                                                title="@lang('Edit Section Info')">
                                                <i class="la la-pen"></i>
                                            </button>

                                            {{-- Toggle Status --}}
                                            <button type="button" class="btn btn-sm {{ $item->status ? 'btn--warning' : 'btn--info' }} confirmationBtn"
                                                data-action="{{ route('admin.custom.sections.status', $item->id) }}"
                                                data-question="@lang('Change status for this custom section?')">
                                                <i class="la {{ $item->status ? 'la-eye-slash' : 'la-eye' }}"></i>
                                            </button>

                                            {{-- Delete --}}
                                            <button type="button" class="btn btn-sm btn--danger confirmationBtn"
                                                data-action="{{ route('admin.custom.sections.delete', $item->id) }}"
                                                data-question="@lang('Are you sure you want to permanently delete this custom section and its items?')">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center py-4" colspan="100%">
                                        <i class="las la-folder-open fs-36 text-muted mb-2 d-block"></i>
                                        <p class="mb-1">@lang('No custom sections created yet.')</p>
                                        <button type="button" class="btn btn-sm btn--primary mt-2" data-bs-toggle="modal" data-bs-target="#createSectionModal">
                                            <i class="las la-plus"></i> @lang('Create Your First Custom Section')
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($customSections->hasPages())
                <div class="card-footer py-3">
                    {{ paginateLinks($customSections) }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Create New Section Modal --}}
<div class="modal fade" id="createSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg--primary text-white">
                <h5 class="modal-title text-white"><i class="las la-plus-circle me-1"></i> @lang('Create New Custom Section')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.custom.sections.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">@lang('Section Name') <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="@lang('e.g. Diwali Mega Offers, Trending Gadgets')">
                        <small class="text-muted">@lang('Internal name for admin identification.')</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">@lang('Layout Design') <span class="text-danger">*</span></label>
                        <select name="layout_type" class="form-control" required>
                            <option value="card_grid">@lang('Card Grid (Deals / Brands style 4-column cards)')</option>
                            <option value="banner_slider">@lang('Banner Slider (Sliding promo images)')</option>
                            <option value="promo_box">@lang('Promo Box (Featured big banner with CTA)')</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">@lang('Frontend Heading / Title')</label>
                        <input type="text" name="heading" class="form-control" placeholder="@lang('e.g. Exclusive Festive Deals')">
                        <small class="text-muted">@lang('Shown as the main title on the website.')</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">@lang('Frontend Subheading (Optional)')</label>
                        <input type="text" name="subheading" class="form-control" placeholder="@lang('e.g. Hand-picked savings up to 70% off')">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--primary btn-sm"><i class="las la-check-circle me-1"></i> @lang('Create Section')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Section Modal --}}
<div class="modal fade" id="editSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg--primary text-white">
                <h5 class="modal-title text-white"><i class="las la-pen me-1"></i> @lang('Edit Custom Section Info')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSectionForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">@lang('Section Name') <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">@lang('Layout Design') <span class="text-danger">*</span></label>
                        <select name="layout_type" id="editLayout" class="form-control" required>
                            <option value="card_grid">@lang('Card Grid (Deals / Brands style 4-column cards)')</option>
                            <option value="banner_slider">@lang('Banner Slider (Sliding promo images)')</option>
                            <option value="promo_box">@lang('Promo Box (Featured big banner with CTA)')</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">@lang('Frontend Heading')</label>
                        <input type="text" name="heading" id="editHeading" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">@lang('Frontend Subheading')</label>
                        <input type="text" name="subheading" id="editSubheading" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--primary btn-sm"><i class="las la-save me-1"></i> @lang('Save Changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-confirmation-modal />

@push('script')
<script>
    (function ($) {
        "use strict";
        $('.editBtn').on('click', function () {
            var modal = $('#editSectionModal');
            var id = $(this).data('id');
            var name = $(this).data('name');
            var layout = $(this).data('layout');
            var heading = $(this).data('heading');
            var subheading = $(this).data('subheading');

            $('#editName').val(name);
            $('#editLayout').val(layout);
            $('#editHeading').val(heading);
            $('#editSubheading').val(subheading);

            var actionUrl = "{{ route('admin.custom.sections.update', ':id') }}";
            $('#editSectionForm').attr('action', actionUrl.replace(':id', id));

            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
@endsection
