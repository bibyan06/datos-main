@extends('layouts.dean_layout')

@section('title', 'Dean Account')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/dean/dean_account.css') }}">
@endsection

@section('main-id','dashboard-content')

@section('content') 
<section class="title">
    <div class="title-content">
        <h3>My Profile</h3>
    </div>
</section>

<div class="account-profile">
    <div class="profile-header">
        <img src="{{ asset('images/cover-photo.png') }}" alt="header_image" class="header-image">
    </div>
    <div class="profile-container">
        <div class="profile-picture">
            <img src="{{ asset('images/boy-1.png') }}" alt="Profile Picture">
        </div>
        <div class="profile-details">
            <h2>{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</h2>
            <div class="profile-info">
                <span class="employee-id">{{ $user->employee_id }}</span>
                <span class="position">{{ $user->position }}</span>
            </div>
        </div>
        <div class="edit-button">
            <button id="openModalBtn">
                <i class="bi bi-pencil-square"></i>
            </button>
        </div>
    </div>
</div>

<div class="info-details">
    <h2>Personal Information</h2>
    <div class="info-row">
        <span class="label">Last Name:</span>
        <span class="value" data-field="last_name">{{ $user->last_name }}</span>
    </div>
    <div class="info-row">
        <span class="label">First Name:</span>
        <span class="value" data-field="first_name">{{ $user->first_name }}</span>
    </div>
    <div class="info-row">
        <span class="label">Middle Name:</span>
        <span class="value" data-field="middle_name">{{ $user->middle_name }}</span>
    </div>
    <div class="info-row">
        <div class="label">Age:</div>
        <div class="value" data-field="age">{{ $user->age }}</div>
    </div>
    <div class="info-row">
        <span class="label">Gender:</span>
        <span class="value" data-field="gender">{{ $user->gender }}</span>
    </div>
    <div class="info-row">
        <span class="label">Home Address:</span>
        <span class="value" data-field="home_address">{{ $user->home_address }}</span>
    </div>
    <div class="info-row">
        <span class="label">Email:</span>
        <span class="value" data-field="email">{{ $user->email }}</span>
    </div>
    <div class="info-row">
        <span class="label">Phone Number:</span>
        <span class="value" data-field="phone_number">{{ $user->phone_number }}</span>
    </div>
    <div class="info-row">
        <div class="label">Username:</div>
        <div class="value" data-field="username">{{ $user->username }}</div>
    </div>
</div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Update Profile</h2>
        <form id="updateProfileForm">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ $user->last_name }}" required>
                    <span class="error-message" id="error-last_name"></span> 
                </div>
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ $user->first_name }}" required>
                    <span class="error-message" id="error-first_name"></span>
                </div>
                <div class="form-group">
                    <label for="middlename">Middle Name</label>
                    <input type="text" id="middle_name" name="middle_name" value="{{ $user->middle_name }}" required>
                    <span class="error-message" id="error-middle_name"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" value="{{ $user->age }}" min="18" max="100" step="1" required>
                    <span class="error-message" id="error-age"></span>
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}">
                    <span class="error-message" id="error-email"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Home Address</label>
                    <input type="text" id="home_address" name="home_address" value="{{ $user->home_address }}" required>
                    <span class="error-message" id="error-home_address"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone_number" value="{{ $user->phone_number }}" required>
                    <span class="error-message" id="error-phone"></span>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ $user->username }}" required>
                    <span class="error-message" id="error-username"></span>
                </div>
            </div>
            <div class="form-row">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <div class="password-container">
                    <input type="password" id="current_password" name="current_password" placeholder="Enter current password" required>
                    <i class="bi bi-eye-slash" id="toggleCurrentPassword"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="New Password" required>
                    <i class="bi bi-eye-slash" id="toggleNewPassword"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password" required>
            </div>
        </div>
        <span class="error-message" id="error-password" style="color: red;"></span>
            <div class="form-buttons">
                <button type="button" id="closeModalBtn">Cancel</button>
                <button type="button" id="saveChangesBtn">Save Changes</button>
            </div>
        </form>
    </div>
</div>
            <!-- Warning Modal -->
            <div id="warningModal" class="modal">
                <div class="modal-content">
                    <span id="warningCloseBtn" class="close">&times;</span>
                    <h2>Warnings</h2>
                    <div id="warningContent"></div>
                    <button id="warningCloseBtn">Close</button>
                </div>
            </div>
@endsection


@section('custom-js')
    <script src="{{ asset('js/dean/dean_account.js') }}"></script>
    <script>const profileUpdateUrl = "/dean/update-profile";</script>
@endsection