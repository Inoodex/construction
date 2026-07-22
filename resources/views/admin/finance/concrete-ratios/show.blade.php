@extends('admin.layouts.master')

@section('title', 'CR: ' . $concreteRatio->reference_no)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">{{ $concreteRatio->title }}</h2>
            <p class="text-xs text-white-dark font-mono">{{ $concreteRatio->reference_no }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($concreteRatio->isDraft())
                <form action="{{ route('admin.finance.concrete-ratios.approve', $concreteRatio->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve this ratio?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                </form>
                <a href="{{ route('admin.finance.concrete-ratios.edit', $concreteRatio->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
            @endif
            @if($concreteRatio->isApproved())
                <form action="{{ route('admin.finance.concrete-ratios.complete', $concreteRatio->id) }}" method="POST" class="inline" onsubmit="return confirm('Mark as completed?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-info">Complete</button>
                </form>
                <form action="{{ route('admin.finance.concrete-ratios.reopen', $concreteRatio->id) }}" method="POST" class="inline" onsubmit="return confirm('Reopen to draft?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Reopen</button>
                </form>
            @endif
            <form action="{{ route('admin.finance.concrete-ratios.recalculate', $concreteRatio->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">Recalculate</button>
            </form>
            <a href="{{ route('admin.finance.concrete-ratios.pdf', $concreteRatio->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">PDF</a>
            <a href="{{ route('admin.finance.concrete-ratios.index') }}" class="btn btn-sm btn-secondary">
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
            <span class="badge {{ $sc[$concreteRatio->status] ?? '' }} capitalize">{{ $concreteRatio->status }}</span>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Project</label>
            <p class="font-semibold text-xs">{{ $concreteRatio->project->name ?? 'N/A' }}</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Grade</label>
            <p class="font-semibold text-xs">{{ $concreteRatio->grade ?? '-' }}</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Waste %</label>
            <p class="text-xs">{{ $concreteRatio->waste_percent ?? '0' }}%</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Source BBS</label>
            <p class="text-xs">{{ $concreteRatio->rodCalculation->reference_no ?? '-' }}</p>
        </div>
        <div class="panel flex-1 min-w-[140px] py-2 px-3">
            <label class="text-[10px] text-white-dark uppercase">Created By</label>
            <p class="text-xs">{{ $concreteRatio->creator->name ?? '-' }}</p>
        </div>
    </div>

    {{-- Summary --}}
    <div class="mt-6 flex flex-wrap gap-6">
        <div class="panel text-center flex flex-col items-center justify-center flex-1 min-w-[180px]">
            <label class="text-xs text-white-dark">Total Volume</label>
            <p class="text-3xl font-bold text-primary mt-2">{{ number_format($summary['total_volume_m3'], 4) }}</p>
            <p class="text-xs text-white-dark">m³</p>
        </div>
        <div class="flex-1 min-w-[180px] grid grid-cols-2 gap-4">
            <div class="panel text-center flex flex-col items-center justify-center">
                <label class="text-xs text-white-dark">Cement</label>
                <p class="text-2xl font-bold mt-2">{{ number_format($summary['total_cement_bags'], 2) }}</p>
                <p class="text-xs text-white-dark">bags</p>
            </div>
            <div class="panel text-center flex flex-col items-center justify-center">
                <label class="text-xs text-white-dark">Sand</label>
                <p class="text-2xl font-bold mt-2">{{ number_format($summary['total_sand_m3'], 4) }}</p>
                <p class="text-xs text-white-dark">m³</p>
            </div>
            <div class="panel text-center flex flex-col items-center justify-center">
                <label class="text-xs text-white-dark">Aggregate</label>
                <p class="text-2xl font-bold mt-2">{{ number_format($summary['total_aggregate_m3'], 4) }}</p>
                <p class="text-xs text-white-dark">m³</p>
            </div>
            <div class="panel text-center flex flex-col items-center justify-center">
                <label class="text-xs text-white-dark">Water</label>
                <p class="text-2xl font-bold mt-2">{{ number_format($summary['total_water_liters'], 2) }}</p>
                <p class="text-xs text-white-dark">liters</p>
            </div>
        </div>
    </div>

    <script>
        function copyBbs() {
            return {
                selectedBbs: '',
                bbsList: [],
                bbsMembers: [],

                init() {
                    this.loadBbsList();
                },

                async loadBbsList() {
                    try {
                        const resp = await fetch(`/dashboard/finance/concrete-ratios/bbs-by-project/{{ $concreteRatio->project_id }}`, {
                            headers: { 'Accept': 'application/json' },
                        });
                        const data = await resp.json();
                        this.bbsList = data;
                        const sel = this.$refs.bbsSelect;
                        sel.innerHTML = '<option value="">-- Select BBS --</option>';
                        data.forEach(bbs => {
                            const opt = document.createElement('option');
                            opt.value = bbs.id;
                            opt.textContent = bbs.reference_no + ' — ' + bbs.title + ' (' + bbs.members_count + ' members)';
                            sel.appendChild(opt);
                        });
                    } catch (e) {
                        console.error('Failed to load BBS list:', e);
                    }
                },

                async loadBbsMembers() {
                    this.bbsMembers = [];
                    const val = this.$refs.bbsSelect.value;
                    if (!val) return;

                    try {
                        const resp = await fetch(`/dashboard/finance/concrete-ratios/copy-from-bbs`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ rod_calculation_id: val }),
                        });
                        this.bbsMembers = await resp.json();
                    } catch (e) {
                        console.error('Failed to load BBS members:', e);
                    }
                },

                async copyMembers() {
                    const val = this.$refs.bbsSelect.value;
                    if (!val) return;

                    try {
                        const resp = await fetch(`/dashboard/finance/concrete-ratios/{{ $concreteRatio->id }}/copy-members`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ rod_calculation_id: val }),
                        });

                        if (resp.ok) {
                            window.location.reload();
                        }
                    } catch (e) {
                        console.error('Failed to copy members:', e);
                    }
                },
            };
        }
    </script>

    {{-- Copy from BBS --}}
    @if($concreteRatio->isDraft() && $concreteRatio->members->isEmpty())
    <div class="panel mt-6" x-data="copyBbs()" x-init="loadBbsList()">
        <div class="flex items-center gap-3 mb-3">
            <h5 class="text-sm font-semibold">Copy Members from BBS</h5>
            <span class="text-xs text-white-dark">(optional — or add members manually below)</span>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="text-xs">Select Rod Calculation</label>
                <select class="form-select" @change="loadBbsMembers()" x-ref="bbsSelect">
                    <option value="">-- Select BBS --</option>
                </select>
            </div>
        </div>

        <div x-show="bbsMembers.length > 0" x-cloak class="mt-4">
            <p class="text-xs font-semibold mb-2" x-text="bbsMembers.length + ' member(s) will be copied:'"></p>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th class="text-left py-1">Type</th>
                            <th class="text-left">Code</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">L (mm)</th>
                            <th class="text-right">W (mm)</th>
                            <th class="text-right">H (mm)</th>
                            <th class="text-right">Cover</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(m, idx) in bbsMembers" :key="idx">
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-1" x-text="m.type"></td>
                                <td x-text="m.member_code"></td>
                                <td class="text-right" x-text="m.quantity"></td>
                                <td class="text-right" x-text="m.length ?? '-'"></td>
                                <td class="text-right" x-text="m.width ?? '-'"></td>
                                <td class="text-right" x-text="m.height ?? '-'"></td>
                                <td class="text-right" x-text="m.cover ?? '-'"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <button type="button" @click="copyMembers()" class="btn btn-sm btn-primary">Copy Members</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Members --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-base font-semibold">Structural Members</h5>
            @if($concreteRatio->isDraft())
                <button type="button" onclick="document.getElementById('addMemberForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Add Member</button>
            @endif
        </div>

        @if($concreteRatio->isDraft())
        {{-- Add Member Form --}}
        <div id="addMemberForm" class="mb-5 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.concrete-ratios.members.store', $concreteRatio->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-3 md:grid-cols-5" x-data="{ memberType: '' }">
                    <div>
                        <label class="text-xs">Type *</label>
                        <select name="type" class="form-select" required x-model="memberType" x-show="memberType !== 'custom'">
                            <option value="">Select Type</option>
                            @foreach(\App\Constants\RodMemberType::ALL as $type)
                                <option value="{{ $type }}">{{ \App\Constants\RodMemberType::LABELS[$type] ?? $type }}</option>
                            @endforeach
                        </select>
                        <div x-show="memberType === 'custom'" x-cloak class="flex gap-1">
                            <input type="text" name="type" class="form-input" required placeholder="Enter type" />
                            <button type="button" class="btn btn-sm btn-outline-danger" @click="memberType = ''">✕</button>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs">Code *</label>
                        <input type="text" name="member_code" class="form-input" required placeholder="e.g. F1" />
                    </div>
                    <div>
                        <label class="text-xs">Qty *</label>
                        <input type="number" name="quantity" class="form-input" required value="1" min="1" />
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
                <div class="grid grid-cols-4 gap-3 mt-2">
                    <div>
                        <label class="text-xs">Cement (bags)</label>
                        <input type="number" name="cement_bags" class="form-input" step="0.01" min="0" />
                    </div>
                    <div>
                        <label class="text-xs">Sand (m³)</label>
                        <input type="number" name="sand_m3" class="form-input" step="0.0001" min="0" />
                    </div>
                    <div>
                        <label class="text-xs">Aggregate (m³)</label>
                        <input type="number" name="aggregate_m3" class="form-input" step="0.0001" min="0" />
                    </div>
                    <div>
                        <label class="text-xs">Water (liters)</label>
                        <input type="number" name="water_liters" class="form-input" step="0.01" min="0" />
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

        {{-- Members Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b dark:border-gray-700">
                        <th class="text-left py-2">Type</th>
                        <th class="text-left">Code</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">L (mm)</th>
                        <th class="text-right">W (mm)</th>
                        <th class="text-right">H (mm)</th>
                        <th class="text-right">Volume (m³)</th>
                        <th class="text-right">Cement</th>
                        <th class="text-right">Sand (m³)</th>
                        <th class="text-right">Agg (m³)</th>
                        <th class="text-right">Water (L)</th>
                        @if($concreteRatio->isDraft())
                            <th class="text-center">Action</th>
                        @endif
                    </tr>
                </thead>
                    @forelse($concreteRatio->members as $member)
                        <tbody x-data="{ editing: false }">
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-1 font-semibold">
                                <span x-show="!editing">{{ \App\Constants\RodMemberType::LABELS[$member->type] ?? $member->type }}</span>
                            </td>
                            <td>
                                <span x-show="!editing">{{ $member->member_code }}</span>
                            </td>
                            <td class="text-right">
                                <span x-show="!editing">{{ $member->quantity }}</span>
                            </td>
                            <td class="text-right">
                                <span x-show="!editing">{{ $member->length ?? '-' }}</span>
                            </td>
                            <td class="text-right">
                                <span x-show="!editing">{{ $member->width ?? '-' }}</span>
                            </td>
                            <td class="text-right">
                                <span x-show="!editing">{{ $member->height ?? '-' }}</span>
                            </td>
                            <td class="text-right font-semibold">
                                <span x-show="!editing">{{ number_format($member->volume_m3, 4) }}</span>
                            </td>
                            <td class="text-right">
                                <span x-show="!editing">{{ number_format($member->cement_bags, 2) }}</span>
                            </td>
                            <td class="text-right">
                                <span x-show="!editing">{{ number_format($member->sand_m3, 4) }}</span>
                            </td>
                            <td class="text-right">
                                <span x-show="!editing">{{ number_format($member->aggregate_m3, 4) }}</span>
                            </td>
                            <td class="text-right font-semibold text-primary">
                                <span x-show="!editing">{{ number_format($member->water_liters, 2) }}</span>
                            </td>
                            @if($concreteRatio->isDraft())
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1" x-show="!editing">
                                        <button type="button" @click="editing = true" class="inline-flex items-center justify-center w-5 h-5 rounded border border-blue-300 text-primary hover:bg-blue-50 hover:text-blue-700 dark:border-blue-700 dark:hover:bg-blue-900" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <form action="{{ route('admin.finance.concrete-ratios.members.destroy', [$concreteRatio->id, $member->id]) }}" method="POST" onsubmit="return confirm('Delete this member?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-5 h-5 rounded border border-red-300 text-danger hover:bg-red-50 hover:text-red-700 dark:border-red-700 dark:hover:bg-red-900" title="Delete member">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                        {{-- Inline Edit Form --}}
                        @if($concreteRatio->isDraft())
                        <tr x-show="editing" x-cloak class="bg-gray-50 dark:bg-gray-800">
                            <td colspan="12" class="p-3">
                                <form action="{{ route('admin.finance.concrete-ratios.members.update', [$concreteRatio->id, $member->id]) }}" method="POST" class="space-y-3">
                                    @csrf @method('PUT')
                                    <div class="grid grid-cols-2 gap-3 md:grid-cols-5">
                                        <div>
                                            <label class="text-xs">Type *</label>
                                            <select name="type" class="form-select" required>
                                                @foreach(\App\Constants\RodMemberType::ALL as $type)
                                                    <option value="{{ $type }}" {{ $member->type === $type ? 'selected' : '' }}>{{ \App\Constants\RodMemberType::LABELS[$type] ?? $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs">Code *</label>
                                            <input type="text" name="member_code" class="form-input" required value="{{ $member->member_code }}" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Qty *</label>
                                            <input type="number" name="quantity" class="form-input" required value="{{ $member->quantity }}" min="1" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Length (mm)</label>
                                            <input type="number" name="length" class="form-input" step="0.01" value="{{ $member->length }}" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Width (mm)</label>
                                            <input type="number" name="width" class="form-input" step="0.01" value="{{ $member->width }}" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="text-xs">Height (mm)</label>
                                            <input type="number" name="height" class="form-input" step="0.01" value="{{ $member->height }}" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Depth (mm)</label>
                                            <input type="number" name="depth" class="form-input" step="0.01" value="{{ $member->depth }}" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Thickness (mm)</label>
                                            <input type="number" name="thickness" class="form-input" step="0.01" value="{{ $member->thickness }}" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-4 gap-3">
                                        <div>
                                            <label class="text-xs">Cement (bags)</label>
                                            <input type="number" name="cement_bags" class="form-input" step="0.01" min="0" value="{{ $member->cement_bags }}" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Sand (m³)</label>
                                            <input type="number" name="sand_m3" class="form-input" step="0.0001" min="0" value="{{ $member->sand_m3 }}" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Aggregate (m³)</label>
                                            <input type="number" name="aggregate_m3" class="form-input" step="0.0001" min="0" value="{{ $member->aggregate_m3 }}" />
                                        </div>
                                        <div>
                                            <label class="text-xs">Water (liters)</label>
                                            <input type="number" name="water_liters" class="form-input" step="0.01" min="0" value="{{ $member->water_liters }}" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="text-xs">Remarks</label>
                                            <input type="text" name="remarks" class="form-input" value="{{ $member->remarks }}" />
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        <button type="button" @click="editing = false" class="btn btn-sm btn-outline-secondary">Cancel</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        </tbody>
                        @endif
                    @empty
                        <tbody><tr><td colspan="{{ $concreteRatio->isDraft() ? 12 : 11 }}" class="text-center py-2">No members added yet.</td></tr></tbody>
                    @endforelse
                </table>
        </div>
    </div>
@endsection
