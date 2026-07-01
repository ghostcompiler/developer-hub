<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('linked_repos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('repo_url');
            $table->string('status')->default('pending'); // pending or approved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linked_repos');
    }
};
