<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MojSkin;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCommentResource;
use App\Http\Resources\ProjectNoteResource;
use App\Http\Resources\ProjectResource;
use App\Models\Comment;
use App\Models\ContactProject;
use App\Models\Image;
use App\Models\Project;
use App\Models\ProjectNote;
use App\Models\ProjectType;
use App\Models\ProjectUser;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public $project_path = 'uploads/projects';

    public function getProjectsList(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست پروژه‌ها',
            'result' => null
        ];

        try {
            $projects = Project::with(['covers', 'images', 'files', 'contacts', 'comments', 'notes', 'members', 'project_type', 'city', 'user']);
            $projects = $projects->orderBy('created_at', 'DESC')->get();

            $response['result'] = ProjectResource::collection($projects);
            $response['status'] = true;
            $response['message'] = 'فهرست پروژه‌ها با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage().' at line '.$e->getLine().' in '.$e->getFile();
            $response['trace'] = $e->getTrace();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function getProjectsExtData()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت اطلاعات تکمیلی پروژه‌ها',
            'result' => null
        ];

        try {
            $response['result'] = [
                'types'     => (new ProjectTypeController())->getProjectTypeList()->original['result'] ?? [],
                'statuses'  => (new ProjectStatusController())->getProjectStatusList()->original['result'] ?? [],
                'results'   => (new ProjectResultController())->getProjectResultList()->original['result'] ?? [],
                'contacts'  => (new ContactController())->getContactsList(new Request([]))->original['result'] ?? [],
                'positions' => (new ContactPositionController())->getPositionsList()->original['result'] ?? [],
                'cities'    => (new CityController())->getCitiesList()->original['result'] ?? [],
                'users'     => (new UserController())->list()->original['result']['users'] ?? [],
            ];
            $response['status'] = true;
            $response['message'] = 'اطلاعات تکمیلی پروژه‌ها با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage().' at line '.$e->getLine().' in '.$e->getFile();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function saveProject(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره پروژه',
            'result' => null
        ];
        try {
            $path = public_path('storage/'.$this->project_path.'/images');
            MojSkin::makeDir($path);

            $projectData = [
                'id' => (isset($request->id) && $request->id != null && $request->id > 0) ? (int) $request->id : null,
                'title' => $request->title,
                'description' => $request->description,
                'project_type_id' => $request->project_type_id,
                'user_percentage' => $request->user_percentage,
                'city_id' => $request->city_id,
                'region' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->region ?? '', 'en', 'fa'), 'en', 'ar'),
                'main_street' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->main_street ?? '', 'en', 'fa'), 'en', 'ar'),
                'aux1' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->aux1 ?? '', 'en', 'fa'), 'en', 'ar'),
                'aux2' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->aux2 ?? '', 'en', 'fa'), 'en', 'ar'),
                'alley1' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->alley1 ?? '', 'en', 'fa'), 'en', 'ar'),
                'alley2' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->alley2 ?? '', 'en', 'fa'), 'en', 'ar'),
                'address' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->address ?? '', 'en', 'fa'), 'en', 'ar'),
                'po_code' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->po_code ?? '', 'en', 'fa'), 'en', 'ar'),
                'lat' => $request->lat,
                'long' => $request->long,
                'blocks' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->blocks ?? '1', 'en', 'fa'), 'en', 'ar'),
                'units' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->units ?? '1', 'en', 'fa'), 'en', 'ar'),
                'floors' => MojSkin::replaceDigits(MojSkin::replaceDigits($request->floors ?? '1', 'en', 'fa'), 'en', 'ar'),
            ];


            if ($projectData['id'] === null) {
                $projectData['user_id'] = Auth::id();
            }

            $project = Project::updateOrCreate(
                ['id' => $projectData['id']],
                $projectData
            );

            $members = json_decode($request->members);
            $contacts = json_decode($request->contacts);

            $project_members = [];
            $project_contacts = [];

            foreach($members as $member) {
                $project_members[] = [
                    'project_id' => $project->id ?? null,
                    'user_id' => $member->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            foreach($contacts as $contact) {
                $project_contacts[] = [
                    'contact_id' => $contact->id,
                    'contact_position_id' => $contact->position->id,
                    'project_id' => $project->id ?? null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            ProjectUser::whereProjectId($project->id)->delete();
            ProjectUser::insert($project_members);

            ContactProject::whereProjectId($project->id)->delete();
            ContactProject::insert($project_contacts);

            if ($project->id !== $projectData['id']) { // Project is a new project
                ProjectNote::create([
                    'project_id' => $project->id,
                    'user_id' => Auth::id(),
                    'note' => 'ثبت پروژه جدید',
                    'project_status_id' => $request->project_status_id,
                    'project_result_id' => $request->project_result_id,
                ]);
            }

            $files = [];
            foreach($request->all() as $file) {
                if (gettype($file) === 'object' and ($file->isValid() ?? false)) {
                    $extension   = $file->getClientOriginalExtension();
                    $alt         = str_replace('.'.$extension, '', $file->getClientOriginalName());
                    $target      = MojSkin::randomFileName(35, $path, 'project-'.$project->id, '', $extension);
                    $stored_file = Storage::disk('public')->putFileAs($this->project_path.'/images', new File($file), $target);

                    $files[] = [
                        'imageable_id' => $project->id,
                        'imageable_type' => 'App\Models\Project',
                        'file_name' => '/storage/'.$this->project_path.'/images/'.$target,
                        'user' => Auth::id(),
                        'alt' => $alt,
                        'type' => $project->id !== $projectData['id'] ? 'PROJECT_COVER' : 'PROJECT_IMAGE',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }

            if (count($files)>0) {
                Image::insert($files);
            }

            $project = Project::whereId($project->id)->with(['covers', 'images', 'files', 'contacts', 'comments', 'notes', 'members', 'project_type', 'city', 'user'])->first();
            $response['result'] = new ProjectResource($project);
            $response['status'] = true;
            $response['message'] = 'پروژه با موفقیت ذخیره شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage().' at line '.$e->getLine().' in '.$e->getFile();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function deleteProject(Request $request)
    {

    }

    public function addProjectComment(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره دیدگاه',
            'result' => null
        ];
        try {
            $project = Project::whereId($request->project)->first();

            if ($project) {
                Comment::make([
                    'comment' => $request->comment,
                    'commentable_id' => $project->id,
                    'commentable_type' => 'App\\Models\\Project',
                ]);
                $comments = Comment::whereCommentableId($project->id)->orderBy('created_at', 'asc')->whereCommentableType('App\\Models\\Project')->get();
                $response['result'] = ProjectCommentResource::collection($comments);
                $response['status'] = true;
                $response['message'] = 'دیدگاه شما با موفقیت ذخیره شد';
            } else {
                $response['message'] = 'پروژه مورد نظر یافت نشد';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function addProjectNote(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره پیگیری',
            'result' => null
        ];
        try {
            $project = Project::whereId($request->project)->first();

            if ($project) {
                ProjectNote::create([
                    'user_id' => Auth::id(),
                    'note' => $request->note,
                    'project_id' => $project->id,
                    'project_status_id' => $request->status,
                    'project_result_id' => $request->result,
                ]);
                $notes = ProjectNote::whereProjectId($project->id)->orderBy('created_at', 'desc')->get();
                $response['result'] = ProjectNoteResource::collection($notes);
                $response['status'] = true;
                $response['message'] = 'دیدگاه شما با موفقیت ذخیره شد';
            } else {
                $response['message'] = 'پروژه مورد نظر یافت نشد';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function addProjectAttachment(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره پیوست',
            'result' => null
        ];
        $path = public_path('storage/'.$this->project_path.'/attachments');
        MojSkin::makeDir($path);
        try {
            $project = Project::whereId($request->project)->first();

            if ($project) {
                $file = $request->attachment;
                if (gettype($file) === 'object' and ($file->isValid() ?? false)) {
                    $extension   = $file->getClientOriginalExtension();
                    $alt         = str_replace('.'.$extension, '', $file->getClientOriginalName());
                    $target      = MojSkin::randomFileName(35, $path, 'project-'.$project->id, '', $extension);
                    $stored_file = Storage::disk('public')->putFileAs($this->project_path.'/attachments', new File($file), $target);

                    Image::create([
                        'imageable_id' => $project->id,
                        'imageable_type' => 'App\Models\Project',
                        'file_name' => '/storage/'.$this->project_path.'/attachments/'.$target,
                        'user' => Auth::id(),
                        'alt' => $alt,
                        'type' => 'PROJECT_ATTACHMENT',
                    ]);

                    $project = Project::whereId($project->id)->with(['covers', 'images', 'files', 'contacts', 'comments', 'notes', 'members', 'project_type', 'city', 'user'])->first();

                    $response['result'] = new ProjectResource($project);
                    $response['status'] = true;
                    $response['message'] = 'پیوست پروژه با موفقیت ذخیره شد';
                } else {
                    $response['message'] = 'پیوست ارسالی معتبر نیست';
                }
            } else {
                $response['message'] = 'پروژه مورد نظر یافت نشد';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
