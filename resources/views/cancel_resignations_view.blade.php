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
                        <h4 class="card-title">Cancel Resignation Request</h4>
                        <div class="table-responsive">
                            <table id='example' class="table">
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
                                            Position
                                        </th>
                                        
                                        <th>
                                            Cancelled Date
                                        </th>
                                        <th>
                                            Cancelled By
                                        </th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                    <tr>
                                        <th>
                                            <img src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$employee->user_info->id.'.png'}}" class="mr-2" alt="image"> 
                                            {{$employee->user_info->first_name.' '.$employee->user_info->last_name}}
                                        </th>
                                        <th>
                                            @if($employee->user_info->companies)
                                            {{$employee->user_info->companies[0]->name}}
                                            @endif
                                        </th>
                                        <th>
                                            @if($employee->user_info->departments)
                                            {{$employee->user_info->departments[0]->name}}
                                            @endif
                                        </th>
                                        <th>
                                            {{$employee->user_info->position}}
                                        </th>
                                        <th>
                                            {{date('M. d Y',strtotime($employee->cancel_date))}}
                                        </th>
                                    
                                        <th>
                                          {{$employee->cancelled_by->name}}
                                        </th>
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
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    
    <!-- partial -->
</div>
<!-- main-panel ends -->
</div>

@endsection
