<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('version_number');
            $table->string('club_logo_path')->nullable();
            $table->string('payment_receipt_path')->nullable();
            $table->string('players_roster_path')->nullable();
            $table->text('observations')->nullable();
            $table->enum('status', ['received', 'under_review', 'accepted', 'rejected', 'replaced'])->default('received');
            $table->timestamps();

            $table->unique(['submission_id', 'version_number']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_versions');
    }
};
