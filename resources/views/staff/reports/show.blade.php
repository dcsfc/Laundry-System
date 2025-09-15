@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staff.reports.index') }}">Reports</a></li>
                        <li class="breadcrumb-item active">Report #{{ $id }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Weekly Report Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Report Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Report ID:</strong></td>
                                    <td>#{{ $id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Week Period:</strong></td>
                                    <td>Dec 1-7, 2024</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Orders:</strong></td>
                                    <td>25</td>
                                </tr>
                                <tr>
                                    <td><strong>Revenue:</strong></td>
                                    <td>₱62,500.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('staff.reports.index') }}" class="btn btn-secondary me-2">Back to Reports</a>
                        <a href="{{ route('staff.reports.edit', $id) }}" class="btn btn-primary">Edit Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
