@extends('admin.layouts.master')

@section('title', 'RFI Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $rfi->rfi_number }} — {{ $rfi->subject }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.documents.rfis.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back
            </a>
            @if(!auth()->user()->hasRole('client'))
                <a href="{{ route('admin.core.documents.rfis.edit', $rfi) }}" class="btn btn-primary gap-2">Edit</a>
            @endif
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">RFI Details</h5>
                <div class="flex gap-2">
                    @php $pColors = ['low' => 'badge-outline-success', 'medium' => 'badge-outline-warning', 'high' => 'badge-outline-danger']; @endphp
                    <span class="badge {{ $pColors[$rfi->priority] ?? 'badge-outline-secondary' }} capitalize">{{ $rfi->priority }}</span>
                    @php $sColors = ['open' => 'badge-outline-info', 'answered' => 'badge-outline-success', 'closed' => 'badge-outline-secondary']; @endphp
                    <span class="badge {{ $sColors[$rfi->status] ?? 'badge-outline-secondary' }} capitalize">{{ $rfi->status }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label class="text-xs text-white-dark">RFI Number</label><p class="font-semibold">{{ $rfi->rfi_number }}</p></div>
                <div><label class="text-xs text-white-dark">Project</label><p class="font-semibold">{{ $rfi->project->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Raised By</label><p class="font-semibold">{{ $rfi->raiser->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Assigned To</label><p class="font-semibold">{{ $rfi->assignee->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Related Drawing</label><p class="font-semibold">{{ $rfi->drawing->drawing_number ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Due Date</label><p class="font-semibold">{{ $rfi->due_date?->format('d M Y') ?? '—' }}</p></div>
            </div>

            <hr class="my-4 border-white-light dark:border-gray-700">
            <div><label class="text-xs text-white-dark">Question</label><p class="mt-1 whitespace-pre-wrap">{{ $rfi->question }}</p></div>

            @if($rfi->getFirstMedia('attachment'))
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div>
                    <label class="text-xs text-white-dark">Attachment</label>
                    <p class="mt-1">
                        <a href="{{ $rfi->getFirstMedia('attachment')->getUrl() }}" target="_blank" class="text-primary hover:underline">
                            {{ $rfi->getFirstMedia('attachment')->name }}
                        </a>
                    </p>
                </div>
            @endif

            @if($rfi->answer)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div>
                    <label class="text-xs text-white-dark">Answer</label>
                    <div class="mt-2 rounded-lg bg-success/10 p-4">
                        <p class="whitespace-pre-wrap">{{ $rfi->answer }}</p>
                        <div class="mt-2 text-xs text-white-dark">
                            Answered by {{ $rfi->answerer->name ?? '—' }} on {{ $rfi->answered_date?->format('d M Y') ?? '—' }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Info</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Created</span>
                    <span class="text-xs font-semibold">{{ $rfi->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Linked Change Orders</span>
                    <span class="text-xs font-semibold">{{ $rfi->changeOrders->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(!$rfi->answer && !auth()->user()->hasRole('client') && $rfi->status === 'open')
        <div class="panel mt-6">
            <h6 class="mb-4 text-sm font-semibold">Submit Answer</h6>
            <form action="{{ route('admin.core.documents.rfis.answer', $rfi) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="answer">Answer <span class="text-danger">*</span></label>
                    <textarea name="answer" id="answer" class="form-textarea" rows="4" required placeholder="Provide your answer to this RFI..."></textarea>
                    @error('answer') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-2" onclick="return confirm('Submit this answer?');">Submit Answer</button>
            </form>
        </div>
    @endif
@endsection
