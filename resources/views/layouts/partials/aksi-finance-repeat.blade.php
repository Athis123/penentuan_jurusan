<div class="dropdown">
    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
        <i class="fas fa-cog"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{ $detailUrl }}"><i class="fas fa-eye mr-2"></i>Detail</a>

        @if($canApprove && $statusApproval != 2)
            <form action="{{ $repeatapproveUrl }}" method="POST" class="form-approve">
                @csrf @method('PUT')
                <button class="dropdown-item text-success" type="submit"><i class="fas fa-check-circle mr-2"></i>Approve</button>
            </form>
        @endif

        @if($canApprove && $statusApproval == 2)
            <form action="{{ $repeatunapproveUrl }}" method="POST" class="form-unapprove">
                @csrf @method('PUT')
                <button class="dropdown-item text-danger" type="submit"><i class="fas fa-times-circle mr-2"></i>Batal Approve</button>
            </form>
        @endif
    </div>
</div>