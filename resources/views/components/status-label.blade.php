<span class="
    @if($status == 'Approved') 
        status-approved
    @elseif($status == 'Declined') 
        status-declined
    @elseif($status == 'Pending') 
        status-pending
    @endif">
    {{ $status }}
</span>