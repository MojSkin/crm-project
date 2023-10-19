<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MojSkin;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsedFormInboxDetailsResource;
use App\Http\Resources\UsedFormInboxResource;
use App\Models\Reject;
use App\Models\UsedForm;
use App\Models\UsedFormDetail;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use App\Models\Invoice as UserInvoice;

class UsedFormController extends Controller
{
    public function saveFormData(Request $request)
    {
        $transactions = UsedForm::select('transaction')->pluck('transaction')->toArray();
        $found = false;
        do {
            $transaction = strtoupper(Str::random(12));
            Str::replace($transaction, 'I', 'L');
            Str::replace($transaction, 'O', 'P');
            Str::replace($transaction, '1', 'M');
            Str::replace($transaction, '0', '9');
            $found = array_search($transaction, $transactions);
        } while ($found !== false);

        $request->merge(['transaction' => $transaction]);

        if (Auth::check()) {
            $request->merge(['user_id' => Auth::id()]);
        }

        $form = UsedForm::create([
            'date' => Carbon::now(),
            'form_id' => $request->form_id,
            'user_id' => $request->user_id,
            'user_ip' => $request->ip(),
            'transaction' => $request->transaction,
        ]);
        $fields = [];
        $request->merge(['fields' => json_decode($request->fields)]);
        for ($i=0; $i < count($request->fields); $i++) {
            $value = $request->fields[$i]->value;
            if (strpos($value, '_attachment') > 0) {
                $files_count = intval(substr($value, strpos($value, '_attachment')+12));
                $files = [];
                if ($files_count > 0 ) {
                    $field_name = substr($value, 0, strpos($value, '_attachment'));
                    $path = 'uploads/form_attachments/'.$form->id;
                    MojSkin::makeDir(storage_path('storage/'.$path));
                    for ($j = 1; $j <= $files_count; $j++) {
                        if ($request->hasFile($field_name.'_'.$j) and $request->file($field_name.'_'.$j)->isValid()) {
                            $tempFile = $request->file($field_name.'_'.$j);
                            $mime = str_replace('/', '_', $tempFile->getMimeType());
                            $target = MojSkin::randomFileName(35, '/storage/uploads/form_attachments', $j.'__'.$field_name.'__'.$mime.'__', '__'.$request[$field_name.'_'.$j]->getClientOriginalName());
                            $stored_file = Storage::disk('public')->putFileAs($path, new File($request->file($field_name.'_'.$j)), $target);
                            $files[] = $target;
                        }
                    }
                    $value = implode('|', $files);
                } else {
                    $value = null;
                }
            }
            $fields[] = [
                'value' => $value,
                'form_field_id' => $request->fields[$i]->form_field_id,
                'form_id' => $form->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        UsedFormDetail::insert($fields);
        $hasPayment = -1;
        $invoice = null;
        if (isset($request->hasPayment) and $request->hasPayment >= 0) {
            $invoice = new Invoice;
            $invoice->amount($request->hasPayment / 10);
            $invoice->detail(['form' => $form->id])
                    ->detail(['description' => 'پرداخت بابت پیش‌ثبت‌نام با شماره پیگیری '.$transaction]);
            $payment = Payment::callbackUrl(env('APP_URL_FULL') . '/verify-payment')->detail(['description' => 'پرداخت بابت پیش‌ثبت‌نام با شماره پیگیری '.$transaction]);
            $payment = $payment->config('description', 'پرداخت بابت پیش‌ثبت‌نام با شماره پیگیری '.$transaction);
            $payment = $payment->purchase($invoice, function($driver, $transactionId) use($invoice, $form) {
                UserInvoice::create([
                    'uuid' => $invoice->getUuid(),
                    'transactionId' => $transactionId,
                    'gateway' => $invoice->getDriver() ?? 'not-implemented',
                    'status' => 0,
                    'amount' => $invoice->getAmount(),
                    'target' => 'form',
                    'target_id' => $form->id,
                    'details' => json_encode($invoice->getDetails())
                ]);
            })->pay();
        }

        $response = [
            'status' => true,
            'message' => 'فرم شما با موفقیت ثبت گردید.',
            'result' => $transaction
        ];

        if ($invoice != null) {
            $response['action'] = $payment;
        }

        return response()->json($response, 200);
    }

    public function getFormInbox(Request $request)
    {
        $forms = UsedForm::whereFormId($request->form_id)->with(['details', 'user', 'rejects'])->orderBy('created_at', 'DESC')->limit(25)->get();
        $count = UsedForm::orderBy('id')->count();
        $forms = UsedFormInboxResource::collection($forms);
        $response = [
            'status' => 'true',
            'message' => 'صندوق ورودی با موفقیت دریافت شد.',
            'result' => $forms,
            'total_records' => $count
        ];
        return $response;
    }

    public function getFormInboxMore(Request $request)
    {
        $forms = UsedForm::whereFormId($request->form_id);
        $forms->where('id', '<', $request->last_inbox);
        $forms->with(['details', 'user', 'rejects']);
        $forms = $forms->orderBy('created_at', 'DESC')->limit(15)->get();
        $response = [
            'status' => 'true',
            'message' => count($forms) > 0 ? 'صندوق ورودی با موفقیت دریافت شد.' : 'پایان صندوق ورودی',
            'result' => UsedFormInboxResource::collection($forms),
        ];
        return $response;
    }

    public function trackForm(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'ورود کد پیگیری الزامی است',
            'result' => null
        ];

        if (isset($request->track_id) and $request->track_id != null) {
            $status = UsedForm::getDefault('statuses');
            $form = UsedForm::whereTransaction($request->track_id);
            $form->when(isset($request->form) and $request->form != null)->whereFormId($request->form);
            $form = $form->with('form', 'details', 'rejects')->first();
            if ($form) {
                $form = [
                    'id' => $form->id,
                    'user_id' => $form->user_id,
                    'user' => $form->user,
                    'transaction' => $form->transaction,
                    'status' => $form->status,
                    'state' => $status[$form->status] ?? $status[99],
                    'date' => $form->date,
                    'user_ip' => $form->user_ip,
                    'invoice' => $form->invoice,
                    'rejects' => $form->rejects,
                    'fields' => UsedFormInboxDetailsResource::collection($form->details)
                ];
                $response['message'] = 'پیگیری فرم موفقیت‌آمیز بود.';
                $response['status'] = true;
                $response['result'] = $form;
            } else {
                $response['message'] = 'کد پیگیری وارد شده معتبر نیست';
            }
        }

        return response()->json($response, 200);
    }

    public function editFormStatus(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'پارامترهای ارسالی نامعتبر است.',
            'result' => null
        ];
        $status = UsedForm::getDefault('statuses');
        $t = [];
        foreach ($status as $k => $s) {
            $t[] = $k;
        }
        $validator = Validator::make($request->all(), [
            'form_id' => 'required|exists:used_forms,id',
            'status' => [
                'required',
                Rule::in($t)
            ],
            'comment' => 'required|min:10|max:250',
        ]);

        if ($validator->fails()) {
            $response['messages'] = $this->normalizeError($validator->errors());

            return response()->json($response, 400);
        }

        $form = UsedForm::whereId($request->form_id)->first();
        $form->status = $request->status;
        $form->save();

        Reject::create([
            'user' => Auth::id(),
            'comment' => $request->comment,
            'status' => $request->status,
            'rejectable_id' => $request->form_id,
            'rejectable_type' => 'App\Models\UsedForm'
        ]);

        $response = [
            'status' => true,
            'message' => 'وضعیت فرم با موفقیت ویرایش شد.',
            'result' => Reject::where('rejectable_type', 'App\Models\UsedForm')->where('rejectable_id', $request->form_id)->with('user')->get()
        ];

        return response()->json($response, 200);
    }
}
