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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('stars_count')->default(0);
            $table->integer('forks_count')->default(0);
            $table->integer('open_issues_count')->default(0);
            $table->integer('downloads_count')->default(0);
            $table->string('language')->nullable();
            $table->string('license_name')->nullable();
            $table->string('github_url');
            $table->string('homepage_url')->nullable();
            $table->string('default_branch')->default('main');
            $table->json('topics')->nullable();
            $table->json('releases_info')->nullable();
            $table->mediumText('readme_html')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
