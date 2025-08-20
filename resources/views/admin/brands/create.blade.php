@extends('admin.layouts.admin')
@section('content')
    <div class="card mt-4">
        <div class="card-header card-header-bg text-white">
            <h6 class="d-flex align-items-center mb-0 dt-heading">{{ __('cms.brands.heading') }}
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
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
                <div class="row">
                        <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                            {{-- Loop through all active languages and create a tab for each --}}
                            @foreach($activeLanguages as $language)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $language->name }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $language->name }}" type="button" role="tab">{{ ucwords($language->name) }}</button>
                                </li>
                            @endforeach
                        </ul>
                    
                        <div class="tab-content mt-3" id="languageTabContent">
                            @foreach($activeLanguages as $language)
                                <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="{{ $language->name }}" role="tabpanel">
                                    <label class="form-label">{{ __('cms.brands.name') }} ({{ $language->code }})</label>
                     <input type="text"
                         name="translations[{{ $language->code }}][name]"
                         class="form-control @error("translations.{$language->code}.name") is-invalid @enderror"
                         value="{{ old("translations.{$language->code}.name") }}"
                         data-required="true" required>
                                    @error("translations.{$language->code}.name")
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                        
                                    <label class="form-label mt-3">{{ __('cms.brands.description') }} ({{ $language->code }})</label>
                                    <textarea name="translations[{{ $language->code }}][description]"
                                              class="form-control ck-editor-multi-languages @error("translations.{$language->code}.description") is-invalid @enderror">{{ old("translations.{$language->code}.description") }}</textarea>
                                    @error("translations.{$language->code}.description")
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach  
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="logo_url">{{ __('cms.brands.logo') }}</label>
                                <div class="custom-file">
                                    <label class="btn btn-primary" for="logo_file">{{ __('cms.brands.choose_file') }}</label>
                                    <input type="file" name="logo_url" accept="image/*" class="form-control d-none" id="logo_file">
                                </div>
                                <div class="mt-2" id="logo_preview" style="display:none;">
                                    <img id="logo_preview_img" src="" alt="selected logo" class="img-thumbnail" width="100">
                                </div>
                                @error('logo_url')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
                <button type="submit" class="mt-3 btn btn-primary">{{ __('cms.brands.create') }}</button>
            </form>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
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

                tabLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        setTimeout(updateRequiredAttributes, 50);
                    });
                });

                updateRequiredAttributes();
            });
            </script>
        </div>
    </div>
@endsection
@section('js')
<script>
    // Add an event listener to the file input field for detecting changes
    document.getElementById('logo_file').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var previewElement = document.getElementById('logo_preview');
        var previewImage = document.getElementById('logo_preview_img');

        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                previewElement.style.display = 'block';
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            previewElement.style.display = 'none';
        }
    });
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    // Select all elements with the class 'ck-editor-multi-languages' and apply CKEditor to each
    document.querySelectorAll('.ck-editor-multi-languages').forEach((element) => {
        ClassicEditor
            .create(element)
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endsection
