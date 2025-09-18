<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DataTable extends Component
{
    public $columns;
    public $data;
    public $actions;
    public $bulkActions;
    public $searchable;
    public $sortable;
    public $pagination;
    public $pageSize;
    public $currentPage;
    public $emptyMessage;
    public $hoverEffects;
    public $alternatingRows;
    public $customClass;
    public $title;
    public $description;
    public $showRoleFilter;
    public $availableRoles;
    public $colorScheme;

    public function __construct(
        $columns = [],
        $data = [],
        $actions = [],
        $bulkActions = false,
        $searchable = true,
        $sortable = true,
        $pagination = true,
        $pageSize = 10,
        $currentPage = 1,
        $emptyMessage = 'No data found',
        $hoverEffects = true,
        $alternatingRows = true,
        $customClass = 'bg-slate-800 text-slate-200',
        $title = 'Data Table',
        $description = 'Manage your data records',
        $showRoleFilter = false,
        $availableRoles = [],
        $colorScheme = 'sky'
    ) {
        $this->columns = $columns;
        $this->data = $data;
        $this->actions = $actions;
        $this->bulkActions = $bulkActions;
        $this->searchable = $searchable;
        $this->sortable = $sortable;
        $this->pagination = $pagination;
        $this->pageSize = $pageSize;
        $this->currentPage = $currentPage;
        $this->emptyMessage = $emptyMessage;
        $this->hoverEffects = $hoverEffects;
        $this->alternatingRows = $alternatingRows;
        $this->customClass = $customClass;
        $this->title = $title;
        $this->description = $description;
        $this->showRoleFilter = $showRoleFilter;
        $this->availableRoles = $availableRoles;
        $this->colorScheme = $colorScheme;
    }

    public function render(): View
    {
        return view('components.data-table');
    }
}
