# Data Table Component Documentation

## Overview
A comprehensive, reusable Data Table component built with Laravel Blade and Tailwind CSS for professional SaaS dashboards. This component provides all the essential features you need for data management without external dependencies.

## Features
- ✅ **Sticky Headers** - Headers stay visible when scrolling
- ✅ **Sortable Columns** - Click headers to sort with visual indicators
- ✅ **Global Search** - Search across all columns with debounced input
- ✅ **Pagination** - Configurable page sizes (10/25/50/100) with navigation
- ✅ **Responsive Design** - Horizontal scroll on small screens
- ✅ **Alternating Rows** - Better readability with alternating row colors
- ✅ **Hover Effects** - Subtle interactions for better UX
- ✅ **Bulk Actions** - Select multiple rows for batch operations
- ✅ **Column Filters** - Individual column filtering capabilities
- ✅ **Custom Actions** - Configurable action buttons per row
- ✅ **Empty States** - Professional empty state with icons
- ✅ **Loading States** - Built-in loading state support
- ✅ **Accessibility** - WCAG compliant with proper focus states

## Installation

1. Place the component file at: `resources/views/components/data-table.blade.php`
2. Include Alpine.js and Tailwind CSS in your layout
3. Use the component in your Blade templates

## Basic Usage

```blade
<x-data-table
    :columns="$columns"
    :data="$data"
    :actions="$actions"
/>
```

## Props

### Required Props
- `columns` (array) - Column definitions
- `data` (array) - Data to display

### Optional Props
- `actions` (array) - Action buttons for each row
- `searchable` (boolean) - Enable global search (default: true)
- `sortable` (boolean) - Enable column sorting (default: true)
- `pagination` (boolean) - Enable pagination (default: true)
- `bulkActions` (boolean) - Enable bulk selection (default: false)
- `pageSize` (integer) - Records per page (default: 10)
- `totalRecords` (integer) - Total number of records (default: 0)
- `currentPage` (integer) - Current page number (default: 1)
- `sortKey` (string) - Initial sort column (default: null)
- `sortDirection` (string) - Initial sort direction (default: 'asc')
- `searchQuery` (string) - Initial search query (default: '')
- `showFilters` (boolean) - Show column filters (default: false)
- `filters` (array) - Column filter configurations
- `emptyMessage` (string) - Message when no data (default: 'No data available')
- `loading` (boolean) - Show loading state (default: false)
- `stickyHeader` (boolean) - Sticky header (default: true)
- `alternatingRows` (boolean) - Alternating row colors (default: true)
- `hoverEffects` (boolean) - Row hover effects (default: true)
- `responsive` (boolean) - Responsive design (default: true)
- `customClass` (string) - Additional CSS classes

## Column Configuration

```php
$columns = [
    [
        'key' => 'id',                    // Data key
        'label' => 'ID',                  // Display label
        'sortable' => true,               // Can be sorted
        'searchable' => true,             // Included in search
        'filterable' => true,             // Can be filtered
        'filter' => [                     // Filter configuration
            'value' => '',                // Initial filter value
            'type' => 'text'              // Filter type
        ]
    ],
    // ... more columns
];
```

## Action Configuration

```php
$actions = [
    [
        'label' => 'View',                // Button text
        'icon' => 'M15 12a3 3 0...',     // SVG path (optional)
        'onclick' => 'viewItem(row.id)',  // Click handler
        'class' => 'text-blue-600'        // Additional CSS classes
    ],
    // ... more actions
];
```

## Examples

### User Management
```blade
<x-data-table
    :columns="[
        ['key' => 'id', 'label' => 'ID', 'sortable' => true],
        ['key' => 'name', 'label' => 'Full Name', 'sortable' => true, 'searchable' => true],
        ['key' => 'email', 'label' => 'Email', 'sortable' => true, 'searchable' => true],
        ['key' => 'role', 'label' => 'Role', 'sortable' => true],
        ['key' => 'status', 'label' => 'Status', 'sortable' => true],
    ]"
    :data="$users"
    :actions="[
        ['label' => 'View', 'icon' => 'M15 12a3 3 0...', 'onclick' => 'viewUser(row.id)'],
        ['label' => 'Edit', 'icon' => 'M11 5H6a2 2 0...', 'onclick' => 'editUser(row.id)'],
        ['label' => 'Delete', 'icon' => 'M19 7l-.867 12...', 'onclick' => 'deleteUser(row.id)', 'class' => 'text-red-600'],
    ]"
    :bulk-actions="true"
    :show-filters="true"
    :page-size="25"
    :total-records="100"
    :current-page="1"
    :sort-key="'name'"
    :sort-direction="'asc'"
/>
```

