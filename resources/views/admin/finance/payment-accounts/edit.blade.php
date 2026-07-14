@extends('admin.layouts.master')

@section('title', 'Edit Payment Account')

@section('content')
    <h2 class="text-xl font-semibold uppercase">Edit Payment Account</h2>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.payment-accounts.update', $paymentAccount->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Account Name *</label>
                    <input type="text" name="name" class="form-input mt-1" required value="{{ old('name', $paymentAccount->name) }}" />
                </div>
                <div>
                    <label class="text-xs text-white-dark">Account Type *</label>
                    <select name="type" class="form-select mt-1" required>
                        <option value="bank" {{ old('type', $paymentAccount->type) == 'bank' ? 'selected' : '' }}>Bank Account</option>
                        <option value="mfs" {{ old('type', $paymentAccount->type) == 'mfs' ? 'selected' : '' }}>MFS (Mobile Financial Service)</option>
                        <option value="cash" {{ old('type', $paymentAccount->type) == 'cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Account Number / Mobile No.</label>
                    <input type="text" name="account_number" class="form-input mt-1" value="{{ old('account_number', $paymentAccount->account_number) }}" />
                </div>
                <div>
                    <label class="text-xs text-white-dark">Bank / Provider Name</label>
                    <input type="text" name="bank_name" class="form-input mt-1" value="{{ old('bank_name', $paymentAccount->bank_name) }}" />
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <select name="status" class="form-select mt-1" required>
                        <option value="active" {{ old('status', $paymentAccount->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $paymentAccount->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Notes</label>
                    <input type="text" name="notes" class="form-input mt-1" value="{{ old('notes', $paymentAccount->notes) }}" />
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.finance.payment-accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Account</button>
            </div>
        </form>
    </div>
@endsection
