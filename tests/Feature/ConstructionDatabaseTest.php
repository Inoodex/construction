<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConstructionDatabaseSeeder;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\Site;
use App\Models\Task;
use App\Models\Material;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceivedNote;
use App\Models\MaterialTransfer;
use App\Models\MaterialIssueSlip;
use App\Models\MaterialWastage;
use App\Models\ReportTemplate;
use App\Models\ScheduledReport;

class ConstructionDatabaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all tables migrate and seed correctly,
     * and that Eloquent relationships work properly.
     */
    public function test_construction_database_migrations_and_seeder(): void
    {
        // 1. Run migrations and seed data
        $this->seed(ConstructionDatabaseSeeder::class);

        // 2. Assert Vendor records are correct
        $this->assertDatabaseCount('vendors', 3);
        $steelVendor = Vendor::where('trade_category', 'Steel')->first();
        $this->assertNotNull($steelVendor);
        $this->assertEquals('Steel King Industries', $steelVendor->name);
        $this->assertEquals('approved', $steelVendor->status);
        $this->assertEquals(5, $steelVendor->performance_rating);

        // 3. Assert Projects & Sites
        $this->assertDatabaseCount('projects', 2);
        $activeProject = Project::where('status', 'active')->first();
        $this->assertNotNull($activeProject);
        $this->assertCount(2, $activeProject->sites);
        
        $site = $activeProject->sites->first();
        $this->assertInstanceOf(Site::class, $site);
        $this->assertEquals($activeProject->id, $site->project_id);

        // 4. Assert Tasks & Dependencies
        $this->assertDatabaseCount('tasks', 4);
        $taskWithDependency = Task::where('name', 'Foundation Pile Casting')->first();
        $this->assertNotNull($taskWithDependency);
        $this->assertCount(1, $taskWithDependency->dependencies);
        
        $dependency = $taskWithDependency->dependencies->first();
        $this->assertEquals('Soil Testing and Soil Reinforcement', $dependency->name);

        $dependentTasks = $dependency->dependentTasks;
        $this->assertCount(1, $dependentTasks);
        $this->assertEquals('Foundation Pile Casting', $dependentTasks->first()->name);

        // 5. Assert Materials & Warehouses
        $this->assertDatabaseCount('materials', 3);
        $this->assertDatabaseCount('warehouses', 2);

        // 6. Assert Stock Levels
        $this->assertDatabaseCount('stocks', 4); // central depot (2) + site A cement (1) + site A steel (1)
        $centralCentralDepot = Warehouse::where('name', 'Central Equipment & Materials Depot')->first();
        $cementMaterial = Material::where('sku', 'MAT-CM-PCC')->first();
        
        $cementStock = Stock::where('warehouse_id', $centralCentralDepot->id)
                             ->where('material_id', $cementMaterial->id)
                             ->first();
        $this->assertNotNull($cementStock);
        $this->assertEquals(2500.0000, floatval($cementStock->quantity));

        // 7. Assert Procurement Flows
        $this->assertDatabaseCount('purchase_requisitions', 1);
        $this->assertDatabaseCount('purchase_orders', 1);
        $this->assertDatabaseCount('goods_received_notes', 1);

        $po = PurchaseOrder::first();
        $this->assertEquals('PO-2026-0001', $po->po_number);
        $this->assertEquals($steelVendor->id, $po->vendor_id);
        $this->assertCount(1, $po->items);

        $grn = GoodsReceivedNote::first();
        $this->assertEquals('GRN-2026-0001', $grn->grn_number);
        $this->assertEquals($po->id, $grn->purchase_order_id);
        $this->assertCount(1, $grn->items);

        // 8. Assert Stock Movements (Transfer, Issue, Wastage)
        $this->assertDatabaseCount('material_transfers', 1);
        $transfer = MaterialTransfer::first();
        $this->assertEquals('TRF-2026-0001', $transfer->transfer_number);
        $this->assertCount(1, $transfer->items);

        $this->assertDatabaseCount('material_issue_slips', 1);
        $issue = MaterialIssueSlip::first();
        $this->assertEquals('ISS-2026-0001', $issue->issue_number);
        $this->assertCount(1, $issue->items);

        $this->assertDatabaseCount('material_wastages', 1);
        $wastage = MaterialWastage::first();
        $this->assertEquals('Cutoff scrap from structural pillar P3 column reinforcement binding.', $wastage->reason);

        // 9. Assert Reports & Analytics
        $this->assertDatabaseCount('report_templates', 1);
        $this->assertDatabaseCount('scheduled_reports', 1);

        $template = ReportTemplate::first();
        $this->assertEquals('Project Steel Consumption & Cost Report', $template->name);
        $this->assertEquals('bar', $template->configuration['chart_type']);

        $schedule = ScheduledReport::first();
        $this->assertEquals($template->id, $schedule->report_template_id);
        $this->assertContains('admin@construction.com', $schedule->recipients);
    }
}
