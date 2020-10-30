<div class="modal fade" id="edit_account{{$account->account_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Edit Account</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <style>
                   
                    #roles{{$account->account_id}}_chosen{
                        width: 100% !important;
                    }
                </style>
            <form  method='POST' action='edit-account/{{$account->account_id}}'  >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Email :
                           
                        <input  class='form-control' value = '{{$account->email}}'readonly>
                        <input type='hidden' name="name" value = '{{$account->user_id}}' >
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Roles :
                        <select class='form-control chosen-select' name="roles[]" id='roles{{$account->account_id}}' multiple required>
                                <option></option>
                                @foreach($roles as $role)
                                <option value='{{$role->id}}' {{ (in_array($role->id,json_decode($account->role_id)) ? "selected":"") }}>{{$role->role_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit"  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
