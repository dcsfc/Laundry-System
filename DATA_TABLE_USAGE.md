# 📊 Reusable Data Table Component - Complete Usage Guide

## 🚀 **Overview**

The `x-data-table` component is a powerful, reusable data table that supports **8 different management types** with dynamic form fields, validation, and API integration. It automatically adapts its form fields, validation rules, and API endpoints based on the `formType` parameter.

## 🎯 **Supported Management Types**

| Form Type | Description | Key Fields |
|-----------|-------------|------------|
| `user` | User Management | Name, Email, Phone, Role, Password, Status |
| `service` | Service Management | Service Name, Price, Description, Category, Status |
| `inventory` | Inventory Management | Item Name, Quantity, Unit, Threshold, Cost Price, Status |
| `order` | Order Management | Customer, Service, Drop-off Date, Pickup Date, Notes, Status |
| `payment` | Payment Management | Order, Amount, Payment Method, Reference Number, Status |
| `announcement` | Announcement Management | Title, Message, Status, Priority |
| `role` | Role Management | Role Name, Description, Permissions, Status |
| `permission` | Permission Management | Permission Name, Description, Module, Action, Status |

## 📋 **Complete Usage Examples**

### **1. User Management** ✅
```blade
<x-data-table 
    :columns="$userColumns" 
    :data="$users" 
    :actions="$userActions" 
    formType="user"
    title="User Management" 
    description="Manage system users"
    colorScheme="indigo"
/>
```

**Form Fields:**
- **Name** (text, required)
- **Email** (email, required)
- **Phone Number** (tel, optional)
- **Role** (select, required) - Customer/Staff/Admin/Super Admin
- **Password** (password, required)
- **Status** (select, optional) - Active/Inactive/Pending

### **2. Service Management** ✅
```blade
<x-data-table 
    :columns="$serviceColumns" 
    :data="$services" 
    :actions="$serviceActions" 
    formType="service"
    title="Service Management" 
    description="Manage laundry services"
    colorScheme="sky"
/>
```

**Form Fields:**
- **Service Name** (text, required)
- **Price** (number, required) - with 0.01 step
- **Description** (textarea, optional)
- **Category** (text, optional)
- **Duration** (number, optional) - in minutes
- **Status** (select, optional) - Active/Inactive

### **3. Inventory Management** 🔄
```blade
<x-data-table 
    :columns="$inventoryColumns" 
    :data="$inventory" 
    :actions="$inventoryActions" 
    formType="inventory"
    title="Inventory Management" 
    description="Manage inventory items"
    colorScheme="emerald"
/>
```

**Form Fields:**
- **Item Name** (text, required)
- **Quantity** (number, required)
- **Unit** (text, required) - e.g., "pieces", "kg", "liters"
- **Threshold** (number, required) - low stock alert
- **Cost Price** (number, optional) - with 0.01 step
- **Selling Price** (number, optional) - with 0.01 step
- **Status** (select, optional) - Available/Out of Stock/Low Stock

### **4. Order Management** 🔄
```blade
<x-data-table 
    :columns="$orderColumns" 
    :data="$orders" 
    :actions="$orderActions" 
    formType="order"
    title="Order Management" 
    description="Manage customer orders"
    colorScheme="blue"
/>
```

**Form Fields:**
- **Customer** (select, required) - dropdown of customers
- **Service** (select, required) - dropdown of services
- **Drop-off Date** (date, required)
- **Pickup Date** (date, required)
- **Notes** (textarea, optional)
- **Status** (select, optional) - Scheduled/Priced/In Progress/Completed/Cancelled
- **Payment Status** (select, optional) - Unpaid/Paid
- **Payment Method** (select, optional) - Cash/GCash/Credit Card/PayPal

### **5. Payment Management** 🔄
```blade
<x-data-table 
    :columns="$paymentColumns" 
    :data="$payments" 
    :actions="$paymentActions" 
    formType="payment"
    title="Payment Management" 
    description="Manage payments"
    colorScheme="green"
/>
```

