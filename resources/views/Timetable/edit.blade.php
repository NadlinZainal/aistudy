@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Edit Timetable Details</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('timetable.update', $timetable->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- User -->
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control">
                <option value="">-- Select User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $timetable->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Subject -->
        <div class="form-group">
            <label for="subject_id">Subject</label>
            <select name="subject_id" id="subject_id" class="form-control">
                <option value="">-- Select Subject --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $timetable->subject_id == $subject->id ? 'selected' : '' }}>
                        {{ $subject->subject_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Day -->
        <div class="form-group">
            <label for="day_id">Day</label>
            <select name="day_id" id="day_id" class="form-control">
                <option value="">-- Select Day --</option>
                @foreach($days as $day)
                    <option value="{{ $day->id }}" {{ $timetable->day_id == $day->id ? 'selected' : '' }}>
                        {{ $day->day_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Hall -->
        <div class="form-group">
            <label for="hall_id">Hall</label>
            <select name="hall_id" id="hall_id" class="form-control">
                <option value="">-- Select Hall --</option>
                @foreach($halls as $hall)
                    <option value="{{ $hall->id }}" {{ $timetable->hall_id == $hall->id ? 'selected' : '' }}>
                        {{ $hall->lecture_hall_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Lecturer Group -->
        <div class="form-group">
            <label for="lecturer_group_id">Lecturer Group</label>
            <select name="lecturer_group_id" id="lecturer_group_id" class="form-control">
                <option value="">-- Select Group --</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ $timetable->lecturer_group_id == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Time From -->
        <div class="form-group">
            <label for="time_from">Time From</label>
            <input type="text" name="time_from" id="time_from" class="form-control" value="{{ $timetable->time_from }}">
        </div>

        <!-- Time To -->
        <div class="form-group">
            <label for="time_to">Time To</label>
            <input type="text" name="time_to" id="time_to" class="form-control" value="{{ $timetable->time_to }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Timetable</button>
        <a href="{{ route('timetable.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
