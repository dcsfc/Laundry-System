@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staff.payments.index') }}">Payments</a></li>
                        <li class="breadcrumb-item active">Payment #{{ $id }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Payment Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Payment Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Payment ID:</strong></td>
                                    <td>#{{ $id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order ID:</strong></td>
                                    <td>#123</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td>₱1,250.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Method:</strong></td>
                                    <td>Cash</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('staff.payments.index') }}" class="btn btn-secondary me-2">Back to Payments</a>
                        <a href="{{ route('staff.payments.edit', $id) }}" class="btn btn-primary">Edit Payment</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
