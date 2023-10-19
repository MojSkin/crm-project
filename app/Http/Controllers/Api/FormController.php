<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FormResource;
use App\Models\Form;
use App\Models\FormField;
use App\Models\UsedForm;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function formOptions()
    {
        $result = [
            'field_types' => Form::getDefault('field_types'),
            'statuses'    => UsedForm::getDefault('statuses'),
        ];
        $response = [
            'status' => true,
            'message' => 'دریافت اطلاعات موفقیت‌آمیز بود.',
            'result' => $result
        ];

        return response()->json($response, 200);
    }

    public function formsList()
    {
        $response = [
            'status' => true,
            'message' => 'دریافت اطلاعات موفقیت‌آمیز بود.',
            'result' => null
        ];

        try {
            $forms = Form::orderBy('title')->with(['fields', 'useCount'])->get();
            $response['result'] = $forms;
        } catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return response()->json($response, 200);
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        $response = [
            'status' => true,
            'message' => 'دریافت اطلاعات موفقیت‌آمیز بود.',
            'result' => null
        ];

        if ($validator->fails()) {
            $response['messages'] = $this->normalizeError($validator->errors());
            $response['message'] = 'بروز خطا هنگام اعتبارسنجی اطلاعات ارسالی';
            $response['status']  = false;

            return response()->json($response, 400);
        }

        try {
            $form = Form::whereId($request->id)->first();
            $form->active = !$form->active;
            $form->save();
            $response['result'] = $form;
        } catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }

        return response()->json($response, 200);
    }

    public function saveForm(Request $request)
    {
        $result = [
            'status' => false,
            'message' => 'بروز خطای ناشناخته هنگام ایجاد / ویرایش فرم درخواست!',
            'result' => null
        ];
        if (gettype($request->fields)=='array' and count($request->fields)>0) {
            $newFields = [];
            $oldFields = [];
            foreach ($request->fields as $field) {
                $field['id'] = (int) $field['id'];
                $field['type'] = $field['type_id'];
                $validator = [];
                if ($field['is_required']) $validator[] = 'required';
                switch ($field['type']) {
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                    case 6:
                    case 15:
                        if ($field['min']>0) $validator[] = 'min:'.$field['min'];
                        if ($field['max']>0) $validator[] = 'max:'.$field['max'];
                        break;
                    case 11:
                        $validator[] = 'digits:10';
                        $validator[] = 'is_number';
                        $validator[] = 'is_melli';
                        break;
                    case 12:
                        $validator[] = 'digits:10';
                        $validator[] = 'is_number';
                        break;
                    case 13:
                        $validator[] = 'is_mobile';
                        break;
                    case 14:
                        $validator[] = 'is_email';
                        break;
                    case 3:
                        if ($field['min']>0) $validator[] = 'min_value:'.$field['min'];
                        if ($field['max']>0) $validator[] = 'max_value:'.$field['max'];
                        break;
                }
                $field['validator'] = implode('|', $validator);
                unset($field['type_id']);
                if ($field['id']>0) {
                    $oldFields[] = $field;
                } else {
                    $newFields[] = $field;
                }
            }
            unset($request['fields']);
            if ($request->id and $request->id>0) {
                $req = Form::whereId($request->id)->update($request->all());
                $req = Form::whereId($request->id)->first();
                $result['message'] = 'فرم مورد نظر با موفقیت ویرایش شد.';
            } else {
                $req = Form::create($request->all());
                $result['message'] = 'فرم مورد نظر با موفقیت ثبت شد.';
            }
            if (count($newFields)>0) {
                foreach ($newFields as $field) {
                    $field['form_id'] = $req->id;
                    FormField::create($field);
                }
            }
            if (count($oldFields)>0) {
                foreach ($oldFields as $field) {
                    unset($field['created_at']);
                    unset($field['updated_at']);
                    unset($field['index']);
                    FormField::whereId($field['id'])->update($field);
                }
            }
            $result = [
                'status' => true,
                'result' => Form::whereId(($request->id and $request->id>0)?$request->id:$req->id)->with('fields')->first()
            ];
        } else {
            $result['message'] = 'هر فرم می‌بایست حداقل یک فیلد تعریف شده داشته باشد.';
        }
        return response()->json($result, 200);
    }

    public function usedFormCount(Request $request) {
        $response = [
            'status' => true,
            'count' => UsedForm::whereFormId($request->id)->count()
        ];

        return response()->json($response, 200);
    }

    public function deleteForm(Request $request) {
        Form::whereId($request->id)->delete();
        FormField::whereFormId($request->id)->delete();

        return response()->json(['status' => true], 200);
    }

    public function deleteField(Request $request) {
        FormField::whereId($request->field_id)->whereFormId($request->form_id)->delete();

        return response()->json(['status' => true], 200);
    }

    public function getFormDetails(Request $request)
    {
        $response = [
            'status' => false,
            'result' => null,
        ];
        $form = Form::whereId($request->id)->select(['id', 'title', 'description', 'active'])->first();
        if ($form) {
            $form = new FormResource($form);
            $response = [
                'status' => true,
                'result' => $form,
            ];
        }
        return response()->json($response, 200);
    }
}
