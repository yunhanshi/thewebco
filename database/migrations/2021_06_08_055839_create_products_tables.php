<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('product_category');

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191)->default('');
            $table->decimal('price', 8, 2)->default(0);
            $table->integer('sort_order')->default(100);
            $table->softDeletes();
            $table->timestamps();
            $table->index(["name"]);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191)->default('');
            $table->integer('sort_order')->default(100);
            $table->softDeletes();
            $table->timestamps();
            $table->index(["name"]);
        });

        Schema::create('product_category', function (Blueprint $table) {
            $table->bigInteger('product_id');
            $table->bigInteger('category_id');
            $table->timestamps();
            $table->unique(["product_id", "category_id"]);
            $table->index(["category_id"]);
        });


    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('product_category');
    }
}
