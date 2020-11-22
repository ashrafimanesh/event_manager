<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateUserEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event_manager:update_user_events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user events view to check users to send event day';

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
        $sql = <<<SQL
CREATE OR REPLACE VIEW user_events AS
SELECT ep.id, ou.user_id, u.email, ep.event_id, e.name as event_name, ep.turn_order,e.start_date
FROM event_participants ep
INNER JOIN `events` e on e.id=ep.event_id
INNER JOIN organ_users ou on ou.id = ep.organ_user_id
INNER JOIN users u on u.id=ou.user_id
WHERE e.start_date is not null and ep.status='active' and e.expired_date is null and ep.turned_at is null
SQL;
        DB::statement($sql);

        return 0;
    }
}
