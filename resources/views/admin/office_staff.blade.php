@extends('layouts.admin_layout')

@section('title', 'List of Office Staffs' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/office_staff.css') }}">
@endsection

@section('main-id','dashboard-content')

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
                        <input type="text" class="search-text" placeholder="Search Office Staff">
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
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Employee ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($officeStaff as $staff)
                                <tr>
                                    <!-- <td>{{ $staff->id }}</td> -->
                                    <td>{{ $staff->first_name }} {{ $staff->last_name }}</td>
                                    <td>{{ $staff->employee_id }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>
@endsection

@section('custom-js')
    <script src="{{ asset ('js/upload_document.js') }}"></script>
@endsection

</body>
</html>