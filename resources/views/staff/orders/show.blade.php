@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staff.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active">Order #{{ $id }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Order Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Order ID:</strong></td>
                                    <td>#{{ $id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>Customer Name</td>
                                </tr>
                                <tr>
                                    <td><strong>Drop-off Date:</strong></td>
                                    <td>{{ date('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pickup Date:</strong></td>
                                    <td>{{ date('M d, Y', strtotime('+3 days')) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><span class="badge bg-warning">Scheduled</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Price:</strong></td>
                                    <td>₱0.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td><span class="badge bg-danger">Unpaid</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>Not specified</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Notes</h5>
                            <p class="text-muted">No notes available for this order.</p>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('staff.orders.index') }}" class="btn btn-secondary me-2">Back to Orders</a>
                        <a href="{{ route('staff.orders.edit', $id) }}" class="btn btn-primary">Edit Order</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
