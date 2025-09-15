@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Weekly Reports</li>
                    </ol>
                </div>
                <h4 class="page-title">Weekly Reports</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h4 class="header-title">Weekly Reports</h4>
                            <p class="text-muted">View and generate weekly performance reports</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end">
                                <a href="{{ route('staff.reports.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Generate Report
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Report ID</th>
                                    <th>Week Period</th>
                                    <th>Total Orders</th>
                                    <th>Revenue</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="mdi mdi-information-outline"></i>
                                        No reports found. <a href="{{ route('staff.reports.create') }}">Generate your first report</a>
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
