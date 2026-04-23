<div class="dropdown">
    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
        <i class="fas fa-cog"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{ $detailUrl }}"><i class="fas fa-eye mr-2"></i>Detail</a>

        @if($canApprove)
            <form action="{{ $repeatapproveUrl }}" method="POST" class="form-approve px-3">
                @csrf 
                @method('PUT')
                
                <div class="form-group mb-2">
                    <label for="status_approval_id">Status</label>
                    <select name="status_approval_id" class="form-control form-control-sm" required>
                        @foreach($statusList as $status)
                            <option value="{{ $status->id }}" {{ $statusApproval == $status->id ? 'selected' : '' }}>
                                {{ $status->status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-success btn-sm btn-block" type="submit">
                    <i class="fas fa-check-circle mr-1"></i>Setujui
                </button>
            </form>
        @endif

        @if($canApprove && $statusApproval == 2)
            <form action="{{ $repeatunapproveUrl }}" method="POST" class="form-unapprove">
                @csrf @method('PUT')
                <button class="dropdown-item text-danger" type="submit">
                    <i class="fas fa-times-circle mr-2"></i>Batal Approve
                </button>
            </form>
        @endif
    </div>
</div>