**Form Fields:**
- **Order** (select, required) - dropdown of orders
- **Amount** (number, required) - with 0.01 step
- **Payment Method** (select, required) - Cash/GCash/Credit Card/PayPal
- **Reference Number** (text, optional) - for mobile payments
- **Payment Status** (select, optional) - Pending/Paid/Failed
- **Paid At** (datetime-local, optional)

### **6. Announcement Management** 🔄
```blade
<x-data-table 
    :columns="$announcementColumns" 
    :data="$announcements" 
    :actions="$announcementActions" 
    formType="announcement"
    title="Announcement Management" 
    description="Manage announcements"
    colorScheme="purple"
/>
```

**Form Fields:**
- **Title** (text, required)
- **Message** (textarea, required) - 4 rows
- **Status** (select, optional) - Draft/Published/Archived
- **Priority** (select, optional) - Low/Medium/High

### **7. Role Management** 🔄
```blade
<x-data-table 
    :columns="$roleColumns" 
    :data="$roles" 
    :actions="$roleActions" 
    formType="role"
    title="Role Management" 
    description="Manage user roles"
    colorScheme="orange"
/>
```

**Form Fields:**
- **Role Name** (text, required)
- **Description** (textarea, optional)
- **Permissions** (checkbox, optional) - multiple selection
- **Status** (select, optional) - Active/Inactive

### **8. Permission Management** 🔄
```blade
<x-data-table 
    :columns="$permissionColumns" 
    :data="$permissions" 
    :actions="$permissionActions" 
    formType="permission"
    title="Permission Management" 
    description="Manage permissions"
    colorScheme="red"
/>
```

**Form Fields:**
- **Permission Name** (text, required)
- **Description** (textarea, optional)
- **Module** (select, required) - User/Service/Order/Payment/Inventory/Report
- **Action** (select, required) - Create/Read/Update/Delete
- **Status** (select, optional) - Active/Inactive

## 🎨 **Color Schemes**

| Color Scheme | Usage | Hex Colors |
|--------------|-------|------------|
| `indigo` | Super Admin, Premium features | Indigo-600 to Purple-600 |
| `sky` | Admin, Staff, Operations | Sky-600 to Cyan-600 |
| `emerald` | Customer, Growth features | Emerald-500 to Teal-600 |
| `blue` | General management | Blue-600 to Blue-700 |
| `green` | Success, Payments | Green-600 to Green-700 |
| `purple` | Announcements, Communications | Purple-600 to Purple-700 |
| `orange` | Roles, Permissions | Orange-600 to Orange-700 |
| `red` | Critical, Alerts | Red-600 to Red-700 |

## 🔧 **Component Props**

| Prop | Type | Required | Description |
|------|------|----------|-------------|
| `columns` | Array | Yes | Table column definitions |
| `data` | Array | Yes | Data to display |
| `actions` | Array | Yes | Row action buttons |
| `formType` | String | Yes | Form type (user, service, inventory, etc.) |
| `title` | String | No | Table title |
| `description` | String | No | Table description |
| `colorScheme` | String | No | Color theme (indigo, sky, emerald, etc.) |
| `bulkActions` | Boolean | No | Enable bulk actions (default: false) |
| `searchable` | Boolean | No | Enable search (default: true) |
| `sortable` | Boolean | No | Enable sorting (default: true) |
| `pagination` | Boolean | No | Enable pagination (default: true) |
| `pageSize` | Number | No | Items per page (default: 10) |
| `hoverEffects` | Boolean | No | Row hover effects (default: true) |
| `alternatingRows` | Boolean | No | Alternating row colors (default: true) |
| `stickyHeader` | Boolean | No | Sticky table header (default: true) |

## 📝 **Field Types Supported**

