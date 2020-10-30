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
                        <h4 class="card-title">Employees</h4>
                        <div class="table-responsive">
                            <table id='example' class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            Employee Name
                                        </th>
                                        {{-- <th>
                                            Company
                                        </th> --}}
                                        <th>
                                            Department
                                        </th>
                                        <th>
                                            Position
                                        </th>
                                        <th>
                                            Location
                                        </th>
                                        <th>
                                            Date Hired
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                    <tr>
                                        <td>
                                            <img src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$employee->id.'.png'}}" class="mr-2" alt="image">
                                            
                                            {{$employee->last_name.', '.$employee->first_name}}
                                        </td>
                                        <td>
                                            {{$employee->department_name}}
                                        </td>
                                        <td>
                                            {{$employee->position}}
                                        </td>
                                        <td>
                                            {{$employee->location_name}}
                                        </td>
                                        <td>
                                            {{date('M. d, Y',strtotime($employee->date_hired))}}
                                        </td>
                                        <td>
                                            @if($employee->upload_pdf == null)
                                            <a href="resigned-employee/{{$employee->hr_user_id}}/{{$employee->last_name.', '.$employee->first_name}}" onclick="javascript:return confirm('Is {{$employee->last_name.', '.$employee->first_name}} already resigned?')"><button type="button" class="btn btn-gradient-danger btn-sm">Resign</button></a>
                                            @else 
                                            <button type="button" class="btn btn-danger btn-sm">Resigned</button></a>
                                           
                                            @endif
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
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    
    <!-- partial -->
</div>
<!-- main-panel ends -->
</div>

@endsection
