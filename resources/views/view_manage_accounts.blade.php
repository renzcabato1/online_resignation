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
                        <h4 class="card-title">Manage Accounts
                            {{-- <button type="button" data-toggle="modal" data-target="#new_manage_account" data-toggle="new_account" class="btn btn-gradient-info btn-rounded btn-icon">
                                <i class="mdi mdi-account-plus"></i>
                            </button> --}}
                        </h4>
                        <div class="table-responsive">
                            <table id='example'  class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            
                                        </th>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            Position
                                        </th>
                                        <th>
                                            Department
                                        </th>
                                        <th>
                                            Employees
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts as $account)
                                    @php
                                    $a = 0;
                                    $image = 0;
                                    @endphp
                                    @foreach(json_decode($account->role_id) as $role) 
                                    @if(($role == 2) || ($role == 3) || ($role == 7))
                                    @php
                                    $a = 1;
                                    @endphp
                                    @endif
                                    @endforeach
                                    @if($a == 1)
                                    <tr>
                                        <td>
                                            <img src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$account->id.'.png'}}" class="mr-2" alt="image">
                                        </td>
                                        <td>
                                            {{$account->name}}
                                        </td>
                                        <td>
                                            {{$account->position}}
                                        </td>
                                        <td>
                                            {{$account->department_name}}
                                        </td>
                                        <td>
                                            @php
                                                $user_count = count(array_keys($user_id, $account->user_id));
                                                $user_count_id = array_keys($user_id, $account->user_id);
                                            @endphp
                                            @foreach($user_count_id as $user_id_a)
                                                    @php
                                                        $image = $image + 1 ;
                                                    @endphp
                                                    <img style='border:solid 1px black' src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$users[$user_id_a]->id.'.png'}}" class="mr-2" title='{{$users[$user_id_a]->name}}' alt="image">
                                                    @if($image == 5)
                                                        @php
                                                            break;
                                                        @endphp
                                                @endif
                                            @endforeach
                                            @if($user_count > 5)
                                               <span style='background-color:gray;color:white;padding:10px;border-radius: 100%;font-size:14px;' title='
                                               <?php 
                                               foreach($user_count_id as $key => $user_id_a)
                                               { 
                                                   if($key > 4)
                                                   {
                                                        // echo $users[$user_id_a]->name .'&#013';
                                                    }
                                                } 
                                                ?>
                                                '> +{{$user_count - 5}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="manage-account-edit/{{$account->account_id}}" >
                                                <button  type="button" title='Edit' class="btn btn-outline-secondary btn-rounded btn-icon">
                                                    <i class="mdi mdi-table-edit text-dark"></i>                          
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif  
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
</div>
@endsection
