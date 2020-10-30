<div class="modal fade" id="verify{{$signatory->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Verify</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <style>
                input[type=date]::-webkit-inner-spin-button {
                    -webkit-appearance: none;
                    display: none;
                }
            </style>
            <form  method='POST'  enctype="multipart/form-data"  onsubmit='verify_clearance({{$signatory->id}});return false;'>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Accountabilities :
                            <select class='form-control' name = 'accountabilities_{{$signatory->id}}' required>
                                <option value=''></option>
                                @foreach($accountabilities as $accountability)
                                    <option value='{{$accountability->name}}'>{{$accountability->name}}</option>
                                @endforeach
                            </select>
                            {{-- <textarea class='form-control'  name ='accountabilities' required></textarea> --}}
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Total Amount of Accountabilities:
                            <input class='form-control' name='amount_{{$signatory->id}}' value='0.00'  step="0.01" type='number' required>
                        </div>
                    </div>
                    <br>
                    <div class='row'>
                        <div class='col-md-12'>
                            Attachment or Supporting Document :<br>
                            <input  name='attachment_{{$signatory->id}}' type='file' >
                        </div>
                    </div>
                    <br>
                    <div class='row'>
                        <div class='col-md-12'>
                            Remarks :
                            <textarea class='form-control'  name ='remarks_{{$signatory->id}}'></textarea>
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
