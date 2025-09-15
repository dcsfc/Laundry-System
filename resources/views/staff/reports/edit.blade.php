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
                        <li class="breadcrumb-item active">Edit Report #{{ $id }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Weekly Report</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.reports.update', $id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="week_start" class="form-label">Week Start Date</label>
                                    <input type="date" class="form-control" id="week_start" name="week_start" value="2024-12-01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="week_end" class="form-label">Week End Date</label>
                                    <input type="date" class="form-control" id="week_end" name="week_end" value="2024-12-07" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select class="form-select" id="report_type" name="report_type">
                                <option value="summary" selected>Summary Report</option>
                                <option value="detailed">Detailed Report</option>
                                <option value="financial">Financial Report</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('staff.reports.show', $id) }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
