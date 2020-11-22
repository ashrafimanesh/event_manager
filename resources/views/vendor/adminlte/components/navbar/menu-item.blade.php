@inject('menuItemHelper', \JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper)

@if ($menuItemHelper->isSearchBar($item))

    {{-- Search form --}}
    @include('adminlte::components.navbar.menu-item-search-form')

@elseif ($menuItemHelper->isSubmenu($item))

    {{-- Dropdown menu --}}
    @include('adminlte::components.navbar.menu-item-dropdown-menu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Link --}}
    @include('adminlte::components.navbar.menu-item-link')

@endif
