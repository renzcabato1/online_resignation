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
                                            Location
                                        </th>
                                        <th>
                                            Date Hired
                                        </th>
                                        <th>
                                           Status
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
                                            {{$employee->company_name}}
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
                                            @php
                                            $key = array_search($employee->user_id, array_column($upload_pdf_id, 'user_id'));
                                            // dump($key);
                                            @endphp
                                            @if($key !== false)
                                            <label  class="text-danger">Separated <br> {{date('M. d, Y',strtotime($upload_pdf[$key]->last_day))}}</label>
                                            @else 
                                           Active
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                            $key = array_search($employee->user_id, array_column($upload_pdf_id, 'user_id'));
                                            // dump($key);
                                            @endphp
                                            @if($key !== false)
                                            {{$upload_pdf[$key]->type}}
                                            @else 
                                            <a  href="#resign" data-toggle="modal"   onclick='openModal({{$employee->user_id}},"{{$employee->first_name}} {{$employee->last_name}}")'><button type="button" class="btn btn-gradient-danger btn-sm">Upload</button></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @include('upload_resignation')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
             function openModal(userId,userName)
                    {
                        
                        document.getElementById("user_id").value = userId;
                        $("#uploadfile").val('');
                        $("#remarks").val('');
                        $("#effectivedate").val('');
                    }
        </script>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    
    <!-- partial -->
</div>
<!-- main-panel ends -->
</div>

@endsection
