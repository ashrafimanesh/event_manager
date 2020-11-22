<?php

namespace App\View\Components;

use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;
use Yajra\DataTables\Facades\DataTables;

class UsersList extends Component
{
    /** @var LengthAwarePaginator */
    private $usersData;
    private $limit;
    /**
     * @var int
     */
    private $page;
    private $tableId;

    /**
     * Create a new component instance.
     *
     * @param $limit
     * @param $tableId
     */
    public function __construct($limit, $tableId)
    {
        $this->limit = $limit;
        $this->tableId = $tableId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.users-list', ['tableId'=>$this->tableId]);
    }
}
