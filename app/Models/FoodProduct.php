<?php

namespace App\Models;

use App\Traits\StoreImageTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodProduct extends Model
{
    use HasFactory, StoreImageTrait;

    protected $table = "food_products";

    protected $primaryKey = "id";

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'product_images' => 'array',
    ];

    public function foodCategories()
    {
        return $this->belongsToMany(FoodCategory::class, 'food_category_products', 'food_product_id', 'food_category_id');
    }

    public function food_category_products()
    {
        return $this->hasMany(FoodCategoryProduct::class, 'food_product_id', 'id');
    }

    // public function foodMenus()
    // {
    //     return $this->belongsToMany(FoodMenu::class, 'food_menu_products', 'product_id', 'menu_id');
    // }

    public function food_menu_products()
    {
        return $this->hasMany(FoodMenuProduct::class, 'food_product_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(VendorModel::class, 'vendor_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id', 'id');
    }

    public function foodProductCombo()
    {
        return $this->hasMany(FoodProductCombo::class, 'food_product_id', 'id');
    }

    public function getFullImagePaths()
    {
        $images = [];
        if (!empty($this->product_images)) {
            foreach ($this->product_images as $image) {
                $images[] = url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . $image);
            }
        }

        return $images;
    }

    public static function manageFoodProduct($validatedData, $id, $request)
    {
        $foodProduct = empty($id) ? new FoodProduct() : FoodProduct::find($id);

        $foodProduct->vendor_id = $validatedData['vendor_id'] ?? null;
        $foodProduct->shared_product = $validatedData['shared_product'] ?? 0;
        $foodProduct->store_id = $validatedData['store_id'] ?? null;
        $foodProduct->is_editable_by_outlets = $validatedData['is_editable_by_outlets'] ?? 0;
        $foodProduct->product_name = $validatedData['product_name'] ?? null;
        $foodProduct->regular_price = $validatedData['regular_price'] ?? null;
        $foodProduct->sale_price = $validatedData['sale_price'] ?? null;
        $foodProduct->pieces = $validatedData['pieces'] ?? 0;
        $foodProduct->is_veg = $validatedData['is_veg'] ?? null;
        $foodProduct->promotion = $validatedData['promotion'] ?? 0;
        $foodProduct->description = $validatedData['description'] ?? null;

        $imagesList = [];
        if (!empty($foodProduct->product_images)) {
            $imagesList = $foodProduct->product_images;
        }

        for ($i = 0; $i < $validatedData['image_counter']; $i++) {
            if ($file = $request->file("product_image_" . $i)) {
                $file_name = "";
                $imageuploaded = $foodProduct->verifyAndStoreImage($request, "product_image_" . $i, config("global.product_image_upload_dir"));
                if ($imageuploaded != "") {
                    $file_name = $imageuploaded;
                }

                $imagesList[] = $file_name;
            }
        }

        if (is_array($imagesList) && count($imagesList) > 0) {
            $foodProduct->product_images = $imagesList;
        }

        $foodProduct->save();

        $foodProduct->foodCategories()->sync($validatedData['category_ids']);

        $foodProduct->food_menu_products()->delete();
        foreach ($validatedData['menu_ids'] as $value) {
            $foodProduct->food_menu_products()->create([
                'food_menu_id' => $value,
            ]);
        }

        //save food product combos
        if (isset($validatedData['food_heading_id'])) {
            $foodProductCombos = $foodProduct->foodProductCombo();
            foreach ($foodProductCombos as $i) {
                $i->combo_items()->delete();
            }
            $foodProductCombos->delete();
            foreach ($validatedData['food_heading_id'] as $key => $value) {
                $headingId = $validatedData['food_heading_id'][$key];
                $isRequired = $validatedData['is_required'][$key] ?? false;
                $minSelect = $isRequired == 0 ? null : $validatedData['min_select'][$key] ?? null;
                $maxSelect = $isRequired == 0 ? null : $validatedData['max_select'][$key] ?? null;

                $foodProductCombo = $foodProduct->foodProductCombo()->create([
                    'food_heading_id' => $headingId,
                    'is_required' => $isRequired,
                    'min_select' => $minSelect,
                    'max_select' => $maxSelect,
                ]);

                foreach ($validatedData['food_item_id'][$key] ?? [] as $itemKey => $comboItem) {
                    if (!isset($validatedData['food_item_id'][$key][$itemKey])) {
                        continue;
                    }

                    $is_default = isset($validatedData['is_default'][$key][$itemKey]) ? $validatedData['is_default'][$key][$itemKey] : false;
                    $extra_price = isset($validatedData['extra_price'][$key][$itemKey]) ? $validatedData['extra_price'][$key][$itemKey] : 0;

                    $foodProductCombo->combo_items()->create([
                        'food_item_id' => $validatedData['food_item_id'][$key][$itemKey] ?? null,
                        'is_default' => $is_default,
                        'extra_price' => $extra_price,
                    ]);
                }
            }
        }


        $res = ['status' => 1, 'message' => $id ? 'Product Updated' : 'Product Added', 'errors' => []];

        return $res;
    }
}
