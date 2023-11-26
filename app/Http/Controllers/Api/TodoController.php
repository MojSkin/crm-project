<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TodoListResource;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    public function getTodoList()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست کارها',
            'result' => null
        ];

        try {
            $todos = Todo::whereUserId(Auth::id())
                ->select(DB::raw('*, IFNULL(due_date, created_at) as date_order, IF(is_done = 1, 0, IF(DATE(IFNULL(due_date, created_at)) < NOW(), 1, 0)+flag) as order_index'))
                ->orderBy('order_index', 'DESC')
                ->orderBy('date_order', 'ASC')
                ->get();

            $response['result'] = TodoListResource::collection($todos);
            $response['status'] = true;
            $response['message'] = 'فهرست کارها با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage().' at line '.$e->getLine().' in '.$e->getFile();
            $response['trace'] = $e->getTrace();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function saveTodo(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره کار',
            'result' => null
        ];

        try {
            $request->merge(['user_id' => $request->has('user_id') ? $request->user_id : Auth::id(), 'is_done' => $request->has('is_done') ? $request->is_done : false, 'due_date' => $request->due_date != null ? Carbon::parse($request->due_date) : null]);
            $todo = Todo::updateOrCreate(
                ['id' => $request->id],
                $request->all()
            );
            if ($todo) {
                $response['result'] = new TodoListResource($todo);
                $response['status'] = true;
                $response['message'] = ($request->has('id') and $request->id != null) ? 'کار جدید با موفقیت ایجاد شد' : 'کار مورد نظر با موفقیت ویرایش شد';
            } else {
                $response['message'] = 'بروز خطای ناشناخته هنگام ذخیره کار';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage().' at line '.$e->getLine().' in '.$e->getFile();
            $response['trace'] = $e->getTrace();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function deleteTodo(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام حذف کار',
            'result' => null
        ];

        try {
            $todo = Todo::whereId($request->todo)->first();
            if ($todo) {
                $todo->delete();
                $response['status'] = true;
                $response['message'] = 'کار مورد نظر با موفقیت حذف شد';
            } else {
                $response['message'] = 'کار مورد نظر با یافت نشد';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage().' at line '.$e->getLine().' in '.$e->getFile();
            $response['trace'] = $e->getTrace();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
