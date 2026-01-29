<style>
    .groups-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px 0;
        margin-bottom: 30px;
        border-radius: 10px;
    }

    .groups-header h2 {
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
        display: flex;
        gap: 8px;
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

<div class="groups-header">
    <div class="row">
        <div class="col-lg-8">
            <h2><i class="fas fa-users mr-2"></i>Groups Management</h2>
        </div>
        <div class="col-lg-4 text-right">
            <a class="btn btn-add-item" href="{{ route('groups.create') }}">
                <i class="fas fa-plus mr-2"></i>Add New Group
            </a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        <strong>Success!</strong> {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if($groups->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="60px"><i class="fas fa-list"></i> #</th>
                    <th><i class="fas fa-users mr-2"></i>Group Name</th>
                    <th><i class="fas fa-layer-group mr-2"></i>Part</th>
                    <th width="220px" class="text-center"><i class="fas fa-cog mr-2"></i>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $index => $group)
                <tr>
                    <td><span class="row-number">{{ $index + 1 }}</span></td>
                    <td><strong>{{ $group->name }}</strong></td>
                    <td>{{ $group->part }}</td>
                    <td>
                        <div class="action-buttons">
                            <a class="btn btn-action btn-show" href="{{ route('groups.show', $group->id) }}" title="View Details">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a class="btn btn-action btn-edit" href="{{ route('groups.edit', $group->id) }}" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('groups.destroy', $group->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this group?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="no-items">
        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
        <h3>No Groups Found</h3>
        <p>No groups have been added yet. Click the button below to create one.</p>
        <a class="btn btn-add-item" href="{{ route('groups.create') }}">
            <i class="fas fa-plus mr-2"></i>Add First Group
        </a>
    </div>
@endif
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
