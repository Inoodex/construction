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
                    <button type="submit" class="btn btn-sm btn-outline-success gap-1.5">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> -->
                        Approve
                    </button>
                </form>
            @endif
            @if($rodCalculation->isApproved())
                <form action="{{ route('admin.finance.rod-calculations.complete', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Mark as completed?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-info gap-1.5">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> -->
                        Complete
                    </button>
                </form>
                <form action="{{ route('admin.finance.rod-calculations.reopen', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Reopen to draft?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary gap-1.5">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg> -->
                        Reopen
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.finance.rod-calculations.recalculate', $rodCalculation->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary gap-1.5">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg> -->
                    Recalculate
                </button>
            </form>
            <form action="{{ route('admin.finance.rod-calculations.duplicate', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Duplicate this BBS calculation sheet?');">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-info gap-1.5">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> -->
                    Duplicate
                </button>
            </form>
            <a href="{{ route('admin.finance.rod-calculations.pdf', $rodCalculation->id) }}" target="_blank" class="btn btn-sm btn-outline-dark gap-1.5">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg> -->
                PDF
            </a>
            <a href="{{ route('admin.finance.rod-calculations.excel', $rodCalculation->id) }}" class="btn btn-sm btn-outline-success gap-1.5">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="8" y1="13" x2="16" y2="13"></line><line x1="8" y1="17" x2="16" y2="17"></line></svg> -->
                Excel
            </a>
            <a href="{{ route('admin.finance.rod-calculations.edit', $rodCalculation->id) }}" class="btn btn-sm btn-outline-warning gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </a>
            <a href="{{ route('admin.finance.rod-calculations.index') }}" class="btn btn-sm btn-secondary gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
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
            <p class="font-semibold text-xs text-primary">{{ $rodCalculation->steel_grade ?? '-' }}</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Revision</label>
            <p class="font-semibold text-xs">{{ $rodCalculation->revision ?? '-' }}</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Waste %</label>
            <p class="text-xs font-semibold">{{ $rodCalculation->waste_percent ?? '0' }}%</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Created By</label>
            <p class="text-xs font-semibold">{{ $rodCalculation->creator->name ?? '-' }}</p>
        </div>
    </div>

    {{-- Summary Cards (Full Width Grid) --}}
    <div class="mt-6 flex flex-wrap gap-6">
        <div class="panel text-center flex flex-col items-center justify-center flex-1 min-w-[200px]">
            <label class="text-xs text-white-dark uppercase font-semibold">Net Steel Weight</label>
            <p class="text-3xl font-bold text-primary mt-2">{{ number_format($summary['total_kg'], 2) }} <span class="text-sm font-normal text-white-dark">kg</span></p>
            <p class="text-xs font-semibold text-info mt-1">{{ number_format($summary['total_tons'], 3) }} Metric Tons</p>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center flex-1 min-w-[200px]">
            <label class="text-xs text-white-dark uppercase font-semibold">Wastage Allowance</label>
            <p class="text-3xl font-bold text-warning mt-2">{{ $summary['waste_percent'] }}%</p>
            <p class="text-xs font-semibold text-warning mt-1">+{{ number_format($summary['waste_kg'], 1) }} kg (+{{ number_format($summary['waste_tons'], 3) }} T)</p>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center flex-1 min-w-[200px]">
            <label class="text-xs text-white-dark uppercase font-semibold">Gross Total Weight</label>
            <p class="text-3xl font-bold text-success mt-2">{{ number_format($summary['gross_total_kg'], 2) }} <span class="text-sm font-normal text-white-dark">kg</span></p>
            <p class="text-xs font-semibold text-success mt-1">{{ number_format($summary['gross_total_tons'], 3) }} Metric Tons</p>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center flex-1 min-w-[200px]">
            <label class="text-xs text-white-dark uppercase font-semibold">Stock Order (12m Bars)</label>
            <p class="text-3xl font-bold text-info mt-2">{{ number_format($summary['total_12m_sticks'] ?? 0) }}</p>
            <p class="text-xs font-semibold text-info mt-1">Commercial Sticks (12m)</p>
        </div>
    </div>

    {{-- By Diameter Summary & Stock Ordering Table (Full Width Panel) --}}
    <div class="panel mt-6 w-full">
        <div class="flex items-center justify-between mb-3">
            <h5 class="text-sm font-bold uppercase text-primary">By Diameter Summary &amp; Stock Ordering</h5>
            <span class="badge badge-outline-primary text-xs">{{ count($summary['by_diameter']) }} Rebar Sizes</span>
        </div>
        @if(count($summary['by_diameter']))
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <th class="text-left py-2 px-3">Dia (mm)</th>
                            <th class="text-right py-2 px-3">Bars Count</th>
                            <th class="text-right py-2 px-3">Total Length (mm)</th>
                            <th class="text-right py-2 px-3">12m Stock Sticks</th>
                            <th class="text-right py-2 px-3">Net Weight (kg)</th>
                            <th class="text-right py-2 px-3">Net Weight (Tons)</th>
                            @if(($summary['total_steel_cost'] ?? 0) > 0)
                                <th class="text-right py-2 px-3">Est. Cost (৳)</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary['by_diameter'] as $d)
                            <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="py-2.5 px-3 font-bold text-primary">Ø{{ $d['diameter'] }} mm</td>
                                <td class="py-2.5 px-3 text-right font-medium">{{ number_format($d['bars_count']) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono">{{ number_format($d['total_mm'], 0) }} mm</td>
                                <td class="py-2.5 px-3 text-right font-bold text-info">{{ number_format($d['commercial_12m_sticks']) }} pcs</td>
                                <td class="py-2.5 px-3 text-right font-bold">{{ number_format($d['total_kg'], 2) }} kg</td>
                                <td class="py-2.5 px-3 text-right font-bold text-primary">{{ number_format($d['total_tons'], 3) }} T</td>
                                @if(($summary['total_steel_cost'] ?? 0) > 0)
                                    <td class="py-2.5 px-3 text-right font-extrabold text-success">৳{{ number_format($d['estimated_cost'], 2) }}</td>
                                @endif
                            </tr>
                        @endforeach
                        <tr class="border-t-2 dark:border-gray-600 font-extrabold bg-gray-100 dark:bg-gray-800">
                            <td class="py-3 px-3 uppercase">Total</td>
                            <td class="py-3 px-3 text-right">{{ number_format(collect($summary['by_diameter'])->sum('bars_count')) }}</td>
                            <td class="py-3 px-3 text-right font-mono">{{ number_format(collect($summary['by_diameter'])->sum('total_mm'), 0) }} mm</td>
                            <td class="py-3 px-3 text-right text-info">{{ number_format($summary['total_12m_sticks'] ?? 0) }} pcs</td>
                            <td class="py-3 px-3 text-right text-primary">{{ number_format($summary['total_kg'], 2) }} kg</td>
                            <td class="py-3 px-3 text-right text-primary">{{ number_format($summary['total_tons'], 3) }} T</td>
                            @if(($summary['total_steel_cost'] ?? 0) > 0)
                                <td class="py-3 px-3 text-right text-success">৳{{ number_format(collect($summary['by_diameter'])->sum('estimated_cost'), 2) }}</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-white-dark py-4 text-center">No bars added yet.</p>
        @endif
    </div>

    {{-- Steel Cost Estimation Summary --}}
    @if(($summary['total_steel_cost'] ?? 0) > 0 || $rodCalculation->steel_price_per_kg)
    <div class="mt-6 panel bg-primary/5 dark:bg-primary/10 border border-primary/20">
        <div class="flex items-center justify-between mb-3">
            <h5 class="text-sm font-bold uppercase text-primary">Steel Rebar Cost Estimation</h5>
            <span class="text-xs font-semibold text-white-dark">Rate: ৳{{ number_format($summary['steel_price_per_kg'], 2) }} / kg (৳{{ number_format($summary['cost_per_ton'], 2) }} / Ton)</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div class="p-2 rounded bg-white dark:bg-[#0e1726]">
                <span class="text-[10px] text-white-dark uppercase block">Net Steel Weight</span>
                <span class="text-lg font-bold text-primary">{{ number_format($summary['total_kg'], 2) }} kg ({{ number_format($summary['total_tons'], 3) }} T)</span>
            </div>
            <div class="p-2 rounded bg-white dark:bg-[#0e1726]">
                <span class="text-[10px] text-white-dark uppercase block">Gross Steel Weight (incl. {{ $summary['waste_percent'] }}% waste)</span>
                <span class="text-lg font-bold text-warning">{{ number_format($summary['gross_total_kg'], 2) }} kg ({{ number_format($summary['gross_total_tons'], 3) }} T)</span>
            </div>
            <div class="p-2 rounded bg-primary text-white">
                <span class="text-[10px] uppercase block opacity-80">Total Estimated Steel Cost</span>
                <span class="text-xl font-extrabold">৳{{ number_format($summary['total_steel_cost'], 2) }}</span>
                <span class="text-[10px] block opacity-80">Based on Gross Weight</span>
            </div>
        </div>
    </div>
    @endif

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
                                    <tbody x-data="{ editing: false, barDirection: '{{ in_array($bar->direction, \App\Constants\BarDirection::ALL) ? '' : $bar->direction }}' }">
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
                                                        <select name="direction" class="form-select" required x-model="barDirection" x-show="barDirection !== 'OTHER'" :disabled="barDirection === 'OTHER'">
                                                            <option value="">Select</option>
                                                            @foreach(\App\Constants\BarDirection::ALL as $dir)
                                                                <option value="{{ $dir }}" {{ $bar->direction === $dir ? 'selected' : '' }}>{{ $dir }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div x-show="barDirection === 'OTHER'" x-cloak class="flex gap-1">
                                                            <input type="text" name="direction" class="form-input" required placeholder="Enter direction" value="{{ in_array($bar->direction, \App\Constants\BarDirection::ALL) ? '' : $bar->direction }}" :disabled="barDirection !== 'OTHER'" />
                                                            <button type="button" class="btn btn-sm btn-outline-danger" @click="barDirection = ''">✕</button>
                                                        </div>
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
                        <div class="mt-3 rounded border p-3 dark:border-gray-700" x-data="{
                            showBarForm: false,
                            barDirection: '',
                            diameter: '12',
                            actualSize: '',
                            spacing: '',
                            hookLength: 0,
                            barsCount: 1,
                            isManualCount: false,
                            memberDims: {
                                length: {{ $member->length ?? 0 }},
                                width: {{ $member->width ?? 0 }},
                                height: {{ $member->height ?? 0 }},
                                depth: {{ $member->depth ?? 0 }},
                                thickness: {{ $member->thickness ?? 0 }},
                                cover: {{ $member->cover ?? 25 }},
                                type: '{{ $member->type }}'
                            },
                            updateActualSize() {
                                const d = this.memberDims;
                                const l = parseFloat(d.length) || 0;
                                const w = parseFloat(d.width) || 0;
                                const h = parseFloat(d.height) || parseFloat(d.depth) || 0;
                                const t = parseFloat(d.thickness) || 0;
                                const dir = (this.barDirection || '').toUpperCase();

                                let val = 0;
                                if (dir === 'X') val = l || w || h;
                                else if (dir === 'Y') val = w || l || h;
                                else if (dir === 'MAIN' || dir === 'TOP' || dir === 'BOTTOM') {
                                    if (d.type === 'column' && h > 0) val = h;
                                    else val = l || w || h || t;
                                }
                                else if (dir === 'DISTRIBUTION') val = w || l || t;
                                else if (dir === 'STIRRUP') val = (w > 0 && h > 0) ? (2 * (w + h)) : (w || h);
                                else val = l || w || h || t;

                                if (val > 0) this.actualSize = val;
                                this.updateHookLength();
                                this.updateBarsCount();
                            },
                            updateHookLength() {
                                const dia = parseFloat(this.diameter) || 0;
                                const dir = (this.barDirection || '').toUpperCase();
                                if (dir === 'STIRRUP') {
                                    this.hookLength = Math.round(20 * dia);
                                } else if (['MAIN', 'TOP', 'BOTTOM', 'X', 'Y'].includes(dir)) {
                                    this.hookLength = Math.round(18 * dia);
                                } else if (dir === 'DISTRIBUTION') {
                                    this.hookLength = 0;
                                }
                            },
                            updateBarsCount() {
                                if (this.isManualCount) return;
                                const size = parseFloat(this.actualSize) || 0;
                                const sp = parseFloat(this.spacing) || 0;
                                const cover = parseFloat(this.memberDims.cover) || 0;

                                if (sp > 0 && size > 0) {
                                    const eff = Math.max(0, size - (cover * 2));
                                    this.barsCount = Math.floor(eff / sp) + 1;
                                }
                            }
                        }">
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
                                            <select name="direction" class="form-select" required x-model="barDirection" @change="updateActualSize()" x-show="barDirection !== 'OTHER'" :disabled="barDirection === 'OTHER'">
                                                <option value="">Select</option>
                                                @foreach(\App\Constants\BarDirection::ALL as $dir)
                                                    <option value="{{ $dir }}">{{ $dir }}</option>
                                                @endforeach
                                            </select>
                                            <div x-show="barDirection === 'OTHER'" x-cloak class="flex gap-1">
                                                <input type="text" name="direction" class="form-input" required placeholder="Enter direction" :disabled="barDirection !== 'OTHER'" />
                                                <button type="button" class="btn btn-sm btn-outline-danger" @click="barDirection = ''">✕</button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs">Dia (mm) *</label>
                                            <select name="diameter" class="form-select" required x-model="diameter" @change="updateHookLength()">
                                                @foreach(\App\Constants\RodCalculationConstants::DIAMETERS as $d)
                                                    <option value="{{ $d }}">{{ $d }}mm</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs">Actual Size *</label>
                                            <input type="number" name="actual_size" class="form-input" required step="0.01" placeholder="mm" x-model="actualSize" @input="updateBarsCount()" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Spacing (mm)</label>
                                            <input type="number" name="spacing" class="form-input" step="0.01" x-model="spacing" @input="updateBarsCount()" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Hook (mm)</label>
                                            <input type="number" name="hook_length" class="form-input" step="0.01" x-model="hookLength" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Bend (mm)</label>
                                            <input type="number" name="bend_length" class="form-input" step="0.01" value="0" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-4 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs">Lap (mm)</label>
                                            <input type="number" name="lap_length" class="form-input" step="0.01" value="0" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Bar Count</label>
                                            <input type="number" name="bars_count" class="form-input" min="1" x-model="barsCount" @input="isManualCount = true" />
                                        </div>
                                        <div class="flex items-end">
                                            <label class="flex items-center gap-1 text-xs">
                                                <input type="checkbox" name="is_manual_count" value="1" class="rounded" x-model="isManualCount" @change="if (!isManualCount) updateBarsCount()" />
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
