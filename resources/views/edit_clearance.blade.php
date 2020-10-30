@extends('header')
@section('content')

<!-- partial -->
<div class="main-panel">        
    <div class="content-wrapper">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(session()->has('status'))
                    <div class="row">
                        <div class="col-6">
                            <label class="badge badge-success">{{session()->get('status')}}</label>
                        </div>
                    </div>
                    @endif
                    <div class='row'>
                        <style>
                            input[type=date]::-webkit-inner-spin-button {
                                -webkit-appearance: none;
                                display: none;
                            }
                        </style>
                        
                        <div class="col-md-4" style='border-right: 1px solid green;'>
                            <form method='POST' action='edit-clearance/{{$upload_pdf_id}}' onsubmit='show();' >
                                {{ csrf_field() }}
                                <h4 class="card-title">Information</h4>
                                <div class="form-group">
                                    <p>Employee ID : {{$employee->employee_number}} </p>
                                    <p> Name: {{$employee->last_name.', '.$employee->first_name}} </p>
                                    <p> Company: {{$employee->company_name}}</p>
                                    <p> Department: {{$employee->department_name}} </p>
                                    <p> Position: {{$employee->position}} </p>
                                    <div class="form-group">
                                        <label for="personal_email">Personal Email address</label>
                                        <input type="email" class="form-control" id="personal_email" name='personal_email' value='{{$employee->personal_email}}' placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Contact Number</label>
                                        <input type="text" class="form-control" id="exampleInputPassword1" name='contact_number' value='{{$employee->contact_number}}' placeholder="Contact Number" required>
                                    </div>
                                    <div class="form-group">
                                        <label >Mailing Address: </label>
                                        <input class='form-control' name='mailing_address' value='@if($employee != null) {{$employee->mailing_address}} @endif' type='text' required>
                                    </div>
                                    <div class="form-group">
                                        <label >Phone Number (Landline): </label>
                                        <input class='form-control' name='personal_landline' type='text'  value='@if($employee != null) {{$employee->phone_number_landline}} @endif' >
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="last_day">Date of Effectivity of Resignation<br>
                                                (Based on accepted and approved resignation letter.)</label>
                                        <input type="date" class="form-control"  value='{{$employee->effective_date}}' name='last_day' id="last_day" placeholder="Last Day" required>
                                    </div>
                                    {{-- <div class="form-group">
                                        <label >Actual Last Day to Report for Work: </label>
                                        <input type="date" class="form-control" min='{{date('Y-m-d', strtotime("+2 week"))}}'  value='@if($employee != null){{$employee->last_date_work}}@endif' name='last_day_work' id="last_day" placeholder="Last Day" required>
                                    </div> --}}
                                </div>
                                <button type="submit" id='' class="btn btn-primary" >Save Edit Info</button>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <h4 class="card-title">Edit Clearance 
                                <button type="button" data-toggle="modal" data-target="#add_signatory" data-toggle="new_employee"  class="btn btn-sm btn-gradient-danger btn-icon-text">
                                    Add Signatory  <i class="mdi mdi-account-plus"></i>                                                              
                                </button>
                                @include('add_signatory')
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
                                        <td width='10%' rowspan='{{count($keys)}} '>{{$dep_signa->department_name}}</td>
                                        @endif
                                        @foreach($keys as $key)
                                        <td>{{$signatories[$key]->hr_user_name}} </td>
                                        @if($signatories[$key]->status == null)
                                        <td colspan=5><label class="badge badge-danger">Pending</label> <label class="badge badge-info"><a href="remove-signatory/{{$signatories[$key]->id}}" onclick="javascript:return confirm('Are you sure you want to remove {{$signatories[$key]->hr_user_name}} ?')" style='color:white;'>Remove</a></label></td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
