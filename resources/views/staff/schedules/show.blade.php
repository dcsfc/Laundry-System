@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staff.schedules.index') }}">Schedules</a></li>
                        <li class="breadcrumb-item active">Schedule #{{ $id }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Schedule Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Schedule Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Schedule ID:</strong></td>
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
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('staff.schedules.index') }}" class="btn btn-secondary me-2">Back to Schedules</a>
                        <a href="{{ route('staff.schedules.edit', $id) }}" class="btn btn-primary">Edit Schedule</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
