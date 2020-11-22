<?php


namespace App\View\Partials;


use App\OrganUserRole;

class UserRolesListActions extends AbstractPartials
{
    /** @var OrganUserRole */
    private $row;

    /**
     * UserRolesListActions constructor.
     * @param $row
     */
    public function __construct($row)
    {
        $this->row = $row;
    }

    public function render(): ?string
    {
        return '';
    }
}
