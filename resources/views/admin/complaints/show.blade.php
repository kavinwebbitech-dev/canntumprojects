@extends('admin.index')

@section('admin')

<style>
    label { font-weight: bold; }
</style>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Complaint Details</h4>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <tr>
                <th width="25%">User Name</th>
                <td>{{ $complaint->user->name ?? '-' }}</td>
            </tr>

            <tr>
                <th>Order ID</th>
                <td>{{ $complaint->order_id }}</td>
            </tr>

            <tr>
                <th>Remark</th>
                <td>{{ $complaint->remark }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    @if($complaint->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($complaint->status == 'replied')
                        <span class="badge bg-success">Replied</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Reply (Sent to User)</th>
                <td>{{ $complaint->reply ?? 'No reply yet' }}</td>
            </tr>
        </table>

        <hr>

        <h4>Reply to User</h4>

        <form action="{{ route('complaints.reply') }}" method="POST">
            @csrf

            <input type="hidden" name="complaint_id" value="{{ $complaint->id }}">

            <div class="form-group">
                {{-- <label>Reply Message</label> --}}
                <textarea name="reply" class="form-control" rows="2" required>{{ $complaint->reply }}</textarea>
            </div>

            <button class="btn btn-success mt-3">
                Send Reply
            </button>
        </form>
    </div>
</div>

@endsection
