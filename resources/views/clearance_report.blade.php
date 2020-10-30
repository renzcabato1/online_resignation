@extends('header')
@section('content')
<style>
    input[type=date]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        display: none;
    }
</style>
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Clearance Report</h4>
                        
                        <form  method="GET" action="" onsubmit= "show()">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        From
                                        <input type="date" value='{{$from}}' class="form-control" name="from" id='from' required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        To
                                        <input type="date" value='{{$to}}' class="form-control" name="to" id='to' required>
                                        
                                    </div>
                                </div>
                                
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <br>
                                        <button type="submit" class="btn btn-info btn-fill">Generate</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
        @if($from != Null)
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Clearance Result <button onclick="exportF(this)" type="button" class="btn btn-gradient-primary btn-sm"><i class="mdi mdi mdi-export btn-icon-prepend"></i>Export</button></h4>
                        <div class="table-responsive">
                            <table id='clearance_info' class="table" border='1'>
                                <thead>
                                    <tr>
                                        <th>
                                            Last Name
                                        </th>
                                        <th>
                                            First Name
                                        </th>
                                        <th>
                                            M.I.
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
                                            Position Title
                                        </th>
                                        <th>
                                            Date of Upload of Resignation Letter
                                        </th>
                                        <th>
                                            Type
                                        </th>
                                        <th>
                                            Date of Effectivity
                                        </th>
                                        <th>
                                            Date of Approval of Immediate Superior
                                        </th>
                                        <th>
                                            Date Given to Department
                                        </th>
                                        
                                        <th>
                                            Turn Around Time (Submission of Employee to Approval of Immediate Superior)
                                        </th>
                                        <th >
                                            Department
                                        </th>
                                        <th >
                                            Name
                                        </th>
                                        <th >
                                            Date Verified
                                        </th>
                                        <th >
                                            Accountabilities
                                        </th>
                                        <th >
                                            Amount
                                        </th>
                                        <th >
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                 @foreach($clearance_employees as $clerance_info)
                                    @if(  ((date('Y-m-d',strtotime($clerance_info->upload_pdf->letter_info[0]->created_at))) >= $from) &&   ((date('Y-m-d',strtotime($clerance_info->upload_pdf->letter_info[0]->created_at))) <= $to))
                                    <tr>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            {{$clerance_info->upload_pdf->user_info->last_name}}
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            {{$clerance_info->upload_pdf->user_info->first_name}}
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            {{$clerance_info->upload_pdf->user_info->middle_initial}}
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            @if(count($clerance_info->upload_pdf->user_info->companies) > 0)
                                                {{$clerance_info->upload_pdf->user_info->companies[0]->name}}
                                            @endif
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            @if(count($clerance_info->upload_pdf->user_info->departments) > 0)
                                            {{$clerance_info->upload_pdf->user_info->departments[0]->name}}
                                            @endif
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            @if(count($clerance_info->upload_pdf->user_info->departments) > 0)
                                            {{$clerance_info->upload_pdf->user_info->departments[0]->name}}
                                            @endif
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            {{$clerance_info->upload_pdf->user_info->position}}
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            {{date('d-M-Y',strtotime($clerance_info->upload_pdf->letter_info[0]->created_at))}}
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            {{date('d-M-Y',strtotime($clerance_info->upload_pdf->last_day))}}
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            {{date('d-M-Y',strtotime($clerance_info->created_at))}}
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            {{date('d-M-Y',strtotime($clerance_info->clearance_info->created_at))}}
                                        </td>
                                        <td rowspan='{{count($clerance_info->clearance_info->clearance_signatories)}}'>
                                            @php
                                                $cDate = \Carbon\Carbon::parse(date('Y-m-d',strtotime($clerance_info->upload_pdf->letter_info[0]->created_at)));
                                            @endphp
                                            {{$cDate->diffInDays(date('Y-m-d',strtotime($clerance_info->created_at)))}} days
                                        </td>
                                            @foreach($clerance_info->clearance_info->clearance_signatories as $key => $signatories)
                                            @if($key != 0)
                                            <tr>
                                            @endif
                                                <td  >
                                                    @if($signatories->department_id == null)
                                                        DEPARTMENT/DIVISION HEAD
                                                    @else
                                                        {{$signatories->department_info->name}}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$signatories->user_info->name}}
                                                </td>
                                                <td>
                                                     @if($signatories->date_verified == null) Pending @else {{date('d-M-Y',strtotime($signatories->date_verified))}} @endif
                                                </td>
                                                <td>
                                                     @if($signatories->date_verified == null) Pending @else  {!!nl2br($signatories->accountabilities)!!}@endif
                                                </td>
                                                <td>
                                                     @if($signatories->date_verified == null) Pending @else  {{number_format($signatories->amount)}}@endif
                                                </td>
                                                <td>
                                                     @if($signatories->date_verified == null) Pending @else  {!!nl2br($signatories->remarks)!!}@endif
                                                </td>
                                               
                                             
                                            </tr>
                                            @endforeach
                                   @endif 
                                @endforeach
                            </tbody> --}}
                            <tbody>
                                @foreach($clearance_employees as $clerance_info)
                                   @if(  ((date('Y-m-d',strtotime($clerance_info->upload_pdf->letter_info[0]->created_at))) >= $from) &&   ((date('Y-m-d',strtotime($clerance_info->upload_pdf->letter_info[0]->created_at))) <= $to))
                                  
                                           @foreach($clerance_info->clearance_info->clearance_signatories as $key => $signatories)
                                           <tr>
                                               <td   >
                                                   {{$clerance_info->upload_pdf->user_info->last_name}}
                                               </td>
                                               <td   >
                                                   {{$clerance_info->upload_pdf->user_info->first_name}}
                                               </td>
                                               <td   >
                                                   {{$clerance_info->upload_pdf->user_info->middle_initial}}
                                               </td>
                                               <td   >
                                                   @if(count($clerance_info->upload_pdf->user_info->companies) > 0)
                                                       {{$clerance_info->upload_pdf->user_info->companies[0]->name}}
                                                   @endif
                                               </td>
                                               <td   >
                                                   @if(count($clerance_info->upload_pdf->user_info->departments) > 0)
                                                   {{$clerance_info->upload_pdf->user_info->departments[0]->name}}
                                                   @endif
                                               </td>
                                               <td   >
                                                   @if(count($clerance_info->upload_pdf->user_info->departments) > 0)
                                                   {{$clerance_info->upload_pdf->user_info->departments[0]->name}}
                                                   @endif
                                               </td>
                                               <td   >
                                                   {{$clerance_info->upload_pdf->user_info->position}}
                                               </td>
                                               <td   >
                                                   {{date('d-M-Y',strtotime($clerance_info->upload_pdf->letter_info[0]->created_at))}}
                                               </td>
                                               <td   >
                                                {{$clerance_info->upload_pdf->type}}
                                            </td>
                                               <td   >
                                                   {{date('d-M-Y',strtotime($clerance_info->upload_pdf->last_day))}}
                                               </td>
                                               <td   >
                                                   {{date('d-M-Y',strtotime($clerance_info->created_at))}}
                                               </td>
                                               <td   >
                                                   {{date('d-M-Y',strtotime($clerance_info->clearance_info->created_at))}}
                                               </td>
                                               <td   >
                                                   {{-- {{(($clerance_info->upload_pdf->letter_info[0]->created_at)->diffInDays($clerance_info))}} --}}
                                                   @php
                                                       $cDate = \Carbon\Carbon::parse(date('Y-m-d',strtotime($clerance_info->upload_pdf->letter_info[0]->created_at)));
                                                   @endphp
                                                   {{$cDate->diffInDays(date('Y-m-d',strtotime($clerance_info->created_at)))}} days
                                               </td>
                                               <td  >
                                                   @if($signatories->department_id == null)
                                                       DEPARTMENT/DIVISION HEAD
                                                   @else
                                                       {{$signatories->department_info->name}}
                                                   @endif
                                               </td>
                                               <td>
                                                   {{$signatories->user_info->name}}
                                               </td>
                                               <td>
                                                    @if($signatories->date_verified == null) Pending @else {{date('d-M-Y',strtotime($signatories->date_verified))}}@endif
                                               </td>
                                               <td>
                                                    @if($signatories->date_verified == null) Pending @else  {!!nl2br($signatories->accountabilities)!!}@endif
                                               </td>
                                               <td>
                                                    @if($signatories->date_verified == null) Pending @else  {{number_format($signatories->amount)}}@endif
                                               </td>
                                               <td>
                                                    @if($signatories->date_verified == null) Pending @else  {!!nl2br($signatories->remarks)!!}@endif
                                               </td>
                                              
                                            
                                           </tr>
                                           @endforeach
                                  @endif 
                               {{-- </tr> --}}
                               @endforeach
                           </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<script>
    function exportF(elem) {
        // var company_name =  document.getElementById('company_name').innerHTML;  
  
        var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
            var textRange; var j = 0;
            tab = document.getElementById('clearance_info');//.getElementsByTagName('table'); // id of table
            if (tab==null) {
                return false;
            }
            if (tab.rows.length == 0) {
                return false;
            }
            
            for (j = 0 ; j < tab.rows.length ; j++) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                //tab_text=tab_text+"</tr>";
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
            else                 //other browser not tested on IE 11
            //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
            try {
                var blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
                window.URL = window.URL || window.webkitURL;
                link = window.URL.createObjectURL(blob);
                a = document.createElement("a");
                if (document.getElementById("caption")!=null) {
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
            } catch (e) {
            }
            
            
            return false;
            //return (sa);
        }
    </script>
</div>
@endsection
