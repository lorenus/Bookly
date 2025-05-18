<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogroUserTable extends Migration
{
    public function up()
    {
        Schema::create('logro_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('logro_id')->constrained()->cascadeOnDelete();
            $table->integer('progreso')->default(0);
            $table->boolean('completado')->default(false);
            $table->timestamp('completado_en')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'logro_id']); // Evita duplicados
        });
    }

    public function down()
    {
        Schema::dropIfExists('logro_user');
    }
}
