@extends('header')
@section('content')

<!-- partial -->
<div class="main-panel">        
    <div class="content-wrapper">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class='row'>
                        <style>
                            input[type=date]::-webkit-inner-spin-button {
                                -webkit-appearance: none;
                                display: none;
                            }
                        </style>
                        <div class="col-md-4" style='border-right: 1px solid green;'>
                            <h4 class="card-title">Information</h4>
                            <div class="form-group">
                                <p>Employee ID : {{$employee->employee_number}} </p>
                                <p> Name: {{$employee->last_name.', '.$employee->first_name}} </p>
                                <p> Company: {{$employee->company_name}}</p>
                                <p> Department: {{$employee->department_name}} </p>
                                <p> Position: {{$employee->position}} </p>
                                <p> Personal Email:  {{$employee->personal_email}} </p>
                                <p> Contact Number:  {{$employee->contact_number}} </p>
                                <p> Mailing Address:  {{$employee->mailing_address}} </p>
                                <p> Landline:  {{$employee->landline}} </p>
                                <p> Phone Number (Landline):  {{$employee->phone_number_landline}} </p>
                                <p> Effective Date:  {{date('M d, Y',strtotime($employee->effective_date))}} </p>
                                <p> Actual Last Day to Report for Work:  {{date('M d, Y',strtotime($employee->effective_date))}} </p>
                            </div>
                            
                        </div>
                        @php
                        $roles_array = null;
                        if(auth()->user()->role() != null)
                        {
                            $roles = auth()->user()->role()->role_id;
                            $roles_array = json_decode($roles);
                        }
                        @endphp
                        <div class="col-md-8">
                            <h4 class="card-title">Clearance <a href='{{ url('print-clearance-status/'.$employee->upload_pdf_id) }}' target='_'><button type="button" class="btn btn-sm btn-gradient-info btn-icon-text">
                                    Print
                                    <i class="mdi mdi-printer btn-icon-append"></i>                                                                              
                                  </button>
                                </a>
                                @if((in_array(1,$roles_array)) || (in_array(4,$roles_array)) )
                                    <a href='{{ url('edit-clearance-status/'.$employee->upload_pdf_id) }}'><button type="button" class="btn btn-sm btn-gradient-danger btn-icon-text">
                                        Edit  <i class="mdi btn-icon-append">&#9998;</i>                                                              
                                    </button>
                                    </a>
                                @endif
                            </h4>
                            <table class="table table-hover table-bordered" id='tbUser'>
                                <thead>
                                    <tr>
                                        <th width='10%'>Department</th>
                                        <th>Signatory</th>
                                        <th>Accountabilities</th>
                                        <th>Amount</th>
                                        <th>Date Verified</th>
                                        <th>Attachment</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($department_signatories as $dep_signa)
                                    @php
                                        $keys = array_keys($signatories_id, $dep_signa->department_id);
                                    @endphp
                                    <tr>
                                        @if($dep_signa->department_name == null)
                                            <td width='10%' rowspan='{{count($keys)}} '>Department / Division Manager</td>
                                        @else
                                            <td width='10%' rowspan='{{count($keys)}} '>{{strtoupper($dep_signa->department_name)}}</td>
                                        @endif
                                        @foreach($keys as $key)
                                            <td>{{$signatories[$key]->hr_user_name}} </td>
                                                @if($signatories[$key]->status == null)
                                                <td colspan=5><label class="badge badge-danger">Pending</label></td>
                                                @else
                                                    <td>{{$signatories[$key]->accountabilities}}</td>
                                                    <td>{{$signatories[$key]->amount}}</td>
                                                    <td>{{date('M. d, Y',strtotime($signatories[$key]->date_verified))}}</td>
                                                    @if($signatories[$key]->attachment)
                                                        <td><a href='{{ url($signatories[$key]->attachment)}}' target='_'>Download Attachment</a></td>
                                                    @else
                                                    <td>No Attachment</td>
                                                    @endif
                                                    <td>{{$signatories[$key]->remarks}}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- <script type="text/javascript">
                                $("#tbUser").on('click', '.btnDelete', function () {
                                    $(this).closest('tr').remove();
                                });
                                
                                $(".adddepartment").click(function(){
                                    var markup = "<tr><td> <button type='button' class='btn btn-inverse-danger btn-icon btnDelete' title='remove'><i class='mdi mdi-account-remove'></i></button></td><td></td><td> <select class='form-control chosen-select' style='' name='name[]'  multiple  required><option></option>@foreach($employees as $employee)<option value='{{$employee->user_id}}'>{{$employee->name}}</option>@endforeach</select></td></tr>";
                                    $("#tbUser ").append(markup);
                                });    
                            </script> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
