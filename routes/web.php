<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\Core\SiteController;
use App\Http\Controllers\Admin\Core\TaskController;
use App\Http\Controllers\Admin\Core\PhaseController;
use App\Http\Controllers\Admin\Core\MilestoneController;
use App\Http\Controllers\Admin\Core\SiteLogController;
use App\Http\Controllers\Admin\Core\SitePhotoController;
use App\Http\Controllers\Admin\Core\ProjectResourceController;
use App\Http\Controllers\Admin\Core\WorkOrderController;
use App\Http\Controllers\Admin\Core\InspectionChecklistController;
use App\Http\Controllers\Admin\Core\DrawingController;
use App\Http\Controllers\Admin\Core\RfiController;
use App\Http\Controllers\Admin\Core\ChangeOrderController;
use App\Http\Controllers\Admin\Core\DrawingTransmittalController;
use App\Http\Controllers\Admin\SearchController;

Route::get('/', function () {
    return redirect()->route('tyro-login.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('tyro-dashboard.index');
Route::get('/dashboard/search', [SearchController::class, 'search'])->middleware('auth')->name('admin.search');

Route::prefix('dashboard/settings')->name('admin.settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::post('/update', [SettingController::class, 'update'])->name('update');
});

Route::prefix('dashboard/categories')->name('admin.categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
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
    Route::get('projects/{project}/gantt', [ProjectController::class, 'gantt'])->name('projects.gantt');
    Route::get('resource-gantt', [ProjectController::class, 'resourceGanttIndex'])->name('resource-gantt.index');
    Route::get('projects/{project}/resource-gantt', [ProjectController::class, 'resourceGantt'])->name('projects.resource-gantt');
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

    // Phases (global + nested under projects)
    Route::get('phases', [PhaseController::class, 'globalIndex'])->name('phases.index');
    Route::prefix('projects/{project}/phases')->name('projects.phases.')->group(function () {
        Route::get('/', [PhaseController::class, 'index'])->name('index');
        Route::get('create', [PhaseController::class, 'create'])->name('create');
        Route::post('/', [PhaseController::class, 'store'])->name('store');
        Route::get('{phase}', [PhaseController::class, 'show'])->name('show');
        Route::get('{phase}/edit', [PhaseController::class, 'edit'])->name('edit');
        Route::put('{phase}', [PhaseController::class, 'update'])->name('update');
        Route::delete('{phase}', [PhaseController::class, 'destroy'])->name('destroy');
    });

    // Site Logs (global + nested under sites)
    Route::get('site-logs', [SiteLogController::class, 'globalIndex'])->name('site-logs.index');
    Route::get('weather', [SiteLogController::class, 'fetchWeather'])->name('weather.fetch');
    Route::prefix('sites/{site}/logs')->name('sites.logs.')->group(function () {
        Route::get('/', [SiteLogController::class, 'index'])->name('index');
        Route::get('create', [SiteLogController::class, 'create'])->name('create');
        Route::post('/', [SiteLogController::class, 'store'])->name('store');
        Route::get('{log}', [SiteLogController::class, 'show'])->name('show');
        Route::get('{log}/edit', [SiteLogController::class, 'edit'])->name('edit');
        Route::put('{log}', [SiteLogController::class, 'update'])->name('update');
        Route::delete('{log}', [SiteLogController::class, 'destroy'])->name('destroy');
    });

    // Site Photos (global + nested under sites)
    Route::get('site-photos', [SitePhotoController::class, 'globalIndex'])->name('site-photos.index');
    Route::prefix('sites/{site}/photos')->name('sites.photos.')->group(function () {
        Route::get('/', [SitePhotoController::class, 'index'])->name('index');
        Route::post('/', [SitePhotoController::class, 'store'])->name('store');
        Route::post('{photo}/caption', [SitePhotoController::class, 'updateCaption'])->name('caption');
        Route::delete('{photo}', [SitePhotoController::class, 'destroy'])->name('destroy');
    });

    // Project Resources (global + nested under projects)
    Route::get('resources', [ProjectResourceController::class, 'globalIndex'])->name('resources.index');
    Route::prefix('projects/{project}/resources')->name('projects.resources.')->group(function () {
        Route::get('/', [ProjectResourceController::class, 'index'])->name('index');
        Route::get('create', [ProjectResourceController::class, 'create'])->name('create');
        Route::post('/', [ProjectResourceController::class, 'store'])->name('store');
        Route::get('{resource}/edit', [ProjectResourceController::class, 'edit'])->name('edit');
        Route::put('{resource}', [ProjectResourceController::class, 'update'])->name('update');
        Route::delete('{resource}', [ProjectResourceController::class, 'destroy'])->name('destroy');
    });

    // Work Orders
    Route::get('work-orders', [WorkOrderController::class, 'index'])->name('work-orders.index');
    Route::get('work-orders/create', [WorkOrderController::class, 'create'])->name('work-orders.create');
    Route::post('work-orders', [WorkOrderController::class, 'store'])->name('work-orders.store');
    Route::get('work-orders/{work_order}', [WorkOrderController::class, 'show'])->name('work-orders.show');
    Route::get('work-orders/{work_order}/print', [WorkOrderController::class, 'print'])->name('work-orders.print');
    Route::get('work-orders/{work_order}/edit', [WorkOrderController::class, 'edit'])->name('work-orders.edit');
    Route::put('work-orders/{work_order}', [WorkOrderController::class, 'update'])->name('work-orders.update');
    Route::delete('work-orders/{work_order}', [WorkOrderController::class, 'destroy'])->name('work-orders.destroy');

    // Inspection Checklists
    Route::get('inspection-checklists', [InspectionChecklistController::class, 'index'])->name('inspection-checklists.index');
    Route::get('inspection-checklists/create', [InspectionChecklistController::class, 'create'])->name('inspection-checklists.create');
    Route::post('inspection-checklists', [InspectionChecklistController::class, 'store'])->name('inspection-checklists.store');
    Route::get('inspection-checklists/{checklist}', [InspectionChecklistController::class, 'show'])->name('inspection-checklists.show');
    Route::get('inspection-checklists/{checklist}/edit', [InspectionChecklistController::class, 'edit'])->name('inspection-checklists.edit');
    Route::put('inspection-checklists/{checklist}', [InspectionChecklistController::class, 'update'])->name('inspection-checklists.update');
    Route::delete('inspection-checklists/{checklist}', [InspectionChecklistController::class, 'destroy'])->name('inspection-checklists.destroy');

    // Milestones (global + nested under projects)
    Route::get('milestones', [MilestoneController::class, 'globalIndex'])->name('milestones.index');
    Route::prefix('projects/{project}/milestones')->name('projects.milestones.')->group(function () {
        Route::get('/', [MilestoneController::class, 'index'])->name('index');
        Route::get('create', [MilestoneController::class, 'create'])->name('create');
        Route::post('/', [MilestoneController::class, 'store'])->name('store');
        Route::get('{milestone}', [MilestoneController::class, 'show'])->name('show');
        Route::get('{milestone}/edit', [MilestoneController::class, 'edit'])->name('edit');
        Route::put('{milestone}', [MilestoneController::class, 'update'])->name('update');
        Route::delete('{milestone}', [MilestoneController::class, 'destroy'])->name('destroy');
    });

    // Document Management
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('drawings', [DrawingController::class, 'index'])->name('drawings.index');
        Route::get('drawings/create', [DrawingController::class, 'create'])->name('drawings.create');
        Route::post('drawings', [DrawingController::class, 'store'])->name('drawings.store');
        Route::get('drawings/{drawing}', [DrawingController::class, 'show'])->name('drawings.show');
        Route::get('drawings/{drawing}/edit', [DrawingController::class, 'edit'])->name('drawings.edit');
        Route::put('drawings/{drawing}', [DrawingController::class, 'update'])->name('drawings.update');
        Route::delete('drawings/{drawing}', [DrawingController::class, 'destroy'])->name('drawings.destroy');
        Route::post('drawings/{drawing}/revisions', [DrawingController::class, 'addRevision'])->name('drawings.revisions.store');

        Route::get('rfis', [RfiController::class, 'index'])->name('rfis.index');
        Route::get('rfis/create', [RfiController::class, 'create'])->name('rfis.create');
        Route::post('rfis', [RfiController::class, 'store'])->name('rfis.store');
        Route::get('rfis/{rfi}', [RfiController::class, 'show'])->name('rfis.show');
        Route::get('rfis/{rfi}/edit', [RfiController::class, 'edit'])->name('rfis.edit');
        Route::put('rfis/{rfi}', [RfiController::class, 'update'])->name('rfis.update');
        Route::delete('rfis/{rfi}', [RfiController::class, 'destroy'])->name('rfis.destroy');
        Route::post('rfis/{rfi}/answer', [RfiController::class, 'answer'])->name('rfis.answer');

        Route::get('change-orders', [ChangeOrderController::class, 'index'])->name('change-orders.index');
        Route::get('change-orders/create', [ChangeOrderController::class, 'create'])->name('change-orders.create');
        Route::post('change-orders', [ChangeOrderController::class, 'store'])->name('change-orders.store');
        Route::get('change-orders/{changeOrder}', [ChangeOrderController::class, 'show'])->name('change-orders.show');
        Route::get('change-orders/{changeOrder}/edit', [ChangeOrderController::class, 'edit'])->name('change-orders.edit');
        Route::put('change-orders/{changeOrder}', [ChangeOrderController::class, 'update'])->name('change-orders.update');
        Route::delete('change-orders/{changeOrder}', [ChangeOrderController::class, 'destroy'])->name('change-orders.destroy');
        Route::post('change-orders/{changeOrder}/approve', [ChangeOrderController::class, 'approve'])->name('change-orders.approve');
        Route::post('change-orders/{changeOrder}/reject', [ChangeOrderController::class, 'reject'])->name('change-orders.reject');

        Route::get('transmittals', [DrawingTransmittalController::class, 'index'])->name('transmittals.index');
        Route::get('transmittals/create', [DrawingTransmittalController::class, 'create'])->name('transmittals.create');
        Route::post('transmittals', [DrawingTransmittalController::class, 'store'])->name('transmittals.store');
        Route::get('transmittals/{transmittal}', [DrawingTransmittalController::class, 'show'])->name('transmittals.show');
        Route::get('transmittals/{transmittal}/edit', [DrawingTransmittalController::class, 'edit'])->name('transmittals.edit');
        Route::put('transmittals/{transmittal}', [DrawingTransmittalController::class, 'update'])->name('transmittals.update');
        Route::delete('transmittals/{transmittal}', [DrawingTransmittalController::class, 'destroy'])->name('transmittals.destroy');
    });
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
use App\Http\Controllers\Admin\Procurement\SubcontractorController;
use App\Http\Controllers\Admin\Procurement\RfqController;
use App\Http\Controllers\Admin\Procurement\MaterialSubmittalController;
use App\Http\Controllers\Admin\Procurement\MaterialReconciliationController;
use App\Http\Controllers\Admin\Procurement\SubcontractAgreementController;
use App\Http\Controllers\Admin\Procurement\SubcontractProgressPaymentController;
use App\Http\Controllers\Admin\Hr\EmployeeController;
use App\Http\Controllers\Admin\Hr\AttendanceController;
use App\Http\Controllers\Admin\Hr\TimesheetController;
use App\Http\Controllers\Admin\Hr\WageSlipController;
use App\Http\Controllers\Admin\Hr\EquipmentController;
use App\Http\Controllers\Admin\Hr\LeaveRequestController;
use App\Http\Controllers\Admin\Hr\TrainingRecordController;
use App\Http\Controllers\Admin\Hr\PpeIssuanceController;
use App\Http\Controllers\Admin\Hr\IncidentReportController;
use App\Http\Controllers\Admin\Hr\CertificationController;
use App\Http\Controllers\Admin\Hr\HseChecklistController;
use App\Http\Controllers\Admin\Hr\FuelLogController;
use App\Http\Controllers\Admin\Hr\ToolboxTalkController;
use App\Http\Controllers\Admin\Reports\ReportTemplateController;
use App\Http\Controllers\Admin\Reports\ScheduledReportController;
use App\Http\Controllers\Admin\Reports\FinancialReportController;
Route::prefix('dashboard/procurement')->name('admin.procurement.')->group(function () {
    Route::get('vendors', [VendorController::class, 'index'])->name('vendors.index');
    Route::get('vendors/create', [VendorController::class, 'create'])->name('vendors.create');
    Route::post('vendors', [VendorController::class, 'store'])->name('vendors.store');
    Route::get('vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
    Route::get('vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::put('vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
    Route::delete('vendors/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');
    Route::post('vendors/{vendor}/documents', [VendorController::class, 'uploadDocument'])->name('vendors.documents.upload');
    Route::delete('vendors/documents/{document}', [VendorController::class, 'deleteDocument'])->name('vendors.documents.delete');
    Route::put('vendors/{vendor}/qualification', [VendorController::class, 'updateQualification'])->name('vendors.qualification.update');

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
    Route::post('requisitions/{purchase_requisition}/submit', [PurchaseRequisitionController::class, 'submitForApproval'])->name('requisitions.submit');
    Route::get('requisitions/{purchase_requisition}/items', [PurchaseRequisitionController::class, 'getItems'])->name('requisitions.items');

    Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('purchase-orders/{purchase_order}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::get('purchase-orders/{purchase_order}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
    Route::put('purchase-orders/{purchase_order}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
    Route::delete('purchase-orders/{purchase_order}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
    Route::post('purchase-orders/{purchase_order}/submit', [PurchaseOrderController::class, 'submitForApproval'])->name('purchase-orders.submit');
    Route::get('purchase-orders/{purchase_order}/pdf', [PurchaseOrderController::class, 'printPdf'])->name('purchase-orders.pdf');

    Route::get('goods-received-notes', [GoodsReceivedNoteController::class, 'index'])->name('goods-received-notes.index');
    Route::get('goods-received-notes/create', [GoodsReceivedNoteController::class, 'create'])->name('goods-received-notes.create');
    Route::post('goods-received-notes', [GoodsReceivedNoteController::class, 'store'])->name('goods-received-notes.store');
    Route::get('goods-received-notes/{goods_received_note}', [GoodsReceivedNoteController::class, 'show'])->name('goods-received-notes.show');
    Route::post('goods-received-notes/{goods_received_note}/verify', [GoodsReceivedNoteController::class, 'verify'])->name('goods-received-notes.verify');
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
    Route::put('material-transfers/{material_transfer}/status', [MaterialTransferController::class, 'updateStatus'])->name('material-transfers.status');

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

    Route::get('subcontractors', [SubcontractorController::class, 'index'])->name('subcontractors.index');
    Route::get('subcontractors/create', [SubcontractorController::class, 'create'])->name('subcontractors.create');
    Route::post('subcontractors', [SubcontractorController::class, 'store'])->name('subcontractors.store');
    Route::get('subcontractors/{subcontractor}', [SubcontractorController::class, 'show'])->name('subcontractors.show');
    Route::get('subcontractors/{subcontractor}/edit', [SubcontractorController::class, 'edit'])->name('subcontractors.edit');
    Route::put('subcontractors/{subcontractor}', [SubcontractorController::class, 'update'])->name('subcontractors.update');
    Route::delete('subcontractors/{subcontractor}', [SubcontractorController::class, 'destroy'])->name('subcontractors.destroy');

    Route::get('material-submittals', [MaterialSubmittalController::class, 'index'])->name('material-submittals.index');
    Route::get('material-submittals/create', [MaterialSubmittalController::class, 'create'])->name('material-submittals.create');
    Route::post('material-submittals', [MaterialSubmittalController::class, 'store'])->name('material-submittals.store');
    Route::get('material-submittals/{materialSubmittal}', [MaterialSubmittalController::class, 'show'])->name('material-submittals.show');
    Route::get('material-submittals/{materialSubmittal}/edit', [MaterialSubmittalController::class, 'edit'])->name('material-submittals.edit');
    Route::put('material-submittals/{materialSubmittal}', [MaterialSubmittalController::class, 'update'])->name('material-submittals.update');
    Route::delete('material-submittals/{materialSubmittal}', [MaterialSubmittalController::class, 'destroy'])->name('material-submittals.destroy');
    Route::put('material-submittals/{materialSubmittal}/submit', [MaterialSubmittalController::class, 'submit'])->name('material-submittals.submit');
    Route::put('material-submittals/{materialSubmittal}/review', [MaterialSubmittalController::class, 'review'])->name('material-submittals.review');
    Route::get('material-submittals/{materialSubmittal}/resubmit', [MaterialSubmittalController::class, 'resubmitForm'])->name('material-submittals.resubmit-form');
    Route::put('material-submittals/{materialSubmittal}/resubmit', [MaterialSubmittalController::class, 'resubmit'])->name('material-submittals.resubmit');

    Route::get('material-reconciliation', [MaterialReconciliationController::class, 'index'])->name('material-reconciliation.index');

    Route::get('rfqs', [RfqController::class, 'index'])->name('rfqs.index');
    Route::get('rfqs/create', [RfqController::class, 'create'])->name('rfqs.create');
    Route::post('rfqs', [RfqController::class, 'store'])->name('rfqs.store');
    Route::get('rfqs/{rfq}', [RfqController::class, 'show'])->name('rfqs.show');
    Route::get('rfqs/{rfq}/edit', [RfqController::class, 'edit'])->name('rfqs.edit');
    Route::put('rfqs/{rfq}', [RfqController::class, 'update'])->name('rfqs.update');
    Route::delete('rfqs/{rfq}', [RfqController::class, 'destroy'])->name('rfqs.destroy');
    Route::put('rfqs/{rfq}/send', [RfqController::class, 'send'])->name('rfqs.send');
    Route::put('rfqs/{rfq}/close', [RfqController::class, 'close'])->name('rfqs.close');
    Route::get('rfqs/{rfq}/quotations/create', [RfqController::class, 'createQuotation'])->name('rfqs.quotations.create');
    Route::post('rfqs/{rfq}/quotations', [RfqController::class, 'storeQuotation'])->name('rfqs.quotations.store');
    Route::get('rfqs/{rfq}/quotations/{quotation}/edit', [RfqController::class, 'editQuotation'])->name('rfqs.quotations.edit');
    Route::put('rfqs/{rfq}/quotations/{quotation}', [RfqController::class, 'updateQuotation'])->name('rfqs.quotations.update');
    Route::post('rfqs/{rfq}/award', [RfqController::class, 'award'])->name('rfqs.award');

    Route::get('subcontract-agreements', [SubcontractAgreementController::class, 'index'])->name('subcontract-agreements.index');
    Route::get('subcontract-agreements/create', [SubcontractAgreementController::class, 'create'])->name('subcontract-agreements.create');
    Route::post('subcontract-agreements', [SubcontractAgreementController::class, 'store'])->name('subcontract-agreements.store');
    Route::get('subcontract-agreements/{subcontractAgreement}', [SubcontractAgreementController::class, 'show'])->name('subcontract-agreements.show');
    Route::get('subcontract-agreements/{subcontractAgreement}/edit', [SubcontractAgreementController::class, 'edit'])->name('subcontract-agreements.edit');
    Route::put('subcontract-agreements/{subcontractAgreement}', [SubcontractAgreementController::class, 'update'])->name('subcontract-agreements.update');
    Route::delete('subcontract-agreements/{subcontractAgreement}', [SubcontractAgreementController::class, 'destroy'])->name('subcontract-agreements.destroy');
    Route::put('subcontract-agreements/{subcontractAgreement}/activate', [SubcontractAgreementController::class, 'activate'])->name('subcontract-agreements.activate');
    Route::put('subcontract-agreements/{subcontractAgreement}/complete', [SubcontractAgreementController::class, 'complete'])->name('subcontract-agreements.complete');
    Route::put('subcontract-agreements/{subcontractAgreement}/terminate', [SubcontractAgreementController::class, 'terminate'])->name('subcontract-agreements.terminate');

    Route::get('subcontract-progress-payments', [SubcontractProgressPaymentController::class, 'index'])->name('subcontract-progress-payments.index');
    Route::get('subcontract-progress-payments/create', [SubcontractProgressPaymentController::class, 'create'])->name('subcontract-progress-payments.create');
    Route::post('subcontract-progress-payments', [SubcontractProgressPaymentController::class, 'store'])->name('subcontract-progress-payments.store');
    Route::get('subcontract-progress-payments/{subcontractProgressPayment}', [SubcontractProgressPaymentController::class, 'show'])->name('subcontract-progress-payments.show');
    Route::put('subcontract-progress-payments/{subcontractProgressPayment}/status', [SubcontractProgressPaymentController::class, 'updateStatus'])->name('subcontract-progress-payments.status');
    Route::delete('subcontract-progress-payments/{subcontractProgressPayment}', [SubcontractProgressPaymentController::class, 'destroy'])->name('subcontract-progress-payments.destroy');
});

// HR - Employee Management
Route::prefix('dashboard/hr')->name('admin.hr.')->group(function () {
    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::delete('attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('attendance/summary', [AttendanceController::class, 'summary'])->name('attendance.summary');

    Route::get('timesheets', [TimesheetController::class, 'index'])->name('timesheets.index');
    Route::get('timesheets/create', [TimesheetController::class, 'create'])->name('timesheets.create');
    Route::post('timesheets', [TimesheetController::class, 'store'])->name('timesheets.store');
    Route::delete('timesheets/{timesheet}', [TimesheetController::class, 'destroy'])->name('timesheets.destroy');

    Route::get('wage-slips', [WageSlipController::class, 'index'])->name('wage-slips.index');
    Route::get('wage-slips/create', [WageSlipController::class, 'create'])->name('wage-slips.create');
    Route::post('wage-slips', [WageSlipController::class, 'store'])->name('wage-slips.store');
    Route::get('wage-slips/{wageSlip}', [WageSlipController::class, 'show'])->name('wage-slips.show');
    Route::get('wage-slips/{wageSlip}/print', [WageSlipController::class, 'print'])->name('wage-slips.print');
    Route::delete('wage-slips/{wageSlip}', [WageSlipController::class, 'destroy'])->name('wage-slips.destroy');

    Route::get('equipment', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('equipment/create', [EquipmentController::class, 'create'])->name('equipment.create');
    Route::post('equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::get('equipment/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');
    Route::get('equipment/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('equipment/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('equipment/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    Route::post('equipment/{equipment}/maintenance', [EquipmentController::class, 'maintenanceStore'])->name('equipment.maintenance.store');
    Route::delete('equipment/maintenance/{maintenance}', [EquipmentController::class, 'maintenanceDestroy'])->name('equipment.maintenance.destroy');
    Route::post('equipment/{equipment}/update-meter', [EquipmentController::class, 'updateMeter'])->name('equipment.update-meter');

    Route::get('leaves', [LeaveRequestController::class, 'index'])->name('leaves.index');
    Route::get('leaves/create', [LeaveRequestController::class, 'create'])->name('leaves.create');
    Route::post('leaves', [LeaveRequestController::class, 'store'])->name('leaves.store');
    Route::get('leaves/{leave}', [LeaveRequestController::class, 'show'])->name('leaves.show');
    Route::patch('leaves/{leave}/approve', [LeaveRequestController::class, 'approve'])->name('leaves.approve');
    Route::patch('leaves/{leave}/reject', [LeaveRequestController::class, 'reject'])->name('leaves.reject');
    Route::delete('leaves/{leave}', [LeaveRequestController::class, 'destroy'])->name('leaves.destroy');

    Route::get('training-records', [TrainingRecordController::class, 'index'])->name('training-records.index');
    Route::get('training-records/create', [TrainingRecordController::class, 'create'])->name('training-records.create');
    Route::post('training-records', [TrainingRecordController::class, 'store'])->name('training-records.store');
    Route::get('training-records/{trainingRecord}', [TrainingRecordController::class, 'show'])->name('training-records.show');
    Route::get('training-records/{trainingRecord}/edit', [TrainingRecordController::class, 'edit'])->name('training-records.edit');
    Route::put('training-records/{trainingRecord}', [TrainingRecordController::class, 'update'])->name('training-records.update');
    Route::delete('training-records/{trainingRecord}', [TrainingRecordController::class, 'destroy'])->name('training-records.destroy');

    Route::get('ppe-issuances', [PpeIssuanceController::class, 'index'])->name('ppe-issuances.index');
    Route::get('ppe-issuances/create', [PpeIssuanceController::class, 'create'])->name('ppe-issuances.create');
    Route::post('ppe-issuances', [PpeIssuanceController::class, 'store'])->name('ppe-issuances.store');
    Route::get('ppe-issuances/{ppeIssuance}', [PpeIssuanceController::class, 'show'])->name('ppe-issuances.show');
    Route::get('ppe-issuances/{ppeIssuance}/edit', [PpeIssuanceController::class, 'edit'])->name('ppe-issuances.edit');
    Route::put('ppe-issuances/{ppeIssuance}', [PpeIssuanceController::class, 'update'])->name('ppe-issuances.update');
    Route::delete('ppe-issuances/{ppeIssuance}', [PpeIssuanceController::class, 'destroy'])->name('ppe-issuances.destroy');

    Route::get('incident-reports', [IncidentReportController::class, 'index'])->name('incident-reports.index');
    Route::get('incident-reports/create', [IncidentReportController::class, 'create'])->name('incident-reports.create');
    Route::post('incident-reports', [IncidentReportController::class, 'store'])->name('incident-reports.store');
    Route::get('incident-reports/{incidentReport}', [IncidentReportController::class, 'show'])->name('incident-reports.show');
    Route::get('incident-reports/{incidentReport}/edit', [IncidentReportController::class, 'edit'])->name('incident-reports.edit');
    Route::put('incident-reports/{incidentReport}', [IncidentReportController::class, 'update'])->name('incident-reports.update');
    Route::delete('incident-reports/{incidentReport}', [IncidentReportController::class, 'destroy'])->name('incident-reports.destroy');

    Route::get('certifications', [CertificationController::class, 'index'])->name('certifications.index');
    Route::get('certifications/create', [CertificationController::class, 'create'])->name('certifications.create');
    Route::post('certifications', [CertificationController::class, 'store'])->name('certifications.store');
    Route::get('certifications/{certification}', [CertificationController::class, 'show'])->name('certifications.show');
    Route::get('certifications/{certification}/edit', [CertificationController::class, 'edit'])->name('certifications.edit');
    Route::put('certifications/{certification}', [CertificationController::class, 'update'])->name('certifications.update');
    Route::delete('certifications/{certification}', [CertificationController::class, 'destroy'])->name('certifications.destroy');

    Route::get('hse-checklists', [HseChecklistController::class, 'index'])->name('hse-checklists.index');
    Route::get('hse-checklists/sites-by-project', [HseChecklistController::class, 'sitesByProject'])->name('hse-checklists.sites-by-project');
    Route::get('hse-checklists/create', [HseChecklistController::class, 'create'])->name('hse-checklists.create');
    Route::post('hse-checklists', [HseChecklistController::class, 'store'])->name('hse-checklists.store');
    Route::get('hse-checklists/{hseChecklist}', [HseChecklistController::class, 'show'])->name('hse-checklists.show');
    Route::get('hse-checklists/{hseChecklist}/edit', [HseChecklistController::class, 'edit'])->name('hse-checklists.edit');
    Route::put('hse-checklists/{hseChecklist}', [HseChecklistController::class, 'update'])->name('hse-checklists.update');
    Route::delete('hse-checklists/{hseChecklist}', [HseChecklistController::class, 'destroy'])->name('hse-checklists.destroy');

    Route::get('fuel-logs', [FuelLogController::class, 'index'])->name('fuel-logs.index');
    Route::get('fuel-logs/create', [FuelLogController::class, 'create'])->name('fuel-logs.create');
    Route::post('fuel-logs', [FuelLogController::class, 'store'])->name('fuel-logs.store');
    Route::get('fuel-logs/{fuelLog}', [FuelLogController::class, 'show'])->name('fuel-logs.show');
    Route::get('fuel-logs/{fuelLog}/edit', [FuelLogController::class, 'edit'])->name('fuel-logs.edit');
    Route::put('fuel-logs/{fuelLog}', [FuelLogController::class, 'update'])->name('fuel-logs.update');
    Route::delete('fuel-logs/{fuelLog}', [FuelLogController::class, 'destroy'])->name('fuel-logs.destroy');
    Route::get('fuel-logs/equipment/{equipment}/details', [FuelLogController::class, 'equipmentDetails'])->name('fuel-logs.equipment-details');

    Route::get('toolbox-talks', [ToolboxTalkController::class, 'index'])->name('toolbox-talks.index');
    Route::get('toolbox-talks/create', [ToolboxTalkController::class, 'create'])->name('toolbox-talks.create');
    Route::post('toolbox-talks', [ToolboxTalkController::class, 'store'])->name('toolbox-talks.store');
    Route::get('toolbox-talks/{toolboxTalk}', [ToolboxTalkController::class, 'show'])->name('toolbox-talks.show');
    Route::get('toolbox-talks/{toolboxTalk}/edit', [ToolboxTalkController::class, 'edit'])->name('toolbox-talks.edit');
    Route::put('toolbox-talks/{toolboxTalk}', [ToolboxTalkController::class, 'update'])->name('toolbox-talks.update');
    Route::delete('toolbox-talks/{toolboxTalk}', [ToolboxTalkController::class, 'destroy'])->name('toolbox-talks.destroy');
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

    // Cost & Financial Reports
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('budget-vs-actual', [FinancialReportController::class, 'budgetVsActual'])->name('budget-vs-actual');
        Route::get('project-cost-summary', [FinancialReportController::class, 'projectCostSummary'])->name('project-cost-summary');
        Route::get('procurement-spend', [FinancialReportController::class, 'procurementSpend'])->name('procurement-spend');
        Route::get('invoice-status', [FinancialReportController::class, 'invoiceStatus'])->name('invoice-status');
        Route::get('cash-flow', [FinancialReportController::class, 'cashFlow'])->name('cash-flow');
        Route::get('retention-tracker', [FinancialReportController::class, 'retentionTracker'])->name('retention-tracker');
        Route::get('progress-schedule', [FinancialReportController::class, 'progressSchedule'])->name('progress-schedule');
        Route::get('resource-utilisation', [FinancialReportController::class, 'resourceUtilisation'])->name('resource-utilisation');
        Route::get('export/{report}/pdf', [FinancialReportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('export/{report}/excel', [FinancialReportController::class, 'exportExcel'])->name('export.excel');
    });
});

// Finance - Budgeting & Cost Control
use App\Http\Controllers\Admin\Finance\BudgetController;
use App\Http\Controllers\Admin\Finance\MaterialTakeoffController;
use App\Http\Controllers\Admin\Finance\BoqController;
use App\Http\Controllers\Admin\Finance\TenderController;
use App\Http\Controllers\Admin\Finance\InvoiceController;
use App\Http\Controllers\Admin\Finance\RateAnalysisController;
use App\Http\Controllers\Admin\Finance\CostOverrunAlertController;
use App\Http\Controllers\Admin\Finance\IpaController;
use App\Http\Controllers\Admin\Finance\BillController;
use App\Http\Controllers\Admin\Finance\ExpenseController;
use App\Http\Controllers\Admin\Finance\AgingReportController;
use App\Http\Controllers\Admin\Finance\ChartOfAccountController;
use App\Http\Controllers\Admin\Finance\JournalEntryController;
use App\Http\Controllers\Admin\Finance\GeneralLedgerController;
use App\Http\Controllers\Admin\Finance\TrialBalanceController;
use App\Http\Controllers\Admin\Finance\ReceivableController;
use App\Http\Controllers\Admin\Finance\BankGuaranteeController;
use App\Http\Controllers\Admin\Finance\BalanceSheetController;
use App\Http\Controllers\Admin\Finance\IncomeStatementController;
use App\Http\Controllers\Admin\Finance\LabourEntryController;
Route::prefix('dashboard/finance')->name('admin.finance.')->middleware('auth')->group(function () {
    Route::get('budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::get('budgets/create', [BudgetController::class, 'create'])->name('budgets.create');
    Route::post('budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('budgets/forecasting', [BudgetController::class, 'forecasting'])->name('budgets.forecasting');
    Route::get('budgets/{budget}', [BudgetController::class, 'show'])->name('budgets.show');
    Route::get('budgets/{budget}/edit', [BudgetController::class, 'edit'])->name('budgets.edit');
    Route::put('budgets/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('budgets/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');

    Route::get('material-takeoffs', [MaterialTakeoffController::class, 'index'])->name('material-takeoffs.index');
    Route::get('material-takeoffs/create', [MaterialTakeoffController::class, 'create'])->name('material-takeoffs.create');
    Route::post('material-takeoffs', [MaterialTakeoffController::class, 'store'])->name('material-takeoffs.store');
    Route::get('material-takeoffs/{materialTakeoff}', [MaterialTakeoffController::class, 'show'])->name('material-takeoffs.show');
    Route::get('material-takeoffs/{materialTakeoff}/edit', [MaterialTakeoffController::class, 'edit'])->name('material-takeoffs.edit');
    Route::put('material-takeoffs/{materialTakeoff}', [MaterialTakeoffController::class, 'update'])->name('material-takeoffs.update');
    Route::delete('material-takeoffs/{materialTakeoff}', [MaterialTakeoffController::class, 'destroy'])->name('material-takeoffs.destroy');

    Route::get('cost-overrun-alerts', [CostOverrunAlertController::class, 'index'])->name('cost-overrun-alerts.index');
    Route::post('cost-overrun-alerts/{alert}/acknowledge', [CostOverrunAlertController::class, 'acknowledge'])->name('cost-overrun-alerts.acknowledge');
    Route::post('cost-overrun-alerts/{alert}/resolve', [CostOverrunAlertController::class, 'resolve'])->name('cost-overrun-alerts.resolve');

    Route::get('boqs', [BoqController::class, 'index'])->name('boqs.index');
    Route::get('boqs/create', [BoqController::class, 'create'])->name('boqs.create');
    Route::post('boqs', [BoqController::class, 'store'])->name('boqs.store');
    Route::get('boqs/{boq}', [BoqController::class, 'show'])->name('boqs.show');
    Route::get('boqs/{boq}/edit', [BoqController::class, 'edit'])->name('boqs.edit');
    Route::put('boqs/{boq}', [BoqController::class, 'update'])->name('boqs.update');
    Route::delete('boqs/{boq}', [BoqController::class, 'destroy'])->name('boqs.destroy');
    Route::post('boqs/{boq}/items', [BoqController::class, 'addItem'])->name('boqs.items.store');
    Route::delete('boqs/{boq}/items/{boq_item}', [BoqController::class, 'removeItem'])->name('boqs.items.destroy');
    Route::post('boqs/{boq}/items/import', [BoqController::class, 'importItems'])->name('boqs.items.import');
    Route::get('boqs/import/template', [BoqController::class, 'downloadTemplate'])->name('boqs.import.template');

    Route::get('rate-analysis', [RateAnalysisController::class, 'index'])->name('rate-analysis.index');
    Route::get('rate-analysis/create', [RateAnalysisController::class, 'create'])->name('rate-analysis.create');
    Route::post('rate-analysis', [RateAnalysisController::class, 'store'])->name('rate-analysis.store');
    Route::get('rate-analysis/{rateAnalysis}', [RateAnalysisController::class, 'show'])->name('rate-analysis.show');
    Route::get('rate-analysis/{rateAnalysis}/edit', [RateAnalysisController::class, 'edit'])->name('rate-analysis.edit');
    Route::put('rate-analysis/{rateAnalysis}', [RateAnalysisController::class, 'update'])->name('rate-analysis.update');
    Route::delete('rate-analysis/{rateAnalysis}', [RateAnalysisController::class, 'destroy'])->name('rate-analysis.destroy');
    Route::post('rate-analysis/{rateAnalysis}/items', [RateAnalysisController::class, 'addItem'])->name('rate-analysis.items.store');
    Route::delete('rate-analysis/{rateAnalysis}/items/{rateAnalysisItem}', [RateAnalysisController::class, 'removeItem'])->name('rate-analysis.items.destroy');

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
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'printPdf'])->name('invoices.pdf');
    Route::post('invoices/{invoice}/items', [InvoiceController::class, 'addItem'])->name('invoices.items.store');
    Route::delete('invoices/{invoice}/items/{invoice_item}', [InvoiceController::class, 'removeItem'])->name('invoices.items.destroy');
    Route::post('invoices/{invoice}/payments', [InvoiceController::class, 'addPayment'])->name('invoices.payments.store');
    Route::delete('invoices/{invoice}/payments/{payment}', [InvoiceController::class, 'removePayment'])->name('invoices.payments.destroy');

    Route::get('ipas', [IpaController::class, 'index'])->name('ipas.index');
    Route::get('ipas/create', [IpaController::class, 'create'])->name('ipas.create');
    Route::post('ipas', [IpaController::class, 'store'])->name('ipas.store');
    Route::get('ipas/{ipa}', [IpaController::class, 'show'])->name('ipas.show');
    Route::get('ipas/{ipa}/edit', [IpaController::class, 'edit'])->name('ipas.edit');
    Route::put('ipas/{ipa}', [IpaController::class, 'update'])->name('ipas.update');
    Route::delete('ipas/{ipa}', [IpaController::class, 'destroy'])->name('ipas.destroy');
    Route::delete('ipas/{ipa}/items/{ipaItem}', [IpaController::class, 'removeItem'])->name('ipas.items.destroy');
    Route::post('ipas/{ipa}/submit', [IpaController::class, 'submit'])->name('ipas.submit');
    Route::post('ipas/{ipa}/certify', [IpaController::class, 'certify'])->name('ipas.certify');
    Route::post('ipas/{ipa}/approve', [IpaController::class, 'approve'])->name('ipas.approve');
    Route::post('ipas/{ipa}/reject', [IpaController::class, 'reject'])->name('ipas.reject');
    Route::post('ipas/{ipa}/generate-invoice', [IpaController::class, 'generateInvoice'])->name('ipas.generate-invoice');

    Route::get('bills', [BillController::class, 'index'])->name('bills.index');
    Route::get('bills/create', [BillController::class, 'create'])->name('bills.create');
    Route::post('bills', [BillController::class, 'store'])->name('bills.store');
    Route::get('bills/{bill}', [BillController::class, 'show'])->name('bills.show');
    Route::get('bills/{bill}/edit', [BillController::class, 'edit'])->name('bills.edit');
    Route::put('bills/{bill}', [BillController::class, 'update'])->name('bills.update');
    Route::delete('bills/{bill}', [BillController::class, 'destroy'])->name('bills.destroy');
    Route::post('bills/{bill}/items', [BillController::class, 'addItem'])->name('bills.items.store');
    Route::delete('bills/{bill}/items/{billItem}', [BillController::class, 'removeItem'])->name('bills.items.destroy');
    Route::post('bills/{bill}/payments', [BillController::class, 'addPayment'])->name('bills.payments.store');
    Route::delete('bills/{bill}/payments/{billPayment}', [BillController::class, 'removePayment'])->name('bills.payments.destroy');

    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::post('expenses/{expense}/mark-paid', [ExpenseController::class, 'markPaid'])->name('expenses.mark-paid');

    Route::get('chart-of-accounts', [ChartOfAccountController::class, 'index'])->name('chart-of-accounts.index');
    Route::get('chart-of-accounts/create', [ChartOfAccountController::class, 'create'])->name('chart-of-accounts.create');
    Route::post('chart-of-accounts', [ChartOfAccountController::class, 'store'])->name('chart-of-accounts.store');
    Route::get('chart-of-accounts/{chartOfAccount}/edit', [ChartOfAccountController::class, 'edit'])->name('chart-of-accounts.edit');
    Route::put('chart-of-accounts/{chartOfAccount}', [ChartOfAccountController::class, 'update'])->name('chart-of-accounts.update');
    Route::delete('chart-of-accounts/{chartOfAccount}', [ChartOfAccountController::class, 'destroy'])->name('chart-of-accounts.destroy');

    Route::get('journal-entries', [JournalEntryController::class, 'index'])->name('journal-entries.index');
    Route::get('journal-entries/create', [JournalEntryController::class, 'create'])->name('journal-entries.create');
    Route::post('journal-entries', [JournalEntryController::class, 'store'])->name('journal-entries.store');
    Route::get('journal-entries/{journalEntry}', [JournalEntryController::class, 'show'])->name('journal-entries.show');
    Route::delete('journal-entries/{journalEntry}', [JournalEntryController::class, 'destroy'])->name('journal-entries.destroy');

    Route::get('general-ledger', [GeneralLedgerController::class, 'index'])->name('general-ledger.index');
    Route::get('trial-balance', [TrialBalanceController::class, 'index'])->name('trial-balance.index');

    Route::get('receivables', [ReceivableController::class, 'index'])->name('receivables.index');
    Route::get('receivables/create', [ReceivableController::class, 'create'])->name('receivables.create');
    Route::post('receivables', [ReceivableController::class, 'store'])->name('receivables.store');
    Route::get('receivables/{receivable}', [ReceivableController::class, 'show'])->name('receivables.show');
    Route::delete('receivables/{receivable}', [ReceivableController::class, 'destroy'])->name('receivables.destroy');
    Route::post('receivables/{receivable}/payments', [ReceivableController::class, 'addPayment'])->name('receivables.payments.store');
    Route::delete('receivables/{receivable}/payments/{payment}', [ReceivableController::class, 'removePayment'])->name('receivables.payments.destroy');

    Route::get('bank-guarantees', [BankGuaranteeController::class, 'index'])->name('bank-guarantees.index');
    Route::get('bank-guarantees/create', [BankGuaranteeController::class, 'create'])->name('bank-guarantees.create');
    Route::post('bank-guarantees', [BankGuaranteeController::class, 'store'])->name('bank-guarantees.store');
    Route::get('bank-guarantees/{bankGuarantee}', [BankGuaranteeController::class, 'show'])->name('bank-guarantees.show');
    Route::patch('bank-guarantees/{bankGuarantee}/status', [BankGuaranteeController::class, 'updateStatus'])->name('bank-guarantees.status');
    Route::delete('bank-guarantees/{bankGuarantee}', [BankGuaranteeController::class, 'destroy'])->name('bank-guarantees.destroy');

    Route::get('balance-sheet', [BalanceSheetController::class, 'index'])->name('balance-sheet.index');
    Route::get('income-statement', [IncomeStatementController::class, 'index'])->name('income-statement.index');

    Route::get('labour-entries', [LabourEntryController::class, 'index'])->name('labour-entries.index');
    Route::get('labour-entries/create', [LabourEntryController::class, 'create'])->name('labour-entries.create');
    Route::post('labour-entries', [LabourEntryController::class, 'store'])->name('labour-entries.store');
    Route::delete('labour-entries/{labourEntry}', [LabourEntryController::class, 'destroy'])->name('labour-entries.destroy');

    Route::get('aging/ar', [AgingReportController::class, 'arAging'])->name('aging.ar');
    Route::get('aging/ap', [AgingReportController::class, 'apAging'])->name('aging.ap');
});

// Quality Control - Quality Control/QA Module
use App\Http\Controllers\Admin\Quality\NcrController;
use App\Http\Controllers\Admin\Quality\PunchListController;
use App\Http\Controllers\Admin\Quality\ItpController;
use App\Http\Controllers\Admin\Quality\MaterialTestCertificateController;
use App\Http\Controllers\Admin\Quality\CorrectiveActionController;

Route::prefix('dashboard/quality')->name('admin.quality.')->middleware('auth')->group(function () {
    // NCRs
    Route::get('ncrs', [NcrController::class, 'index'])->name('ncrs.index');
    Route::get('ncrs/create', [NcrController::class, 'create'])->name('ncrs.create');
    Route::post('ncrs', [NcrController::class, 'store'])->name('ncrs.store');
    Route::get('ncrs/{ncr}', [NcrController::class, 'show'])->name('ncrs.show');
    Route::get('ncrs/{ncr}/edit', [NcrController::class, 'edit'])->name('ncrs.edit');
    Route::put('ncrs/{ncr}', [NcrController::class, 'update'])->name('ncrs.update');
    Route::delete('ncrs/{ncr}', [NcrController::class, 'destroy'])->name('ncrs.destroy');

    // Punch Lists
    Route::get('punch-lists', [PunchListController::class, 'index'])->name('punch-lists.index');
    Route::get('punch-lists/create', [PunchListController::class, 'create'])->name('punch-lists.create');
    Route::post('punch-lists', [PunchListController::class, 'store'])->name('punch-lists.store');
    Route::get('punch-lists/{punchList}', [PunchListController::class, 'show'])->name('punch-lists.show');
    Route::get('punch-lists/{punchList}/edit', [PunchListController::class, 'edit'])->name('punch-lists.edit');
    Route::put('punch-lists/{punchList}', [PunchListController::class, 'update'])->name('punch-lists.update');
    Route::delete('punch-lists/{punchList}', [PunchListController::class, 'destroy'])->name('punch-lists.destroy');

    // ITPs
    Route::get('itps', [ItpController::class, 'index'])->name('itps.index');
    Route::get('itps/create', [ItpController::class, 'create'])->name('itps.create');
    Route::post('itps', [ItpController::class, 'store'])->name('itps.store');
    Route::get('itps/{itp}', [ItpController::class, 'show'])->name('itps.show');
    Route::get('itps/{itp}/edit', [ItpController::class, 'edit'])->name('itps.edit');
    Route::put('itps/{itp}', [ItpController::class, 'update'])->name('itps.update');
    Route::delete('itps/{itp}', [ItpController::class, 'destroy'])->name('itps.destroy');

    // Material Test Certificates
    Route::get('material-test-certificates', [MaterialTestCertificateController::class, 'index'])->name('material-test-certificates.index');
    Route::get('material-test-certificates/create', [MaterialTestCertificateController::class, 'create'])->name('material-test-certificates.create');
    Route::post('material-test-certificates', [MaterialTestCertificateController::class, 'store'])->name('material-test-certificates.store');
    Route::get('material-test-certificates/{materialTestCertificate}', [MaterialTestCertificateController::class, 'show'])->name('material-test-certificates.show');
    Route::get('material-test-certificates/{materialTestCertificate}/edit', [MaterialTestCertificateController::class, 'edit'])->name('material-test-certificates.edit');
    Route::put('material-test-certificates/{materialTestCertificate}', [MaterialTestCertificateController::class, 'update'])->name('material-test-certificates.update');
    Route::delete('material-test-certificates/{materialTestCertificate}', [MaterialTestCertificateController::class, 'destroy'])->name('material-test-certificates.destroy');

    // Corrective Actions
    Route::get('corrective-actions', [CorrectiveActionController::class, 'index'])->name('corrective-actions.index');
    Route::get('corrective-actions/create', [CorrectiveActionController::class, 'create'])->name('corrective-actions.create');
    Route::post('corrective-actions', [CorrectiveActionController::class, 'store'])->name('corrective-actions.store');
    Route::get('corrective-actions/{correctiveAction}', [CorrectiveActionController::class, 'show'])->name('corrective-actions.show');
    Route::get('corrective-actions/{correctiveAction}/edit', [CorrectiveActionController::class, 'edit'])->name('corrective-actions.edit');
    Route::put('corrective-actions/{correctiveAction}', [CorrectiveActionController::class, 'update'])->name('corrective-actions.update');
    Route::delete('corrective-actions/{correctiveAction}', [CorrectiveActionController::class, 'destroy'])->name('corrective-actions.destroy');
});

// Approvals - Approval Workflow Management
use App\Http\Controllers\Admin\Crm\ClientController;
use App\Http\Controllers\Admin\Crm\LeadController;
use App\Http\Controllers\Admin\Crm\ProposalController;
use App\Http\Controllers\Admin\ApprovalController;

Route::prefix('dashboard/crm')->name('admin.crm.')->middleware('auth')->group(function () {
    Route::resource('clients', ClientController::class);
    Route::post('clients/{client}/contacts', [ClientController::class, 'addContact'])->name('clients.contacts.store');
    Route::delete('clients/{client}/contacts/{contact}', [ClientController::class, 'removeContact'])->name('clients.contacts.destroy');
    Route::post('clients/{client}/communications', [ClientController::class, 'addCommunication'])->name('clients.communications.store');
    Route::post('clients/{client}/documents', [ClientController::class, 'uploadDocument'])->name('clients.documents.store');
    Route::delete('clients/{client}/documents/{document}', [ClientController::class, 'deleteDocument'])->name('clients.documents.destroy');

    Route::resource('leads', LeadController::class);
    Route::post('leads/{lead}/communications', [LeadController::class, 'addCommunication'])->name('leads.communications.store');
    Route::post('leads/{lead}/convert', [LeadController::class, 'convertToClient'])->name('leads.convert');

    Route::resource('proposals', ProposalController::class);
    Route::post('proposals/{proposal}/status', [ProposalController::class, 'updateStatus'])->name('proposals.status');
});

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

// Fallback for unmatched URLs — renders full layout with middleware stack
Route::fallback(function () {
    return response()->view('errors.404', ['errors' => new \Illuminate\Support\ViewErrorBag], 404);
});
