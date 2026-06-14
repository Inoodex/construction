@if (session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.300
         class="mb-4 flex items-center rounded-md border border-success/20 bg-success-light px-4 py-3 text-sm text-success dark:border-success/10 dark:bg-success-dark-light">
        <svg class="h-5 w-5 shrink-0 ltr:mr-3 rtl:ml-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="flex-1">{{ session('success') }}</span>
        <button @click="show = false" class="ml-2 text-success hover:text-success/70">&times;</button>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.300
         class="mb-4 flex items-center rounded-md border border-danger/20 bg-danger-light px-4 py-3 text-sm text-danger dark:border-danger/10 dark:bg-danger-dark-light">
        <svg class="h-5 w-5 shrink-0 ltr:mr-3 rtl:ml-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="flex-1">{{ session('error') }}</span>
        <button @click="show = false" class="ml-2 text-danger hover:text-danger/70">&times;</button>
    </div>
@endif

@if (session('warning'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.300
         class="mb-4 flex items-center rounded-md border border-warning/20 bg-warning-light px-4 py-3 text-sm text-warning dark:border-warning/10 dark:bg-warning-dark-light">
        <svg class="h-5 w-5 shrink-0 ltr:mr-3 rtl:ml-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span class="flex-1">{{ session('warning') }}</span>
        <button @click="show = false" class="ml-2 text-warning hover:text-warning/70">&times;</button>
    </div>
@endif

@if (session('info'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.300
         class="mb-4 flex items-center rounded-md border border-info/20 bg-info-light px-4 py-3 text-sm text-info dark:border-info/10 dark:bg-info-dark-light">
        <svg class="h-5 w-5 shrink-0 ltr:mr-3 rtl:ml-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="flex-1">{{ session('info') }}</span>
        <button @click="show = false" class="ml-2 text-info hover:text-info/70">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 8000)" x-show="show" x-transition.duration.300
         class="mb-4 rounded-md border border-danger/20 bg-danger-light px-4 py-3 text-sm text-danger dark:border-danger/10 dark:bg-danger-dark-light">
        <div class="flex items-center">
            <svg class="h-5 w-5 shrink-0 ltr:mr-3 rtl:ml-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="flex-1 font-medium">Please correct the following errors:</span>
            <button @click="show = false" class="text-danger hover:text-danger/70">&times;</button>
        </div>
        <ul class="mt-2 list-disc space-y-1 ltr:ml-8 rtl:mr-8">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
