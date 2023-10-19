<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getCitiesList()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست شهرستان‌ها',
            'result' => null
        ];

        try {
            $response['result'] = CityResource::collection(City::orderBy('name')->get());
            $response['status'] = true;
            $response['message'] = 'فهرست شهرستان‌ها با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

}
