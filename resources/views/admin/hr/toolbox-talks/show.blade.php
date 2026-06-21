@extends('admin.layouts.master')

@section('title', $toolboxTalk->topic)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $toolboxTalk->topic }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.hr.toolbox-talks.edit', $toolboxTalk) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.hr.toolbox-talks.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">Date</td><td>{{ $toolboxTalk->date->format('d M Y') }}</td></tr>
                <tr><td class="py-1 text-gray-500">Topic</td><td class="font-semibold">{{ $toolboxTalk->topic }}</td></tr>
                <tr><td class="py-1 text-gray-500">Conducted By</td><td>{{ $toolboxTalk->employee?->full_name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Duration</td><td>{{ $toolboxTalk->duration_minutes ? $toolboxTalk->duration_minutes . ' minutes' : '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Location</td><td>{{ $toolboxTalk->location ?? '—' }}</td></tr>
            </table>
        </div>
        <div class="panel">
            <h4 class="font-semibold mb-3">Attendees</h4>
            <p class="text-sm whitespace-pre-wrap">{{ $toolboxTalk->attendees ?? 'None recorded' }}</p>
        </div>
    </div>

    @if($toolboxTalk->discussion_points)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Discussion Points</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $toolboxTalk->discussion_points }}</p>
    </div>
    @endif

    @if($toolboxTalk->action_items)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Action Items</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $toolboxTalk->action_items }}</p>
    </div>
    @endif

    @if($toolboxTalk->notes)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Notes</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $toolboxTalk->notes }}</p>
    </div>
    @endif
@endsection
