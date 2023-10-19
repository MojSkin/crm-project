<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProductCategoryController extends Controller
{
    public function list()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست دسته‌بندی‌های کالا',
            'result' => null
        ];

        try {
            $result = ProductCategory::whereNull('product_category_id')->with('children')->get();
            $response = [
                'status' => true,
                'message' => 'فهرست دسته‌بندی‌های کالا با موفقیت دریافت شد.',
                'result' => $result
            ];
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function saveCategory(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره دسته‌بندی‌های کالا',
            'result' => null
        ];

        $validator = Validator::make($request->all(), [

        ]);

        if ($validator->fails()) {
            $response['messages'] = $this->normalizeError($validator->errors());

            return response()->json($response, 400);
        }

        try {
            $category = ProductCategory::updateOrCreate(
                ['id' => $request->id],
                $request->all()
            );

            $category = ProductCategory::whereId($category->id)->with('children')->get();
            $response = [
                'status' => true,
                'message' => 'دسته‌بندی مورد نظر با موفقیت ذخیره شد.',
                'result' => $category
            ];

        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
