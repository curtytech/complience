<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('color')->nullable();
            $table->text('icon')->nullable();
            $table->text('icon_bg')->nullable();
            $table->timestamps();
        });

        $seeds = [
            '1 - GOVERNANÇA E CONDUTA' => [
                'description' => 'Categoria de documentos de governança e conduta',
                'color' => 'from-indigo-500 to-blue-600',
                'icon' => 'fa-landmark',
                'icon_bg' => 'bg-indigo-100 text-indigo-600',
            ],
            '2 - ANTICORRUPÇÃO E INTEGRIDADE' => [
                'description' => 'Categoria de documentos de anti-corrupção e integridade',
                'color' => 'from-emerald-500 to-teal-600',
                'icon' => 'fa-scale-balanced',
                'icon_bg' => 'bg-emerald-100 text-emerald-600',
            ],
            '3 - CANAL DE DENÚNCIAS E INVESTIGAÇÕES' => [
                'description' => 'Categoria de documentos de denúncias e investigações',
                'color' => 'from-rose-500 to-pink-600',
                'icon' => 'fa-megaphone',
                'icon_bg' => 'bg-rose-100 text-rose-600',
            ],
            '4 - TRABALHISTAS E RELAÇÕES INTERNAS' => [
                'description' => 'Categoria de documentos de trabalhistas e relações internas',
                'color' => 'from-amber-500 to-orange-600',
                'icon' => 'fa-users',
                'icon_bg' => 'bg-amber-100 text-amber-600',
            ],
            '5 - DADOS E TECNOLOGIA' => [
                'description' => 'Categoria de documentos de dados e tecnologia',
                'color' => 'from-violet-500 to-purple-600',
                'icon' => 'fa-server',
                'icon_bg' => 'bg-violet-100 text-violet-600',
            ],
            '6 - FINANCEIRO E FISCAL' => [
                'description' => 'Categoria de documentos financeiros e fisais',
                'color' => 'from-lime-500 to-green-600',
                'icon' => 'fa-sack-dollar',
                'icon_bg' => 'bg-lime-100 text-lime-600',
            ],
            '7 - AMBIENTAL' => [
                'description' => 'Categoria de documentos ambientais',
                'color' => 'from-green-500 to-emerald-600',
                'icon' => 'fa-leaf',
                'icon_bg' => 'bg-green-100 text-green-600',
            ],
            '8 - CONCORRÊNCIA E MERCADO' => [
                'description' => 'Categoria de documentos de concorrência e mercado',
                'color' => 'from-sky-500 to-cyan-600',
                'icon' => 'fa-chart-line',
                'icon_bg' => 'bg-sky-100 text-sky-600',
            ],
        ];

        foreach ($seeds as $name => $data) {
            DB::table('file_categories')->updateOrInsert(
                ['name' => $name],
                $data
            );
        }

        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('file_categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('path');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
        Schema::dropIfExists('file_categories');
    }
};
