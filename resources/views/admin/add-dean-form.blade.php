@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Dean</h1>

    <form action="{{ route('deans.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Dean Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="college">Select College:</label>
            <select id="college" name="college_id" class="form-control" required>
                <option value="" disabled selected>Select a College</option>
                @foreach($colleges as $college)
                    <option value="{{ $college->id }}">{{ $college->college_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Dean</button>
    </form>
</div>
@endsection
