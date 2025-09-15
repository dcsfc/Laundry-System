@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
                <h4 class="page-title">Orders Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h4 class="header-title">Orders List</h4>
                            <p class="text-muted">Manage customer orders and transactions</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end">
                                <a href="{{ route('staff.orders.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Add New Order
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Drop-off Date</th>
                                    <th>Pickup Date</th>
                                    <th>Status</th>
                                    <th>Total Price</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="mdi mdi-information-outline"></i>
                                        No orders found. <a href="{{ route('staff.orders.create') }}">Create your first order</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
