@extends('layouts.admin_layout')

@section('title', 'List of Office Staffs')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/office_staff.css') }}">
@endsection

@section('main-id', 'dashboard-content')

@section('content') 
    <section class="title">
        <div class="title-content">
            <h3>Office Staff List</h3>
            <div class="title-container">
                <div class="show-admin">
                    <h5>Show</h5>
                    <input type="number" id="entry-number" class="option-text" min="1" value="1">
                    <h5>Entries</h5>
                </div>    
                <div class="admin-search-bar">
                    <input type="text" class="search-text" id="search-office-staff" placeholder="Search Office Staff">
                    <div class="icon"><i class="bi bi-search"></i></div>
                </div>
            </div>    
        </div>
    </section>         
    <div id="dashboard-section">
        <div class="dashboard-container">
            @if($officeStaff->isEmpty())
                <p>No office staff found.</p>
            @else
                <table id="office-staff-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Employee ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($officeStaff as $staff)
                            <tr class="office-staff-row">
                                <td>{{ $staff->first_name }} {{ $staff->last_name }}</td>
                                <td>{{ $staff->employee_id }}</td>
                            </tr>
                        @endforeach
                        <tr id="no-results" style="display: none;">
                            <td colspan="3" style="text-align: center; color:red;">Office staff does not exist</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset ('js/upload_document.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-office-staff');
            const tableRows = document.querySelectorAll('#office-staff-table tbody tr.office-staff-row');
            const noResultsMessage = document.getElementById('no-results');

            searchInput.addEventListener('keyup', function() {
                let searchQuery = this.value.toLowerCase();
                let hasVisibleRow = false;
                
                tableRows.forEach(row => {
                    let name = row.cells[0].textContent.toLowerCase();
                    let employeeId = row.cells[1].textContent.toLowerCase();
                    
                    if (name.includes(searchQuery) || employeeId.includes(searchQuery)) {
                        row.style.display = '';
                        hasVisibleRow = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show or hide the "no results" message based on whether any rows are visible
                noResultsMessage.style.display = hasVisibleRow ? 'none' : 'table-row';
            });
        });
    </script>
@endsection
