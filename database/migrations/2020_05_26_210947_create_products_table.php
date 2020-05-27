<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('producer_id');
            $table->foreign('producer_id')->references('id')->on('producers');

            $table->string('name');
            $table->string('image');
            $table->string('sku_code');
            $table->string('display')->default('Đang cập nhật...');
            $table->string('cpu')->default('Đang cập nhật...');
            $table->string('ram')->default('Đang cập nhật...');
            $table->string('storage')->default('Đang cập nhật...');
            $table->string('graphics')->default('Đang cập nhật...');
            $table->string('dimensions')->default('Đang cập nhật...');
            $table->integer('weight')->default(0);
            $table->string('OS')->default('Đang cập nhật...');
            $table->string('pin')->default('Đang cập nhật...');
            $table->longText('information_details')->nullable();
            $table->longText('product_introduction')->nullable();
            $table->float('rate', 2, 1)->default(0);
            $table->timestamps();
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
    }
}
