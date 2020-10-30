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
                        <h4 class="card-title">Letters Uploaded</h4>
                        <div class="table-responsive">
                            <table id='example' class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            Employee Name
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
                                            Date Uploaded
                                        </th>
                                        <th>
                                           Effective Date
                                        </th>
                                        <th>
                                            File
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
                                            {{$employee->position}}
                                        </td>
                                        <td>
                                            {{$employee->location_name}}
                                        </td>
                                        <td>
                                            {{date('M. d, Y',strtotime($employee->date_hired))}}
                                        </td>
                                        <td>
                                            {{date('M. d, Y',strtotime($letters[$key]->created_at))}}
                                        </td>
                                        <td>
                                            {{date('M. d, Y',strtotime($employee->resignation_date))}}
                                        </td>
                                        <td>
                                            <a href = "{{ asset(''.$letters[$key]->upload_pdf_url.'')}}" target="_">LETTER</a>
                                        </td>
                                        <td>
                                            @php    
                                                $key_proceed = array_search($employee->upload_pdf, array_column($resign_employee_id, 'upload_pdf_id'));
                                                // dump($key);
                                            @endphp
                                            @if($key_proceed === false)
                                                <button data-toggle="modal" data-target="#proceed{{$employee->upload_pdf}}" data-toggle="proceed" type="button" class="btn btn-success btn-sm">Accept</button>
                                                <button data-toggle="modal" data-target="#declined{{$employee->upload_pdf}}" data-toggle="declined" type="button" class="btn btn-danger btn-sm">Decline</button>
                                            @else
                                                @if($employee->clearance_id)
                                                <a href='{{ url('view-clearance/'.$employee->upload_id) }}' ><button  type="button" class="btn btn-success btn-sm">View Clearance</button></a>
                                           
                                                @else
                                                    <span>Sent on HR</span>
                                                @endif
                                            @endif
                                        </td>
                                        @include('proceed')
                                        @include('declined_resignation')
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
@endsection
