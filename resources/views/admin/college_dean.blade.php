@extends('layouts.admin_layout')

@section('title', 'List of College Deans')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/dean.css') }}">
    
@endsection

@section('main-id', 'dashboard-content')

@section('content')
    <main id="dashboard-content">
        <div id="loading-overlay" class="loading-overlay" style="display: none; flex-direction: column; justify-content:center; align-items:center;">
            <div class="spinner"></div>
            <p>Loading...</p>
        </div>
        
        <section class="title">
            <div class="title-content">
                <h3>College Dean List</h3>
                <div class="title-container">
                    <div class="show-dean">
                        <h5>Show</h5>
                        <input type="number" id="entry-number" class="option-text" min="1" value="10">
                        <h5>Entries</h5>
                    </div>
                    <div class="dean-search-bar">
                        <input type="text" id="search-dean" class="search-text" placeholder="Search College Dean">
                        <div class="icon"><i class="bi bi-search"></i></div>
                    </div>
                    <div class="add-account">
                        <button type="button" onclick="showPopupForm()" class="account-btn">Add Account</button>
                    </div>
                    <div class="add-college">
                        <button type="button" onclick="showPopupForm1()" class="college-btn">Add College</button>
                    </div>
                </div>
            </div>
        </section>

           <div id="dashboard-section">
            <div class="dashboard-container">
                @if ($users->isEmpty())
                    <p>No deans found.</p>
                @else
                    <table id="dean-table">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>College</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="college-dean-row" >
                                    <td>{{ $user->employee_id }}</td>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->colleges->college_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <div id="no-results" style="display: none; text-align: center; color:red;">
                    College Dean does not exist
                </div>
            </div>
        </div>

        <div id="overlay" class="overlay"></div>
        <div id="popup-form" class="popup-form">
            <h2>Add College Dean</h2>
            <form action="{{ route('dean.store') }}" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input type="text" id="last-name" name="last-name">
                    </div>
                    <div class="form-group">
                        <label for="first-name">First Name</label>
                        <input type="text" id="first-name" name="first-name">
                    </div>
                    <div class="form-group">
                        <label for="middle-name">Middle Name</label>
                        <input type="text" id="middle-name" name="middle-name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="college">Select College</label>
                    <select id="college" name="college_id" required>
                        <option value="" disabled selected>Select a College</option>
                        @foreach ($colleges as $college)
                            <option value="{{ $college->college_id }}">{{ $college->college_name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" oninput="validatePassword()">
                        <small id="password-help" style="color: red; display: block;"></small>
                    </div>
                    <div class="form-group">
                        <label for="employee-id">Employee ID</label>
                        <input type="text" id="employee-id" name="employee-id">
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="button" id="cancel-btn" onclick="hidePopupForm()">Cancel</button>
                    <button type="button" id="add-account-btn" onclick="addAccount()">Add Account</button>
                </div>
            </form>
        </div>
        <!--ADD COLLEGE POP_UP-->
        <div id="popup-form-1" class="popup-form-1">
            <h2>Add College</h2>
            <form id="college-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="college-name">College Name</label>
                        <input type="text" id="college-name" name="college-name" required>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="button" id="cancel-btn" onclick="hidePopupForm1()">Cancel</button>
                    <button type="button" id="add-college-btn" onclick="addCollege()">Add College</button>
                </div>
            </form>
        </div>

    </main>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/college_dean.js') }}"></script>
    <script>
        function validatePassword() {
            const passwordInput = document.getElementById('password');
            const passwordHelp = document.getElementById('password-help');
            const password = passwordInput.value;
            let messages = [];

            // Check for minimum length of 8 characters
            if (password.length < 8) {
                messages.push("Password must be at least 8 characters.");
            }

            // Check for at least one uppercase letter
            if (!/[A-Z]/.test(password)) {
                messages.push("Password must contain at least one uppercase letter.");
            }

            // Check for at least one number
            if (!/[0-9]/.test(password)) {
                messages.push("Password must contain at least one number.");
            }

            // Display messages or clear them if the password is valid
            if (messages.length > 0) {
                passwordHelp.innerHTML = messages.join('<br>');
            } else {
                passwordHelp.innerHTML = '';
            }
        }
    </script>
@endsection


</body>

</html>
