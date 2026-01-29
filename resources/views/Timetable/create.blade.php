@extends('layouts.template')

@section('content')
<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .form-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .form-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        display: block;
        font-size: 14px;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        color: #721c24;
        margin-bottom: 20px;
        padding: 15px;
    }

    .alert-danger ul {
        margin: 10px 0 0 20px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        flex: 1;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-back {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .form-timetable select.form-control {
    height: auto !important;
    min-height: 45px !important;
    line-height: normal !important;
    padding: 10px 16px !important;
    overflow: visible !important;
}
</style>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-plus mr-2"></i>Add New Timetable</h2>
    </div>

    <div class="form-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-circle mr-2"></i>Please fix the errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        <form action="{{ route('timetable.store') }}" method="POST">
            @csrf

    <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-timetable">
                <strong>User:</strong>
                <select name="user_id" class="form-control">
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-timetable">
                <strong>Subject:</strong>
                <select name="subject_id" class="form-control">
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-timetable">
                <strong>Day:</strong>
                <select name="day_id" class="form-control">
                    <option value="">-- Select Day --</option>
                    @foreach($days as $day)
                        <option value="{{ $day->id }}">{{ $day->day_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-timetable">
                <strong>Hall:</strong>
                <select name="hall_id" class="form-control">
                    <option value="">-- Select Hall --</option>
                    @foreach($halls as $hall)
                        <option value="{{ $hall->id }}">{{ $hall->lecture_hall_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-timetable">
                <strong>Group:</strong>
                <select name="lecturer_group_id" class="form-control">
                    <option value="">-- Select Group --</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
            <div class="form-group">
                <label for="time_from" class="form-label">Time From</label>
                <input type="time" id="time_from" name="time_from" class="form-control" value="{{ old('time_from') }}" required>
            </div>

            <div class="form-group">
                <label for="time_to" class="form-label">Time To</label>
                <input type="time" id="time_to" name="time_to" class="form-control" value="{{ old('time_to') }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i>Submit
                </button>
                <a href="{{ route('timetable.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
