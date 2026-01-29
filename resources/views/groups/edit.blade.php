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
</style>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-edit mr-2"></i>Edit Group</h2>
    </div>

    <div class="form-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <strong><i class="fas fa-exclamation-circle mr-2"></i>Whoops!</strong> There were some problems with your input.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('groups.update', $group->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Group Name</label>
                <input type="text" id="name" name="name" value="{{ $group->name }}" class="form-control" placeholder="Enter group name" required>
            </div>

            <div class="form-group">
                <label for="part" class="form-label">Part</label>
                <input type="text" id="part" name="part" value="{{ $group->part }}" class="form-control" placeholder="Enter part number or name" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('groups.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
