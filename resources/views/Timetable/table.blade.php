@section('content')
<style>
    .subjects-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px 0;
        margin-bottom: 30px;
        border-radius: 10px;
    }

    .subjects-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 28px;
    }

    .btn-add-item {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 10px 25px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-add-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        margin-bottom: 20px;
        color: #155724;
    }

    .table-responsive {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
    }

    .table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e9ecef;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        box-shadow: inset 0 0 5px rgba(102, 126, 234, 0.1);
    }

    .table td {
        vertical-align: middle;
        padding: 15px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .action-buttons form {
        margin: 0;
    }

    .btn-action {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-show {
        background-color: #17a2b8;
        color: white;
    }

    .btn-show:hover {
        background-color: #138496;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(23, 162, 184, 0.3);
    }

    .btn-edit {
        background-color: #007bff;
        color: white;
    }

    .btn-edit:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
    }

    .no-items {
        background: white;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        color: #6c757d;
    }

    .row-number {
        font-weight: 600;
        color: #667eea;
    }
</style>

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>List of Timetables</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('timetable.create') }}"> Add New Timetable</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<table class="table table-bordered">
    <tr>
        <!-- <th>No</th> -->
        <th>User</th>
        <th>Subject</th>
        <th>Day</th>
        <th>Hall</th>
        <th>Group</th>
        <th>Time From</th>
        <th>Time To</th>
        <th width="280px">Action</th>
    </tr>

    @foreach ($timetable as $s)
    <tr>
            <td>{{ $s->user->name ?? 'Not Available' }}</td>
            <td>{{ $s->subject->subject_name ?? 'Not Available' }}</td>
            <td>{{ $s->day->day_name ?? 'Not Available' }}</td>
            <td>{{ $s->hall->lecture_hall_name ?? 'Not Available' }}</td>
            <td>{{ $s->group->name ?? 'Not Available' }}</td>
            <td>{{ $s->time_from }}</td>
            <td>{{ $s->time_to }}</td>
            <td>
            <form action="{{ route('timetable.destroy', $s->id) }}" method="POST">
                
                <a class="btn btn-info" href="{{ route('timetable.show', $s->id) }}">Show</a>
                <a class="btn btn-primary" href="{{ route('timetable.edit', $s->id) }}">Edit</a>

                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@endsection