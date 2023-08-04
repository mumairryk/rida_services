<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\FoodHeading;
use App\Models\FoodItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodItemsController extends Controller
{
    public function storeHeading(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'heading_name' => 'required|unique:food_headings,name|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => '0',
                'errors' => $validated->errors(),
                'message' => 'Validation Error'
            ]);
        }

        $heading = new FoodHeading();
        $heading->name = $request->heading_name;
        $heading->save();

        return response()->json([
            'status' => '1',
            'message' => 'Heading Added Successfully',
            'errors' => [],
            'data' => [
                'id' => $heading->id,
                'name' => $heading->name
            ]
        ]);
    }

    public function storeItems(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'item_name' => 'required|unique:food_items,name|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => '0',
                'errors' => $validated->errors(),
                'message' => 'Validation Error'
            ]);
        }

        $item = new FoodItems();
        $item->name = $request->item_name;
        $item->save();

        return response()->json([
            'status' => '1',
            'message' => 'Item Added Successfully',
            'errors' => [],
            'data' => [
                'id' => $item->id,
                'name' => $item->name
            ]
        ]);
    }
}
