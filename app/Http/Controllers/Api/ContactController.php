<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MojSkin;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContactEditResource;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\ContactContactTag;
use App\Models\ContactDetail;
use App\Models\ContactTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ContactController extends Controller
{
    public function getContactsList(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست مخاطبین',
            'result' => null
        ];

        try {
            $contacts = Contact::orderBy(DB::raw("TRIM(CONCAT(COALESCE(`lName`, ''), ' ',COALESCE(`fName`,'')))"));
            if ($request->filters != null) {
                $filtered = 0;
                $records = [];
                $filters = $request->filters;
                unset($filters['phone'], $filters['email'], $filters['email'], $filters['others']);
                $contacts = null;
                foreach ($filters as $key => $value) {
                    if ($key == 'prefix') {
                        if ($value>0) {
                            if ($filtered==0) {
                                $contacts = Contact::where('prefix', $value);
                            } else {
                                $contacts->orWhere('prefix', $value);
                            }
                            $filtered++;
                        }
                    } else {
                        if ($value and strlen($value)>0) {
                            if ($filtered==0) {
                                $contacts = Contact::where($key, 'like', '%'.$value.'%');
                            } else {
                                $contacts->orWhere($key, 'like', '%'.$value.'%');
                            }
                            $filtered++;
                        }
                    }
                }
                if ($contacts) {
                    $records = $contacts->pluck('id')->toArray();
                }
                $filters = (object) $request->filters;
                if ($filters->phone) $records = array_merge($records, ContactDetail::where('section', 'phone')->where('value', 'like', '%'.$filters->phone.'%')->pluck('contact_id')->toArray());
                if ($filters->email) $records = array_merge($records, ContactDetail::where('section', 'email')->where('value', 'like', '%'.$filters->email.'%')->pluck('contact_id')->toArray());
                if ($filters->address) $records = array_merge($records, ContactDetail::where('section', 'address')->where('value', 'like', '%'.$filters->address.'%')->pluck('contact_id')->toArray());
                if ($filters->other) $records = array_merge($records, ContactDetail::where('section', 'other')->where('value', 'like', '%'.$filters->other.'%')->pluck('contact_id')->toArray());
                $records = array_unique($records);
                $contacts = $contacts->whereIn('id', $records)->with('tags')->get();
            } else {
                $contacts = $contacts->with('tags')->get();
            }
            $response['status'] = true;
            $response['result'] = ContactResource::collection($contacts);
            $response['message'] = 'اطلاعات مخاطبین با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function getContactsExtData()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت اطلاعات تکمیلی اشخاص',
            'result' => null
        ];

        try {
            $response['result'] = [
                'prefixes' => Contact::getDefault('prefix_text'),
                'additional_infos' => Contact::getDefault('additional_infos'),
                'tags' => ContactTag::select('id', 'title', 'bgColor', 'textColor')->orderBy('title')->get()
            ];
            $response['message'] = 'اطلاعات تکمیلی اشخاص با موفقیت دریافت شد';
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function saveContact(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطای ناشناخته هنگام ایجاد / ویرایش مخاطب!',
            'result' => null
        ];
        $logo = false;
        if ($request['id'] and $request['id'] != null) {
            $contact = Contact::whereId($request['id'])->first();
            if (isset($request->avatar) and strlen($request->avatar) > 0) {
                $logo = $request->avatar;
                unset($request->avatar);
            }
        }
        try {
            $contact = Contact::updateOrCreate(
                ['id' => $request->id],
                [
                    'fName' => $request->fName,
                    'prefix' => $request->prefix,
                    'lName' => $request->lName,
                    'nickName' => $request->nickname,
                    'title' => $request->title,
                    'organization' => $request->organization,
                ]);

            if ($logo) {
                if ($contact->fileName && file_exists(public_path('/storage/contact_avatars/' . $contact->fileName))) {
                    MojSkin::unlinkFile(public_path('/storage/contact_avatars/' . $contact->fileName));
                }
                $fileName = MojSkin::randomFileName(10, public_path('/storage/contact_avatars/'), '', '', 'png', true);
                MojSkin::makeDir(public_path('/storage/contact_avatars/'), 0755, true);
                $saveFullPath = public_path('/storage/contact_avatars/' . $fileName);
                $avatarData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $logo));
                $avatar = Image::make($avatarData)->resize(300, 300);
                $avatar->save($saveFullPath);
                $contact->fileName = $fileName;
                $contact->save();
            } elseif ($contact->fileName && file_exists(public_path('/storage/contact_avatars/' . $contact->fileName))) {
                MojSkin::unlinkFile(public_path('/storage/contact_avatars/' . $contact->fileName));
                $contact->fileName = null;
                $contact->save();
            }
            ContactDetail::whereContactId($contact->id)->delete();
            if(count($request->additional_infos)){
                foreach ($request->additional_infos as $additional_info) {
                    if (strlen($additional_info['section']) && strlen($additional_info['label']) && strlen($additional_info['value'])) {
                        ContactDetail::create([
                            'contact_id' => $contact->id,
                            'section' => $additional_info['section'],
                            'title' => $additional_info['label'],
                            'value' => $additional_info['value'],
                        ]);
                    }
                }
            }
            $response = [
                'status' => true,
                'message' => 'مخاطب مورد نظر با موفقیت ثبت شد!',
                'result' => new ContactResource($contact)
            ];
        } catch (\Exception $e) {
            $response['errorMessage'] = $e->getMessage();
        }
        return response()->json($response, 200);
    }

    public function getSingleContact(Request $request)
    {
        $contact = Contact::whereId($request->id)->with('tags')->first();
        $response = [
            'status' => false,
            'message' => 'مخاطب مورد نظر یافت نشد!',
        ];
        if ($contact) {
            $response = [
                'status' => true,
                'result' => new ContactEditResource($contact),
            ];
        }
        return response()->json($response, 200);
    }
    public function deleteContact(Request $request)
    {
        $contact = Contact::whereId($request->id)->first();
        $response = [
            'status' => false,
            'message' => 'مخاطب مورد نظر یافت نشد!',
        ];
        if ($contact) {
            $contact->details()->delete();
            if ($contact->fileName != null) {
                if ($contact->fileName && file_exists(public_path('/storage/contact_avatars/' . $contact->fileName))) {
                    MojSkin::unlinkFile(public_path('/storage/contact_avatars/' . $contact->fileName));
                }
            }
            $contact->delete();
            $response = [
                'status' => true,
                'message' => 'مخاطب با موفقیت حذف شد!',
            ];
        }
        return response()->json($response, 200);

    }

    public function setContactTag(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'خطا هنگام اجرای برچسب مخاطب!',
        ];
        try {
            $contactTag = ContactContactTag::whereContactId($request->contact_id)->whereContactTagId($request->tag_id)->first();
            if ($contactTag) {
                $contactTag->delete();
                $response['message'] = 'برچسب مخاطب با موفقیت برداشته شد.';
            } else {
                ContactContactTag::create([
                    'contact_id' => $request->contact_id,
                    'contact_tag_id' => $request->tag_id,
                ]);
                $response['message'] = 'برچسب مخاطب با موفقیت افزوده شد.';
            }
            $contact = Contact::whereId($request->contact_id)->with('tags')->first();
            $response['result'] = $contact->tags;
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }
        return response()->json($response, 200);
    }
}
