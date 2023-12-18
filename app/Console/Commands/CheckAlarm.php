<?php

namespace App\Console\Commands;

use App\Events\AlarmEvent;
use App\Http\Controllers\Api\AlarmController;
use App\Http\Resources\AlarmResource;
use App\Models\Alarm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckAlarm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alarm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user alarms and return alarms of all users grouped by user id as an array output';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $now = Carbon::now();
        $users = Alarm::whereIsActive(true)->distinct('user_id')->pluck('user_id')->toArray();
        $alarms = [];
        $count = 0;
        foreach($users as $user) {
//            $userAlarm = Alarm::whereIsActive(true)->where(function () use ($now){
//                DB::raw('alaram_date="'.$now->format('Y-m-d'.'" OR weekdays LIKE "%'.$now->dayOfWeek.'%"'));
//            });
            $userAlarm = Alarm::all();
            $userAlarm = $userAlarm->whereUserId($user);
            $userAlarm = $userAlarm->whereAlarmTime($now->format('H:i:00'));
            $userAlarm = $userAlarm->orderBy('alarm_date', 'ASC')->orderBy('alarm_time', 'ASC')->get();
            if ($userAlarm->count() > 0) {
                $count += $userAlarm->count();
                $alarms = AlarmResource::collection($userAlarm);
                event(new AlarmEvent($alarms[0]->user->username, $alarms));
            }
        }
        if ($count > 0) {
            $this->info($count . ' alarm(s) were found.');
        } else {
            $this->info('No alarms were found.');
        }
    }
}
