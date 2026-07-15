@extends('admin.layouts.master')

@section('title', 'Create Payment Account')

@section('content')
    <h2 class="text-xl font-semibold uppercase">Create Payment Account</h2>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.payment-accounts.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Account Name *</label>
                    <input type="text" name="name" class="form-input mt-1" required value="{{ old('name') }}" placeholder="Type account name" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-white-dark">Account Type *</label>
                    <select name="type" class="form-select mt-1" required>
                        <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Bank Account</option>
                        <option value="mfs" {{ old('type') == 'mfs' ? 'selected' : '' }}>MFS (Mobile Financial Service)</option>
                        <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                    @error('type') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-white-dark">Account Number / Mobile No.</label>
                    <input type="text" name="account_number" class="form-input mt-1" value="{{ old('account_number') }}" placeholder="Type account number or mobile number" />
                </div>
                <div>
                    <label class="text-xs text-white-dark">Bank / Provider Name</label>
                    <input type="text" name="bank_name" class="form-input mt-1" value="{{ old('bank_name') }}" placeholder="Type bank or provider name" />
                </div>
                <div>
                    <label class="text-xs text-white-dark">Opening Balance *</label>
                    <input type="number" name="opening_balance" step="0.01" min="0" class="form-input mt-1" required value="{{ old('opening_balance', '0') }}" />
                    @error('opening_balance') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-white-dark">Notes</label>
                    <input type="text" name="notes" class="form-input mt-1" value="{{ old('notes') }}" placeholder="Optional notes" />
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.finance.payment-accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
    </div>
@endsection
