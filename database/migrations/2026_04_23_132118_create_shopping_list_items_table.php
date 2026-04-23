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
        Schema::create('shopping_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shopping_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('catalog_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('emoji', 8)->nullable();
            $table->string('category')->nullable();
            $table->decimal('quantity', 8, 2)->default(1.00);
            $table->string('unit', 20)->default('un');
            $table->string('preferred_store')->nullable();
            $table->boolean('is_bought')->default(false);
            $table->timestamp('bought_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['shopping_list_id', 'is_bought']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_list_items');
    }
};
