<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactPositionResource;
use App\Models\ContactPosition;
use App\Models\ContactProject;
use Illuminate\Http\Request;

class ContactPositionController extends Controller
{
    public function getPositionsList()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست جایگاه اشخاص',
            'result' => null
        ];

        try {
            $response['result'] = ContactPositionResource::collection(ContactPosition::orderBy('title')->get());
            $response['status'] = true;
            $response['message'] = 'اطلاعات جایگاه اشخاص با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function savePosition(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره جایگاه',
            'result' => null
        ];

        try {
            $position = ContactPosition::updateOrCreate(
                [
                    'id' => $request->id ?? null
                ],
                $request->all()
            );
            $act = (isset($request->id) and $request->id != null) ? 'ویرایش' : 'ذخیره';
            $response['result'] = new ContactPositionResource($position);
            $response['status'] = true;
            $response['message'] = 'جایگاه مورد نظر با موفقیت '.$act.' شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function deletePosition(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام حذف جایگاه',
            'result' => null
        ];

        try {
            ContactProject::whereContactPositionId($request->position_id)->delete();
            ContactPosition::whereId($request->position_id)->delete();
            $response['status'] = true;
            $response['message'] = 'اطلاعات جایگاه اشخاص با موفقیت حذف شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
