<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\Core\SiteController;
use App\Http\Controllers\Admin\Core\TaskController;

Route::get('/', function () {
    return redirect()->route('tyro-login.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('tyro-dashboard.index');

Route::prefix('dashboard/settings')->name('admin.settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::post('/update', [SettingController::class, 'update'])->name('update');
});

// Role Management Overrides
use App\Http\Controllers\Admin\RoleController as LocalRoleController;
Route::prefix('dashboard/roles')->name('tyro-dashboard.roles.')->group(function () {
    Route::get('/', [LocalRoleController::class, 'index'])->name('index');
    Route::get('/create', [LocalRoleController::class, 'create'])->name('create');
    Route::post('/', [LocalRoleController::class, 'store'])->name('store');
    Route::get('{id}/edit', [LocalRoleController::class, 'edit'])->name('edit');
    Route::put('{id}', [LocalRoleController::class, 'update'])->name('update');
    Route::post('{id}/toggle', [LocalRoleController::class, 'toggleStatus'])->name('toggle');
    Route::delete('{id}', [LocalRoleController::class, 'destroy'])->name('destroy');
});

// Core - Project Management
use App\Http\Controllers\Admin\Core\ProjectController;
Route::prefix('dashboard/core')->name('admin.core.')->group(function () {
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Sites
    Route::get('sites', [SiteController::class, 'index'])->name('sites.index');
    Route::get('sites/create', [SiteController::class, 'create'])->name('sites.create');
    Route::post('sites', [SiteController::class, 'store'])->name('sites.store');
    Route::get('sites/{site}', [SiteController::class, 'show'])->name('sites.show');
    Route::get('sites/{site}/edit', [SiteController::class, 'edit'])->name('sites.edit');
    Route::put('sites/{site}', [SiteController::class, 'update'])->name('sites.update');
    Route::delete('sites/{site}', [SiteController::class, 'destroy'])->name('sites.destroy');

    // Tasks
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

// Procurement - Vendor Management
use App\Http\Controllers\Admin\Procurement\VendorController;
use App\Http\Controllers\Admin\Procurement\MaterialController;
use App\Http\Controllers\Admin\Procurement\PurchaseRequisitionController;
use App\Http\Controllers\Admin\Procurement\PurchaseOrderController;
use App\Http\Controllers\Admin\Procurement\GoodsReceivedNoteController;
use App\Http\Controllers\Admin\Procurement\WarehouseController;
use App\Http\Controllers\Admin\Procurement\StockController;
use App\Http\Controllers\Admin\Procurement\MaterialTransferController;
use App\Http\Controllers\Admin\Procurement\MaterialIssueSlipController;
use App\Http\Controllers\Admin\Procurement\MaterialWastageController;
use App\Http\Controllers\Admin\Reports\ReportTemplateController;
use App\Http\Controllers\Admin\Reports\ScheduledReportController;
Route::prefix('dashboard/procurement')->name('admin.procurement.')->group(function () {
    Route::get('vendors', [VendorController::class, 'index'])->name('vendors.index');
    Route::get('vendors/create', [VendorController::class, 'create'])->name('vendors.create');
    Route::post('vendors', [VendorController::class, 'store'])->name('vendors.store');
    Route::get('vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
    Route::get('vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::put('vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
    Route::delete('vendors/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    Route::get('materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('materials/{material}', [MaterialController::class, 'show'])->name('materials.show');
    Route::get('materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::put('materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');

    Route::get('requisitions', [PurchaseRequisitionController::class, 'index'])->name('requisitions.index');
    Route::get('requisitions/create', [PurchaseRequisitionController::class, 'create'])->name('requisitions.create');
    Route::post('requisitions', [PurchaseRequisitionController::class, 'store'])->name('requisitions.store');
    Route::get('requisitions/{purchase_requisition}', [PurchaseRequisitionController::class, 'show'])->name('requisitions.show');
    Route::get('requisitions/{purchase_requisition}/edit', [PurchaseRequisitionController::class, 'edit'])->name('requisitions.edit');
    Route::put('requisitions/{purchase_requisition}', [PurchaseRequisitionController::class, 'update'])->name('requisitions.update');
    Route::delete('requisitions/{purchase_requisition}', [PurchaseRequisitionController::class, 'destroy'])->name('requisitions.destroy');

    Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('purchase-orders/{purchase_order}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::get('purchase-orders/{purchase_order}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
    Route::put('purchase-orders/{purchase_order}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
    Route::delete('purchase-orders/{purchase_order}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');

    Route::get('goods-received-notes', [GoodsReceivedNoteController::class, 'index'])->name('goods-received-notes.index');
    Route::get('goods-received-notes/create', [GoodsReceivedNoteController::class, 'create'])->name('goods-received-notes.create');
    Route::post('goods-received-notes', [GoodsReceivedNoteController::class, 'store'])->name('goods-received-notes.store');
    Route::get('goods-received-notes/{goods_received_note}', [GoodsReceivedNoteController::class, 'show'])->name('goods-received-notes.show');
    Route::delete('goods-received-notes/{goods_received_note}', [GoodsReceivedNoteController::class, 'destroy'])->name('goods-received-notes.destroy');

    Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::get('warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
    Route::post('warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::get('warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
    Route::get('warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
    Route::put('warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
    Route::delete('warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');

    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('stocks/create', [StockController::class, 'create'])->name('stocks.create');
    Route::post('stocks', [StockController::class, 'store'])->name('stocks.store');
    Route::get('stocks/{stock}', [StockController::class, 'show'])->name('stocks.show');
    Route::get('stocks/{stock}/edit', [StockController::class, 'edit'])->name('stocks.edit');
    Route::put('stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');
    Route::delete('stocks/{stock}', [StockController::class, 'destroy'])->name('stocks.destroy');

    Route::get('material-transfers', [MaterialTransferController::class, 'index'])->name('material-transfers.index');
    Route::get('material-transfers/create', [MaterialTransferController::class, 'create'])->name('material-transfers.create');
    Route::post('material-transfers', [MaterialTransferController::class, 'store'])->name('material-transfers.store');
    Route::get('material-transfers/{material_transfer}', [MaterialTransferController::class, 'show'])->name('material-transfers.show');
    Route::delete('material-transfers/{material_transfer}', [MaterialTransferController::class, 'destroy'])->name('material-transfers.destroy');

    Route::get('material-issue-slips', [MaterialIssueSlipController::class, 'index'])->name('material-issue-slips.index');
    Route::get('material-issue-slips/create', [MaterialIssueSlipController::class, 'create'])->name('material-issue-slips.create');
    Route::post('material-issue-slips', [MaterialIssueSlipController::class, 'store'])->name('material-issue-slips.store');
    Route::get('material-issue-slips/{material_issue_slip}', [MaterialIssueSlipController::class, 'show'])->name('material-issue-slips.show');
    Route::delete('material-issue-slips/{material_issue_slip}', [MaterialIssueSlipController::class, 'destroy'])->name('material-issue-slips.destroy');

    Route::get('material-wastages', [MaterialWastageController::class, 'index'])->name('material-wastages.index');
    Route::get('material-wastages/create', [MaterialWastageController::class, 'create'])->name('material-wastages.create');
    Route::post('material-wastages', [MaterialWastageController::class, 'store'])->name('material-wastages.store');
    Route::get('material-wastages/{material_wastage}', [MaterialWastageController::class, 'show'])->name('material-wastages.show');
    Route::get('material-wastages/{material_wastage}/edit', [MaterialWastageController::class, 'edit'])->name('material-wastages.edit');
    Route::put('material-wastages/{material_wastage}', [MaterialWastageController::class, 'update'])->name('material-wastages.update');
    Route::delete('material-wastages/{material_wastage}', [MaterialWastageController::class, 'destroy'])->name('material-wastages.destroy');
});

// Reports - Report Templates
Route::prefix('dashboard/reports')->name('admin.reports.')->group(function () {
    Route::get('report-templates', [ReportTemplateController::class, 'index'])->name('report-templates.index');
    Route::get('report-templates/create', [ReportTemplateController::class, 'create'])->name('report-templates.create');
    Route::post('report-templates', [ReportTemplateController::class, 'store'])->name('report-templates.store');
    Route::get('report-templates/{report_template}', [ReportTemplateController::class, 'show'])->name('report-templates.show');
    Route::get('report-templates/{report_template}/edit', [ReportTemplateController::class, 'edit'])->name('report-templates.edit');
    Route::put('report-templates/{report_template}', [ReportTemplateController::class, 'update'])->name('report-templates.update');
    Route::delete('report-templates/{report_template}', [ReportTemplateController::class, 'destroy'])->name('report-templates.destroy');

    Route::get('scheduled-reports', [ScheduledReportController::class, 'index'])->name('scheduled-reports.index');
    Route::get('scheduled-reports/create', [ScheduledReportController::class, 'create'])->name('scheduled-reports.create');
    Route::post('scheduled-reports', [ScheduledReportController::class, 'store'])->name('scheduled-reports.store');
    Route::get('scheduled-reports/{scheduled_report}', [ScheduledReportController::class, 'show'])->name('scheduled-reports.show');
    Route::get('scheduled-reports/{scheduled_report}/edit', [ScheduledReportController::class, 'edit'])->name('scheduled-reports.edit');
    Route::put('scheduled-reports/{scheduled_report}', [ScheduledReportController::class, 'update'])->name('scheduled-reports.update');
    Route::delete('scheduled-reports/{scheduled_report}', [ScheduledReportController::class, 'destroy'])->name('scheduled-reports.destroy');
});

// Finance - Budgeting & Cost Control
use App\Http\Controllers\Admin\Finance\BudgetController;
use App\Http\Controllers\Admin\Finance\BoqController;
use App\Http\Controllers\Admin\Finance\TenderController;
use App\Http\Controllers\Admin\Finance\InvoiceController;
Route::prefix('dashboard/finance')->name('admin.finance.')->group(function () {
    Route::get('budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::get('budgets/create', [BudgetController::class, 'create'])->name('budgets.create');
    Route::post('budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('budgets/{budget}', [BudgetController::class, 'show'])->name('budgets.show');
    Route::get('budgets/{budget}/edit', [BudgetController::class, 'edit'])->name('budgets.edit');
    Route::put('budgets/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('budgets/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');

    Route::get('boqs', [BoqController::class, 'index'])->name('boqs.index');
    Route::get('boqs/create', [BoqController::class, 'create'])->name('boqs.create');
    Route::post('boqs', [BoqController::class, 'store'])->name('boqs.store');
    Route::get('boqs/{boq}', [BoqController::class, 'show'])->name('boqs.show');
    Route::get('boqs/{boq}/edit', [BoqController::class, 'edit'])->name('boqs.edit');
    Route::put('boqs/{boq}', [BoqController::class, 'update'])->name('boqs.update');
    Route::delete('boqs/{boq}', [BoqController::class, 'destroy'])->name('boqs.destroy');
    Route::post('boqs/{boq}/items', [BoqController::class, 'addItem'])->name('boqs.items.store');
    Route::delete('boqs/{boq}/items/{boq_item}', [BoqController::class, 'removeItem'])->name('boqs.items.destroy');

    Route::get('tenders', [TenderController::class, 'index'])->name('tenders.index');
    Route::get('tenders/create', [TenderController::class, 'create'])->name('tenders.create');
    Route::post('tenders', [TenderController::class, 'store'])->name('tenders.store');
    Route::get('tenders/{tender}', [TenderController::class, 'show'])->name('tenders.show');
    Route::get('tenders/{tender}/edit', [TenderController::class, 'edit'])->name('tenders.edit');
    Route::put('tenders/{tender}', [TenderController::class, 'update'])->name('tenders.update');
    Route::delete('tenders/{tender}', [TenderController::class, 'destroy'])->name('tenders.destroy');
    Route::post('tenders/{tender}/bids', [TenderController::class, 'addBid'])->name('tenders.bids.store');
    Route::put('tenders/{tender}/bids/{tender_bid}', [TenderController::class, 'updateBid'])->name('tenders.bids.update');
    Route::delete('tenders/{tender}/bids/{tender_bid}', [TenderController::class, 'removeBid'])->name('tenders.bids.destroy');

    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::post('invoices/{invoice}/items', [InvoiceController::class, 'addItem'])->name('invoices.items.store');
    Route::delete('invoices/{invoice}/items/{invoice_item}', [InvoiceController::class, 'removeItem'])->name('invoices.items.destroy');
    Route::post('invoices/{invoice}/payments', [InvoiceController::class, 'addPayment'])->name('invoices.payments.store');
    Route::delete('invoices/{invoice}/payments/{payment}', [InvoiceController::class, 'removePayment'])->name('invoices.payments.destroy');
});

// Approvals - Approval Workflow Management
use App\Http\Controllers\Admin\ApprovalController;
Route::prefix('dashboard/approvals')->name('admin.approvals.')->middleware('auth')->group(function () {
    // Workflow configuration (admin only) — must be before {approval} wildcard
    Route::prefix('workflows')->name('workflows.')->group(function () {
        Route::get('/', [ApprovalController::class, 'configureWorkflows'])->name('index');
        Route::get('create', [ApprovalController::class, 'createWorkflow'])->name('create');
        Route::post('/', [ApprovalController::class, 'storeWorkflow'])->name('store');
        Route::get('{workflow}/edit', [ApprovalController::class, 'editWorkflow'])->name('edit');
        Route::put('{workflow}', [ApprovalController::class, 'updateWorkflow'])->name('update');
        Route::delete('{workflow}', [ApprovalController::class, 'deleteWorkflow'])->name('destroy');
    });

    // User approvals (pending approvals for user)
    Route::get('/', [ApprovalController::class, 'index'])->name('index');
    Route::get('{approval}', [ApprovalController::class, 'show'])->name('show');
    Route::post('{approval}/approve', [ApprovalController::class, 'approve'])->name('approve');
    Route::post('{approval}/reject', [ApprovalController::class, 'reject'])->name('reject');
    Route::post('{approval}/withdraw', [ApprovalController::class, 'withdraw'])->name('withdraw');
});
