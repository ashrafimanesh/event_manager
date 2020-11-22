<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendEventEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event_manager:send_event_email {--eventId=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event(s) emails. Set --eventId={x} to send email of specific event.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $eventId = $this->option('eventId');
        if ($eventId > 0) {
            try{
                $this->handleEventEmails($eventId);
            }catch (\Throwable $exception){
                Log::error('event_id: ['.$eventId.'] '.$exception->getFile().' '.$exception->getMessage());
            }
            $this->call(UpdateUserEventsCommand::class);
            return;
        }
        $this->info('start to get events');
        //Select all available events id and call each event
        $result = DB::table('user_events')->select('event_id')->groupBy('event_id')->get();
        foreach ($result as $item) {
            try {
                $this->call(SendEventEmailCommand::class, ['--eventId' => $item->event_id]);
            } catch (\Throwable $exception) {
                Log::error('event_id: ['.$item->event_id.'] '.$exception->getFile().' '.$exception->getMessage());
            }
        }
        if($result->count()<1){
            $this->info('No event');
        }
        else{
            $this->info('finished');
        }
        return 0;
    }

    private function handleEventEmails($eventId)
    {
        $result = DB::table('user_events')->where('event_id', $eventId)->orderBy('turn_order','ASC')->get();
        if($result->count()<1){
            return;
        }
        $day = Carbon::now();
        $this->info('start to get event '.$eventId.' participants');
        foreach($result as $key=>$item){
            $day = $day->addDay();
            $eventDate = $day;
            DB::statement('UPDATE event_participants SET turned_at="'.$eventDate->toDateString().'" where id='.$item->id);
            //@TODO send event mail to user
        }
    }
}
