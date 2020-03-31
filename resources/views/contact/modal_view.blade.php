<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">View Request</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6">
            <strong><i class="fa fa-user margin-r-5"></i>  Name</strong>
            <p class="text-muted">
                {{ $data->name }}
            </p>
        </div>
        <div class="col-lg-6">
            <strong><i class="fa fa-building margin-r-5"></i>  Company</strong>
            <p class="text-muted">
                {{ $data->company }}
            </p>
        </div>
        <div class="col-lg-12"><hr></div>
        <div class="col-lg-6">
            <strong><i class="fa fa-bullseye margin-r-5"></i>  IP</strong>
            <p class="text-muted">
                {{ $data->ip }}
            </p>
        </div>
        <div class="col-lg-6">
            <strong><i class="fa fa-envelope margin-r-5"></i>  Email</strong>
            <p class="text-muted">
                {{ $data->email }}
            </p>
        </div>
        <div class="col-lg-12"><hr></div>
        <div class="col-lg-6">
            <strong><i class="fa fa-phone margin-r-5"></i>  Phone</strong>
            <p class="text-muted">
                {{ $data->phone }}
            </p>
        </div>
        <div class="col-lg-6">
            <strong><i class="fa fa-question margin-r-5"></i>  Message</strong>
            <p class="text-muted">
                {{ $data->message }}
            </p>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>