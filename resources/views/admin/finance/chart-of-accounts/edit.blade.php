@extends('admin.layouts.master')

@section('title', 'Edit Account')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Account</h2>
        <a href="{{ route('admin.finance.chart-of-accounts.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.chart-of-accounts.update', $account) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="account_code">Account Code <span class="text-danger">*</span></label>
                    <input type="text" name="account_code" id="account_code" class="form-input" required value="{{ old('account_code', $account->account_code) }}" />
                    @error('account_code') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="name">Account Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $account->name) }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-select" required onchange="updateNormalBalance()">
                        <option value="asset" {{ old('type', $account->type) == 'asset' ? 'selected' : '' }}>Asset</option>
                        <option value="liability" {{ old('type', $account->type) == 'liability' ? 'selected' : '' }}>Liability</option>
                        <option value="equity" {{ old('type', $account->type) == 'equity' ? 'selected' : '' }}>Equity</option>
                        <option value="income" {{ old('type', $account->type) == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ old('type', $account->type) == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    @error('type') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="normal_balance">Normal Balance <span class="text-danger">*</span></label>
                    <select name="normal_balance" id="normal_balance" class="form-select" required>
                        <option value="debit" {{ old('normal_balance', $account->normal_balance) == 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="credit" {{ old('normal_balance', $account->normal_balance) == 'credit' ? 'selected' : '' }}>Credit</option>
                    </select>
                    @error('normal_balance') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="parent_id">Parent Account</label>
                    <select name="parent_id" id="parent_id" class="form-select">
                        <option value="">— None (Top Level) —</option>
                        @foreach($parentAccounts as $p)
                            <option value="{{ $p->id }}" {{ old('parent_id', $account->parent_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->account_code }} - {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="2">{{ old('description', $account->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $account->is_active) ? 'checked' : '' }} class="form-checkbox" />
                        <span>Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Account</button>
                <button type="button" onclick="window.location.href='{{ route('admin.finance.chart-of-accounts.index') }}'" class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>

<script>
function updateNormalBalance() {
    var type = document.getElementById('type').value;
    var nb = document.getElementById('normal_balance');
    if (type === 'asset' || type === 'expense') {
        nb.value = 'debit';
    } else if (type === 'liability' || type === 'equity' || type === 'income') {
        nb.value = 'credit';
    }
}
</script>
@endsection
