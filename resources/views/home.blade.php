@extends('header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-home"></i>                 
                </span>
                Dashboard
            </h3>
            
        </div>
        <div class="row">
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white">
                    <div class="card-body">
                        <img src="images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                        <h4 class="font-weight-normal mb-3">Total Employee
                            <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{$employees}}</h2>
                        {{-- <h6 class="card-text">Increased by 0%</h6> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                        <img src="images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>                  
                        <h4 class="font-weight-normal mb-3">Monthly Resigned({{date('F Y')}})
                            <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{$resigned_monthy}}</h2>
                        {{-- <h6 class="card-text">Increased by 250%</h6> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                    <div class="card-body">
                        <img src="images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>                                    
                        <h4 class="font-weight-normal mb-3">Total Resigned
                            <i class="mdi mdi-diamond mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{count($total_resigned)}}</h2>
                        {{-- <h6 class="card-text">Increased by 5%</h6> --}}
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Separated <button onclick="exportF(this)" type="button" class="btn btn-gradient-primary btn-sm"><i class="mdi mdi mdi-export btn-icon-prepend"></i>Export</button></h4></h4>
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
                                            Location
                                        </th>
                                        <th>
                                            Position
                                        </th>
                                        <th>
                                            Date Effective
                                        </th>
                                        <th>
                                            Type
                                        </th>
                                        <th>
                                            Date Submitted
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($total_resigned as $total_resign)
                                    <tr>
                                        <td>
                                            <img src="{{'http://10.96.4.126:8668/storage/id_image/employee_image/'.$total_resign->user_info->id.'.png'}}" class="mr-2" alt="image">
                                            {{$total_resign->user_info->first_name}}  {{$total_resign->user_info->last_name}}
                                        </td>
                                        <td>
                                           {{($total_resign->user_info->companies != null) ? $total_resign->user_info->companies[0]->name : ""}}
                                        </td>
                                        <td>
                                            {{($total_resign->user_info->departments != null) ? $total_resign->user_info->departments[0]->name : ""}}
                                        </td>
                                        <td>
                                            {{($total_resign->user_info->locations != null) ? $total_resign->user_info->locations[0]->name : ""}}
                                        </td>
                                        <td>
                                            {{$total_resign->user_info->position}} 
                                        </td>
                                        {{-- <td>
                                            <a href = "{{ asset(''.$total_resign->letter_info[0]->upload_pdf_url.'')}}" target="_"> <button type="button" class="btn btn-info btn-sm">LETTER</button></a>
                                        </td> --}}
                                        <td>
                                            {{date('M. d, Y',strtotime($total_resign->last_day))}}
                                        </td>
                                        <td>
                                            {{$total_resign->type}} 
                                        </td>
                                        <td>
                                            {{date('M. d, Y',strtotime($total_resign->created_at))}}
                                        </td>
                                        <td>
                                            @if($total_resign->acceptance_info == null)
                                            <button  data-toggle="modal" data-target="#email{{$total_resign->id}}"  type="button" class="btn btn-sm btn-gradient-danger btn-icon-text">
                                                Pending for Acceptance
                                            </button>
                                            @else
                                            @if($total_resign->clearance_info == null)
                                            For Clearance
                                            @else
                                        
                                            @if(count($total_resign->clearance_info->clearance_signatories) == count($total_resign->clearance_info->clearance_signatories_count))
                                            <a href='{{ url('print-clearance-status/'.$total_resign->id) }}' target='_'><button type="button" class="btn btn-sm btn-gradient-success btn-icon-text">
                                                Cleared                                                                             
                                            </button>
                                            @else
                                            <a href='{{ url('print-clearance-status/'.$total_resign->id) }}' target='_'><button type="button" class="btn btn-sm btn-gradient-info btn-icon-text">
                                                Clearance                                                                             
                                            </button>
                                            @endif
                                        </a>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                
                                @include('manual_email')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function exportF(elem) 
        {
            
            var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
                var textRange; var j = 0;
                tab = document.getElementById('example');
                if (tab==null)
                {
                    return false;
                }
                if (tab.rows.length == 0) 
                {
                    return false;
                }
                
                for (j = 0 ; j < tab.rows.length ; j++)
                {
                    tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                }
                
                tab_text = tab_text + "</table>";
                tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
                tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
                tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
                
                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE ");
                
                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                    txtArea1.document.open("txt/html", "replace");
                    txtArea1.document.write(tab_text);
                    txtArea1.document.close();
                    txtArea1.focus();
                    sa = txtArea1.document.execCommand("SaveAs", true, "clearance_report.xls");
                }
                else        
                try {
                    var blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
                    window.URL = window.URL || window.webkitURL;
                    link = window.URL.createObjectURL(blob);
                    a = document.createElement("a");
                    if (document.getElementById("caption")!=null)
                    {
                        a.download=document.getElementById("caption").innerText;
                    }
                    else
                    {
                        a.download =  "clearance_report";
                    }
                    
                    a.href = link;
                    
                    document.body.appendChild(a);
                    
                    a.click();
                    
                    document.body.removeChild(a);
                } 
                catch (e)
                {
                }
                return false;
            }
            function email_manual(employee_id,letter_id)
            {
                document.getElementById("myDiv").style.display="block";
                $.ajax(
                {  //create an ajax request to load_page.php
                    type: "GET",
                    url: "{{ url('/manual-email') }}",            
                    data:
                    {
                        "employee_id" : employee_id,
                        "letter_id" : letter_id,
                    }     ,
                    dataType: "json",   //expect html to be returned
                    success: function(data)
                    {
                        console.log(data);

                        document.getElementById("info"+data.id).style.display="block";
                        document.getElementById("myDiv").style.display="none";
                    }
                    ,
                    error: function(e)
                    {
                        console.log(e);
                        document.getElementById("myDiv").style.display="none";
                    }
                }
                );
            }
        </script>
    </div>
</div>
@endsection
