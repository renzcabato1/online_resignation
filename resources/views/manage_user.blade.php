@extends('header')
@section('content')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        @if(session()->has('status'))
        <div class="row">
            <div class="col-6">
                <label class="badge badge-success">{{session()->get('status')}}</label>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class='row'>
                            <div class='col-md-10'>
                                <h4 class="card-title">Account Information</h4>
                                <div class="media">
                                    <img style='width: 44px;height: 44px;border-radius: 100%;border:solid 1px black' src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$info->id.'.png'}}" alt="image">
                                    <div class="nav-profile-text d-flex flex-column" style=' margin-left: 1rem;'>
                                        <span class="font-weight-bold mb-2">{{$info->name}}</span>
                                        <span class="text-muted text-small">{{$info->position}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-2'>
                                <button data-toggle="modal" data-target="#new_employee" data-toggle="new_employee"  type="button" class="btn btn-gradient-primary btn-icon-text">
                                    <i class="mdi mdi-account-plus btn-icon-prepend"></i>
                                    New Employee
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class='row'>
                            <div class='col-md-12'>
                                <table id='example' class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Company
                                            </th>
                                            <th>
                                                Department
                                            </th>
                                            <th>
                                                Position
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    
                                    @foreach($accounts as $account)
                                    <tr>
                                        <td>
                                            <img src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$account->id.'.png'}}" class="mr-2" alt="image">
                                        </td>
                                        <td>
                                            {{$account->last_name.', '.$account->first_name}}
                                        </td>
                                        <td>
                                            {{$account->company_name}}
                                        </td>
                                        <td>
                                            {{$account->department_name}}
                                        </td>
                                        <td>
                                            {{$account->position}}
                                        </td>
                                        <td>
                                            <a href="delete-employee/{{$account->manage_user_id}}" onclick="javascript:return confirm('Are you sure you want to Remove this employee ?')"><button type="button" class="btn btn-gradient-danger btn-sm">Remove</button></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('new_employee')
    </div>
</div>
@endsection
