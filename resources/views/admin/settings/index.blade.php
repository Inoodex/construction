@extends('admin.layouts.master')

@section('title', 'Application Settings')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Application Settings</h5>
        </div>
        <div class="mb-5">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="app_name">Application Name</label>
                        <input id="app_name" type="text" name="app_name"
                            value="{{ $settings['app_name'] ?? config('app.name') }}" class="form-input" />
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="app_logo">Application Logo</label>
                        <input id="app_logo" type="file" name="app_logo"
                            class="form-input file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90" />
                        @if(isset($settings['app_logo']))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo" class="h-20 w-auto" />
                            </div>
                        @else
                            <div class="mt-2 text-xs text-gray-500">Current: Default Logo</div>
                        @endif
                    </div>
                    <div>
                        <label for="app_favicon">Application Favicon</label>
                        <input id="app_favicon" type="file" name="app_favicon"
                            class="form-input file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90" />
                        @if(isset($settings['app_favicon']))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['app_favicon']) }}" alt="Favicon"
                                    class="h-10 w-auto" />
                            </div>
                        @else
                            <div class="mt-2 text-xs text-gray-500">Current: Default Favicon</div>
                        @endif
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
@endsection