@extends('layouts.office_staff_layout')

@section('title', 'Office Staff Account' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/os/staff_account.css') }}">
@endsection

@section('main-id','dashboard-content')

@section('content')
    <section class="title">
        <div class="title-content">
            <h3>My Profile</h3>
        </div>
    </section>

    @auth
    <div class="account-profile">
        <div class="profile-header">
            <img src="{{ asset('images/cover-photo.png') }}" alt="header_image" class="header-image">
        </div>
        <div class="profile-container">
            <div class="profile-picture">
                <img src="{{ asset('images/boy-1.png') }}" alt="Profile Picture">
            </div>
            <div class="profile-details">
                <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                <div class="profile-info">
                    <span class="employee-id">{{ Auth::user()->employee_id }}</span>
                    <span class="position">{{ Auth::user()->user_type }}</span>
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
            <span class="label">Last Name</span>
            <span class="value" data-field="lastname">{{ Auth::user()->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">First Name:</span>
            <span class="value" data-field="firstname">{{ Auth::user()->first_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Middle Name:</span>
            <span class="value" data-field="middlename">{{ Auth::user()->middle_name }}</span>
        </div>
        <div class="info-row">
            <div class="label">Username:</div>
            <div class="value" data-field="username">{{ Auth::user()->username }}</div>
        </div>
        <div class="info-row">
            <div class="label">Age:</div>
            <div class="value" data-field="age">{{ Auth::user()->age }}</div>
        </div>
        <div class="info-row">
            <span class="label">Sex:</span>
            <span class="value" data-field="sex">{{ Auth::user()->gender }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value" data-field="email">{{ Auth::user()->email }}</span>
        </div>
        <div class="info-row">
            <span class="label">Phone Number:</span>
            <span class="value" data-field="phone">{{ Auth::user()->phone_number }}</span>
        </div>
        <div class="info-row">
            <div class="label">Address:</div>
            <div class="value" data-field="address">{{ Auth::user()->home_address }}</div>
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Update Profile</h2>
            <form id="updateProfileForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="lastname" name="last_name" value="{{ Auth::user()->last_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ Auth::user()->first_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ Auth::user()->middle_name }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" min="18" max="120" value="{{ Auth::user()->age }}" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Sex</label>
                        <select id="gender" name="gender" required>
                            <option value="Male" {{ Auth::user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ Auth::user()->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ Auth::user()->username }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ Auth::user()->home_address }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="{{ Auth::user()->phone_number }}" required>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="button" id="closeModalBtn">Cancel</button>
                    <button type="button" id="saveChangesBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    @endauth

@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>const profileUpdateUrl = "office_staff/update-profile";</script>
    <script src="{{ asset('js/os/staff_account.js') }}"></script>
@endsection