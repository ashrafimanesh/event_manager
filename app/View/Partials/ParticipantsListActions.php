<?php


namespace App\View\Partials;


use App\EventParticipant;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ParticipantsListActions extends AbstractPartials
{
    /**
     * @var EventParticipant
     */
    private $row;

    public function __construct(EventParticipant $row)
    {
        $this->row = $row;
    }

    public function render():?string {

        $actions = '';
        if(Gate::allows(AuthServiceProvider::MANAGE_PARTICIPANTS, $this->row)){
            if(!$this->row->event->expired){
                $actions .= $this->getStatusAction();
            }
        }
        return $actions;
    }

    /**
     * @return string
     */
    public function getStatusAction(): string
    {
        $title = trans('global.DoActive');
        $actions = <<<ACTIONS
<button class="btn btn-success btn-sm" data-toggle="modal" onclick="showActiveForm(this)" data-id="{$this->row->id}" data-target="#active_participant_form">
{$title}
</button>
ACTIONS;
        return $actions;
    }

}
