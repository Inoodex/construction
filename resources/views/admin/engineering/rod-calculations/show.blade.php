@extends('admin.layouts.master')

@section('title', 'Rod & Concrete Calculation: ' . $rodCalculation->reference_no)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold uppercase text-primary">{{ $rodCalculation->title }}</h2>
            <p class="text-xs text-white-dark font-mono mt-0.5">{{ $rodCalculation->reference_no }} | Project: <span class="font-semibold text-black dark:text-white">{{ $rodCalculation->project->name ?? 'N/A' }}</span></p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($rodCalculation->isDraft())
                <form action="{{ route('admin.finance.rod-calculations.approve', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve this BBS & Concrete calculation?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success gap-1.5">Approve Sheet</button>
                </form>
            @endif
            @if($rodCalculation->isApproved())
                <form action="{{ route('admin.finance.rod-calculations.complete', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Mark as completed?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-info gap-1.5">Complete</button>
                </form>
                <form action="{{ route('admin.finance.rod-calculations.reopen', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Reopen to draft?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary gap-1.5">Reopen</button>
                </form>
            @endif
            @if(!$rodCalculation->isDraft())
                @if($rodCalculation->purchase_requisition_id)
                    <a href="{{ route('admin.procurement.requisitions.show', $rodCalculation->purchase_requisition_id) }}" class="btn btn-sm btn-outline-primary inline-flex items-center gap-1.5">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> -->
                        <span>View PR ({{ $rodCalculation->requisition->requisition_number ?? 'PR' }})</span>
                    </a>
                @else
                    <form action="{{ route('admin.finance.rod-calculations.create-pr', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Generate Purchase Requisition for Rebar & Concrete materials?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary inline-flex items-center gap-1.5 font-bold">
                            <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> -->
                            <span>Create PR</span>
                        </button>
                    </form>
                @endif
            @endif
            <form action="{{ route('admin.finance.rod-calculations.recalculate', $rodCalculation->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary gap-1.5">Recalculate</button>
            </form>
            <form action="{{ route('admin.finance.rod-calculations.duplicate', $rodCalculation->id) }}" method="POST" class="inline" onsubmit="return confirm('Duplicate this BBS & Concrete calculation sheet?');">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-info gap-1.5">Duplicate</button>
            </form>
            <a href="{{ route('admin.finance.rod-calculations.pdf', $rodCalculation->id) }}" target="_blank" class="btn btn-sm btn-outline-dark gap-1.5">PDF</a>
            <a href="{{ route('admin.finance.rod-calculations.excel', $rodCalculation->id) }}" class="btn btn-sm btn-outline-success gap-1.5">Excel</a>
            @if($rodCalculation->isDraft())
                <a href="{{ route('admin.finance.rod-calculations.edit', $rodCalculation->id) }}" class="btn btn-sm btn-outline-warning inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span>Edit Specs</span>
                </a>
            @endif
            <a href="{{ route('admin.finance.rod-calculations.index') }}" class="btn btn-sm btn-secondary inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span>Back</span>
            </a>
        </div>
    </div>

    {{-- Top Spec Cards --}}
    <div class="mt-6 flex flex-wrap gap-3">
        <div class="panel flex-1 min-w-[130px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase font-bold">Status</label>
            @php $sc = ['draft' => 'badge-outline-secondary', 'approved' => 'badge-outline-success', 'completed' => 'badge-outline-info']; @endphp
            <div><span class="badge {{ $sc[$rodCalculation->status] ?? '' }} capitalize">{{ $rodCalculation->status }}</span></div>
        </div>
        <div class="panel flex-1 min-w-[130px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase font-bold">Steel Grade</label>
            <p class="font-bold text-xs text-info">{{ $rodCalculation->steel_grade ?? 'Grade 60' }}</p>
        </div>
        <div class="panel flex-1 min-w-[130px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase font-bold">Concrete Grade</label>
            <p class="font-bold text-xs text-success">{{ $rodCalculation->concrete_grade ? App\Constants\ConcreteRatioConstants::GRADE_LABELS[$rodCalculation->concrete_grade] ?? $rodCalculation->concrete_grade : 'Not Set' }}</p>
        </div>
        <div class="panel flex-1 min-w-[130px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase font-bold">Revision</label>
            <p class="font-semibold text-xs">{{ $rodCalculation->revision ?? 'Rev 0' }}</p>
        </div>
        <div class="panel flex-1 min-w-[130px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase font-bold">Waste Allowance</label>
            <p class="text-xs font-semibold">Steel: {{ $rodCalculation->waste_percent ?? 0 }}% | Conc: {{ $rodCalculation->concrete_waste_percent ?? $rodCalculation->waste_percent ?? 0 }}%</p>
        </div>
        <div class="panel flex-1 min-w-[130px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase font-bold">Created By</label>
            <p class="text-xs font-semibold">{{ $rodCalculation->creator->name ?? '-' }}</p>
        </div>
    </div>

    {{-- Executive Summary Grid (Steel + Concrete + Grand Total) --}}
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Steel Weight --}}
        <div class="panel text-center flex flex-col items-center justify-center p-4 border-l-4 border-info">
            <label class="text-xs text-white-dark uppercase font-bold">Total Steel Reinforcement</label>
            <p class="text-2xl font-extrabold text-info mt-1">{{ number_format($summary['total_kg'], 2) }} <span class="text-xs font-normal text-white-dark">kg</span></p>
            <p class="text-xs font-semibold text-white-dark mt-0.5">Gross: {{ number_format($summary['gross_total_tons'], 3) }} Metric Tons</p>
            <p class="text-xs font-bold text-success mt-1">৳{{ number_format($summary['total_steel_cost'], 2) }}</p>
        </div>

        {{-- Concrete Volume --}}
        <div class="panel text-center flex flex-col items-center justify-center p-4 border-l-4 border-success">
            <label class="text-xs text-white-dark uppercase font-bold">Total Concrete Volume</label>
            <p class="text-2xl font-extrabold text-success mt-1">{{ number_format($summary['total_volume_m3'], 2) }} <span class="text-xs font-normal text-white-dark">m³</span></p>
            <p class="text-xs font-semibold text-white-dark mt-0.5">Gross: {{ number_format($summary['gross_volume_cft'], 1) }} CFT</p>
            <p class="text-xs font-bold text-success mt-1">৳{{ number_format($summary['total_concrete_cost'], 2) }}</p>
        </div>

        {{-- Concrete Material Quantities --}}
        <div class="panel text-center flex flex-col items-center justify-center p-4 border-l-4 border-warning">
            <label class="text-xs text-white-dark uppercase font-bold">Key Concrete Materials</label>
            <div class="text-xs font-semibold mt-1 space-y-0.5 text-black dark:text-white">
                <p>Cement: <span class="text-primary font-bold">{{ number_format($summary['gross_cement_bags'], 0) }} Bags</span></p>
                <p>Sand: <span class="text-warning font-bold">{{ number_format($summary['gross_sand_cft'], 0) }} CFT</span></p>
                <p>Aggregate: <span class="text-info font-bold">{{ number_format($summary['gross_aggregate_cft'], 0) }} CFT</span></p>
            </div>
        </div>

        {{-- Combined Grand Total Cost --}}
        <div class="panel text-center flex flex-col items-center justify-center p-4 bg-primary text-white shadow-lg">
            <label class="text-xs uppercase font-bold opacity-80">Combined Grand Total Cost</label>
            <p class="text-2xl font-black mt-1">৳{{ number_format($summary['grand_total_sheet_cost'], 2) }}</p>
            <span class="text-[10px] mt-1 opacity-90 block">Steel: ৳{{ number_format($summary['total_steel_cost'], 0) }} + Conc: ৳{{ number_format($summary['total_concrete_cost'], 0) }}</span>
        </div>
    </div>

    {{-- By Diameter Summary & Concrete Ratio Summary Panels (2 Columns) --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Steel Rebar By-Diameter Summary --}}
        <div class="panel">
            <div class="flex items-center justify-between mb-3 border-b pb-2 dark:border-gray-700">
                <h5 class="text-sm font-bold uppercase text-info">Steel Rebar Breakdown by Diameter</h5>
                <span class="badge badge-outline-info text-xs">{{ count($summary['by_diameter']) }} Sizes</span>
            </div>
            @if(count($summary['by_diameter']))
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                <th class="text-left py-2 px-2">Dia</th>
                                <th class="text-right py-2 px-2">Count</th>
                                <th class="text-right py-2 px-2">12m Sticks</th>
                                <th class="text-right py-2 px-2">Net (kg)</th>
                                <th class="text-right py-2 px-2">Net (Tons)</th>
                                @if(($summary['total_steel_cost'] ?? 0) > 0)
                                    <th class="text-right py-2 px-2">Cost (৳)</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summary['by_diameter'] as $d)
                                <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="py-2 px-2 font-bold text-info">Ø{{ $d['diameter'] }}mm</td>
                                    <td class="py-2 px-2 text-right">{{ number_format($d['bars_count']) }}</td>
                                    <td class="py-2 px-2 text-right font-bold text-primary">{{ number_format($d['commercial_12m_sticks']) }} pcs</td>
                                    <td class="py-2 px-2 text-right font-bold">{{ number_format($d['total_kg'], 2) }}</td>
                                    <td class="py-2 px-2 text-right font-bold text-info">{{ number_format($d['total_tons'], 3) }}T</td>
                                    @if(($summary['total_steel_cost'] ?? 0) > 0)
                                        <td class="py-2 px-2 text-right font-extrabold text-success">৳{{ number_format($d['estimated_cost'], 2) }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr class="border-t-2 dark:border-gray-600 font-extrabold bg-gray-100 dark:bg-gray-800">
                                <td class="py-2.5 px-2 uppercase">Total</td>
                                <td class="py-2.5 px-2 text-right">{{ number_format(collect($summary['by_diameter'])->sum('bars_count')) }}</td>
                                <td class="py-2.5 px-2 text-right text-primary">{{ number_format($summary['total_12m_sticks'] ?? 0) }} pcs</td>
                                <td class="py-2.5 px-2 text-right text-info">{{ number_format($summary['total_kg'], 2) }} kg</td>
                                <td class="py-2.5 px-2 text-right text-info">{{ number_format($summary['total_tons'], 3) }} T</td>
                                @if(($summary['total_steel_cost'] ?? 0) > 0)
                                    <td class="py-2.5 px-2 text-right text-success">৳{{ number_format(collect($summary['by_diameter'])->sum('estimated_cost'), 2) }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-white-dark py-4 text-center">No steel rebar items added yet.</p>
            @endif
        </div>

        {{-- Concrete Mix & Material Estimation Summary --}}
        <div class="panel">
            <div class="flex items-center justify-between mb-3 border-b pb-2 dark:border-gray-700">
                <h5 class="text-sm font-bold uppercase text-success">Concrete Mix &amp; Material Summary</h5>
                <span class="badge badge-outline-success text-xs">{{ $summary['concrete_grade'] ? App\Constants\ConcreteRatioConstants::GRADE_LABELS[$summary['concrete_grade']] ?? $summary['concrete_grade'] : 'Default Mix' }}</span>
            </div>

            <div class="space-y-2 text-xs">
                <div class="grid grid-cols-2 gap-2 bg-gray-50 dark:bg-gray-800 p-2.5 rounded border dark:border-gray-700">
                    <div>
                        <span class="text-white-dark block">Net Concrete Volume:</span>
                        <span class="font-bold text-sm text-success">{{ number_format($summary['total_volume_m3'], 4) }} m³ ({{ number_format($summary['total_volume_cft'], 2) }} CFT)</span>
                    </div>
                    <div>
                        <span class="text-white-dark block">Gross Vol (incl. {{ $summary['concrete_waste_percent'] }}% waste):</span>
                        <span class="font-bold text-sm text-warning">{{ number_format($summary['gross_volume_m3'], 4) }} m³ ({{ number_format($summary['gross_volume_cft'], 2) }} CFT)</span>
                    </div>
                </div>

                <table class="w-full text-xs mt-2 border-t dark:border-gray-700">
                    <thead>
                        <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <th class="text-left py-1.5 px-2">Material</th>
                            <th class="text-right py-1.5 px-2">Net Qty</th>
                            <th class="text-right py-1.5 px-2">Gross (w/ Waste)</th>
                            <th class="text-right py-1.5 px-2">Unit Rate</th>
                            <th class="text-right py-1.5 px-2">Est. Cost (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-1.5 px-2 font-semibold">Cement</td>
                            <td class="py-1.5 px-2 text-right">{{ number_format($summary['total_cement_bags'], 2) }} bags</td>
                            <td class="py-1.5 px-2 text-right font-bold text-primary">{{ number_format($summary['gross_cement_bags'], 2) }} bags</td>
                            <td class="py-1.5 px-2 text-right">৳{{ number_format($summary['cement_price_per_bag'], 2) }}</td>
                            <td class="py-1.5 px-2 text-right font-bold text-success">৳{{ number_format($summary['cement_cost'], 2) }}</td>
                        </tr>
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-1.5 px-2 font-semibold">Sand (Fine Agg)</td>
                            <td class="py-1.5 px-2 text-right">{{ number_format($summary['total_sand_cft'], 2) }} CFT</td>
                            <td class="py-1.5 px-2 text-right font-bold text-warning">{{ number_format($summary['gross_sand_cft'], 2) }} CFT</td>
                            <td class="py-1.5 px-2 text-right">৳{{ number_format($summary['sand_price_per_cft'], 2) }}</td>
                            <td class="py-1.5 px-2 text-right font-bold text-success">৳{{ number_format($summary['sand_cost'], 2) }}</td>
                        </tr>
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-1.5 px-2 font-semibold">Aggregate / Stone</td>
                            <td class="py-1.5 px-2 text-right">{{ number_format($summary['total_aggregate_cft'], 2) }} CFT</td>
                            <td class="py-1.5 px-2 text-right font-bold text-info">{{ number_format($summary['gross_aggregate_cft'], 2) }} CFT</td>
                            <td class="py-1.5 px-2 text-right">৳{{ number_format($summary['aggregate_price_per_cft'], 2) }}</td>
                            <td class="py-1.5 px-2 text-right font-bold text-success">৳{{ number_format($summary['aggregate_cost'], 2) }}</td>
                        </tr>
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-1.5 px-2 font-semibold">Water</td>
                            <td class="py-1.5 px-2 text-right">{{ number_format($summary['total_water_liters'], 1) }} L</td>
                            <td class="py-1.5 px-2 text-right font-bold">{{ number_format($summary['gross_water_liters'], 1) }} L</td>
                            <td class="py-1.5 px-2 text-right text-white-dark">-</td>
                            <td class="py-1.5 px-2 text-right text-white-dark">-</td>
                        </tr>
                        <tr class="border-t-2 dark:border-gray-600 font-extrabold bg-gray-100 dark:bg-gray-800">
                            <td colspan="4" class="py-2 px-2 uppercase">Total Concrete Cost</td>
                            <td class="py-2 px-2 text-right text-success font-black text-sm">৳{{ number_format($summary['total_concrete_cost'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Structural Members Panel --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4 border-b pb-3 dark:border-gray-700">
            <div>
                <h5 class="text-base font-bold uppercase text-primary">Structural Members &amp; Reinforcement Tables</h5>
                <p class="text-xs text-white-dark mt-0.5">Includes steel bar bending schedules and concrete volume material breakdown per member.</p>
            </div>
            @if($rodCalculation->isDraft())
                <button type="button" onclick="document.getElementById('addMemberForm').classList.toggle('hidden')" class="btn btn-primary btn-sm font-bold">+ Add Member</button>
            @endif
        </div>

        @if($rodCalculation->isDraft())
        {{-- Add Member Form --}}
        <div id="addMemberForm" class="mb-6 hidden rounded-xl border border-primary/30 bg-primary/5 p-4 dark:border-primary/20">
            <h6 class="text-xs font-bold uppercase text-primary mb-3">Add New Structural Member</h6>
            <form action="{{ route('admin.finance.rod-calculations.members.store', $rodCalculation->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-3 md:grid-cols-6" x-data="{ memberType: '' }">
                    <div>
                        <label class="text-xs font-semibold">Type *</label>
                        <select name="type" class="form-select text-xs" required x-model="memberType" x-show="memberType !== 'custom'" :disabled="memberType === 'custom'" @change="$el.selectedOptions[0]?.dataset.cover && (document.getElementById('defaultCover').value = $el.selectedOptions[0].dataset.cover)">
                            <option value="">Select Type</option>
                            @foreach(\App\Constants\RodMemberType::ALL as $type)
                                <option value="{{ $type }}" data-cover="{{ \App\Constants\RodMemberType::DEFAULT_COVER[$type] ?? 25 }}">{{ \App\Constants\RodMemberType::LABELS[$type] ?? $type }}</option>
                            @endforeach
                        </select>
                        <div x-show="memberType === 'custom'" x-cloak class="flex gap-1">
                            <input type="text" name="type" class="form-input text-xs" required placeholder="Enter type" :disabled="memberType !== 'custom'" />
                            <button type="button" class="btn btn-sm btn-outline-danger" @click="memberType = ''">✕</button>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold">Member Code *</label>
                        <input type="text" name="member_code" class="form-input text-xs" required placeholder="e.g. C1, B1, F1" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold">Member Qty *</label>
                        <input type="number" name="quantity" class="form-input text-xs" required value="1" min="1" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold">Cover (mm) *</label>
                        <input type="number" name="cover" id="defaultCover" class="form-input text-xs" required value="25" step="0.01" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold">Length (mm)</label>
                        <input type="number" name="length" class="form-input text-xs" step="0.01" placeholder="L in mm" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold">Width (mm)</label>
                        <input type="number" name="width" class="form-input text-xs" step="0.01" placeholder="W in mm" />
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-3">
                    <div>
                        <label class="text-xs font-semibold">Height (mm)</label>
                        <input type="number" name="height" class="form-input text-xs" step="0.01" placeholder="H in mm" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold">Depth (mm)</label>
                        <input type="number" name="depth" class="form-input text-xs" step="0.01" placeholder="D in mm" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold">Thickness (mm)</label>
                        <input type="number" name="thickness" class="form-input text-xs" step="0.01" placeholder="T in mm" />
                    </div>
                </div>
                <div class="mt-3">
                    <label class="text-xs font-semibold">Remarks / Location</label>
                    <input type="text" name="remarks" class="form-input text-xs" placeholder="e.g. Grid A1-A5" />
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" @click="document.getElementById('addMemberForm').classList.add('hidden')" class="btn btn-sm btn-outline-secondary">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary font-bold">Save Member</button>
                </div>
            </form>
        </div>
        @endif

        {{-- Members Accordion List --}}
        @forelse($rodCalculation->members as $member)
            @php
                $cbd = $member->concrete_breakdown;
                $memberKg = $member->bars->sum('total_weight');
                $mSum = $summary['by_member'][$member->id] ?? null;
            @endphp
            <div class="mb-4 rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden shadow-sm" x-data="{ open: false, editingMember: false }">
                {{-- Member Accordion Header Bar --}}
                <div class="flex flex-wrap items-center justify-between cursor-pointer px-4 py-3 bg-gray-50 dark:bg-gray-800/80 hover:bg-gray-100 dark:hover:bg-gray-800 transition" @click="open = !open">
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5L15 12L9 19"/></svg>
                        <span class="font-extrabold text-base text-primary">{{ $member->member_code }}</span>
                        <span class="badge badge-outline-primary text-xs capitalize">{{ \App\Constants\RodMemberType::LABELS[$member->type] ?? $member->type }}</span>
                        <span class="text-xs font-semibold text-white-dark">Qty: {{ $member->quantity }} pcs</span>
                        <span class="text-xs text-white-dark">Cover: {{ $member->cover }}mm</span>

                        {{-- Concrete Member Pill --}}
                        <div class="hidden lg:flex items-center gap-2 rounded-full bg-success/10 px-3 py-1 text-xs font-semibold text-success border border-success/20">
                            <span>Vol: {{ number_format($cbd['volume_m3'], 3) }} m³ ({{ number_format($cbd['volume_cft'], 1) }} CFT)</span>
                            <span>• Cement: {{ number_format($cbd['cement_bags'], 1) }} bags</span>
                            <span>• Sand: {{ number_format($cbd['sand_cft'], 1) }} cft</span>
                            <span>• Agg: {{ number_format($cbd['aggregate_cft'], 1) }} cft</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <span class="font-extrabold text-sm text-primary block">৳{{ number_format($mSum['total_member_cost'] ?? 0, 2) }}</span>
                            <span class="text-[11px] text-white-dark block font-semibold">Rod: <span class="text-info">৳{{ number_format($mSum['steel_cost'] ?? 0, 0) }}</span> | Conc: <span class="text-success">৳{{ number_format($mSum['concrete_cost'] ?? 0, 0) }}</span></span>
                        </div>

                        @if($rodCalculation->isDraft())
                            <button type="button" @click.stop="editingMember = !editingMember; open = true" class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold rounded border border-blue-300 text-primary hover:bg-blue-50 dark:border-blue-700 dark:hover:bg-blue-900 gap-1" title="Edit Member">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <!-- Edit Member -->
                            </button>
                            <form action="{{ route('admin.finance.rod-calculations.members.destroy', [$rodCalculation->id, $member->id]) }}" method="POST" onsubmit="return confirm('Delete member {{ $member->member_code }} and all its rebar entries?');" @click.stop>
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-7 h-7 rounded border border-red-300 text-danger hover:bg-red-50 hover:text-red-700 dark:border-red-700 dark:hover:bg-red-900" title="Delete member">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Member Edit Drawer --}}
                @if($rodCalculation->isDraft())
                <div x-show="editingMember" x-cloak class="p-4 bg-warning/5 border-b border-warning/20">
                    <h6 class="text-xs font-bold uppercase text-warning mb-2">Edit Member Specs: {{ $member->member_code }}</h6>
                    <form action="{{ route('admin.finance.rod-calculations.members.update', [$rodCalculation->id, $member->id]) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-3 md:grid-cols-6">
                            <div>
                                <label class="text-xs font-semibold">Type *</label>
                                <select name="type" class="form-select text-xs" required>
                                    @foreach(\App\Constants\RodMemberType::ALL as $t)
                                        <option value="{{ $t }}" {{ $member->type === $t ? 'selected' : '' }}>{{ \App\Constants\RodMemberType::LABELS[$t] ?? $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold">Code *</label>
                                <input type="text" name="member_code" class="form-input text-xs" required value="{{ $member->member_code }}" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold">Quantity *</label>
                                <input type="number" name="quantity" class="form-input text-xs" required value="{{ $member->quantity }}" min="1" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold">Cover (mm) *</label>
                                <input type="number" name="cover" class="form-input text-xs" required value="{{ $member->cover }}" step="0.01" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold">Length (mm)</label>
                                <input type="number" name="length" class="form-input text-xs" step="0.01" value="{{ $member->length }}" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold">Width (mm)</label>
                                <input type="number" name="width" class="form-input text-xs" step="0.01" value="{{ $member->width }}" />
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mt-2">
                            <div>
                                <label class="text-xs font-semibold">Height (mm)</label>
                                <input type="number" name="height" class="form-input text-xs" step="0.01" value="{{ $member->height }}" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold">Depth (mm)</label>
                                <input type="number" name="depth" class="form-input text-xs" step="0.01" value="{{ $member->depth }}" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold">Thickness (mm)</label>
                                <input type="number" name="thickness" class="form-input text-xs" step="0.01" value="{{ $member->thickness }}" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="text-xs font-semibold">Remarks</label>
                            <input type="text" name="remarks" class="form-input text-xs" value="{{ $member->remarks }}" />
                        </div>
                        <div class="mt-3 flex gap-2">
                            <button type="submit" class="btn btn-sm btn-warning font-bold">Update &amp; Recalculate Member</button>
                            <button type="button" @click="editingMember = false" class="btn btn-sm btn-outline-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- Member Body & Bars Table --}}
                <div x-show="open" x-collapse class="p-4 border-t dark:border-gray-800">
                    {{-- Member Dimensions & Concrete Pill --}}
                    <div class="mb-3 flex flex-wrap items-center justify-between gap-3 text-xs bg-gray-50 dark:bg-gray-800/50 p-2.5 rounded-lg border dark:border-gray-700">
                        <div class="text-white-dark font-medium">
                            <span class="font-bold text-black dark:text-white">Dimensions:</span>
                            @if($member->length) L: {{ $member->length }}mm @endif
                            @if($member->width) W: {{ $member->width }}mm @endif
                            @if($member->height) H: {{ $member->height }}mm @endif
                            @if($member->depth) D: {{ $member->depth }}mm @endif
                            @if($member->thickness) T: {{ $member->thickness }}mm @endif
                            @if($member->remarks) | <span class="italic text-primary">{{ $member->remarks }}</span> @endif
                        </div>

                        {{-- Concrete Detailed Pill --}}
                        <div class="flex items-center gap-3 font-semibold text-success">
                            <span>Conc Vol: <strong>{{ number_format($cbd['volume_m3'], 4) }} m³</strong> ({{ number_format($cbd['volume_cft'], 2) }} CFT)</span>
                            <span>• Cement: <strong>{{ number_format($cbd['cement_bags'], 2) }} Bags</strong></span>
                            <span>• Sand: <strong>{{ number_format($cbd['sand_cft'], 2) }} CFT</strong></span>
                            <span>• Agg: <strong>{{ number_format($cbd['aggregate_cft'], 2) }} CFT</strong></span>
                            <span>• Water: <strong>{{ number_format($cbd['water_liters'], 1) }} L</strong></span>
                        </div>
                    </div>

                    {{-- Member Cost Breakdown Strip --}}
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 text-xs bg-primary/5 dark:bg-primary/10 p-2.5 rounded-lg border border-primary/20">
                        <div class="font-semibold text-black dark:text-white">
                            <span>Steel Weight: <strong>{{ number_format($mSum['total_kg'] ?? 0, 2) }} kg</strong></span>
                            <span class="mx-2 text-gray-400">|</span>
                            <span>Rod Amount (w/ waste): <strong class="text-info">৳{{ number_format($mSum['steel_cost'] ?? 0, 2) }}</strong></span>
                        </div>
                        <div class="font-semibold text-black dark:text-white">
                            <span>Concrete Amount (w/ waste): <strong class="text-success">৳{{ number_format($mSum['concrete_cost'] ?? 0, 2) }}</strong></span>
                            <span class="mx-2 text-gray-400">|</span>
                            <span>Total Member Amount: <strong class="text-primary text-sm font-extrabold">৳{{ number_format($mSum['total_member_cost'] ?? 0, 2) }}</strong></span>
                        </div>
                    </div>

                    {{-- Rebar Bars Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                    <th class="text-left py-1.5 px-2">Bar Name</th>
                                    <th class="text-left py-1.5 px-2">Dir</th>
                                    <th class="text-right py-1.5 px-2">Dia</th>
                                    <th class="text-right py-1.5 px-2">Actual Size</th>
                                    <th class="text-right py-1.5 px-2">Spacing</th>
                                    <th class="text-right py-1.5 px-2">Hook</th>
                                    <th class="text-right py-1.5 px-2">Bend</th>
                                    <th class="text-right py-1.5 px-2">Lap</th>
                                    <th class="text-right py-1.5 px-2">Cutting L.</th>
                                    <th class="text-right py-1.5 px-2">Count</th>
                                    <th class="text-right py-1.5 px-2">Total L.</th>
                                    <th class="text-right py-1.5 px-2">Unit Wt</th>
                                    <th class="text-right py-1.5 px-2">Total Weight</th>
                                    @if($rodCalculation->isDraft())
                                        <th class="text-center py-1.5 px-2">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            @forelse($member->bars as $bar)
                                <tbody x-data="{
                                    editing: false,
                                    barDirection: '{{ in_array($bar->direction, \App\Constants\BarDirection::ALL) ? $bar->direction : 'OTHER' }}',
                                    customDirection: '{{ in_array($bar->direction, \App\Constants\BarDirection::ALL) ? '' : $bar->direction }}',
                                    diameter: '{{ $bar->diameter }}',
                                    actualSize: '{{ $bar->actual_size }}',
                                    spacing: '{{ $bar->spacing }}',
                                    hookLength: '{{ $bar->hook_length }}',
                                    barsCount: '{{ $bar->bars_count }}',
                                    isManualCount: {{ $bar->is_manual_count ? 'true' : 'false' }},
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
                                        const dir = (this.barDirection === 'OTHER' ? this.customDirection : this.barDirection || '').toUpperCase();

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
                                        const dir = (this.barDirection === 'OTHER' ? this.customDirection : this.barDirection || '').toUpperCase();
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
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                    <td class="py-2 px-2 font-bold text-black dark:text-white"><span x-show="!editing">{{ $bar->bar_name }}</span></td>
                                    <td class="py-2 px-2"><span x-show="!editing" class="badge badge-outline-secondary text-[10px]">{{ $bar->direction }}</span></td>
                                    <td class="py-2 px-2 text-right font-bold text-info"><span x-show="!editing">Ø{{ $bar->diameter }}mm</span></td>
                                    <td class="py-2 px-2 text-right"><span x-show="!editing">{{ number_format($bar->actual_size, 0) }}mm</span></td>
                                    <td class="py-2 px-2 text-right"><span x-show="!editing">{{ $bar->spacing ? number_format($bar->spacing, 0) . 'mm' : 'manual' }}</span></td>
                                    <td class="py-2 px-2 text-right"><span x-show="!editing">{{ number_format($bar->hook_length, 0) }}</span></td>
                                    <td class="py-2 px-2 text-right"><span x-show="!editing">{{ number_format($bar->bend_length, 0) }}</span></td>
                                    <td class="py-2 px-2 text-right"><span x-show="!editing">{{ number_format($bar->lap_length, 0) }}</span></td>
                                    <td class="py-2 px-2 text-right font-bold text-black dark:text-white"><span x-show="!editing">{{ number_format($bar->cutting_length, 0) }}mm</span></td>
                                    <td class="py-2 px-2 text-right font-semibold"><span x-show="!editing">{{ $bar->bars_count }}</span></td>
                                    <td class="py-2 px-2 text-right font-mono"><span x-show="!editing">{{ number_format($bar->total_length, 0) }}mm</span></td>
                                    <td class="py-2 px-2 text-right text-white-dark"><span x-show="!editing">{{ number_format($bar->unit_weight, 4) }}</span></td>
                                    <td class="py-2 px-2 text-right font-extrabold text-primary"><span x-show="!editing">{{ number_format($bar->total_weight, 2) }} kg</span></td>
                                    @if($rodCalculation->isDraft())
                                        <td class="py-2 px-2 text-center">
                                            <div class="flex items-center justify-center gap-1" x-show="!editing">
                                                <button type="button" @click="editing = true" class="inline-flex items-center justify-center w-6 h-6 rounded border border-blue-300 text-primary hover:bg-blue-50 dark:border-blue-700 dark:hover:bg-blue-900" title="Edit bar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </button>
                                                <form action="{{ route('admin.finance.rod-calculations.bars.destroy', [$rodCalculation->id, $member->id, $bar->id]) }}" method="POST" onsubmit="return confirm('Delete this bar?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center w-6 h-6 rounded border border-red-300 text-danger hover:bg-red-50 dark:border-red-700 dark:hover:bg-red-900" title="Delete bar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
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
                                                    <label class="text-xs font-semibold">Bar Name *</label>
                                                    <input type="text" name="bar_name" class="form-input text-xs" required value="{{ $bar->bar_name }}" />
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold">Direction *</label>
                                                    <select name="direction" class="form-select text-xs" required x-model="barDirection" @change="updateActualSize()" x-show="barDirection !== 'OTHER'" :disabled="barDirection === 'OTHER'">
                                                        <option value="">Select</option>
                                                        @foreach(\App\Constants\BarDirection::ALL as $dir)
                                                            <option value="{{ $dir }}" {{ $bar->direction === $dir ? 'selected' : '' }}>{{ $dir }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div x-show="barDirection === 'OTHER'" x-cloak class="flex gap-1">
                                                        <input type="text" name="direction" class="form-input text-xs" required placeholder="Enter direction" x-model="customDirection" @input="updateActualSize()" :disabled="barDirection !== 'OTHER'" />
                                                        <button type="button" class="btn btn-sm btn-outline-danger" @click="barDirection = ''">✕</button>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold">Dia (mm) *</label>
                                                    <select name="diameter" class="form-select text-xs" required x-model="diameter" @change="updateHookLength()">
                                                        @foreach(\App\Constants\RodCalculationConstants::DIAMETERS as $d)
                                                            <option value="{{ $d }}" {{ $bar->diameter == $d ? 'selected' : '' }}>{{ $d }}mm</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold">Actual Size *</label>
                                                    <input type="number" name="actual_size" class="form-input text-xs" required step="0.01" x-model="actualSize" @input="updateBarsCount()" />
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold">Spacing (mm)</label>
                                                    <input type="number" name="spacing" class="form-input text-xs" step="0.01" x-model="spacing" @input="updateBarsCount()" />
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold">Hook (mm)</label>
                                                    <input type="number" name="hook_length" class="form-input text-xs" step="0.01" x-model="hookLength" />
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold">Bend (mm)</label>
                                                    <input type="number" name="bend_length" class="form-input text-xs" step="0.01" value="{{ $bar->bend_length }}" />
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-4 gap-2 mt-2">
                                                <div>
                                                    <label class="text-xs font-semibold">Lap (mm)</label>
                                                    <input type="number" name="lap_length" class="form-input text-xs" step="0.01" value="{{ $bar->lap_length }}" />
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold">Bar Count</label>
                                                    <input type="number" name="bars_count" class="form-input text-xs" min="1" x-model="barsCount" @input="isManualCount = true" />
                                                </div>
                                                <div class="flex items-end">
                                                    <label class="flex items-center gap-1 text-xs font-semibold">
                                                        <input type="checkbox" name="is_manual_count" value="1" class="rounded" x-model="isManualCount" @change="if (!isManualCount) updateBarsCount()" />
                                                        Manual count
                                                    </label>
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold">Sort Order</label>
                                                    <input type="number" name="sort_order" class="form-input text-xs" value="{{ $bar->sort_order }}" />
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2 mt-2">
                                                <div>
                                                    <label class="text-xs font-semibold">Remarks</label>
                                                    <input type="text" name="remarks" class="form-input text-xs" value="{{ $bar->remarks }}" />
                                                </div>
                                            </div>
                                            <div class="flex gap-2 mt-3">
                                                <button type="submit" class="btn btn-sm btn-primary font-bold">Save Bar</button>
                                                <button type="button" @click="editing = false" class="btn btn-sm btn-outline-secondary">Cancel</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endif
                                </tbody>
                            @empty
                                <tbody><tr><td colspan="{{ $rodCalculation->isDraft() ? 14 : 13 }}" class="text-center py-4 text-white-dark">No rebar entries added to this member yet.</td></tr></tbody>
                            @endforelse
                        </table>
                    </div>

                    {{-- Add Bar Form --}}
                    @if($rodCalculation->isDraft())
                        <div class="mt-4 rounded-lg border p-3.5 bg-gray-50/50 dark:bg-gray-800/40 dark:border-gray-700" x-data="{
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
                            <button type="button" @click="showBarForm = !showBarForm" class="btn btn-outline-primary btn-sm font-bold">+ Add Rebar Entry</button>
                            <div x-show="showBarForm" x-collapse class="mt-3">
                                <form action="{{ route('admin.finance.rod-calculations.bars.store', [$rodCalculation->id, $member->id]) }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-4 gap-2 md:grid-cols-7">
                                        <div>
                                            <label class="text-xs font-semibold">Bar Name *</label>
                                            <input type="text" name="bar_name" class="form-input text-xs" required placeholder="e.g. Main Bar / Stirrup" />
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold">Direction *</label>
                                            <select name="direction" class="form-select text-xs" required x-model="barDirection" @change="updateActualSize()" x-show="barDirection !== 'OTHER'" :disabled="barDirection === 'OTHER'">
                                                <option value="">Select</option>
                                                @foreach(\App\Constants\BarDirection::ALL as $dir)
                                                    <option value="{{ $dir }}">{{ $dir }}</option>
                                                @endforeach
                                            </select>
                                            <div x-show="barDirection === 'OTHER'" x-cloak class="flex gap-1">
                                                <input type="text" name="direction" class="form-input text-xs" required placeholder="Enter direction" :disabled="barDirection !== 'OTHER'" />
                                                <button type="button" class="btn btn-sm btn-outline-danger" @click="barDirection = ''">✕</button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold">Dia (mm) *</label>
                                            <select name="diameter" class="form-select text-xs" required x-model="diameter" @change="updateHookLength()">
                                                @foreach(\App\Constants\RodCalculationConstants::DIAMETERS as $d)
                                                    <option value="{{ $d }}">{{ $d }}mm</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold">Actual Size *</label>
                                            <input type="number" name="actual_size" class="form-input text-xs" required step="0.01" placeholder="mm" x-model="actualSize" @input="updateBarsCount()" />
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold">Spacing (mm)</label>
                                            <input type="number" name="spacing" class="form-input text-xs" step="0.01" x-model="spacing" @input="updateBarsCount()" placeholder="e.g. 150" />
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold">Hook (mm)</label>
                                            <input type="number" name="hook_length" class="form-input text-xs" step="0.01" x-model="hookLength" />
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold">Bend (mm)</label>
                                            <input type="number" name="bend_length" class="form-input text-xs" step="0.01" value="0" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-4 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs font-semibold">Lap (mm)</label>
                                            <input type="number" name="lap_length" class="form-input text-xs" step="0.01" value="0" />
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold">Bar Count</label>
                                            <input type="number" name="bars_count" class="form-input text-xs" min="1" x-model="barsCount" @input="isManualCount = true" />
                                        </div>
                                        <div class="flex items-end">
                                            <label class="flex items-center gap-1 text-xs font-semibold">
                                                <input type="checkbox" name="is_manual_count" value="1" class="rounded" x-model="isManualCount" @change="if (!isManualCount) updateBarsCount()" />
                                                Manual count
                                            </label>
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold">Sort Order</label>
                                            <input type="number" name="sort_order" class="form-input text-xs" value="0" />
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label class="text-xs font-semibold">Remarks</label>
                                        <input type="text" name="remarks" class="form-input text-xs" placeholder="e.g. 2 nos extra top" />
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm mt-3 font-bold">Save Bar Entry</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center text-white-dark py-6">No structural members added to this sheet yet. Click "+ Add Member" above to start.</p>
        @endforelse
    </div>
@endsection
