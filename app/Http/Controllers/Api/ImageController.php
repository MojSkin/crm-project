<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MojSkin;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectFileResource;
use App\Http\Resources\ProjectImageResource;
use App\Http\Resources\ProjectResource;
use App\Models\Image;
use App\Models\Project;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function renameAlt(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ویرایش عنوان/وضعیت',
            'result' => null
        ];

        try {
            $image = Image::whereId($request->image)->first();
            if ($image) {
                $image->update([
                    'alt' => $request->alt,
                    'type' => $request->type ?? $image->type ?? 'IMAGE'
                ]);
                if ($image->imageable_type === 'App\Models\Project') {
                    $project = Project::whereId($image->imageable_id)->first();
                    $response['result'] = [
                        'covers' => count($project->covers) > 0 ? ProjectImageResource::collection($project->covers) : [['id' => -1, 'filename' => asset('assets/images/png/no-image.png')]],
                        'images' => ProjectImageResource::collection($project->images),
                        'files'  => ProjectFileResource::collection($project->files),
                    ];
                }

                $response['status'] = true;
                $response['message'] = 'ویرایش عنوان/وضعیت با موفقیت انجام شد';
            } else {
                $response['status'] = false;
                $response['message'] = 'آیتم مورد نظر یافت نشد';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function deleteImage(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام حذف تصویر',
            'result' => null
        ];

        try {
            $image = Image::whereId($request->image)->first();

            if ($image) {
                if ($image->imageable_type === 'App\Models\Project') {
                    $project = Project::whereId($image->imageable_id)->first();
                    $response['result'] = [
                        'covers' => count($project->covers) > 0 ? ProjectImageResource::collection($project->covers) : [['id' => -1, 'filename' => asset('assets/images/png/no-image.png')]],
                        'images' => ProjectImageResource::collection($project->images),
                    ];
                }
                $image->delete();
                if (file_exists(public_path($image->file_name))) {
                    MojSkin::unlinkFile(public_path($image->file_name));
                }
                $response['status'] = true;
                $response['message'] = 'تصویر مورد نظر با موفقیت حذف شد';
            } else {
                $response['status'] = false;
                $response['message'] = 'تصویر مورد نظر یافت نشد';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function downloadFile(Request $request)
    {
        if ($request->file) {
            $file = Image::whereId($request->file)->with('imageable')->first();
            $owner = $file->imageable;
            if ($file) {
                $fileName  = str_replace(' ', '-', $owner->title ?? '');
                $fileName .= (strlen($fileName) > 0 ? '-' : '').str_replace(' ', '-', $file->alt);
                $fileName .= '-'.time();
                $mime_type = mime_content_type(public_path($file->file_name));
                $f = public_path($file->file_name);
                return response()->download($f, $fileName, ['Content-Type: '.$mime_type]);
            }
        }
        return response()->json(null, 404);
    }
}
