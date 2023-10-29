<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectStatusResource;
use App\Models\ProjectNote;
use App\Models\ProjectStatus;
use Illuminate\Http\Request;

class ProjectStatusController extends Controller
{
    public function getProjectStatusList()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست وضعیت‌های پروژه',
            'result' => null
        ];

        try {
            $response['result'] = ProjectStatusResource::collection(ProjectStatus::orderBy('title')->get());
            $response['status'] = true;
            $response['message'] = 'اطلاعات وضعیت‌های پروژه با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
    public function saveProjectStatus(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره وضعیت پروژه',
            'result' => null
        ];

        try {
            $position = ProjectStatus::updateOrCreate(
                [
                    'id' => $request->id ?? null
                ],
                $request->all()
            );
            $act = (isset($request->id) and $request->id != null) ? 'ویرایش' : 'ذخیره';
            $response['result'] = new ProjectStatusResource($position);
            $response['status'] = true;
            $response['message'] = 'وضعیت پروژه با موفقیت '.$act.' شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
    public function deleteProjectStatus(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام حذف وضعیت پروژه',
            'result' => null
        ];

        try {
            if (ProjectNote::whereProjectStatusId($request->status_id)->count() > 0) {
                $response['message'] = 'به دلیل استفاده از این وضعیت در پیگیری پروژه‌ها، امکان حذف آن وجود ندارد';
            } else {
                ProjectStatus::whereId($request->status_id)->delete();
                $response['status'] = true;
                $response['message'] = 'اطلاعات وضعیت پروژه با موفقیت حذف شد';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function setProjectStatusAsDefault(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام تغییر در وضعیت پروژه',
            'result' => null
        ];

        try {
            ProjectStatus::whereIsDefault(true)->update([
                'is_default' => false
            ]);
            ProjectStatus::whereId($request->status_id)->update([
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
