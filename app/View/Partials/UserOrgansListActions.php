<?php


namespace App\View\Partials;


use App\OrganUser;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;

class UserOrgansListActions extends AbstractPartials
{
    /** @var OrganUser */
    private $row;
    /**
     * @var null
     */
    private $userId;

    public function __construct($row, $userId)
    {
        $this->row = $row;
        $this->userId = $userId;
    }

    public function render(): ?string
    {
        $actions = $this->getEventsAction();

        if(Gate::allows(AuthServiceProvider::MANAGE_ROLES)){
            $actions .= $this->getRolesAction();
        }

        return $actions;
    }

    /**
     * @return string
     */
    protected function getRolesAction(): string
    {
        $title = trans('global.Roles'). ' ('.$this->row->roles_count. ')';
        $url = route('user.roles', ['userOrganId' => $this->row->id]);
        $actions = <<<ACTIONS
<a href="{$url}" data-id="{$this->row->id}" class="roles btn btn-success btn-sm">{$title}</a>
ACTIONS;
        return $actions;
    }

    /**
     * @return string
     */
    public function getEventsAction(): string
    {
        $title = trans('global.Events');
        $url = route('organ.events', ['organId' => $this->row->id]);
        return <<<ACTIONS
<a href="{$url}" data-id="{$this->row->id}" class="events btn btn-success btn-sm">{$title}</a>
ACTIONS;
    }
}
