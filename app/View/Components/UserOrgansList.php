<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserOrgansList extends Component
{
    private $userId;
    private $tableId;

    /**
     * Create a new component instance.
     *
     * @param $userId
     * @param $tableId
     */
    public function __construct($userId, $tableId)
    {
        //
        $this->userId = $userId;
        $this->tableId = $tableId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.user-organs-list', ['userId'=>$this->userId, 'tableId'=>$this->tableId]);
    }
}
