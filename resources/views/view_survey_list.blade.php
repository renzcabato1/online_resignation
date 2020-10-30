@extends('header')
@section('content')

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Survey List</h4>
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
                                            Date Submitted
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($surveys as $survey)
                                    <tr>
                                        <td>
                                            {{$survey->first_name. " ". $survey->last_name}}
                                        </td>
                                        <td>
                                            {{$survey->company_name}}
                                        </td>
                                        <td>
                                            {{$survey->department_name}}
                                        </td>
                                        <td>
                                            {{$survey->position}}
                                        </td>
                                        <td>
                                            {{$survey->location_name}}
                                        </td>
                                        <td>
                                            {{date('M. d, Y',strtotime($survey->created_at))}}
                                        </td>
                                        <td>
                                            <button type="button" data-toggle="modal" data-target="#view_survey{{$survey->id}}" class="btn btn-gradient-success btn-sm">View Survey</button>
                                          @include('view_survey')
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
</div>
@endsection
