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
    public $pageSizeOptions;
    public $currentPage;
    public $emptyMessage;
    public $emptyDescription;
    public $hoverEffects;
    public $alternatingRows;
    public $stickyHeader;
    public $customClass;
    public $title;
    public $description;
    public $showRoleFilter;
    public $availableRoles;
    public $colorScheme;
    public $addButton;
    public $addButtonLabel;
    public $addButtonAction;
    public $formType;

    public function __construct(
        $columns = [],
        $data = [],
        $actions = [],
        $bulkActions = false,
        $searchable = true,
        $sortable = true,
        $pagination = true,
        $pageSize = 10,
        $pageSizeOptions = [10, 25, 50, 100],
        $currentPage = 1,
        $emptyMessage = 'No data found',
        $emptyDescription = 'Start by adding your first item to the system.',
        $hoverEffects = true,
        $alternatingRows = true,
        $stickyHeader = true,
        $customClass = 'bg-slate-800 text-slate-200',
        $title = 'Data Table',
        $description = 'Manage your data records',
        $showRoleFilter = false,
        $availableRoles = [],
        $colorScheme = 'sky',
        $addButton = false,
        $addButtonLabel = 'Add New Item',
        $addButtonAction = 'addItem',
        $formType = 'default'
    ) {
        $this->columns = $columns;
        $this->data = $data;
        $this->actions = $actions;
        $this->bulkActions = $bulkActions;
        $this->searchable = $searchable;
        $this->sortable = $sortable;
        $this->pagination = $pagination;
        $this->pageSize = $pageSize;
        $this->pageSizeOptions = $pageSizeOptions;
        $this->currentPage = $currentPage;
        $this->emptyMessage = $emptyMessage;
        $this->emptyDescription = $emptyDescription;
        $this->hoverEffects = $hoverEffects;
        $this->alternatingRows = $alternatingRows;
        $this->stickyHeader = $stickyHeader;
        $this->customClass = $customClass;
        $this->title = $title;
        $this->description = $description;
        $this->showRoleFilter = $showRoleFilter;
        $this->availableRoles = $availableRoles;
        $this->colorScheme = $colorScheme;
        $this->addButton = $addButton;
        $this->addButtonLabel = $addButtonLabel;
        $this->addButtonAction = $addButtonAction;
        $this->formType = $formType;
    }

    public function render(): View
    {
        return view('components.data-table');
    }
}
