<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('priority')->default(0)->nullable();
            $table->boolean('is_expirable')->default(true);
            $table->unsignedInteger('specification')->index();
            $table->unsignedInteger('status');
            $table->unsignedInteger('type')->nullable();
            $table->unsignedInteger('content');
            $table->unsignedInteger('model')->nullable();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->json('company')->nullable();
            $table->string('modality')->nullable();
            $table->string('link')->unique()->nullable();
            $table->json('remuneration')->nullable();
            $table->json('requirement')->nullable();
            $table->string('notification')->nullable();
            $table->json('configuration')->nullable();
            $table->integer('result')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
