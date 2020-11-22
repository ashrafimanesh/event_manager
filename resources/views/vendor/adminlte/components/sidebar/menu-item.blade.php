@inject('menuItemHelper', \JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper)

@if ($menuItemHelper->isHeader($item))

    {{-- Header --}}
    @include('adminlte::components.sidebar.menu-item-header')

@elseif ($menuItemHelper->isSearchBar($item))

    {{-- Search form --}}
    @include('adminlte::components.sidebar.menu-item-search-form')

@elseif ($menuItemHelper->isSubmenu($item))

    {{-- Treeview menu --}}
    @include('adminlte::components.sidebar.menu-item-treeview-menu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Link --}}
    @include('adminlte::components.sidebar.menu-item-link')

@endif
