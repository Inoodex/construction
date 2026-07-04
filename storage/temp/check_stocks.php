<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$stocks = DB::table('stocks')->select('id','material_id','warehouse_id','site_id','quantity')->get();
foreach ($stocks as $s) {
    echo "material={$s->material_id} warehouse={$s->warehouse_id} site={$s->site_id} qty={$s->quantity}\n";
}
echo "warehouses: " . DB::table('warehouses')->count() . ", sites: " . DB::table('sites')->count() . "\n";
echo "warehouse ids: " . DB::table('warehouses')->pluck('id')->implode(',') . "\n";
echo "site ids: " . DB::table('sites')->pluck('id')->implode(',') . "\n";
