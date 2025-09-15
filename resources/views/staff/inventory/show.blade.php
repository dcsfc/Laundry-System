@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staff.inventory.index') }}">Inventory</a></li>
                        <li class="breadcrumb-item active">Item #{{ $id }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Inventory Item Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Item Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Item ID:</strong></td>
                                    <td>#{{ $id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Item Name:</strong></td>
                                    <td>Ariel Powder Detergent</td>
                                </tr>
                                <tr>
                                    <td><strong>Quantity:</strong></td>
                                    <td>25 kilos</td>
                                </tr>
                                <tr>
                                    <td><strong>Unit:</strong></td>
                                    <td>kilos</td>
                                </tr>
                                <tr>
                                    <td><strong>Threshold:</strong></td>
                                    <td>5 kilos</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('staff.inventory.index') }}" class="btn btn-secondary me-2">Back to Inventory</a>
                        <a href="{{ route('staff.inventory.edit', $id) }}" class="btn btn-primary">Edit Item</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
