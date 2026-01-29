@extends('layouts.template')

@section('content')
<style>
    .details-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px 0;
        margin-bottom: 30px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .details-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 28px;
    }

    .btn-back {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid white;
        padding: 10px 25px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-back:hover {
        background-color: white;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .details-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .details-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 30px;
        font-weight: 600;
        font-size: 18px;
    }

    .details-card-body {
        padding: 30px;
    }

    .detail-item {
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e9ecef;
    }

    .detail-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #667eea;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .detail-value {
        color: #333;
        font-size: 16px;
        font-weight: 500;
    }

    .icon-info {
        color: #667eea;
        margin-right: 10px;
    }
</style>

<div class="details-header">
    <h2><i class="fas fa-users mr-2"></i>Group Details</h2>
    <a class="btn-back" href="{{ route('groups.index') }}"><i class="fas fa-arrow-left mr-2"></i>Back</a>
</div>

<div class="details-card">
    <div class="details-card-header">
        <i class="fas fa-info-circle mr-2"></i>Group Information
    </div>
    <div class="details-card-body">
        <div class="detail-item">
            <span class="detail-label"><i class="fas fa-users icon-info"></i>Group Name</span>
            <div class="detail-value">{{ $group->name }}</div>
        </div>

        <div class="detail-item">
            <span class="detail-label"><i class="fas fa-layer-group icon-info"></i>Part</span>
            <div class="detail-value">{{ $group->part }}</div>
        </div>
    </div>
</div>
@endsection
