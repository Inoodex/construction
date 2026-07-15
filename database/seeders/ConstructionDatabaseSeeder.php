<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\Site;
use App\Models\Task;
use App\Models\Material;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\GoodsReceivedNote;
use App\Models\GoodsReceivedNoteItem;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\MaterialTransfer;
use App\Models\MaterialTransferItem;
use App\Models\MaterialIssueSlip;
use App\Models\MaterialIssueSlipItem;
use App\Models\MaterialWastage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ConstructionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Users (if not already seeded)
        $admin = User::firstOrCreate(
            ['email' => 'hello@inoodex.com'],
            [
                'name' => 'Project Administrator',
                'password' => Hash::make('hello@inoodex.com'),
            ]
        );

        $engineer = User::firstOrCreate(
            ['email' => 'engineer@construction.com'],
            [
                'name' => 'Site Engineer Dave',
                'password' => Hash::make('password'),
            ]
        );

        $procurementOfficer = User::firstOrCreate(
            ['email' => 'procurement@construction.com'],
            [
                'name' => 'Procurement Officer Alice',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Seed Vendors
        $vendorsData = [
            [
                'name' => 'Steel King Industries',
                'contact_name' => 'John Steel',
                'email' => 'sales@steelking.com',
                'phone' => '+8801711122233',
                'address' => 'Tejgaon Industrial Area, Dhaka',
                'trade_category' => 'Steel',
                'status' => 'approved',
                'credit_limit' => 5000000.00,
                'payment_terms' => 'Net 30',
                'performance_rating' => 5,
            ],
            [
                'name' => 'LafargeHolcim Cement Ltd',
                'contact_name' => 'Karim Rahman',
                'email' => 'info@lafarge.com',
                'phone' => '+8801711144455',
                'address' => 'Gulshan 2, Dhaka',
                'trade_category' => 'Cement',
                'status' => 'approved',
                'credit_limit' => 3000000.00,
                'payment_terms' => 'Net 15',
                'performance_rating' => 4,
            ],
            [
                'name' => 'National Bricks & Co',
                'contact_name' => 'Abul Kalam',
                'email' => 'kalam@nationalbricks.com',
                'phone' => '+8801711166677',
                'address' => 'Savar, Dhaka',
                'trade_category' => 'Bricks',
                'status' => 'approved',
                'credit_limit' => 1000000.00,
                'payment_terms' => 'Cash on Delivery',
                'performance_rating' => 4,
            ]
        ];

        $vendors = [];
        foreach ($vendorsData as $vendorData) {
            $vendors[] = Vendor::create($vendorData);
        }

        // 3. Seed Projects
        $project1 = Project::create([
            'name' => 'Dhaka Elevated Expressway Sec-2',
            'description' => 'Construction of the second phase of elevated expressway linking Airport Road to Mohakhali.',
            'budget' => 250000000.00,
            'start_date' => Carbon::now()->subMonths(3)->toDateString(),
            'end_date' => Carbon::now()->addMonths(18)->toDateString(),
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        $project2 = Project::create([
            'name' => 'ConstroPro Commercial Tower',
            'description' => '25-storied premium commercial space construction at Motijheel.',
            'budget' => 180000000.00,
            'start_date' => Carbon::now()->addMonth(1)->toDateString(),
            'end_date' => Carbon::now()->addMonths(24)->toDateString(),
            'status' => 'planning',
            'created_by' => $admin->id,
        ]);

        // 4. Seed Sites
        $siteA = Site::create([
            'project_id' => $project1->id,
            'name' => 'Banani Interchange Site',
            'location_address' => 'Banani, Dhaka',
            'status' => 'active',
        ]);

        $siteB = Site::create([
            'project_id' => $project1->id,
            'name' => 'Mohakhali Flyover Link Site',
            'location_address' => 'Mohakhali, Dhaka',
            'status' => 'active',
        ]);

        // 5. Seed Tasks & Dependencies
        $task1 = Task::create([
            'project_id' => $project1->id,
            'site_id' => $siteA->id,
            'name' => 'Soil Testing and Soil Reinforcement',
            'description' => 'Conduct deep soil testing and perform sand piling.',
            'assigned_to' => $engineer->id,
            'start_date' => Carbon::now()->subMonths(3)->toDateString(),
            'end_date' => Carbon::now()->subMonths(2)->toDateString(),
            'priority' => 'critical',
            'status' => 'closed',
            'progress_percent' => 100,
        ]);

        $task2 = Task::create([
            'project_id' => $project1->id,
            'site_id' => $siteA->id,
            'name' => 'Foundation Pile Casting',
            'description' => 'Casting of 45 piles of 1200mm diameter.',
            'assigned_to' => $engineer->id,
            'start_date' => Carbon::now()->subMonths(2)->toDateString(),
            'end_date' => Carbon::now()->subMonth(1)->toDateString(),
            'priority' => 'high',
            'status' => 'closed',
            'progress_percent' => 100,
        ]);
        $task2->dependencies()->attach($task1->id);

        $task3 = Task::create([
            'project_id' => $project1->id,
            'site_id' => $siteA->id,
            'name' => 'Pillar Substructure Rebar Binding',
            'description' => 'Binding steel bars for pillars P1 to P12.',
            'assigned_to' => $engineer->id,
            'start_date' => Carbon::now()->subMonth(1)->toDateString(),
            'end_date' => Carbon::now()->addDays(15)->toDateString(),
            'priority' => 'high',
            'status' => 'in_progress',
            'progress_percent' => 60,
        ]);
        $task3->dependencies()->attach($task2->id);

        $task4 = Task::create([
            'project_id' => $project1->id,
            'site_id' => $siteA->id,
            'name' => 'Concrete Pouring for Pillars',
            'description' => 'Pouring grade-35 concrete for reinforced columns.',
            'assigned_to' => $engineer->id,
            'start_date' => Carbon::now()->addDays(16)->toDateString(),
            'end_date' => Carbon::now()->addDays(30)->toDateString(),
            'priority' => 'critical',
            'status' => 'open',
            'progress_percent' => 0,
        ]);
        $task4->dependencies()->attach($task3->id);

        // 6. Seed Materials
        $mSteel = Material::create([
            'name' => 'Reinforced Steel Rebar 16mm',
            'sku' => 'MAT-ST-16MM',
            'unit' => 'Tons',
            'description' => 'Deformed reinforcement bar grade 500W.',
        ]);

        $mCement = Material::create([
            'name' => 'Portland Composite Cement (PCC)',
            'sku' => 'MAT-CM-PCC',
            'unit' => 'Bags',
            'description' => 'Premium grade PCC cement.',
        ]);

        $mSand = Material::create([
            'name' => 'Sylhet Coarse Sand',
            'sku' => 'MAT-SD-SYLHET',
            'unit' => 'CFT',
            'description' => 'Coarse river sand for concrete mix.',
        ]);

        // 7. Seed Warehouses
        $wCentral = Warehouse::create([
            'name' => 'Central Equipment & Materials Depot',
            'location_address' => 'Tongi, Gazipur',
            'status' => 'active',
        ]);

        $wSecondary = Warehouse::create([
            'name' => 'Mirpur Temporary Storage Hub',
            'location_address' => 'Mirpur, Dhaka',
            'status' => 'active',
        ]);

        // 8. Seed Stocks
        Stock::create([
            'warehouse_id' => $wCentral->id,
            'material_id' => $mSteel->id,
            'quantity' => 150.0000,
        ]);

        Stock::create([
            'warehouse_id' => $wCentral->id,
            'material_id' => $mCement->id,
            'quantity' => 2500.0000,
        ]);

        Stock::create([
            'site_id' => $siteA->id,
            'material_id' => $mCement->id,
            'quantity' => 450.0000,
        ]);

        // 9. Seed Procurement Flow (PR -> PO -> GRN)
        // A Requisition
        $pr = PurchaseRequisition::create([
            'project_id' => $project1->id,
            'requested_by' => $engineer->id,
            'requisition_number' => 'PR-2026-0001',
            'status' => 'approved',
            'required_date' => Carbon::now()->addDays(7)->toDateString(),
        ]);

        PurchaseRequisitionItem::create([
            'purchase_requisition_id' => $pr->id,
            'material_id' => $mSteel->id,
            'quantity' => 25.5000,
            'estimated_unit_price' => 95000.00,
        ]);

        PurchaseRequisitionItem::create([
            'purchase_requisition_id' => $pr->id,
            'material_id' => $mCement->id,
            'quantity' => 1000.0000,
            'estimated_unit_price' => 520.00,
        ]);

        // A Purchase Order
        $po = PurchaseOrder::create([
            'purchase_requisition_id' => $pr->id,
            'vendor_id' => $vendors[0]->id, // Steel King
            'po_number' => 'PO-2026-0001',
            'status' => 'ordered',
            'total_amount' => 2422500.00, // 25.5 * 95000
            'order_date' => Carbon::now()->subDays(3)->toDateString(),
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'material_id' => $mSteel->id,
            'quantity' => 25.5000,
            'unit_price' => 95000.00,
        ]);

        // A Goods Received Note
        $grn = GoodsReceivedNote::create([
            'purchase_order_id' => $po->id,
            'grn_number' => 'GRN-2026-0001',
            'received_date' => Carbon::now()->subDay()->toDateString(),
            'received_by' => $engineer->id,
            'status' => 'verified',
        ]);

        GoodsReceivedNoteItem::create([
            'goods_received_note_id' => $grn->id,
            'material_id' => $mSteel->id,
            'quantity_received' => 25.5000,
            'quantity_accepted' => 25.5000,
            'quantity_rejected' => 0.0000,
        ]);

        // Update Stock based on accepted GRN quantity (representing the central depot stock update)
        $stock = Stock::where('warehouse_id', $wCentral->id)
                      ->where('material_id', $mSteel->id)
                      ->first();
        if ($stock) {
            $stock->increment('quantity', 25.5000);
        } else {
            Stock::create([
                'warehouse_id' => $wCentral->id,
                'material_id' => $mSteel->id,
                'quantity' => 25.5000,
            ]);
        }

        // 10. Seed Stock Movement: Transfer
        $transfer = MaterialTransfer::create([
            'from_warehouse_id' => $wCentral->id,
            'to_site_id' => $siteA->id,
            'transfer_number' => 'TRF-2026-0001',
            'status' => 'completed',
            'transfer_date' => Carbon::now()->toDateString(),
        ]);

        MaterialTransferItem::create([
            'material_transfer_id' => $transfer->id,
            'material_id' => $mSteel->id,
            'quantity' => 10.0000,
        ]);

        // Adjust stocks for the completed transfer
        Stock::where('warehouse_id', $wCentral->id)
             ->where('material_id', $mSteel->id)
             ->first()
             ->decrement('quantity', 10.0000);

        $siteStock = Stock::where('site_id', $siteA->id)
                          ->where('material_id', $mSteel->id)
                          ->first();
        if ($siteStock) {
            $siteStock->increment('quantity', 10.0000);
        } else {
            Stock::create([
                'site_id' => $siteA->id,
                'material_id' => $mSteel->id,
                'quantity' => 10.0000,
            ]);
        }

        // 11. Seed Stock Movement: Issue Slip
        $issueSlip = MaterialIssueSlip::create([
            'project_id' => $project1->id,
            'site_id' => $siteA->id,
            'issued_to' => $engineer->id,
            'issue_number' => 'ISS-2026-0001',
            'issue_date' => Carbon::now()->toDateString(),
        ]);

        MaterialIssueSlipItem::create([
            'material_issue_slip_id' => $issueSlip->id,
            'material_id' => $mSteel->id,
            'quantity' => 4.5000,
        ]);

        // Consume stock on-site for rebar binding
        Stock::where('site_id', $siteA->id)
             ->where('material_id', $mSteel->id)
             ->first()
             ->decrement('quantity', 4.5000);

        // 12. Seed Stock Movement: Wastage
        MaterialWastage::create([
            'project_id' => $project1->id,
            'site_id' => $siteA->id,
            'material_id' => $mSteel->id,
            'quantity' => 0.2000,
            'reason' => 'Cutoff scrap from structural pillar P3 column reinforcement binding.',
            'reported_date' => Carbon::now()->toDateString(),
            'reported_by' => $engineer->id,
        ]);
    }
}
