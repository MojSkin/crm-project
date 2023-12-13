<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResultResource;
use App\Models\ProjectNote;
use App\Models\ProjectResult;
use Illuminate\Http\Request;

class ProjectResultController extends Controller
{
    public function getProjectResultList()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست انواع پروژه',
            'result' => null
        ];

        try {
            $response['result'] = ProjectResultResource::collection(ProjectResult::orderBy('title')->get());
            $response['status'] = true;
            $response['message'] = 'اطلاعات انواع پروژه با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
    public function saveProjectResult(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره نوع پروژه',
            'result' => null
        ];

        try {
            $position = ProjectResult::updateOrCreate(
                [
                    'id' => $request->id ?? null
                ],
                $request->all()
            );
            $act = (isset($request->id) and $request->id != null) ? 'ویرایش' : 'ذخیره';
            $response['result'] = new ProjectResultResource($position);
            $response['status'] = true;
            $response['message'] = 'نوع پروژه با موفقیت '.$act.' شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
    public function deleteProjectResult(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام حذف نتیجه پروژه',
            'result' => null
        ];

        try {
            if (ProjectNote::whereProjectStatusId($request->result_id)->count() > 0) {
                $response['message'] = 'به دلیل استفاده از این نتیجه در پیگیری پروژه‌ها، امکان حذف آن وجود ندارد';
            } else {
                ProjectResult::whereId($request->result_id)->delete();
                $response['status'] = true;
                $response['message'] = 'اطلاعات نتیجه پروژه با موفقیت حذف شد';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function setProjectResultAsDefault(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام تغییر در نتیجه پروژه',
            'result' => null
        ];

        try {
            ProjectResult::whereIsDefault(true)->update([
                'is_default' => false
            ]);
            ProjectResult::whereId($request->result_id)->update([
                'is_default' => true
            ]);
            $response['status'] = true;
            $response['message'] = 'وضعیت پروژه با موفقیت به عنوان پیش‌فرض ثبت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
