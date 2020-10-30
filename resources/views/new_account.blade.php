<div class="modal fade" id="new_account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">New Account</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <style>
                #email_chosen{
                    width: 100% !important;
                }
                #roles_chosen{
                    width: 100% !important;
                }
            </style>
            <form  method='POST' action='add-account' onsubmit='show();'  >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Email :
                            <select class='form-control chosen-select' name="name" id='email' data-placeholder="Choose Employee..."  required>
                                <option></option>
                                @foreach($employees as $employee)
                                <option value='{{$employee->user_id}}'>{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Roles :
                            <select class='form-control chosen-select' name="roles[]" id='roles' multiple required>
                                <option></option>
                                @foreach($roles as $role)
                                <option value='{{$role->id}}'>{{$role->role_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id='' class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
