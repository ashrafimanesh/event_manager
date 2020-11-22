<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ParticipantsList extends Component
{
    private $tableId;
    private $eventId;

    /**
     * Create a new component instance.
     *
     * @param $eventId
     * @param $tableId
     */
    public function __construct($eventId, $tableId)
    {
        //
        $this->tableId = $tableId;
        $this->eventId = $eventId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.participants-list', ['eventId'=>$this->eventId, 'tableId'=>$this->tableId]);
    }
}
