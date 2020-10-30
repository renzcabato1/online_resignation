<div class="modal fade" id="add_signatory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Add Signatory</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <style>
                #name_chosen{
                    width: 100% !important;
                }
                #department_choice_chosen{
                    width: 100% !important;
                }
            </style>
            <form  method='POST' action='new-signatory/{{$upload_pdf_id}}' onsubmit='show();'  >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Department Name:
                            <select class='form-control chosen-select' name="department"  id='department_choice'  >
                                <option value="">DEPARTMENT / DIVISION MANAGER</option>
                                @foreach($signatures as $signature)
                                    <option value='{{$signature->id}}'>{{$signature->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Signatory Name:
                            <select class='form-control chosen-select' name="name[]" id='name' multiple  required>
                                <option></option>
                                @foreach($employees_data as $employee)
                                <option value='{{$employee->user_id}}'>{{$employee->name}}</option>
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
