<?php


namespace App\View\Partials;


use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;

class OrgansListActions extends AbstractPartials
{
    private $row;

    public function __construct($row)
    {
        $this->row = $row;
    }

    public function render(): ?string
    {
        $actions = '';
        $actions .= $this->getEventsAction();
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
