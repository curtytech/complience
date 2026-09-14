<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\FileCategory;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;

$categoryStyles = [
    '1 - GOVERNANÇA E CONDUTA' => [
        'name' => 'Governança e Conduta',
        'icon' => 'fa-landmark',
        'color' => 'from-indigo-500 to-blue-600',
        'iconBg' => 'bg-indigo-100 text-indigo-600',
    ],
    '2 - ANTICORRUPÇÃO E INTEGRIDADE' => [
        'name' => 'Anticorrupção e Integridade',
        'icon' => 'fa-scale-balanced',
        'color' => 'from-emerald-500 to-teal-600',
        'iconBg' => 'bg-emerald-100 text-emerald-600',
    ],
    '3 - CANAL DE DENÚNCIAS E INVESTIGAÇÕES' => [
        'name' => 'Canal de Denúncias e Investigações',
        'icon' => 'fa-megaphone',
        'color' => 'from-rose-500 to-pink-600',
        'iconBg' => 'bg-rose-100 text-rose-600',
    ],
    '4 - TRABALHISTAS E RELAÇÕES INTERNAS' => [
        'name' => 'Trabalhistas e Relações Internas',
        'icon' => 'fa-users',
        'color' => 'from-amber-500 to-orange-600',
        'iconBg' => 'bg-amber-100 text-amber-600',
    ],
    '5 - DADOS E TECNOLOGIA' => [
        'name' => 'Dados e Tecnologia',
        'icon' => 'fa-server',
        'color' => 'from-violet-500 to-purple-600',
        'iconBg' => 'bg-violet-100 text-violet-600',
    ],
    '6 - FINANCEIRO E FISCAL' => [
        'name' => 'Financeiro e Fiscal',
        'icon' => 'fa-sack-dollar',
        'color' => 'from-lime-500 to-green-600',
        'iconBg' => 'bg-lime-100 text-lime-600',
    ],
    '7 - AMBIENTAL' => [
        'name' => 'Ambiental',
        'icon' => 'fa-leaf',
        'color' => 'from-green-500 to-emerald-600',
        'iconBg' => 'bg-green-100 text-green-600',
    ],
    '8 - CONCORRÊNCIA E MERCADO' => [
        'name' => 'Concorrência e Mercado',
        'icon' => 'fa-chart-line',
        'color' => 'from-sky-500 to-cyan-600',
        'iconBg' => 'bg-sky-100 text-sky-600',
    ],
];

$descriptions = [
    '1 - GOVERNANÇA E CONDUTA' => 'Políticas, códigos de conduta e procedimentos de governança corporativa.',
    '2 - ANTICORRUPÇÃO E INTEGRIDADE' => 'Políticas anticorrupção, conflitos de interesse e integridade.',
    '3 - CANAL DE DENÚNCIAS E INVESTIGAÇÕES' => 'Canal de denúncias, comitê de ética e procedimentos de investigação.',
    '4 - TRABALHISTAS E RELAÇÕES INTERNAS' => 'Políticas trabalhistas, prevenção ao assédio e auditorias internas.',
    '5 - DADOS E TECNOLOGIA' => 'LGPD, segurança da informação e políticas de tecnologia.',
    '6 - FINANCEIRO E FISCAL' => 'Políticas financeiras, reembolso de despesas e crédito comercial.',
    '7 - AMBIENTAL' => 'Políticas ambientais e gestão de resíduos.',
    '8 - CONCORRÊNCIA E MERCADO' => 'Relação com concorrentes, fornecedores e área pública.',
];

$sourceDir = resource_path('views/site');
$destBase = storage_path('app/public/site');

if (!is_dir($destBase)) {
    mkdir($destBase, 0755, true);
    echo "Created storage dir: $destBase\n";
}

$folders = glob($sourceDir . '/*', GLOB_ONLYDIR);
sort($folders);

$admin = User::firstOrCreate(
    ['email' => 'admin@compliance.com'],
    ['name' => 'Admin Compliance', 'password' => Hash::make('password')]
);
echo "Using user ID: " . $admin->id . "\n\n";

$totalFiles = 0;
foreach ($folders as $folderPath) {
    $folderName = basename($folderPath);
    if (!isset($categoryStyles[$folderName])) {
        echo "SKIP unknown folder: $folderName\n";
        continue;
    }

    $style = $categoryStyles[$folderName];

    $destDir = $destBase . DIRECTORY_SEPARATOR . $folderName;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $category = FileCategory::firstOrCreate(
        ['name' => $style['name']],
        ['description' => $descriptions[$folderName] ?? null]
    );
    echo "=== Category: {$category->name} (ID {$category->id})\n";

    $files = FacadesFile::files($folderPath);
    foreach ($files as $splFile) {
        $filename = $splFile->getFilename();
        $destFile = $destDir . DIRECTORY_SEPARATOR . $filename;
        if (!file_exists($destFile)) {
            copy($splFile->getPathname(), $destFile);
        }
        $relPath = 'site/' . $folderName . '/' . $filename;

        $name = pathinfo($filename, PATHINFO_FILENAME);
        $file = File::firstOrCreate(
            ['path' => $relPath],
            [
                'user_id' => $admin->id,
                'category_id' => $category->id,
                'name' => $name,
                'description' => null,
            ]
        );
        echo "  [OK] $filename\n";
        $totalFiles++;
    }
    echo "  => {$category->fresh()->files()->count()} files\n\n";
}

echo "DONE: $totalFiles arquivos em " . FileCategory::count() . " categorias.\n";
exit(0);
