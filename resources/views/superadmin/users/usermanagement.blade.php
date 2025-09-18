@extends('layouts.sidebar')

@section('title', 'User Management - Super Admin')

@section('content')
<x-data-table 
    :columns="$columns"
    :data="$users"
    :actions="$actions"
    :bulk-actions="false"
    :searchable="true"
    :sortable="true"
    :pagination="true"
    :page-size="10"
    :current-page="1"
    :empty-message="'No users found'"
    :hover-effects="true"
    :alternating-rows="true"
    :sticky-header="true"
    :custom-class="'bg-slate-800 text-slate-200'"
    :title="'User Management'"
    :description="'Manage system users, roles, and permissions for all staff and customers'"
    :add-button="true"
    :add-button-label="'Add New User'"
    :add-button-action="'addUser'"
    :show-role-filter="true"
    :available-roles="$roles"
    formType="user"
    colorScheme="indigo"
/>
@endsection