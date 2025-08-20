@extends('admin.layouts.admin')

@section('content')
    <div class="card mt-4">
        <div class="card-header card-header-bg text-white">
            <h6 class="d-flex align-items-center mb-0 dt-heading">{{ __('cms.banners.create_banner') }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="type">{{ __('cms.banners.banner_type') }}</label>
                    <select name="type" class="form-control" required>
                        <option value="promotion">{{ __('cms.banners.promotion') }}</option>
                        <option value="sale">{{ __('cms.banners.sale') }}</option>
                        <option value="seasonal">{{ __('cms.banners.seasonal') }}</option>
                        <option value="featured">{{ __('cms.banners.featured') }}</option>
                        <option value="announcement">{{ __('cms.banners.announcement') }}</option>
                    </select>
                </div>

                <div id="languages-container" class="mt-4">
                    @if(!empty($languages) && count($languages) > 0)
                        <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                            @foreach($languages as $language)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                            id="{{ $language->code }}-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#{{ $language->code }}" 
                                            type="button" role="tab">
                                        {{ ucwords($language->name) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content mt-3" id="languageTabContent">
                            @foreach($languages as $language)
                                <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" 
                                     id="{{ $language->code }}" role="tabpanel">
                                     
                                    <div class="form-group">
                                        <label for="languages_{{ $language->code }}_title">{{ __('cms.banners.title') }}</label>
                         <input id="languages_{{ $language->code }}_title" type="text" name="languages[{{ $language->code }}][title]" 
                             class="form-control @error('languages.' . $language->code . '.title') is-invalid @enderror"
                             value="{{ old('languages.' . $language->code . '.title') }}" data-required="true" required>
                                        @error('languages.' . $language->code . '.title') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="languages_{{ $language->code }}_description">{{ __('cms.banners.description') }}</label>
                                        <textarea id="languages_{{ $language->code }}_description" name="languages[{{ $language->code }}][description]" 
                                                  class="form-control @error('languages.' . $language->code . '.description') is-invalid @enderror"
                                                  rows="3">{{ old('languages.' . $language->code . '.description') }}</textarea>
                                        @error('languages.' . $language->code . '.description') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </div>

                                    <label class="form-label mt-2" for="image_file_{{ $language->code }}">{{ __('cms.banners.image') }} ({{ $language->code }})</label>
                                    <div class="input-group">
                                        <label for="image_file_{{ $language->code }}" class="btn btn-primary">
                                            {{ __('cms.banners.choose_file') }}
                                        </label>

                         <input type="file" id="image_file_{{ $language->code }}" 
                             name="languages[{{ $language->code }}][image]" 
                             class="d-none form-control @error('languages.' . $language->code . '.image') is-invalid @enderror"
                             accept="image/*"
                             data-required="true" required
                             onchange="updateFileName(this, '{{ $language->code }}'); previewImage(this, '{{ $language->code }}')"> 
                                        
                                        <span id="file-name-{{ $language->code }}" class="ms-2 text-muted"></span>
                                    </div>
                                    
                                    @error('languages.' . $language->code . '.image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div id="image_preview_{{ $language->code }}" class="mt-2" style="display: none;">
                                        <img id="image_preview_img_{{ $language->code }}" src="#" 
                                             alt="{{ __('cms.banners.image_preview') }}" 
                                             class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-danger">{{ __('cms.banners.no_languages_available') }}</p>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary mt-3">{{ __('cms.banners.save') }}</button>
            </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var form = document.querySelector('form');
                        var tabLinks = document.querySelectorAll('.nav-link');
                        var tabPanes = document.querySelectorAll('.tab-pane');

                        function updateRequiredAttributes() {
                            tabPanes.forEach(function(pane) {
                                var inputs = pane.querySelectorAll('input, textarea');
                                if (pane.classList.contains('active')) {
                                    inputs.forEach(function(input) {
                                        input.removeAttribute('disabled');
                                        if (input.hasAttribute('data-required')) {
                                            input.setAttribute('required', 'required');
                                        }
                                    });
                                } else {
                                    inputs.forEach(function(input) {
                                        input.setAttribute('disabled', 'disabled');
                                        input.removeAttribute('required');
                                    });
                                }
                            });
                        }

                        // Cập nhật khi chuyển tab
                        tabLinks.forEach(function(link) {
                            link.addEventListener('click', function() {
                                setTimeout(updateRequiredAttributes, 50);
                            });
                        });

                        // Khởi tạo khi load trang
                        updateRequiredAttributes();
                    });
                </script>
        </div>
    </div>

    <script>
        function updateFileName(input, langCode) {
            let fileNameSpan = document.getElementById('file-name-' + langCode);
            if (input.files.length > 0) {
                fileNameSpan.textContent = input.files[0].name;
            } else {
                fileNameSpan.textContent = '{{ __("cms.banners.no_file_chosen") }}';
            }
        }

        function previewImage(input, langCode) {
            let previewDiv = document.getElementById('image_preview_' + langCode);
            let previewImg = document.getElementById('image_preview_img_' + langCode);

            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewDiv.style.display = 'none';
            }
        }
    </script>
@endsection