| Field Type | Description | Example |
|------------|-------------|---------|
| `text` | Single-line text input | Name, Title |
| `email` | Email input with validation | Email Address |
| `tel` | Phone number input | Phone Number |
| `password` | Password input with show/hide | Password |
| `number` | Numeric input | Price, Quantity, Amount |
| `date` | Date picker | Drop-off Date, Pickup Date |
| `datetime-local` | Date and time picker | Paid At |
| `textarea` | Multi-line text input | Description, Notes, Message |
| `select` | Dropdown selection | Role, Status, Payment Method |
| `checkbox` | Multiple checkbox selection | Permissions |
| `radio` | Single radio button selection | Priority |
| `file` | File upload input | Image, Document |
| `url` | URL input with validation | Website, Link |

## 🚀 **Key Features**

### ✅ **Dynamic Form Generation**
- Automatically generates form fields based on `formType`
- Supports 13 different field types
- Dynamic validation rules
- Responsive 2-column layout

### ✅ **Smart Validation**
- Real-time form validation
- Field-specific error messages
- Required field indicators
- Custom validation rules per form type

### ✅ **API Integration**
- Automatic API endpoint detection
- CSRF token handling
- AJAX form submission
- Error handling and notifications

### ✅ **Responsive Design**
- Mobile-first approach
- Adaptive layouts
- Touch-friendly interactions
- Cross-device compatibility

### ✅ **Accessibility**
- ARIA labels and descriptions
- Keyboard navigation
- Screen reader support
- Focus management

### ✅ **Professional UI/UX**
- Modern gradient designs
- Smooth animations
- Loading states
- Success/error notifications

## 🔄 **Form Workflow**

1. **User clicks "Add New" button**
2. **Modal opens with dynamic form fields**
3. **User fills out form with real-time validation**
4. **Form submits via AJAX to appropriate API endpoint**
5. **Success/error notification displayed**
6. **Table refreshes with new data**
7. **Modal closes automatically**

## 🛠️ **Implementation Steps**

### **Step 1: Controller Setup**
```php
// In your controller
public function index()
{
    $data = Model::all();
    $columns = [
        ['key' => 'id', 'label' => 'ID', 'sortable' => true],
        ['key' => 'name', 'label' => 'Name', 'sortable' => true, 'searchable' => true],
        // ... more columns
    ];
    $actions = [
        ['label' => 'View', 'onclick' => 'viewItem'],
        ['label' => 'Edit', 'onclick' => 'editItem'],
        ['label' => 'Delete', 'onclick' => 'deleteItem']
    ];
    
    return view('your-view', compact('data', 'columns', 'actions'));
}
```

### **Step 2: View Implementation**
```blade
@extends('layouts.app')

@section('content')
    <x-data-table 
        :columns="$columns" 
        :data="$data" 
        :actions="$actions" 
        formType="your-form-type"
        title="Your Management" 
        description="Manage your items"
        colorScheme="your-color"
    />
@endsection
```

### **Step 3: API Endpoints**
```php
// Create API endpoints for form submission
Route::post('/your-endpoint', [YourController::class, 'store']);
Route::put('/your-endpoint/{id}', [YourController::class, 'update']);
Route::delete('/your-endpoint/{id}', [YourController::class, 'destroy']);
```

## 🎯 **Best Practices**

1. **Use appropriate color schemes** for different management types
2. **Provide clear column definitions** with proper sorting/searching
3. **Implement proper validation** in both frontend and backend
4. **Handle errors gracefully** with user-friendly messages
5. **Test all form types** to ensure proper functionality
6. **Use consistent naming** for form fields and API endpoints

## 🚀 **Ready to Use!**

The data table component is now fully configured to support all 8 management types with dynamic form generation, validation, and API integration. Simply set the `formType` parameter and the component will automatically render the appropriate form fields and handle all interactions!

---

**Need help?** Check the component source code in `resources/views/components/data-table.blade.php` for detailed implementation examples.
