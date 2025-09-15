@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Schedules</li>
                    </ol>
                </div>
                <h4 class="page-title">Schedule Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h4 class="header-title">Schedules List</h4>
                            <p class="text-muted">Manage drop-off and pickup schedules</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end">
                                <a href="{{ route('staff.schedules.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Add New Schedule
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Schedule ID</th>
                                    <th>Customer</th>
                                    <th>Drop-off Date</th>
                                    <th>Pickup Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="mdi mdi-information-outline"></i>
                                        No schedules found. <a href="{{ route('staff.schedules.create') }}">Create your first schedule</a>
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
