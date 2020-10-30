<div class="modal fade" id="resign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" >Upload Resignation</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form  method='POST' action='upload-resignation' enctype="multipart/form-data" onsubmit='show();' >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <style>
                        input[type=date]::-webkit-inner-spin-button {
                            -webkit-appearance: none;
                            display: none;
                        }
                    </style>
                    <span id='name'></span>
                    <div class='row'>
                        <div class='col-md-12'>
                            Document :<br>
                            <input name='uploadFile' id='uploadfile' type='file' accept="application/pdf" required>
                            <input class='form-control' name='userid' id='user_id' type='hidden'  required>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Type :<br>
                            <select name='type_resign' class='form-control' required>
                                <option value="Resign">Resign</option>
                                <option value="Retirement">Retirement</option>
                                <option value="Retrenchment">Retrenchment</option>
                                <option value="Redundancy">Redundancy</option>
                                <option value="Termination">Termination</option>
                                <option value="Non-regularized">Non-regularized</option>
                                <option value="Transfer">Transfer</option>
                            </select>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Effective Date :
                            <input class='form-control' id='effectivedate' name='effectivedate' type='date' required></textarea>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Remarks :
                            <textarea class='form-control' id='remarks' name='remarks' required></textarea>
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