### Products with Custom Styling
```blade
<x-data-table
    :columns="[
        ['key' => 'id', 'label' => 'ID', 'sortable' => true],
        ['key' => 'name', 'label' => 'Product Name', 'sortable' => true, 'searchable' => true],
        ['key' => 'price', 'label' => 'Price', 'sortable' => true],
        ['key' => 'stock', 'label' => 'Stock', 'sortable' => true],
        ['key' => 'status', 'label' => 'Status', 'sortable' => true],
    ]"
    :data="$products"
    :actions="[
        ['label' => 'View', 'onclick' => 'viewProduct(row.id)'],
        ['label' => 'Edit', 'onclick' => 'editProduct(row.id)'],
    ]"
    :bulk-actions="true"
    :show-filters="true"
    :page-size="50"
    :custom-class="'shadow-lg'"
/>
```

### Orders with Minimal Configuration
```blade
<x-data-table
    :columns="[
        ['key' => 'id', 'label' => 'Order ID', 'sortable' => true],
        ['key' => 'customer', 'label' => 'Customer', 'sortable' => true],
        ['key' => 'amount', 'label' => 'Amount', 'sortable' => true],
        ['key' => 'status', 'label' => 'Status', 'sortable' => true],
    ]"
    :data="$orders"
    :actions="[
        ['label' => 'View', 'onclick' => 'viewOrder(row.id)'],
    ]"
    :pagination="false"
    :searchable="false"
/>
```

## Advanced Features

### Custom Cell Content
You can use slots for custom cell rendering:

```blade
@foreach($columns as $column)
    <td class="px-6 py-4 text-sm text-gray-900">
        @if($column['key'] === 'status')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="row.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                <span x-text="row.status"></span>
            </span>
        @else
            <span x-text="row['{{ $column['key'] }}']"></span>
        @endif
    </td>
@endforeach
```

### Bulk Actions
Enable bulk actions to select multiple rows:

```blade
<x-data-table
    :bulk-actions="true"
    :actions="[
        ['label' => 'Bulk Delete', 'onclick' => 'bulkDelete()', 'class' => 'text-red-600'],
        ['label' => 'Export Selected', 'onclick' => 'exportSelected()'],
    ]"
/>
```

### Column Filters
Enable individual column filtering:

```blade
<x-data-table
    :show-filters="true"
    :columns="[
        ['key' => 'name', 'label' => 'Name', 'filterable' => true],
        ['key' => 'status', 'label' => 'Status', 'filterable' => true],
    ]"
/>
```

## Styling

The component uses Tailwind CSS classes and includes custom styles for:
- Custom scrollbars
- Smooth transitions
- Focus states for accessibility
- Professional table aesthetics

### Custom CSS Classes
You can add custom classes via the `customClass` prop:

```blade
<x-data-table
    :custom-class="'shadow-2xl border-2'"
    :columns="$columns"
    :data="$data"
/>
```

## JavaScript Integration

The component uses Alpine.js for interactivity. Key methods available:

- `sort(column)` - Sort by column
- `search()` - Trigger search
- `changePageSize(size)` - Change page size
- `goToPage(page)` - Navigate to page
- `toggleRowSelection(id)` - Toggle row selection
- `toggleAllRows()` - Toggle all rows
- `clearFilters()` - Clear all filters
- `toggleFilters()` - Toggle filter panel

## Accessibility

The component is built with accessibility in mind:
- Proper ARIA labels
- Keyboard navigation support
- Focus management
- Screen reader compatibility
- High contrast support

## Browser Support

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+

## Performance

- Debounced search (300ms)
- Efficient sorting algorithms
- Minimal DOM manipulation
- Optimized re-rendering

## Troubleshooting

### Common Issues

1. **Alpine.js not working**: Ensure Alpine.js is loaded before the component
2. **Styling issues**: Check Tailwind CSS is properly configured
3. **Sorting not working**: Verify column keys match data keys
4. **Pagination issues**: Ensure totalRecords is set correctly

### Debug Mode

Enable debug mode by adding `x-init="console.log('Data Table initialized')"` to the component.

## License

This component is part of your Laravel application and follows the same license terms.
