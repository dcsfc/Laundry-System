@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Edit Profile</h1>
                <p class="page-subtitle">Update your account information</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route(auth()->user()->role->name . '.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ auth()->user()->phone_number }}">
                                </div>
                </div>
            </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <p class="form-control-plaintext">{{ auth()->user()->role->name ?? 'No Role' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <p class="form-control-plaintext">
                            <span class="badge bg-{{ auth()->user()->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst(auth()->user()->status ?? 'active') }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Member Since</label>
                        <p class="form-control-plaintext">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
