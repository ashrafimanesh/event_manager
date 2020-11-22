<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserRolesList extends Component
{
    private $userOrganId;
    private $tableId;

    /**
     * Create a new component instance.
     *
     * @param $tableId
     * @param $userOrganId
     */
    public function __construct($tableId, $userOrganId)
    {
        //
        $this->userOrganId = $userOrganId;
        $this->tableId = $tableId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.user-roles-list', [
            'tableId'=>$this->tableId,
            'userOrganId'=>$this->userOrganId
        ]);
    }
}
