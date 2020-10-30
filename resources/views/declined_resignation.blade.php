<div class="modal fade" id="proceed{{$employee->upload_pdf}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Proceed to HR</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form  method='POST' action='proceed/{{$employee->upload_pdf}}' onsubmit='show();' >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Effectivity Date :
                            <input type='date' name='last_day' value='{{$employee->resignation_date}}'  class='form-control' required>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Remarks :
                            <textarea class='form-control' name='remarks' required></textarea>
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
