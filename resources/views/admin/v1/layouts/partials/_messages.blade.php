@if (count($errors) > 0)
    <div class="alert alert-danger" data-error-flash>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-success" data-flash>
        {{ session('status') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success" data-flash>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" data-error-flash>
        {{ session('error') }}
    </div>
@endif

<div class="modal fade" tabindex="-1" role="dialog" id="confirm-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" data-confirm>Delete Record</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure about deleting this record?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="yes">Yes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="confirm-status-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" data-confirm>Change Status</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure about change status of this record?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="yes">Yes</button>
            </div>
        </div>
    </div>
</div>
