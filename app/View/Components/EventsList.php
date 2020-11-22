<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EventsList extends Component
{
    private $tableId;
    /**
     * @var null
     */
    private $afterStartSuccessAction;
    /**
     * @var null
     */
    private $afterStopSuccessAction;

    /**
     * Create a new component instance.
     *
     * @param $tableId
     * @param null $afterStartSuccessAction
     * @param null $afterStopSuccessAction
     */
    public function __construct($tableId, $afterStartSuccessAction = null, $afterStopSuccessAction = null)
    {
        //
        $this->tableId = $tableId;
        $this->afterStartSuccessAction = $afterStartSuccessAction;
        $this->afterStopSuccessAction = $afterStopSuccessAction;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.events-list', [
            'tableId'=>$this->tableId,
            'afterStartSuccessAction'=>$this->afterStartSuccessAction,
            'afterStopSuccessAction'=>$this->afterStopSuccessAction,
        ]);
    }
}
