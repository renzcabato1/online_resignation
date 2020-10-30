@extends('header')
@section('content')

<!-- partial -->
<div class="main-panel">        
    <div class="content-wrapper">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method='POST' action='new-clearance/{{$upload_pdf_id}}' onsubmit='show();' >
                        {{ csrf_field() }}
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
                                    <p>Employee ID : {{$employee->employee_number}} <br>
                                        Name: {{$employee->last_name.', '.$employee->first_name}} <br>
                                        Company: {{$employee->company_name}}<br>
                                        Department: {{$employee->department_name}} <br>
                                        Position: {{$employee->position}} </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="personal_email">Personal Email address</label>
                                        <input type="email" class="form-control" id="personal_email" name='personal_email' value='@if($personal_info_data != null){{$personal_info_data->email_add}}@endif' placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Contact Number</label>
                                        <input type="text" class="form-control" id="exampleInputPassword1" name='contact_number' value='@if($personal_info_data != null){{$personal_info_data->phone_number_mobile}}@endif' placeholder="Contact Number" required>
                                    </div>
                                    <div class="form-group">
                                        <label >Mailing Address: </label>
                                        <input class='form-control' name='mailing_address' value='@if($personal_info_data != null) {{$personal_info_data->mailing_address}} @endif' type='text' required>
                                    </div>
                                    <div class="form-group">
                                        <label >Landline: </label>
                                        <input class='form-control' name='landline' type='text'  value='@if($personal_info_data != null) {{$personal_info_data->landline}} @endif' >
                                    </div>
                                    <div class="form-group">
                                        <label >Phone Number (Landline): </label>
                                        <input class='form-control' name='personal_landline' type='text'  value='@if($personal_info_data != null) {{$personal_info_data->phone_number_landline}} @endif' >
                                    </div>

                                    <div class="form-group">
                                        <label for="last_day">Date of Effectivity of Resignation<br>
                                                (Based on accepted and approved resignation letter.)</label>
                                                {{-- {{$employee}} --}}
                                        <input type="date" class="form-control"  value='{{$employee->last_day}}' name='last_day' id="last_day" placeholder="Last Day" >
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h4 class="card-title">Clearance Form
                                    </h4>
                                    <table class="table table-hover table-bordered" id='tbUser'>
                                        <thead>
                                            <tr>
                                                <th width='10%'></th>
                                                <th width='30%'>Department</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <tr>
                                                <td>
                                                    Default
                                                </td>
                                                <td>
                                                    {{$employee->department_name}}
                                                </td>
                                                <td>
                                                    <select class='form-control'  name="name[]"  multiple hidden >
                                                        <option></option>
                                                        @foreach($manage_accounts as $manage_account)
                                                        <option value='{{$manage_account->user_id}}' selected>{{strtoupper($manage_account->user_name)}}</option>
                                                        @endforeach
                                                    </select>
                                                    @foreach($manage_accounts as $a => $manage_account)
                                                    @if((count($manage_accounts)-1) == $a)
                                                    {{$manage_account->user_name}}
                                                    @else
                                                    {{$manage_account->user_name.','}}
                                                    
                                                    @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @foreach($signatures as $signature)
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-inverse-danger btn-icon " title='remove'>
                                                        <i class="mdi mdi-account-remove"></i>
                                                    </button>
                                                    {{-- btnDelete --}}
                                                </td>
                                                <td>
                                                    {{$signature->name}}
                                                </td>
                                                <td>
                                                    <select class='form-control chosen-select' style='' name="name[]"  multiple  required>
                                                        <option></option>
                                                        @foreach($employees as $employee)
                                                            {{-- @if($employee->department_id == $signature->id) --}}
                                                            <option value='{{$employee->user_id.'-'.$signature->id}}'>{{strtoupper($employee->name)}}</option>
                                                            {{-- @endif --}}
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan='3' align='right'>
                                                    <button type="submit" id='' class="btn btn-primary" >Submit</button>
                                                </td>
                                            </tr>
                                        </tfoot>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection
    