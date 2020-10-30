<div class="modal fade" id="email{{$total_resign->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                <h5 class="modal-title" id="exampleModalLabel" >Send email Manually (Approver)<br> <span style='color:green;display:none;' id='info{{$total_resign->id}}'>Email successfully sent!</span></h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                {{ csrf_field() }}
                @foreach($total_resign->approver_id as $approver)
                <div class='row'>
                    <div class='col-md-8'>
                        {{$approver->approver->name}}
                    </div>
                    <div class='col-md-4'>
                        <button class='btn btn-sm btn-gradient-info' onclick='email_manual({{$approver->approver_id}},{{$total_resign->id}})'>Send Email</button>
                    </div>
                </div>
                <br>
                @endforeach
            </div>
        </div>
    </div>
</div>
