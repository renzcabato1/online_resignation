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
                        <h4 class="card-title">Survey Report</h4>
                        
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
                        <h4 class="card-title">Survey Result <button onclick="exportF(this)" type="button" class="btn btn-gradient-primary btn-sm"><i class="mdi mdi mdi-export btn-icon-prepend"></i>Export</button></h4>
                        <div class="table-responsive">
                            <table id='survey_view' class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>
                                            Name
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
                                            No. of months / years worked with the company
                                        </th>
                                        <th>
                                            Effectivity Date
                                        </th>
                                        <th>
                                            Age
                                        </th>
                                        <th>
                                            Primary Reason for leaving
                                        </th>
                                        <th>
                                            If primary reason is others, please indicate reason
                                        </th>
                                        <th>
                                            If primary reason is work, please indicate main consideration
                                        </th>
                                        <th>
                                            If primary reason is others, please indicate reason:
                                        </th>
                                        
                                        <th>
                                            1. My immediate superior regularly gives me feedback and coaching.	
                                            
                                        </th>
                                        <th>
                                            Remarks
                                            
                                        </th>
                                        <th>
                                            2. My team works well together and promotes a harmonious and positive working environment for me.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            3. My job responsibilities and performance expectations are clear.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            4. My job responsibilities and performance expectations are clear.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            5. The company provides trainings and other opportunities for learning and development.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            6. The company's systems and processes are clear and efficient.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            7. The company provides a competitive compensation and benefits package.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        
                                        <th>
                                            8. The company's culture is generally positive.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            9. The company's culture is generally positive.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            10. The company's leaders display good leadership qualities and expertise in their field.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            11. I am aware of and appreciate the company's engagement programs.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            12. I would recommend this company to others exploring career opportunities.
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                        <th>
                                            Other suggestions for improvement
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($surveys as $survey)
                                    @php
                                    $diff = strtotime($survey->survey_created_at)-strtotime($survey->date_hired);
                                    $year = floor($diff/ (365*60*60*24));
                                    $months = floor(($diff - $year * 365*60*60*24) / (30*60*60*24));
                                    $diff_birth = strtotime($survey->survey_created_at)-strtotime($survey->birthdate);
                                    $year_birth = floor($diff_birth/ (365*60*60*24));
                                    @endphp
                                    <tr>
                                        <td>
                                            {{$survey->first_name.' '.$survey->last_name}}
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
                                            {{$year . ' Year/s and '. $months .' Month/s'}}
                                        </td>
                                        <td>
                                            {{date('d-M-Y',strtotime($survey->effectivity_date))}}
                                        </td>
                                        <td>
                                            {{number_format($year_birth)}}
                                        </td>
                                        <td>
                                            {{$survey->primary_reason}} 
                                        </td>
                                        <td>
                                            {{$survey->other_primary}} 
                                        </td>
                                        <td>
                                            {{$survey->work_reason}} 
                                        </td>
                                        <td>
                                            {{$survey->work_remarks}} 
                                        </td>
                                        <td>
                                            {{substr($survey->one, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_1}} 
                                        </td>
                                        
                                        <td>
                                            {{substr($survey->two, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_2}} 
                                        </td>
                                        <td>
                                            {{substr($survey->three, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_3}} 
                                        </td>
                                        <td>
                                            {{substr($survey->four, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_4}} 
                                        </td>
                                        <td>
                                            {{substr($survey->five, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_5}} 
                                        </td>
                                        <td>
                                            {{substr($survey->six, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_6}} 
                                        </td>
                                        <td>
                                            {{substr($survey->seven, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_7}} 
                                        </td>
                                        <td>
                                            {{substr($survey->eight, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_8}} 
                                        </td>
                                        <td>
                                            {{substr($survey->nine, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_9}} 
                                        </td>
                                        <td>
                                            {{substr($survey->ten, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_10}} 
                                        </td>
                                        <td>
                                            {{substr($survey->eleven, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_11}} 
                                        </td>
                                        <td>
                                            {{substr($survey->twelve, -1)}} 
                                        </td>
                                        <td>
                                            {{$survey->comment_12}} 
                                        </td>
                                        <td>
                                            {{$survey->other_suggestion}} 
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
        @endif
    </div>
</div>
<script>
    function exportF(elem) {
        // var company_name =  document.getElementById('company_name').innerHTML;  
  
        var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
            var textRange; var j = 0;
            tab = document.getElementById('survey_view');//.getElementsByTagName('table'); // id of table
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
                sa = txtArea1.document.execCommand("SaveAs", true, "survey_report.xls");
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
                    a.download =  "survey_report";
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
@endsection
