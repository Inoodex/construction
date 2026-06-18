@extends('admin.layouts.master')

@section('title', 'New Journal Voucher')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Journal Voucher</h2>
        <a href="{{ route('admin.finance.journal-entries.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.journal-entries.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
                <div class="form-group">
                    <label for="journal_number">Journal # <span class="text-danger">*</span></label>
                    <input type="text" name="journal_number" id="journal_number" class="form-input" required value="{{ old('journal_number', $nextNumber) }}" />
                    @error('journal_number') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="date">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-input" required value="{{ old('date', date('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>Payment</option>
                        <option value="receipt" {{ old('type') == 'receipt' ? 'selected' : '' }}>Receipt</option>
                        <option value="contra" {{ old('type') == 'contra' ? 'selected' : '' }}>Contra</option>
                        <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="pt-2 text-xs text-white-dark">Debits must equal credits</div>
                </div>
                <div class="form-group md:col-span-4">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-input" value="{{ old('description') }}" placeholder="Brief description of the transaction" />
                </div>
            </div>

            <div class="mb-3 mt-6 flex items-center justify-between">
                <h4 class="text-base font-semibold">Journal Lines</h4>
                <button type="button" id="add-line" class="btn btn-sm btn-secondary">+ Add Line</button>
            </div>

            @error('items')
                <div class="mb-3 text-sm text-danger">{{ $message }}</div>
            @enderror

            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto" id="journal-lines">
                    <thead>
                        <tr>
                            <th class="w-1/4">Account</th>
                            <th>Description</th>
                            <th class="w-28">Debit (৳)</th>
                            <th class="w-28">Credit (৳)</th>
                            <th class="w-16 text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(old('items'))
                            @foreach(old('items') as $i => $item)
                                <tr>
                                    <td>
                                        <select name="items[{{ $i }}][account_id]" class="form-select" required>
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}" {{ ($item['account_id'] ?? '') == $acc->id ? 'selected' : '' }}>
                                                    {{ $acc->account_code }} - {{ $acc->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="items[{{ $i }}][description]" class="form-input" value="{{ $item['description'] ?? '' }}" /></td>
                                    <td><input type="number" step="0.01" min="0" name="items[{{ $i }}][debit_amount]" class="form-input debit-input" value="{{ $item['debit_amount'] ?? 0 }}" oninput="updateTotals()" /></td>
                                    <td><input type="number" step="0.01" min="0" name="items[{{ $i }}][credit_amount]" class="form-input credit-input" value="{{ $item['credit_amount'] ?? 0 }}" oninput="updateTotals()" /></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-line" onclick="this.closest('tr').remove(); updateTotals();">×</button></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>
                                    <select name="items[0][account_id]" class="form-select" required>
                                        <option value="">Select Account</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->account_code }} - {{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="items[0][description]" class="form-input" /></td>
                                <td><input type="number" step="0.01" min="0" name="items[0][debit_amount]" class="form-input debit-input" value="0" oninput="updateTotals()" /></td>
                                <td><input type="number" step="0.01" min="0" name="items[0][credit_amount]" class="form-input credit-input" value="0" oninput="updateTotals()" /></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-line" onclick="this.closest('tr').remove(); updateTotals();">×</button></td>
                            </tr>
                            <tr>
                                <td>
                                    <select name="items[1][account_id]" class="form-select" required>
                                        <option value="">Select Account</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->account_code }} - {{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="items[1][description]" class="form-input" /></td>
                                <td><input type="number" step="0.01" min="0" name="items[1][debit_amount]" class="form-input debit-input" value="0" oninput="updateTotals()" /></td>
                                <td><input type="number" step="0.01" min="0" name="items[1][credit_amount]" class="form-input credit-input" value="0" oninput="updateTotals()" /></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-line" onclick="this.closest('tr').remove(); updateTotals();">×</button></td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="font-semibold">
                            <td colspan="2" class="text-right">Totals:</td>
                            <td><span id="total-debit">0.00</span></td>
                            <td><span id="total-credit">0.00</span></td>
                            <td class="text-center"><span id="balance-indicator" class="text-xs font-semibold">⛔ Unbalanced</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" id="post-btn" class="btn btn-primary px-10" disabled>Post Journal</button>
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
            </div>
        </form>
    </div>

<script>
var lineIndex = {{ old('items') ? count(old('items')) : 2 }};
var accounts = @json($accounts->map(fn($a) => ['id' => $a->id, 'code' => $a->account_code, 'name' => $a->name]));

document.getElementById('add-line').addEventListener('click', function () {
    var tbody = document.querySelector('#journal-lines tbody');
    var options = '<option value="">Select Account</option>';
    accounts.forEach(function (a) {
        options += '<option value="' + a.id + '">' + a.code + ' - ' + a.name + '</option>';
    });
    var row = document.createElement('tr');
    row.innerHTML = `
        <td><select name="items[${lineIndex}][account_id]" class="form-select" required>${options}</select></td>
        <td><input type="text" name="items[${lineIndex}][description]" class="form-input" /></td>
        <td><input type="number" step="0.01" min="0" name="items[${lineIndex}][debit_amount]" class="form-input debit-input" value="0" oninput="updateTotals()" /></td>
        <td><input type="number" step="0.01" min="0" name="items[${lineIndex}][credit_amount]" class="form-input credit-input" value="0" oninput="updateTotals()" /></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-line" onclick="this.closest('tr').remove(); updateTotals();">×</button></td>
    `;
    tbody.appendChild(row);
    lineIndex++;
});

function updateTotals() {
    var debit = 0, credit = 0;
    document.querySelectorAll('.debit-input').forEach(function (el) { debit += parseFloat(el.value) || 0; });
    document.querySelectorAll('.credit-input').forEach(function (el) { credit += parseFloat(el.value) || 0; });
    document.getElementById('total-debit').textContent = debit.toFixed(2);
    document.getElementById('total-credit').textContent = credit.toFixed(2);

    var balanced = Math.abs(debit - credit) < 0.01;
    var hasDebit = debit > 0;
    var hasCredit = credit > 0;
    var indicator = document.getElementById('balance-indicator');
    var btn = document.getElementById('post-btn');
    if (balanced && hasDebit && hasCredit) {
        indicator.textContent = '✅ Balanced';
        indicator.className = 'text-xs font-semibold text-success';
        btn.disabled = false;
    } else {
        indicator.textContent = '⛔ Unbalanced';
        indicator.className = 'text-xs font-semibold text-danger';
        btn.disabled = true;
    }
}

document.addEventListener('DOMContentLoaded', updateTotals);
</script>
@endsection
