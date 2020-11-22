<?php


namespace App\View\Partials;


use App\Providers\AuthServiceProvider;
use App\User;
use Illuminate\Support\Facades\Gate;

class UsersListActions extends AbstractPartials
{
    /**
     * @var User
     */
    private $row;

    public function __construct(User $row)
    {
        $this->row = $row;
    }

    public function render():?string {
        $actions = '';
        if(Gate::allows(AuthServiceProvider::MANAGE_ORGANIZATION)){
            $actions .= $this->getOrgansAction();
        }
        return $actions;
    }

    /**
     * @return string
     */
    protected function getOrgansAction(): string
    {
        $title = trans('global.Organization'). ' ('.$this->row->organs_count.')';
        $url = route('user.organs', ['userId' => $this->row->id]);
        $actions = <<<ACTIONS
<a href="{$url}" data-id="{$this->row->id}" class="organization btn btn-success btn-sm">{$title}</a>
ACTIONS;
        return $actions;
    }

}
