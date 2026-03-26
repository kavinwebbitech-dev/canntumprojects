@if ($messages->count() == 0)
    <p class="text-center text-muted">No messages yet.</p>
@endif

@foreach ($messages as $msg)
    @if ($msg->remark)
        <div style="text-align:right; margin-bottom:10px;">
            <div
                style="
                display:inline-block;
                background:#007bff;
                color:white;
                padding:8px 12px;
                border-radius:10px;
                max-width:75%;
                text-align:left;">
                {{ $msg->remark }}
            </div><br>
            <small class="text-muted">
                {{ $msg->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
            </small>
        </div>
    @endif

    @if ($msg->reply)
        <div style="text-align:left; margin-bottom:10px;">
            <div
                style="
                display:inline-block;
                background:#28a745;
                color:white;
                padding:8px 12px;
                border-radius:10px;
                max-width:75%;
                text-align:left;">
                {{ $msg->reply }}
            </div><br>
            <small class="text-muted">
                {{ $msg->updated_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
            </small>

        </div>
    @endif
@endforeach
