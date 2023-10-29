<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectTypeResource;
use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Http\Request;

class ProjectTypeController extends Controller
{
    public function getProjectTypeList()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست انواع پروژه',
            'result' => null
        ];

        try {
            $response['result'] = ProjectTypeResource::collection(ProjectType::orderBy('title')->get());
            $response['status'] = true;
            $response['message'] = 'اطلاعات انواع پروژه با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
    public function saveProjectType(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره نوع پروژه',
            'result' => null
        ];

        try {
            $position = ProjectType::updateOrCreate(
                [
                    'id' => $request->id ?? null
                ],
                $request->all()
            );
            $act = (isset($request->id) and $request->id != null) ? 'ویرایش' : 'ذخیره';
            $response['result'] = new ProjectTypeResource($position);
            $response['status'] = true;
            $response['message'] = 'نوع پروژه با موفقیت '.$act.' شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
    public function deleteProjectType(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام حذف نوع پروژه',
            'result' => null
        ];

        try {
            if (Project::whereProjectTypeId($request->type_id)->count() > 0) {
                $response['message'] = 'به دلیل استفاده از این نوع پروژه در فهرست پروژه‌ها، امکان حذف آن وجود ندارد';
            } else {
                ProjectType::whereId($request->type_id)->delete();
                $response['status'] = true;
                $response['message'] = 'اطلاعات نوع پروژه با موفقیت حذف شد';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
