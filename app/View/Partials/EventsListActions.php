<?php


namespace App\View\Partials;

use App\EventModel;
use App\EventParticipant;
use App\Providers\AuthServiceProvider;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EventsListActions extends AbstractPartials
{
    /** @var EventModel */
    private $row;

    public function __construct($row)
    {
        $this->row = $row;
    }

    public function render(): ?string
    {
        /** @var User $user */
        $user = Auth::user();
        $currentUserOrganId = $user->getCurrentUserOrganId();
        $actions = '';
        if(Gate::allows(AuthServiceProvider::JOIN_TO_EVENT, $this->row) && $currentUserOrganId){
            $actions .= $this->getJoinAction($currentUserOrganId);
        }
        if(Gate::allows(AuthServiceProvider::MANAGE_PARTICIPANTS, $this->row) && $currentUserOrganId){
            $actions .= $this->getParticipantsAction();
        }
        if(Gate::allows(AuthServiceProvider::START_EVENT, $this->row) && $currentUserOrganId){
            $actions .= $this->getStartEventAction();
            $actions .= $this->getExpireEventAction();
        }
        return $actions;
    }

    /**
     * @param $currentUserOrganId
     * @param string $actions
     * @return string
     */
    public function getJoinAction($currentUserOrganId):string
    {
        $actions = '';
        $exist = $this->row->isParticipant($currentUserOrganId);
        if (!$exist && !$this->row->expired) {
            $joinTitle = trans('global.JoinToEvent');
            $joinUrl = route('event.join', ['eventId' => $this->row->id]);
            $actions .= <<<ACTIONS
<a href="{$joinUrl}" data-id="{$this->row->id}" class="join_to_event btn btn-success btn-sm">{$joinTitle}</a>
ACTIONS;
        }
        return $actions;
    }

    /**
     * @return string
     */
    public function getParticipantsAction(): string
    {
        $actions = '';
        if ($this->row->participants_count > 0) {
            $participantTitle = trans('global.Participants').' ('.$this->row->participants_count.')';
            $participantUrl = route('event.participants', ['eventId' => $this->row->id]);
            $actions .= <<<ACTIONS
<a href="{$participantUrl}" data-id="{$this->row->id}" class="join_to_event btn btn-success btn-sm">{$participantTitle}</a>
ACTIONS;
        }
        return $actions;
    }

    public function getStartEventAction():string {
        $actions = '';
        if ($this->row->startAble()) {
            $title = trans('global.StartEvent');
            $onSubmitText = trans('global.Wait');
            $actions .= <<<ACTIONS
<button onclick="startEvent(this)" data-on-submit-text="{$onSubmitText}" data-id="{$this->row->id}" class="start_event btn btn-success btn-sm">{$title}</button>
ACTIONS;
        }
        return $actions;
    }

    public function getExpireEventAction():string {
        $actions = '';
        if ($this->row->expireAble()) {
            $title = trans('global.StopEvent');
            $onSubmitText = trans('global.Wait');
            $actions .= <<<ACTIONS
<button onclick="stopEvent(this)" data-on-submit-text="{$onSubmitText}" data-id="{$this->row->id}" class="start_event btn btn-success btn-sm">{$title}</button>
ACTIONS;
        }
        return $actions;
    }
}
