<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OrgansList extends Component
{
    private $tableId;

    /**
     * Create a new component instance.
     * @param $tableId
     */
    public function __construct($tableId)
    {
        $this->tableId = $tableId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.organs-list', ['tableId'=>$this->tableId]);
    }
}
