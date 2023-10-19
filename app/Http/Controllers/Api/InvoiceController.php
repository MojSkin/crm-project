<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\UsedForm;
use Illuminate\Http\Request;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class InvoiceController extends Controller
{
    public function verifyInvoice(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'شماره تراکنش ارسالی نامعتبر است',
            'result' => null
        ];

        if (isset($request->transaction)) {
            $invoice = Invoice::where('transactionId', $request->transaction)->first();
            if ($invoice) {
                $invoice->details = gettype($invoice->details) === 'array' ? $invoice->details : json_decode($invoice->details);
                $form = null;
                if ($invoice->target === 'form' && isset($invoice->details['form'])) {
                    $form = UsedForm::whereId($invoice->details['form'])->first();
                }
                try {
                    $receipt = Payment::amount($invoice->amount)->transactionId($invoice->transactionId)->verify();
                    $message = $receipt->getReferenceId();
                    $status  = true;
                } catch (InvalidPaymentException $exception) {
                    $errorCode = $exception->getCode();
                    $message = $exception->getMessage() . ' - خطای شماره ' . $exception->getCode();

                    $status  = false;
                }

                $response = [
                    'status' => $status,
                    'message' => $message,
                    'result' => $receipt ?? null
                ];

                $invoice->status = $status ? 1 : ($errorCode ?? 99);

                if ($status) {
                    $invoice->callbackId = $receipt->getReferenceId();
                    $invoice->gateway    = $receipt->getDriver();
                    if ($form) {
                        $response['form'] = $form->transaction;
                    }
                }

                $invoice->save();

            }
        }

        return response()->json($response, 200);
    }
}
