@extends('admin.layouts.master')

@section('title', 'Rod Calculations: ' . $rodCalculation->reference_no)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">{{ $rodCalculation->title }}</h2>
            <p class="text-xs text-white-dark font-mono">{{ $rodCalculation->reference_no }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($rodCalculation->isDraft())
                <form action="{{ route('admin.finance.rod-calculations.approve', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve this calculation?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                </form>
                <a href="{{ route('admin.finance.rod-calculations.edit', $rodCalculation->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
            @endif
            @if($rodCalculation->isApproved())
                <form action="{{ route('admin.finance.rod-calculations.complete', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Mark as completed?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-info">Complete</button>
                </form>
                <form action="{{ route('admin.finance.rod-calculations.reopen', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Reopen to draft?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Reopen</button>
                </form>
            @endif
            <form action="{{ route('admin.finance.rod-calculations.recalculate', $rodCalculation->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">Recalculate</button>
            </form>
            <a href="{{ route('admin.finance.rod-calculations.pdf', $rodCalculation->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">PDF</a>
            <a href="{{ route('admin.finance.rod-calculations.index') }}" class="btn btn-sm btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back
            </a>
        </div>
    </div>

    {{-- Header Cards --}}
    <div class="mt-6 flex flex-wrap gap-3">
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Status</label>
            @php $sc = ['draft' => 'badge-outline-secondary', 'approved' => 'badge-outline-success', 'completed' => 'badge-outline-info']; @endphp
            <span class="badge {{ $sc[$rodCalculation->status] ?? '' }} capitalize">{{ $rodCalculation->status }}</span>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Project</label>
            <p class="font-semibold text-xs">{{ $rodCalculation->project->name ?? 'N/A' }}</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Steel Grade</label>
            <p class="font-semibold text-xs">{{ $rodCalculation->steel_grade ?? '-' }}</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Revision</label>
            <p class="font-semibold text-xs">{{ $rodCalculation->revision ?? '-' }}</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Created By</label>
            <p class="text-xs">{{ $rodCalculation->creator->name ?? '-' }}</p>
        </div>
    </div>

    {{-- Summary --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-4">
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark">Total Steel Weight</label>
            <p class="text-3xl font-bold text-primary mt-2">{{ number_format($summary['total_kg'], 2) }}</p>
            <p class="text-xs text-white-dark">kg</p>
            <div class="mt-3 text-xs text-white-dark">
                @foreach($summary['by_diameter'] as $d)
                    <span class="inline-block mr-2">Ø{{ $d['diameter'] }}: {{ number_format($d['total_kg'], 1) }}kg</span>
                @endforeach
            </div>
        </div>
        <div class="panel lg:col-span-3">
            <label class="text-xs text-white-dark mb-2 block font-semibold">By Diameter</label>
            @if(count($summary['by_diameter']))
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead><tr class="border-b dark:border-gray-700"><th class="text-left py-2">Dia (mm)</th><th class="text-right py-2">Bars</th><th class="text-right py-2">Length (mm)</th><th class="text-right py-2">Weight (kg)</th></tr></thead>
                        <tbody>
                            @foreach($summary['by_diameter'] as $d)
                                <tr class="border-t dark:border-gray-700">
                                    <td class="py-2 font-semibold">{{ $d['diameter'] }}</td>
                                    <td class="py-2 text-right">{{ $d['bars_count'] }}</td>
                                    <td class="py-2 text-right">{{ number_format($d['total_mm'], 0) }}</td>
                                    <td class="py-2 text-right font-semibold">{{ number_format($d['total_kg'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="border-t-2 dark:border-gray-600 font-bold">
                                <td class="py-2">Total</td>
                                <td class="py-2 text-right">{{ collect($summary['by_diameter'])->sum('bars_count') }}</td>
                                <td class="py-2 text-right">{{ number_format(collect($summary['by_diameter'])->sum('total_mm'), 0) }}</td>
                                <td class="py-2 text-right text-primary">{{ number_format($summary['total_kg'], 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-white-dark py-4 text-center">No bars added yet.</p>
            @endif
        </div>
    </div>

    {{-- Members --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-base font-semibold">Structural Members</h5>
            @if($rodCalculation->isDraft())
                <button type="button" onclick="document.getElementById('addMemberForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Add Member</button>
            @endif
        </div>

        @if($rodCalculation->isDraft())
        {{-- Add Member Form --}}
        <div id="addMemberForm" class="mb-5 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.rod-calculations.members.store', $rodCalculation->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-3 md:grid-cols-6" x-data="{ memberType: '' }">
                    <div>
                        <label class="text-xs">Type *</label>
                        <select name="type" class="form-select" required x-model="memberType" x-show="memberType !== 'custom'" :disabled="memberType === 'custom'" @change="$el.selectedOptions[0]?.dataset.cover && (document.getElementById('defaultCover').value = $el.selectedOptions[0].dataset.cover)">
                            <option value="">Select Type</option>
                            @foreach(\App\Constants\RodMemberType::ALL as $type)
                                <option value="{{ $type }}" data-cover="{{ \App\Constants\RodMemberType::DEFAULT_COVER[$type] ?? 25 }}">{{ \App\Constants\RodMemberType::LABELS[$type] ?? $type }}</option>
                            @endforeach
                        </select>
                        <div x-show="memberType === 'custom'" x-cloak class="flex gap-1">
                            <input type="text" name="type" class="form-input" required placeholder="Enter type" :disabled="memberType !== 'custom'" />
                            <button type="button" class="btn btn-sm btn-outline-danger" @click="memberType = ''">✕</button>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs">Code *</label>
                        <input type="text" name="member_code" class="form-input" required placeholder="e.g. B1" />
                    </div>
                    <div>
                        <label class="text-xs">Qty *</label>
                        <input type="number" name="quantity" class="form-input" required value="1" min="1" />
                    </div>
                    <div>
                        <label class="text-xs">Cover (mm) *</label>
                        <input type="number" name="cover" id="defaultCover" class="form-input" required value="25" step="0.01" />
                    </div>
                    <div>
                        <label class="text-xs">Length (mm)</label>
                        <input type="number" name="length" class="form-input" step="0.01" />
                    </div>
                    <div>
                        <label class="text-xs">Width (mm)</label>
                        <input type="number" name="width" class="form-input" step="0.01" />
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-2">
                    <div>
                        <label class="text-xs">Height (mm)</label>
                        <input type="number" name="height" class="form-input" step="0.01" />
                    </div>
                    <div>
                        <label class="text-xs">Depth (mm)</label>
                        <input type="number" name="depth" class="form-input" step="0.01" />
                    </div>
                    <div>
                        <label class="text-xs">Thickness (mm)</label>
                        <input type="number" name="thickness" class="form-input" step="0.01" />
                    </div>
                </div>
                <div class="mt-2">
                    <label class="text-xs">Remarks</label>
                    <input type="text" name="remarks" class="form-input" />
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add Member</button>
            </form>
        </div>
        @endif

        {{-- Members Accordion --}}
        @forelse($rodCalculation->members as $member)
            <div class="mb-4 rounded-lg border dark:border-gray-700" x-data="{ open: false }">
                <div class="flex items-center justify-between cursor-pointer px-4 py-3 bg-gray-50 dark:bg-gray-800 rounded-t-lg" @click="open = !open">
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-90': open }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5L15 12L9 19"/></svg>
                        <span class="font-semibold text-sm">{{ $member->member_code }}</span>
                        <span class="badge badge-outline-secondary text-xs capitalize">{{ \App\Constants\RodMemberType::LABELS[$member->type] ?? $member->type }}</span>
                        <span class="text-xs text-white-dark">Qty: {{ $member->quantity }}</span>
                        <span class="text-xs text-white-dark">Cover: {{ $member->cover }}mm</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $memberKg = $member->bars->sum('total_weight');
                        @endphp
                        <span class="font-semibold text-sm text-primary">{{ number_format($memberKg, 2) }} kg</span>
                        @if($rodCalculation->isDraft())
                            <form action="{{ route('admin.finance.rod-calculations.members.destroy', [$rodCalculation->id, $member->id]) }}" method="POST" onsubmit="return confirm('Delete this member and all its bars?');" @click.stop>
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-6 h-6 rounded border border-red-300 text-danger hover:bg-red-50 hover:text-red-700 dark:border-red-700 dark:hover:bg-red-900" title="Delete member">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div x-show="open" x-collapse class="p-4">
                    {{-- Member Dims --}}
                    <div class="mb-3 text-xs text-white-dark">
                        @if($member->length) L: {{ $member->length }}mm @endif
                        @if($member->width) W: {{ $member->width }}mm @endif
                        @if($member->height) H: {{ $member->height }}mm @endif
                        @if($member->depth) D: {{ $member->depth }}mm @endif
                        @if($member->thickness) T: {{ $member->thickness }}mm @endif
                        @if($member->remarks) | {{ $member->remarks }} @endif
                    </div>

                    {{-- Bars Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b dark:border-gray-700">
                                    <th class="text-left py-1">Bar Name</th>
                                    <th class="text-left">Dir</th>
                                    <th class="text-right">Dia</th>
                                    <th class="text-right">Actual</th>
                                    <th class="text-right">Spacing</th>
                                    <th class="text-right">Hook</th>
                                    <th class="text-right">Bend</th>
                                    <th class="text-right">Lap</th>
                                    <th class="text-right">Cut L.</th>
                                    <th class="text-right">Count</th>
                                    <th class="text-right">Total L.</th>
                                    <th class="text-right">Unit W.</th>
                                    <th class="text-right">Total W.</th>
                                    @if($rodCalculation->isDraft())
                                        <th class="text-center">Action</th>
                                    @endif
                                </tr>
                            </thead>
                                @forelse($member->bars as $bar)
                                    <tbody x-data="{ editing: false }">
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-1 font-semibold"><span x-show="!editing">{{ $bar->bar_name }}</span></td>
                                        <td><span x-show="!editing">{{ $bar->direction }}</span></td>
                                        <td class="text-right"><span x-show="!editing">{{ $bar->diameter }}</span></td>
                                        <td class="text-right"><span x-show="!editing">{{ $bar->actual_size }}</span></td>
                                        <td class="text-right"><span x-show="!editing">{{ $bar->spacing ?? 'manual' }}</span></td>
                                        <td class="text-right"><span x-show="!editing">{{ $bar->hook_length }}</span></td>
                                        <td class="text-right"><span x-show="!editing">{{ $bar->bend_length }}</span></td>
                                        <td class="text-right"><span x-show="!editing">{{ $bar->lap_length }}</span></td>
                                        <td class="text-right font-semibold"><span x-show="!editing">{{ number_format($bar->cutting_length, 0) }}</span></td>
                                        <td class="text-right"><span x-show="!editing">{{ $bar->bars_count }}</span></td>
                                        <td class="text-right"><span x-show="!editing">{{ number_format($bar->total_length, 0) }}</span></td>
                                        <td class="text-right"><span x-show="!editing">{{ number_format($bar->unit_weight, 4) }}</span></td>
                                        <td class="text-right font-semibold text-primary"><span x-show="!editing">{{ number_format($bar->total_weight, 2) }}</span></td>
                                        @if($rodCalculation->isDraft())
                                            <td class="text-center">
                                                <div class="flex items-center justify-center gap-1" x-show="!editing">
                                                    <button type="button" @click="editing = true" class="inline-flex items-center justify-center w-5 h-5 rounded border border-blue-300 text-primary hover:bg-blue-50 hover:text-blue-700 dark:border-blue-700 dark:hover:bg-blue-900" title="Edit bar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </button>
                                                    <form action="{{ route('admin.finance.rod-calculations.bars.destroy', [$rodCalculation->id, $member->id, $bar->id]) }}" method="POST" onsubmit="return confirm('Delete this bar?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center justify-center w-5 h-5 rounded border border-red-300 text-danger hover:bg-red-50 hover:text-red-700 dark:border-red-700 dark:hover:bg-red-900" title="Delete bar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    @if($rodCalculation->isDraft())
                                    <tr x-show="editing" x-cloak class="bg-gray-50 dark:bg-gray-800">
                                        <td colspan="14" class="p-3">
                                            <form action="{{ route('admin.finance.rod-calculations.bars.update', [$rodCalculation->id, $member->id, $bar->id]) }}" method="POST" class="space-y-3">
                                                @csrf @method('PUT')
                                                <div class="grid grid-cols-4 gap-2 md:grid-cols-7">
                                                    <div>
                                                        <label class="text-xs">Bar Name *</label>
                                                        <input type="text" name="bar_name" class="form-input" required value="{{ $bar->bar_name }}" />
                                                    </div>
                                                    <div>
                                                        <label class="text-xs">Direction *</label>
                                                        <select name="direction" class="form-select" required>
                                                            @foreach(\App\Constants\BarDirection::ALL as $dir)
                                                                <option value="{{ $dir }}" {{ $bar->direction === $dir ? 'selected' : '' }}>{{ $dir }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs">Dia (mm) *</label>
                                                        <select name="diameter" class="form-select" required>
                                                            @foreach(\App\Constants\RodCalculationConstants::DIAMETERS as $d)
                                                                <option value="{{ $d }}" {{ $bar->diameter == $d ? 'selected' : '' }}>{{ $d }}mm</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs">Actual Size *</label>
                                                        <input type="number" name="actual_size" class="form-input" required step="0.01" value="{{ $bar->actual_size }}" />
                                                    </div>
                                                    <div>
                                                        <label class="text-xs">Spacing (mm)</label>
                                                        <input type="number" name="spacing" class="form-input" step="0.01" value="{{ $bar->spacing }}" />
                                                    </div>
                                                    <div>
                                                        <label class="text-xs">Hook</label>
                                                        <input type="number" name="hook_length" class="form-input" step="0.01" value="{{ $bar->hook_length }}" />
                                                    </div>
                                                    <div>
                                                        <label class="text-xs">Bend</label>
                                                        <input type="number" name="bend_length" class="form-input" step="0.01" value="{{ $bar->bend_length }}" />
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-4 gap-2 mt-2">
                                                    <div>
                                                        <label class="text-xs">Lap (mm)</label>
                                                        <input type="number" name="lap_length" class="form-input" step="0.01" value="{{ $bar->lap_length }}" />
                                                    </div>
                                                    <div>
                                                        <label class="text-xs">Bar Count</label>
                                                        <input type="number" name="bars_count" class="form-input" min="1" value="{{ $bar->bars_count }}" />
                                                    </div>
                                                    <div class="flex items-end">
                                                        <label class="flex items-center gap-1 text-xs">
                                                            <input type="checkbox" name="is_manual_count" value="1" class="rounded" {{ $bar->is_manual_count ? 'checked' : '' }} />
                                                            Manual count
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs">Sort</label>
                                                        <input type="number" name="sort_order" class="form-input" value="{{ $bar->sort_order }}" />
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2 mt-2">
                                                    <div>
                                                        <label class="text-xs">Remarks</label>
                                                        <input type="text" name="remarks" class="form-input" value="{{ $bar->remarks }}" />
                                                    </div>
                                                </div>
                                                <div class="flex gap-2">
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                    <button type="button" @click="editing = false" class="btn btn-sm btn-outline-secondary">Cancel</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    </tbody>
                                @empty
                                    <tbody><tr><td colspan="{{ $rodCalculation->isDraft() ? 14 : 13 }}" class="text-center py-2">No bars added yet.</td></tr></tbody>
                                @endforelse
                        </table>
                    </div>

                    {{-- Add Bar Form --}}
                    @if($rodCalculation->isDraft())
                        <div class="mt-3 rounded border p-3 dark:border-gray-700" x-data="{ showBarForm: false }">
                            <button type="button" @click="showBarForm = !showBarForm" class="btn btn-outline-primary">+ Add Bar</button>
                            <div x-show="showBarForm" x-collapse class="mt-2">
                                <form action="{{ route('admin.finance.rod-calculations.bars.store', [$rodCalculation->id, $member->id]) }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-4 gap-2 md:grid-cols-7">
                                        <div>
                                            <label class="text-xs">Bar Name *</label>
                                            <input type="text" name="bar_name" class="form-input" required placeholder="e.g. Main bar" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Direction *</label>
                                            <select name="direction" class="form-select" required>
                                                @foreach(\App\Constants\BarDirection::ALL as $dir)
                                                    <option value="{{ $dir }}">{{ $dir }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs">Dia (mm) *</label>
                                            <select name="diameter" class="form-select" required>
                                                @foreach(\App\Constants\RodCalculationConstants::DIAMETERS as $d)
                                                    <option value="{{ $d }}">{{ $d }}mm</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs">Actual Size *</label>
                                            <input type="number" name="actual_size" class="form-input" required step="0.01" placeholder="mm" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Spacing (mm)</label>
                                            <input type="number" name="spacing" class="form-input" step="0.01" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Hook</label>
                                            <input type="number" name="hook_length" class="form-input" step="0.01" value="0" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Bend</label>
                                            <input type="number" name="bend_length" class="form-input" step="0.01" value="0" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-4 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs">Lap (mm)</label>
                                            <input type="number" name="lap_length" class="form-input" step="0.01" value="0" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Bar Count (if manual)</label>
                                            <input type="number" name="bars_count" class="form-input" min="1" />
                                        </div>
                                        <div class="flex items-end">
                                            <label class="flex items-center gap-1 text-xs">
                                                <input type="checkbox" name="is_manual_count" value="1" class="rounded" />
                                                Manual count
                                            </label>
                                        </div>
                                        <div>
                                            <label class="text-xs">Sort</label>
                                            <input type="number" name="sort_order" class="form-input" value="0" />
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label class="text-xs">Remarks</label>
                                        <input type="text" name="remarks" class="form-input" />
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Add Bar</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center text-white-dark py-4">No members added yet. Click "Add Member" above.</p>
        @endforelse
    </div>
@endsection
