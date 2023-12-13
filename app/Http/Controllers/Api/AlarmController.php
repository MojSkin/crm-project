<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlarmResource;
use App\Models\Alarm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlarmController extends Controller
{
    public function getAlarmList()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست یادآورها',
            'result' => null
        ];

        try {
            $alarms = Alarm::whereUserId(Auth::id())
                ->orderBy('alarm_date', 'ASC')
                ->orderBy('alarm_time', 'ASC')
                ->get();
            $response['result'] = AlarmResource::collection($alarms);
            $response['status'] = true;
            $response['message'] = 'فهرست یادآورها با موفقیت دریافت شد';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage().' at line '.$e->getLine().' in '.$e->getFile();
            $response['trace'] = $e->getTrace();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function getAlarmsForNow($user = null)
    {
        $now = Carbon::now();
        $alarms = Alarm::whereUserId($user || Auth::id())
            ->whereAlarmTime($now->format('H:i:00'))
            ->where(function ($q) use ($now){
                $q->whereAlarmDate($now->format('Y-m-d'))
                  ->orWhere('weekdays', 'like', $now->dayOfWeek);
            })
            ->orderBy('alarm_date', 'ASC')
            ->orderBy('alarm_time', 'ASC')
            ->get();
        return AlarmResource::collection($alarms);
    }

    public function changeAlarmStatus(Request $request)
    {

    }
}
