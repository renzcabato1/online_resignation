<div id="view_survey{{$survey->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style='width:1200px;'>
        <!-- Modal content-->
        <div class="modal-content" >
            {{-- <form  method='POST' action='survey-update/{{$survey->survey_id}}' enctype="multipart/form-data" onsubmit='show()' > --}}
                <div class="modal-body">
                    {{ csrf_field() }}
                    <table width='100%' border='1'>
                        <tr>
                            <td rowspan='3' width='13%'>
                                <img src='{{ asset('images/front-logo.png')}}' style='width:90px;height:90px;' >
                            </td>
                            <td colspan='2'>
                                <span style='padding-left:10px'>La Filipina Uy Gongco Group of Companies</span>
                            </td>
                        </tr>
                        <tr style='font-size:12px;' >
                            <td>
                                <span style='padding-left:10px'>  Rev. No. 00</span>
                            </td>
                            <td>
                                Effective date : April 5, 2019
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2'>
                                <span style='padding-left:10px'>  <b>EXIT INTERVIEW FORM</b></span>
                            </td>
                        </tr>
                    </table>
                    <table width='100%' border='1'>
                        <tr align='center' style='color:white;background:#385524;'>
                            <td>
                                EMPLOYEE INFORMATION
                            </td>
                        </tr>
                    </table>
                    <table width='100%' border='1'>
                        <tr >
                            <td>
                                Position Title<br>
                                {{$survey->position}}
                            </td>
                            <td>
                                Department<br>
                                {{$survey->department_name}}
                            </td>
                            <td>
                                Company<br>
                                {{$survey->company_name}}
                            </td>
                        </tr>
                        <tr >
                            <td>
                                Location / Work Sitee<br>
                                {{$survey->location_name}}
                            </td>
                            <td>
                                No. of months / years worked with the company<br>
                                @php
                                $diff = strtotime(date('Y-m-d'))-strtotime($survey->date_hired);
                                $year = floor($diff/ (365*60*60*24));
                                $months = floor(($diff - $year * 365*60*60*24) / (30*60*60*24));
                                $diff_birth = strtotime(date('Y-m-d'))-strtotime($survey->birthdate);
                                $year_birth = floor($diff_birth/ (365*60*60*24));
                                @endphp
                                {{$year . ' Year/s and '. $months .' Month/s'}}
                            </td>
                            <td>
                                Age<br>
                                {{number_format($year_birth)}}
                            </td>
                        </tr>
                        <tr >
                            <td colspan='3'>
                                
                                Primary reason for leaving:<br>
                                <div class='row'>
                                    <div class='col-md-2'>
                                        <input type='radio' name = 'primary_reason' value='Work Abroad'  {{ ( $survey->primary_reason == 'Work Abroad') ? 'checked' : '' }} selected disabled>
                                        Work (abroad)
                                    </div>
                                    <div class='col-md-2'>
                                        <input type='radio' name = 'primary_reason' value='Work (other co.)'  {{ ( $survey->primary_reason == 'Work (other co.)') ? 'checked' : '' }}  disabled>
                                        Work (other co.)
                                    </div>
                                    <div class='col-md-3'>
                                        <input type='radio' name = 'primary_reason' value='Personal /Family Matter'  {{ ( $survey->primary_reason == 'Personal /Family Matter') ? 'checked' : '' }}  disabled>
                                        Personal /Family Matter
                                    </div>
                                    <div class='col-md-3'>
                                        <input type='radio' name = 'primary_reason' value='Migration/Relocation'  {{ ( $survey->primary_reason == 'Migration/Relocation') ? 'checked' : '' }}  disabled>
                                        Migration/Relocation
                                    </div>
                                    <div class='col-md-2'>
                                        <input type='radio' name = 'primary_reason' value='Others' {{ ( $survey->primary_reason == 'Others') ? 'checked' : '' }}  disabled>
                                        Others
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr >
                            <td colspan='3'>
                                If primary reason is others, please indicate reason:<br>
                                <div class='row'>
                                    <div class='col-md-12'>
                                    <textarea name= 'others_primary' style='width:100%;' readonly>{{$survey->other_primary}}</textarea>
                                    </div>
                                    
                                </div>
                            </td>
                        </tr>
                        <tr >
                            <td colspan='3'>
                                If primary reason is work, please indicate main consideration:<br>
                                <div class='row'>
                                    <div class='col-md-2'>
                                        <input type='radio' name = 'work_reason' value='Salary' {{ ( $survey->work_reason == 'Salary') ? 'checked' : '' }}  disabled>
                                        Salary
                                    </div>
                                    <div class='col-md-2'>
                                        <input type='radio' name = 'work_reason' value='Career devt.' {{ ( $survey->work_reason == 'Career devt.') ? 'checked' : '' }} disabled>
                                        Career dev't.
                                    </div>
                                    <div class='col-md-3'>
                                        <input type='radio' name = 'work_reason' value='Personal /Family Matter' {{ ( $survey->work_reason == 'Personal /Family Matter') ? 'checked' : '' }} disabled>
                                        Personal /Family Matter
                                    </div>
                                    <div class='col-md-3'>
                                        <input type='radio' name = 'work_reason' value='Migration/Relocation' {{ ( $survey->work_reason == 'Migration/Relocatio') ? 'checked' : '' }} disabled>
                                        Location
                                    </div>
                                    <div class='col-md-2'>
                                        <input type='radio' name = 'work_reason' value='Others' {{ ( $survey->work_reason == 'Others') ? 'checked' : '' }} disabled>
                                        Others
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr >
                            <td colspan='3'>
                                If primary reason is others, please indicate reason:<br>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <textarea name= 'work_remarks' style='width:100%;' readonly>{{$survey->work_remarks}}</textarea>
                                    </div>
                                    
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table border='1'>
                        <tr align='center' style='color:white;background:#385524;'>
                            <td >
                                STATEMENTS
                            </td>
                            <td >
                                RATING
                            </td>
                            <td >
                                COMMENTS
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%'   align='left'>
                                1. My immediate superior regularly gives me feedback and coaching.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="1_1">1 Strongly Disagree<br />
                                            <input type='radio' id='1_1' name = 'one' value='1_1' {{ ( $survey->one == '1_1') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="1_2">2 Disagree<br />
                                            <input type='radio' id='1_2' name = 'one' value='1_2' {{ ( $survey->one == '1_2') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="1_3">3 Agree<br />
                                            <input type='radio' name = 'one' id='1_3' value='1_3' {{ ( $survey->one == '1_3') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="1_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'one' id='1_4' value='1_4' {{ ( $survey->one == '1_4') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_1' style='width:100%;'>{{$survey->comment_1}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%'  align='left'>
                                2. My team works well together and promotes a harmonious and positive working environment for me.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="2_1">1 Strongly Disagree<br />
                                            <input type='radio' id='2_1' name = 'two' value='2_1' {{ ( $survey->two == '2_1') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="2_2">2 Disagree<br />
                                            <input type='radio' id='2_2' name = 'two' value='2_2' {{ ( $survey->two == '2_2') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="2_3">3 Agree<br />
                                            <input type='radio' id='2_3' name = 'two' value='2_3' {{ ( $survey->two == '2_3') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="2_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'two' id='2_4' value='2_4' {{ ( $survey->two == '2_4') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_2' style='width:100%;'>{{$survey->comment_2}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%' align='left'>
                                3. My job responsibilities and performance expectations are clear.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="3_1">1 Strongly Disagree<br />
                                            <input type='radio' id='3_1' name = 'three' value='3_1' {{ ( $survey->three == '3_1') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="3_2">2 Disagree<br />
                                            <input type='radio' id='3_2' name = 'three' id='3_2' {{ ( $survey->three == '3_2') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="3_3">3 Agree<br />
                                            <input type='radio' id='3_3' name = 'three' value='3_3' {{ ( $survey->three == '3_3') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="3_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'three' id='3_4' value='3_4' {{ ( $survey->three == '3_4') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_3' style='width:100%;'>{{$survey->comment_3}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%' align='left'>
                                4. My job responsibilities and performance expectations are clear.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="4_1">1 Strongly Disagree<br />
                                            <input type='radio' id='3_1' name = 'four' value='4_1'  {{ ( $survey->four == '4_1') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="4_2">2 Disagree<br />
                                            <input type='radio' id='3_2' name = 'four' id='4_2' {{ ( $survey->four == '4_2') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="4_3">3 Agree<br />
                                            <input type='radio' id='4_3' name = 'four' value='4_3' {{ ( $survey->four == '4_3') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="4_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'four' id='4_4' value='4_4' {{ ( $survey->four == '4_4') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_4' style='width:100%;'>{{$survey->comment_4}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%' align='left' >
                                5. The company provides trainings and other opportunities for learning and development.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="5_1">1 Strongly Disagree<br />
                                            <input type='radio' id='5_1' name = 'fifth' value='5_1' {{ ( $survey->five == '5_1') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="5_2">2 Disagree<br />
                                            <input type='radio' id='5_2' name = 'fifth' id='5_2'  {{ ( $survey->five == '5_2') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="5_3">3 Agree<br />
                                            <input type='radio' id='5_3' name = 'fifth' value='5_3'  {{ ( $survey->five == '5_3') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="5_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'fifth' id='5_4' value='5_4'  {{ ( $survey->five == '5_4') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_5' style='width:100%;'>{{$survey->comment_5}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%' align='left'>
                                6. The company's systems and processes are clear and efficient.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="6_1">1 Strongly Disagree<br />
                                            <input type='radio' id='6_1' name = 'six' value='6_1' {{ ( $survey->six == '6_1') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="6_2">2 Disagree<br />
                                            <input type='radio' id='6_2' name = 'six' id='6_2' {{ ( $survey->six == '6_2') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="6_3">3 Agree<br />
                                            <input type='radio' id='6_3' name = 'six' value='6_3' {{ ( $survey->six == '6_3') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="6_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'six' id='6_4' value='6_4' {{ ( $survey->six == '6_4') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_6' style='width:100%;'>{{$survey->comment_6}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%' align='left'>
                                7. The company provides a competitive compensation and benefits package.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="7_1">1 Strongly Disagree<br />
                                            <input type='radio' id='7_1' name = 'seven' value='7_1' {{ ( $survey->seven == '7_1') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="7_2">2 Disagree<br />
                                            <input type='radio' id='7_2' name = 'seven' id='7_2' {{ ( $survey->seven == '7_2') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="7_3">3 Agree<br />
                                            <input type='radio' id='7_3' name = 'seven' value='7_3' {{ ( $survey->seven == '7_3') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="7_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'seven' id='7_4' value='7_4' {{ ( $survey->seven == '7_4') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_7' style='width:100%;'>{{$survey->comment_7}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%' align='left' >
                                8. The company's culture is generally positive.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="8_1">1 Strongly Disagree<br />
                                            <input type='radio' id='8_1' name = 'eight' value='8_1' {{ ( $survey->eight == '8_1') ? 'checked' : '' }} disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="8_2">2 Disagree<br />
                                            <input type='radio' id='8_2' name = 'eight' id='8_2' {{ ( $survey->eight == '8_2') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="8_3">3 Agree<br />
                                            <input type='radio' id='8_3' name = 'eight' value='8_3' {{ ( $survey->eight == '8_3') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="8_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'eight' id='8_4' value='8_4'  {{ ( $survey->eight == '8_4') ? 'checked' : '' }} readonly>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_8' style='width:100%;'>{{$survey->comment_8}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%' align='left'>
                                9. The company's culture is generally positive.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="9_1">1 Strongly Disagree<br />
                                            <input type='radio' id='9_1' name = 'nine' value='9_1' {{ ( $survey->nine == '9_1') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="9_2">2 Disagree<br />
                                            <input type='radio' id='9_2' name = 'nine' id='9_2' {{ ( $survey->nine == '9_2') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="9_3">3 Agree<br />
                                            <input type='radio' id='9_3' name = 'nine' value='9_3' {{ ( $survey->nine == '9_3') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="9_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'nine' id='9_4' value='9_4' {{ ( $survey->nine == '9_4') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_9' style='width:100%;'>{{$survey->comment_9}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%' align='left'>
                                10. The company's leaders display good leadership qualities and expertise in their field.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="10_1">1 Strongly Disagree<br />
                                            <input type='radio' id='10_1' name = 'ten' value='10_1' {{ ( $survey->ten == '10_1') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="10_2">2 Disagree<br />
                                            <input type='radio' id='10_2' name = 'ten' id='10_2' {{ ( $survey->ten == '10_2') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="10_3">3 Agree<br />
                                            <input type='radio' id='10_3' name = 'ten' value='10_3' {{ ( $survey->ten == '10_3') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="10_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'ten' id='10_4' value='10_4' {{ ( $survey->ten == '10_4') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_10' style='width:100%;'>{{$survey->comment_10}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                            <td width='20%' align='left' >
                                11. I am aware of and appreciate the company's engagement programs.
                            </td>
                            <td width='60%'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <label for="11_1">1 Strongly Disagree<br />
                                            <input type='radio' id='11_1' name = 'eleven' value='11_1' {{ ( $survey->eleven == '11_1') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="11_2">2 Disagree<br />
                                            <input type='radio' id='11_2' name = 'eleven' value='11_2' {{ ( $survey->eleven == '11_2') ? 'checked' : '' }}   disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label for="11_3">3 Agree<br />
                                            <input type='radio' id='11_3' name = 'eleven' value='11_3' {{ ( $survey->eleven == '11_3') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="11_4">4 Strongly Agree<br />
                                            <input type='radio' name = 'eleven' id='11_4' value='11_4' {{ ( $survey->eleven == '11_4') ? 'checked' : '' }}  disabled>
                                        </label>
                                    </div>
                                    
                                </div>
                            </td>
                            <td width='20%'>
                                <textarea name='comment_11' style='width:100%;' readonly>{{$survey->comment_11}}</textarea>
                            </td>
                        </tr>
                        <tr align='center' >
                                <td width='20%' align='left' >
                                    12. I would recommend this company to others exploring career opportunities.
                                </td>
                                <td width='60%'>
                                    <div class='row'>
                                        <div class='col-md-4'>
                                            <label for="12_1">1 Strongly Disagree<br />
                                                <input type='radio' id='12_1' name = 'twelve' value='12_1' {{ ( $survey->twelve == '12_1') ? 'checked' : '' }}  disabled>
                                            </label>
                                        </div>
                                        <div class='col-md-3'>
                                            <label for="12_2">2 Disagree<br />
                                                <input type='radio' id='12_2' name = 'twelve' value='12_2' {{ ( $survey->twelve == '12_2') ? 'checked' : '' }}  disabled>
                                            </label>
                                        </div>
                                        <div class='col-md-2'>
                                            <label for="12_3">3 Agree<br />
                                                <input type='radio' id='12_3' name = 'twelve' value='12_3' {{ ( $survey->twelve == '12_3') ? 'checked' : '' }}  disabled>
                                            </label>
                                        </div>
                                        <div class='col-md-3'>
                                            <label for="12_4">4 Strongly Agree<br />
                                                <input type='radio' name = 'twelve' id='12_4' value='12_4' {{ ( $survey->twelve == '12_4') ? 'checked' : '' }}  readonly>
                                            </label>
                                        </div>
                                        
                                    </div>
                                </td>
                                <td width='20%'>
                                    <textarea name='comment_12' style='width:100%;' readonly>{{$survey->comment_12}}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='3'>
                                        Other suggestions for improvement:<br>
                                    <textarea name='other_suggestion' style='width:100%;' readonly>{{$survey->other_suggestion}}</textarea>
                                </td>
                            </tr>
                            <tr align='center'>
                                <td colspan='3'>
                                        Thank you for your honest feedback. Trust that all your replies shall be treated with outmost confidentiality. Should the results of this survey be reported, responses will be generalized. Respondents will not be identified individually.
                                </td>
                            </tr>
                
                </div>
            </form>
        </div>
    </div>
</div>