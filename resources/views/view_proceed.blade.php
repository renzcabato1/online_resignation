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
                        <h4 class="card-title">Resigned  Employee</h4>
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
                                            Last Day
                                        </th>
                                        <th>
                                            Date Uploaded
                                        </th>
                                        <th>
                                            File
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                    @php
                                        $key = array_search($employee->upload_pdf, array_column($letters_id, 'upload_pdf_id'));
                                        // dump($key);
                                    @endphp
                                    @if($key !== false)
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
                                            {{date('M. d, Y',strtotime($employee->upload_pdf_last_day))}}
                                        </td>
                                        <td>
                                            {{date('M. d, Y',strtotime($letters[$key]->created_at))}}
                                        </td>
                                        <td>
                                            <a href = "{{ asset(''.$letters[$key]->upload_pdf_url.'')}}" target="_"> <button type="button" class="btn btn-info btn-sm">LETTER</button></a>
                                        </td>
                                        <td>
                                            {{$employee->resign_employees_remarks}}
                                        </td>
                                        <td>
                                             
                                            @if($employee->clearance_id)
                                                <a href='{{ url('/view-clearance/'.$employee->upload_pdf_id) }}' ><button  type="button" class="btn btn-success btn-sm">View Clearance</button></a>
                                           
                                            @else
                                                <a href='{{ url('/to-clearance/'.$employee->upload_pdf_id) }}' ><button  type="button" class="btn btn-danger btn-sm">Proceed to Clearance</button></a>
                                            @endif
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
