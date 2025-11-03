<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mohon_tinggal_kenderaan', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('no_rujukan', 50)->unique();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('id_lot')->index();
            $table->string('model', 100)->nullable();
            $table->string('no_pendaftaran', 50)->nullable();
            $table->string('warna', 50)->nullable();
            $table->string('aras', 10)->nullable();
            $table->string('tujuan', 100)->nullable();
            $table->dateTime('tarikh_mula')->nullable();
            $table->dateTime('tarikh_tamat')->nullable();
            $table->string('bangunan', 50)->nullable();
            $table->dateTime('tarikh_mohon')->nullable();
            $table->tinyInteger('status_permohonan')->default(0);
            $table->string('status', 5)->default('1');
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('deleted_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_lot')->references('id')->on('lots')->onDelete('restrict')->onUpdate('cascade');
            $table->index(['status_permohonan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mohon_tinggal_kenderaan');
    }
};
