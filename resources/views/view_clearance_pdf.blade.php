
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" type="image/png" href="{{ asset('/images/icon.png')}}"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        table { 
            border-spacing: 0;
            border-collapse: collapse;
        }
        
        body{
            font-family: "Calibri", Helvetica, sans-serif;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <table width='100%' border='1'>
        <tr>
            <td rowspan='3' width='13%'>
                <img src='{{ asset('images/front-logo.png')}}' width='70px' style='padding:10px;'>
            </td>
            <td colspan='2'>
                <span style='padding-left:10px'>La Filipina Uy Gongco Group of Companies</span>
            </td>
        </tr>
        <tr >
            <td>
                <span style='padding-left:10px'>   Doc. No. LFHR – F – 020 Rev. No. 01</span>
            </td>
            <td>
                Effective date : May 16, 2014
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <span style='padding-left:10px'>  <b> @if(($employee->resign_type == "Resign")||($employee->resign_type == "Retirement"))CLEARANCE FORM @else ACCOUNTABILITY FORM @endif</b></span>
            </td>
        </tr>
    </table>
    <br>
    <table width='100%' border='1'>
        <tr>
            <td colspan='5'>
                <p style='text-indent: 10%;'> This is to inform you that <b><u>{{'   '.$employee->first_name.' '.$employee->last_name.'   '}}</u></b> is separated from <b><u>{{'   '.$employee->company_name.' / '.$employee->department_name.'   '}}</u></b> Department effective <b><u>{{date('M d, Y',strtotime($employee->effective_date))}}</u></b>. Please sign on the space provided below and indicate if subject employee has an outstanding obligation or accountability to settle with you.</p> 
            </td>
        </tr>
        <tr align='center'>
            <td width='10%;'>
                <b> DEPARTMENT </b>
            </td>
            <td width='30%;'>
                <b>SIGNATORY</b>
            </td>
            <td>
                <b>ACCOUNTABILITIES</b>
            </td>
            <td>
                <b>AMOUNT</b>
            </td>
            
            <td>
                <b>DATE VERIFIED</b>
            </td>
        </tr>
        @php
        $total_amount = 0 ;
        @endphp
        @foreach($department_signatories as $dep_signa)
        @php
            $keys = array_keys($signatories_id, $dep_signa->department_id);
        @endphp
        <tr >
            @if($dep_signa->department_name == null)
            <td style='' rowspan="{{count($keys)}}">DEPARTMENT/DIVISION HEAD</td>
            @else
            <td  rowspan="{{count($keys)}}">{{strtoupper($dep_signa->department_name)}}</td>
            @endif  
            @foreach($keys as $key)
            @if($key == $keys[array_key_first($keys)])
            @else
            <tr >
                @endif
                <td>{{$signatories[$key]->hr_user_name}} </td>
                @if($signatories[$key]->status == null)
                <td colspan=3 align='center'>Pending</td>
                @else
                <td>{!!nl2br($signatories[$key]->accountabilities)!!}</td>
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($signatories[$key]->amount,2)}}</td>
                <td>{{date('M. d, Y',strtotime($signatories[$key]->date_verified))}}</td>
                @endif
            </tr>
            @php
            $total_amount = $total_amount + $signatories[$key]->amount;
            @endphp
            @endforeach
        </tr>
        @endforeach
        <tr >
            <td colspan='3' align='center'>
                <b>TOTAL AMOUNT OF MONETARY ACCOUNTABILITIES</b>
            </td>
            <td colspan='2'>
                <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <b>{{number_format($total_amount,2)}}</b>
            </td>
        </tr>
        @if(($employee->resign_type == "Resign")||($employee->resign_type == "Retirement"))
        <tr>
            <td colspan='5' >
                <span><b>AUTHORIZATION:</b></span>
                <p style='text-indent: 10%;'> This is to authorize the company to deduct from my accrued wages, or any kind of compensation due to me, my outstanding
                    obligation or debt as may be reflected above. I understand that this is without prejudice to the results of an audit investigation if any, conducted after clearance has been granted.
                    <br>
                    <br>
                </p> 
                
                <div style='text-align:center;width:40%;padding-left:60%;'>
                    <img width='250px' height='60px' src='{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_signature/'.$employee_id->id.'.png'}}' style='position:absolute;z-index:-1;top:-50px;'>
                    <u style=''>{{'   '.strtoupper($employee->first_name).' '.strtoupper($employee->last_name).'   '}} </u><br>
                    Employee
                </div>
            </td>
        </tr>
        <tr>
            <td colspan='5' style=''>
                <span><b>ACKNOWLEDGEMENT:</b></span>
                <p style='text-indent: 10%;'> The employee concerned has paid the amount of <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <u><b> {{number_format($total_amount,2)}} </b> </u>
                    <br>
                </p> 
                <br>
                <div style='text-align:center;width:40%;padding-left:60%;'>
                    @if($signatories_finance != null)
                    <b><u style=''>___{{$signatories_finance->hr_user_name}}____</u></b><br>
                    @else
                    <b><u style=''>____________________________</u></b><br>
                    @endif
                    Accounting Dept. Head
                </div>
            </td>
        </tr>
        @else
        <tr>
            <td colspan='5' >
                <span><b>Note:</b></span>
                <p style='text-indent: 10%;'> This accountability form is not intended to clear the terminated employee. It is used only for the purpose of identifying employee's accountabilities to the Company before his pay and other benefits he is legally entitled to can be released.
                    <br>
                    <br>
                </p> 
               
            </td>
        </tr>
        @endif
        
        <tr>
            <td colspan='5' style=''>
                <br>
                <br>
                <br>
                <br>
                <div style='text-align:center'>
                        <b>____<u style=''>@if($account != null){{$account->name}} @else ____________________ @endif</u>____</b><br>
                    Head, Corporate Human Resources
                </div>
            </td>
        </tr>
    </table>
</body>
</html>