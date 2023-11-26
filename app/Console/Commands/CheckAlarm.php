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
            $userAlarm = Alarm::whereIsActive(true)->where(function ($q) use ($now){
                $q->whereAlarmDate($now->format('Y-m-d'))
                    ->orWhere('weekdays', 'like', $now->dayOfWeek);
            });
            $userAlarm = $userAlarm->whereUserId($user);
//        $userAlarm = $userAlarm->whereAlarmTime($now->format('H:i:00'));
            $userAlarm = $userAlarm->orderBy('alarm_date', 'ASC')->orderBy('alarm_time', 'ASC')->get();
            $count += $userAlarm->count();
            $alarms[$user] = AlarmResource::collection($userAlarm);
        }
        if ($count > 0) {
            $this->info($count . ' alarm(s) were found.');
            event(new AlarmEvent($alarms));
        } else {
            $this->info('No alarms were found.');
        }
    }
}
