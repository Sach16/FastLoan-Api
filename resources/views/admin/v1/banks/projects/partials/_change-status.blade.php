@if($project->pivot->status == 'APPROVED')
    <form
            action="{{ route('admin.v1.banks.projects.update',
                                ['banks' => $bank->id, 'projects' => $project->id]) }}"
            method="post" class="form-inline inline-block">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <input type="hidden" name="status" value="PENDING">
        <input type="hidden" name="bank_id" value="{{ $bank->id }}">
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <input type="hidden" name="agent_id" value="{{ $project->pivot->agent_id }}">
        <button class="btn btn-xs btn-danger" type="submit">Revoke Approval</button>
    </form>
@endif
@if($project->pivot->status == 'PENDING')
    <form
            action="{{ route('admin.v1.banks.projects.update',
                            ['banks' => $bank->id, 'projects' => $project->id]) }}"
            method="post" class="form-inline inline-block">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <input type="hidden" name="status" value="APPROVED">
        <input type="hidden" name="bank_id" value="{{ $bank->id }}">
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <input type="hidden" name="agent_id" value="{{ $project->pivot->agent_id }}">
        <button class="btn btn-xs btn-danger" type="submit">Approve</button>
    </form>
    <form
            action="{{ route('admin.v1.banks.projects.update',
                                ['banks' => $bank->id, 'projects' => $project->id]) }}"
            method="post" class="form-inline inline-block">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <input type="hidden" name="status" value="REJECTED">
        <input type="hidden" name="bank_id" value="{{ $bank->id }}">
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <input type="hidden" name="agent_id" value="{{ $project->pivot->agent_id }}">
        <button class="btn btn-xs btn-danger" type="submit">Reject</button>
    </form>
@endif