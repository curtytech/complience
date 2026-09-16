<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade');
            $table->string('name')->required();
            $table->text('email')->required();
            $table->text('cpf')->required();
            $table->timestamps();
        });

  
    }

    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
