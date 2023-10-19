<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getSettingValues()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت تنظیمات سیستم',
            'result' => null
        ];

        try {
            $settings = Setting::all();
            $result = [];
            foreach ($settings as $val) {
                $result[$val->key] = $val->value;
            }
            $response = [
                'status' => true,
                'message' => 'تنظیمات سیستم با موفقیت دریافت شد.',
                'result' => $result
            ];
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return $response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function saveSettings(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره تنظیمات سیستم',
            'result' => null
        ];

        try {
            Setting::truncate();
            $records = [];
            foreach ($request->all() as $key => $value) {
                $records[] = [
                    'key' => $key,
                    'value' => $value,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            Setting::insert($records);
            $response = [
                'status' => true,
                'message' => 'تنظیمات سیستم با موفقیت ذخیره شد.',
            ];
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
