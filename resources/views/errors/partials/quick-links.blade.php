@auth
    <div class="error-page__links">
        <a href="{{ route('admin.core.projects.index') }}">Projects</a>
        <a href="{{ route('admin.core.sites.index') }}">Sites</a>
        <a href="{{ route('admin.procurement.purchase-orders.index') }}">Purchase Orders</a>
        <a href="{{ route('admin.finance.invoices.index') }}">Invoices</a>
    </div>
@endauth
