# Simple Reusable Data Table Component

A clean, simple, and reusable data table component for Laravel Blade with Alpine.js.

## 📁 Files

- `resources/views/components/data-table.blade.php` - Main component
- `public/css/data-table.css` - Basic styling
- `DATATABLE_README.md` - This documentation

## 🚀 Usage

### Basic Usage

```blade
<x-data-table
    :columns="$columns"
    :data="$data"
    title="Users"
    description="Manage your users"
/>
```

### With Actions

```blade
<x-data-table
    :columns="$columns"
    :data="$data"
    :actions="$actions"
    title="Users"
    description="Manage your users"
/>
```

### With Search and Pagination

```blade
<x-data-table
    :columns="$columns"
    :data="$data"
    :actions="$actions"
    :searchable="true"
    :pagination="true"
    :page-size="25"
    title="Users"
    description="Manage your users"
/>
```

## ⚙️ Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `columns` | Array | `[]` | Table columns configuration |
| `data` | Array | `[]` | Data to display |
| `actions` | Array | `[]` | Action buttons |
| `searchable` | Boolean | `true` | Enable search functionality |
| `sortable` | Boolean | `true` | Enable sorting (future feature) |
| `pagination` | Boolean | `true` | Enable pagination |
| `pageSize` | Integer | `10` | Items per page |
| `emptyMessage` | String | `'No data available'` | Empty state message |
| `title` | String | `'Data Table'` | Table title |
| `description` | String | `'Manage your data records'` | Table description |
| `colorScheme` | String | `'blue'` | Color scheme (blue, green, purple, indigo) |

## 📊 Column Configuration

```php
$columns = [
    ['key' => 'id', 'label' => 'ID'],
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'email', 'label' => 'Email'],
    ['key' => 'status', 'label' => 'Status'],
];
```

## 🎯 Action Configuration

```php
$actions = [
    ['key' => 'view', 'label' => 'View'],
    ['key' => 'edit', 'label' => 'Edit'],
    ['key' => 'delete', 'label' => 'Delete'],
];
```

## 📡 Event Handling

### Listen for Actions

```javascript
document.addEventListener('datatable:action', function(event) {
    const { row, action } = event.detail;
    console.log('Action:', action, 'Row:', row);
    
    // Handle different actions
    switch(action) {
        case 'view':
            viewUser(row);
            break;
        case 'edit':
            editUser(row);
            break;
        case 'delete':
            deleteUser(row);
            break;
    }
});
```

### Listen for Add Button

```javascript
document.addEventListener('datatable:add', function(event) {
    console.log('Add button clicked');
    // Open add modal or redirect
});
```

## 🎨 Styling

The component uses Tailwind CSS classes and includes a basic CSS file. You can customize the appearance by:

1. Modifying `public/css/data-table.css`
2. Adding custom classes to the component
3. Using the `colorScheme` prop for different color themes

## 📱 Responsive Design

The component is fully responsive and adapts to different screen sizes:

- **Desktop**: Full table layout with all features
- **Tablet**: Optimized spacing and layout
- **Mobile**: Stacked layout with touch-friendly controls

## 🔧 Customization

### Custom Color Schemes

Add new color schemes by modifying the `$colors` array in the component:

```php
$colors = [
    'blue' => 'bg-blue-500',
    'green' => 'bg-green-500',
    'purple' => 'bg-purple-500',
    'indigo' => 'bg-indigo-500',
    'red' => 'bg-red-500', // Add new color
];
```

### Custom Styling

Override the default styles by adding custom CSS:

```css
.data-table-container {
    /* Your custom styles */
}

.data-table-add-button {
    /* Custom button styles */
}
```

## 📋 Example Implementation

### Controller

```php
public function index()
{
    $users = User::all();
    
    $columns = [
        ['key' => 'id', 'label' => 'ID'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'created_at', 'label' => 'Created'],
    ];
    
    $actions = [
        ['key' => 'view', 'label' => 'View'],
        ['key' => 'edit', 'label' => 'Edit'],
        ['key' => 'delete', 'label' => 'Delete'],
    ];
    
    return view('users.index', compact('users', 'columns', 'actions'));
}
```

### View

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-data-table
        :columns="$columns"
        :data="$users"
        :actions="$actions"
        title="Users"
        description="Manage your team members"
        color-scheme="blue"
        :searchable="true"
        :pagination="true"
        :page-size="15"
    />
</div>

<script>
document.addEventListener('datatable:action', function(event) {
    const { row, action } = event.detail;
    
    switch(action) {
        case 'view':
            window.location.href = `/users/${row.id}`;
            break;
        case 'edit':
            window.location.href = `/users/${row.id}/edit`;
            break;
        case 'delete':
            if (confirm('Are you sure?')) {
                // Delete user
                fetch(`/users/${row.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => location.reload());
            }
            break;
    }
});

document.addEventListener('datatable:add', function(event) {
    window.location.href = '/users/create';
});
</script>
@endsection
```

## 🎯 Features

- ✅ **Search**: Real-time search functionality
- ✅ **Pagination**: Built-in pagination with customizable page size
- ✅ **Actions**: Customizable action buttons
- ✅ **Responsive**: Mobile-friendly design
- ✅ **Events**: Custom event system for interactions
- ✅ **Empty State**: Helpful empty state message
- ✅ **Color Schemes**: Multiple color options
- ✅ **Simple**: Clean, minimal codebase

## 🚀 Future Enhancements

- [ ] Sorting functionality
- [ ] Bulk actions
- [ ] Export features
- [ ] Advanced filtering
- [ ] Column visibility toggle
- [ ] Row selection
- [ ] Loading states

---

**Simple, clean, and effective!** 🎉

