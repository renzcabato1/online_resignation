<div class="modal fade" id="profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form  method='POST' action='change-password'  >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <label style="position:relative; top:7px;">Name : {{Auth::user()->name}}</label>
                    <br>
                    <label style="position:relative; top:7px;">Email : {{Auth::user()->email}}</label>
                    <br>
                    <hr>
                    <label style="position:relative; top:7px;">New Password:</label>
                    <input type='password'  class="form-control" pattern=".{8,}"  name='password' id='password'  onkeyup='check();' required>
                    
                    <p style="font-size:10px;color:red">Passwords must be at least 8 characters long.</p>
                    
                    <label style="position:relative; top:7px;"> Confirm Password :</label>
                    <input type='password'  class="form-control"  pattern=".{8,}" name='password_confirmation'  onkeyup='check();' id='confirm_password'  required>
                    <p style="font-size:10px;" id='message'></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id='submit' class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
