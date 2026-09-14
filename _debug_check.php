<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "FileCategory count = " . App\Models\FileCategory::count() . "\n";
$c = App\Models\FileCategory::orderBy('id')->first();
if ($c) {
    echo "\nFirst record (id={$c->id}):\n";
    echo "  name        = {$c->name}\n";
    echo "  color       = " . var_export($c->color, true) . "\n";
    echo "  icon        = " . var_export($c->icon, true) . "\n";
    echo "  icon_bg     = " . var_export($c->icon_bg, true) . "\n";
    echo "  description = " . var_export($c->description, true) . "\n";
}
echo "\nTodas as 8 categorias:\n";
foreach (App\Models\FileCategory::orderBy('id')->get() as $row) {
    $icon = $row->icon ?? 'NULL';
    $color = $row->color ?? 'NULL';
    $bg = $row->icon_bg ?? 'NULL';
    echo "  [{$row->id}] {$row->name} | icon={$icon} | color={$color} | bg={$bg}\n";
}
echo "\nDB colunas de file_categories:\n";
foreach (Illuminate\Support\Facades\Schema::getColumnListing('file_categories') as $col) {
    echo "  - $col\n";
}
