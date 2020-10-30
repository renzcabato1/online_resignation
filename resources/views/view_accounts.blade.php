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
                        <h4 class="card-title">Users
                            <button type="button" data-toggle="modal" data-target="#new_account" data-toggle="new_account" class="btn btn-gradient-info btn-rounded btn-icon">
                                <i class="mdi mdi-account-plus"></i>
                            </button>
                        </h4>
                        <div class="table-responsive">
                            <table id='example' class="table">
                                <thead>
                                    <tr>
                                        <th>
                                        </th>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            Email
                                        </th>
                                        <th>
                                            Position
                                        </th>
                                        <th>
                                            Department
                                        </th>
                                        <th>
                                            Roles
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts as $account)
                                    <tr>
                                        <td>
                                            <img src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$account->id.'.png'}}" class="mr-2" alt="image">
                                        </td>
                                        <td>
                                            {{$account->name}}
                                        </td>
                                        {{-- <td>
                                            {{$employee->company_name}}
                                        </td> --}}
                                        <td>
                                            {{$account->email}}
                                        </td>
                                        <td>
                                            {{$account->position}}
                                        </td>
                                        <td>
                                            {{$account->department_name}}
                                        </td>
                                        <td>
                                            @foreach(json_decode($account->role_id) as $role) 
                                            @php
                                            $key = array_search($role, array_column($role_array, 'id'));
                                            @endphp
                                            <mark class='bg-info text-white'>{{$roles[$key]->role_name}}</mark>
                                            <br>
                                            <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="reset-account/{{$account->user_id}}" onclick="javascript:return confirm('Are you sure you want to reset password ?')">
                                                <button type="button" title='Reset Password' class="btn btn-outline-secondary btn-rounded btn-icon">
                                                    <i class="mdi mdi-replay text-dark"></i>                          
                                                </button>
                                            </a>
                                            <button href="#edit_account{{$account->account_id}}" data-toggle="modal"  type="button" title='Edit' class="btn btn-outline-secondary btn-rounded btn-icon">
                                                <i class="mdi mdi-table-edit text-dark"></i>                          
                                            </button>
                                            @include('edit_account')
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('new_account')
</div>
</div>
@endsection
