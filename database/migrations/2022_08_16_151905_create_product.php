<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->integer('product_type')->default(0);
            $table->longText('product_desc_full')->nullable();
            $table->longText('product_desc_short')->nullable();
            $table->string('product_sale_from',200)->nullable();
            $table->string('product_sale_to',200)->nullable();
            $table->string('product_featured_image',600)->nullable();
            $table->string('product_tag',600)->nullable();
            $table->integer('product_created_by')->default(0);
            $table->integer('product_updated_by')->default(0);
            $table->integer('product_status')->default(0);
            $table->integer('product_deleted')->default(0);
            $table->string('product_name',900)->nullable();
            $table->integer('product_variation_type')->default(0);
            $table->integer('product_taxable')->default(1);
            $table->integer('product_vender_id')->default(0);
            $table->string('product_image',200)->nullable();
            $table->string('product_unique_iden',200)->nullable();
            $table->integer('product_brand_id')->default(0);
            $table->string('product_name_arabic',900)->nullable();
            $table->longText('product_desc_full_arabic')->nullable();
            $table->longText('product_desc_short_arabic')->nullable();
            $table->integer('cash_points')->default(0);
            $table->integer('offer_enabled')->default(0);
            $table->integer('deal_enabled')->default(0);
            $table->integer('is_today_offer')->default(0);
            $table->string('today_offer_date',200)->nullable();
            $table->double('thanku_perc', 15, 2)->nullable();
            $table->integer('custom_status')->nullable();
            $table->longText('meta_title')->nullable();
            $table->longText('meta_keyword')->nullable();
            $table->longText('meta_description')->nullable();
            $table->integer('product_vendor_status')->default(0);
            $table->string('product_gender',100)->nullable();
            $table->integer('is_kandora')->default(0);
            $table->integer('collection_id')->default(0);
            $table->integer('hot_offer_enabled')->default(0);
            $table->integer('trending_enabled')->default(0);
            $table->integer('offers_list')->default(0);
            $table->integer('zero_quantity_orders')->default(0);
            $table->string('product_code',300)->nullable();
            $table->string('product_tags',1000)->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('offer_for_short')->default(0);
            $table->integer('hot_offer_sort_order')->default(0);
            $table->integer('new_trending_sort_order')->default(0);
            $table->integer('author_id')->default(0);
            $table->integer('deleted')->default(0);
            $table->integer('default_category_id')->default(0);
            $table->integer('default_attribute_id')->default(0);
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
        Schema::dropIfExists('product');
    }
}
