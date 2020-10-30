@extends('header')
@section('content')

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div id='success' class="row" style="display:none;">
            <div class="col-6">
                <label class="badge badge-success">Successfully verified</label>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Pending For Clearance </h4>
                        <div class="table-responsive">
                            <table id='clearance_info' class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            Employee Name
                                        </th>
                                        <th>
                                            Company
                                        </th>
                                        <th>
                                            Department
                                        </th>
                                        <th>
                                            Signatory
                                        </th>
                                        <th>
                                            Date Authorized
                                        </th>
                                        <th>
                                            Type
                                        </th>
                                        <th>
                                            Date of Effectivity
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($signatories as $signatory)
                                    <tr id='clearance_{{$signatory->id}}'>
                                        <td>
                                            {{$signatory->hr_u_name}} 
                                        </td>
                                        <td>
                                            {{$signatory->clearance_info->upload_pdf_info->user_info->companies[0]->name}} 
                                        </td>
                                        <td>
                                            @if($signatory->department_name == null)
                                            Department / Division Manager
                                            @else
                                            {{$signatory->department_name}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$signatory->hr_user_name}}
                                        </td>
                                        <td>
                                            {{date('M. d, Y',strtotime($signatory->created_at))}}
                                        </td>
                                        <td>
                                           {{$signatory->type}}
                                        </td>
                                        <td>
                                            {{date('M. d, Y',strtotime($signatory->clearance_info->effective_date))}}
                                            
                                            @if($signatory->clearance_info->effective_date < date('Y-m-d')) <span style='color:red;'>Expired</span>@endif
                                        </td>
                                        <td>
                                            <a href='{{ url('print-clearance-status/'.$signatory->upload_id) }}' target='_'><button type="button" class="btn btn-sm btn-gradient-info btn-icon-text">
                                                View    
                                                <i class="mdi mdi-printer btn-icon-append"></i>                                                                              
                                            </button>
                                        </a>
                                        @if($signatory->user_id_pdf == auth()->user()->id)
                                        Pending
                                        @else
                                        <button type="button" data-toggle="modal" data-target="#verify{{$signatory->id}}" class="btn btn-gradient-success btn-sm">Verify</button>
                                        @endif
                                    </td>
                                </tr>
                                @include('proceed_clearance')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
   
    <script type="text/javascript">
        function verify_clearance(clearance_id)
        {
            document.getElementById("myDiv").style.display="block";
            $.ajax
            ({
                url: '{{ url("/verify") }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',              
                data: {
                    "_method": 'POST',
                    "id" : clearance_id,
                    "accountabilities": $('select[name="accountabilities_'+clearance_id+'"]').val(),
                    "amount": $('input[name="amount_'+clearance_id+'"]').val(),
                    "attachment": $('input[name="attachment_'+clearance_id+'"]').val(),
                    "remarks": $('textarea[name="remarks_'+clearance_id+'"]').val(),
                },
                success: function(result)
                {
                    $('#verify'+result).remove();
                    $('.modal-backdrop').remove();
                    document.getElementById("myDiv").style.display="none";
                    $( "#clearance_"+result).remove();
                    document.getElementById("success").style.display="block";
                },
                error: function(data)
                {
                    console.log(data);
                    document.getElementById("myDiv").style.display="none";
                }
            });
        }
    </script>
</div>
</div>
@endsection
