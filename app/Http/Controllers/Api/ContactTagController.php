<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactContactTag;
use App\Models\ContactTag;
use Illuminate\Http\Request;

class ContactTagController extends Controller
{
    public function getContactTagsList()
    {
        $response = [
            'status' => null,
            'message' => 'بروز خطا هنگام دریافت فهرست برچسب مخاطبین',
            'result' => null
        ];

        try {
            $response['status'] = true;
            $response['result'] = ContactTag::select('title', 'bgColor', 'textColor')->orderBy('title')->get();
            $response['message'] = 'اطلاعات برچسب مخاطبین با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function saveContactTag(Request $request)
    {
        $response = [
            'status' => null,
            'message' => 'بروز خطا هنگام ذخیره برچسب مخاطبین',
            'result' => null
        ];

        try {
            ContactTag::updateOrCreate(
                [
                    'id' => $request->id ?? null
                ],
                [
                    'title' => $request->title,
                    'bgColor' => $request->bgColor ?? 'rgba(0, 0, 0, 0)',
                    'textColor' => $request->textColor ?? 'rgba(200, 200, 200, 0.85)',
                ]
            );
            $act = (isset($request->id) and $request->id != null) ? 'ویرایش' : 'ذخیره';
            $response['status'] = true;
            $response['result'] = null;
            $response['message'] = 'برچسب مورد نظر با موفقیت '.$act.' شد.';
        } catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function deleteContactTag(Request $request)
    {
        $response = [
            'status' => null,
            'message' => 'بروز خطا هنگام حذف برچسب مخاطبین',
            'result' => null
        ];

        try {
            ContactContactTag::whereContactTagId($request->tag_id)->delete();
            ContactTag::whereId($request->tag_id)->delete();
            $response['status'] = true;
            $response['result'] = null;
            $response['message'] = 'برچسب مورد نظر با موفقیت حذف شد.';
        } catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

}
