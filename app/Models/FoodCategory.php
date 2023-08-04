<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodCategory extends Model
{
    use HasFactory;

    protected $table = "food_categories";

    protected $primaryKey = "id";

    protected $guarded = ['id', 'updated_at', 'created_at', 'deleted_at'];

    public function getImageAttribute($image)
    {
        return get_uploaded_image_url($image, 'food_category_image_upload_dir', 'placeholder.png');
    }

    public function getBannerImageAttribute($banner_image)
    {
        return get_uploaded_image_url($banner_image, 'food_category_image_upload_dir', 'placeholder.png');
    }

    public function children()
    {
        return $this->hasMany(FoodCategory::class, 'parent_id', 'id');
    }

    public static function sort_item($item = [])
    {
        if (!empty($item)) {
            DB::beginTransaction();
            try {
                $i = 0;
                foreach ($item as $key) {
                    FoodCategory::where('id', $key)
                        ->update(['sort_order' => $i]);
                    $i++;
                }
                DB::commit();
                return 1;
            } catch (\Exception $e) {
                DB::rollback();
                return 0;
            }
        } else {
            return 0;
        }
    }
}
