<?php

namespace App\Http\Controllers;
use App\Account;
use App\Employee;
use App\Role;
use App\User;
use App\UploadPdf;
use App\Email;
use App\ManageUser;
use Illuminate\Http\Request;
use App\Notifications\UploadNotif;
use GuzzleHttp\Client;
use App\Notifications\ApproveResignation;
use App\ResignEmployee;
use App\Approver;
use URL;
use App\Letter;
class AccountController extends Controller
{
    //
    public function view_users()
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();
        $roles = Role::orderBy('role_name','asc')->get();
        $role_array = collect($roles)->toArray();
        
        $accounts = Account::leftJoin('hr_portal.users as hr_users','accounts.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employee_data','hr_users.id','=','employee_data.user_id')
        ->leftJoin('hr_portal.department_employee as department_employee','employee_data.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('accounts.*','hr_users.*','employee_data.*','accounts.id as account_id','departments.name as department_name')
        ->get();
        
        $account_id = collect($accounts->pluck('user_id'))->toArray();
        
        $employees = User::leftJoin('hr_portal.employees as employee_data','users.id','=','employee_data.user_id')
        ->select('users.*','employee_data.*')
        ->where('employee_data.status','!=','Inactive')
        ->whereNotIn('employee_data.user_id',$account_id)
        ->orderBy('users.email','asc')
        ->get();
        
        return view('view_accounts',array
        (
            'accounts' => $accounts,
            'name' => $name,
            'roles' => $roles,
            'role_array' => $role_array,
            'employees' => $employees,
        ));
    }
    public function change_password(Request $request)
    {
        $this->validate(request(),[
            'password' => 'required|min:8|confirmed',
            ]    
        ); 
        $data =  User::find(auth()->user()->id);
        $data->password = bcrypt($request->input('password'));
        $data->save();
        $request->session()->flash('status','Your Password Successfully Changed!');
        return back();
    }
    public function new_account(Request $request)
    {
        $account = new Account;
        $account->user_id = $request->name;
        $account->status = "Active";
        $account->role_id = json_encode($request->roles);
        $account->save();
        $request->session()->flash('status','New Account Successfully added!');
        return back();
    }
    public function reset_password(Request $request,$id)
    {
        $data =  User::find($id);
        $data->password = bcrypt(12345678);
        $data->save();
        $request->session()->flash('status',$data->name.' new password : 12345678');
        return back();
    }
    public function edit_account(Request $request,$id)
    {
        $account = Account::findOrfail($id);
        $account->user_id = $request->name;
        $account->role_id = json_encode($request->roles);
        $account->save();
        $request->session()->flash('status','Successfully Changed!');
        return back();
    }
    public function resigned_employee(Request $request,$user_id,$name)
    {
        
        $user = User::findOrFail($user_id);
        $upload_pdf = new UploadPdf;
        $upload_pdf->user_id = $user_id;
        $upload_pdf->action_by = auth()->user()->id;
        $upload_pdf->save();
        // $user->notify(new UploadNotif($user));
        $request->session()->flash('status',$user->name.' can now upload the resignation letter.');
        return back();
        
    }
    public function getApprover()
    {
        
        $employees = User::leftJoin('hr_portal.employees as employee_data','users.id','=','employee_data.user_id')
        ->select('users.email','users.id','users.name')
        ->where('employee_data.status','!=','Inactive')
        ->orderBy('users.email','asc')
        ->get();
        
        foreach($employees as $employee)
        {
            $client = new Client();
            $result = $client->post('http://172.17.2.250:4849/TAWebAPI/EmployeeAPI/getApprovers', ['json' => ['Payload' =>  array("Username" => "admin", "Password" => '@pip@$$w0rd',"EmailAddress" => $employee->email,)]]);
            $datas = json_decode(($result->getBody()), true);
            $data = $datas['JsonData'];
            $dataFinal = json_decode($data, true);
            if($dataFinal['ResponseMessage'] != "Email Address not found")
            {
               
                    $superNew = new Approver;
                    $superNew->user_email  =$employee->email;
                    $superNew->user_id  =$employee->id;
                    
                    if (array_key_exists("SupervisorEmail",$dataFinal))
                    {
                        $superNew->supervisor_name  = $dataFinal['SupervisorName'];
                        $superNew->supervisor_email  = $dataFinal['SupervisorEmail'];
                        $data = User::where('email','=',$dataFinal['SupervisorEmail'])->first();
                        if($data != null)
                        {
                            $superNew->approver_id = $data->id;
                        }
                    }
                    
                    $superNew->save();
                
            }
            else
            {
                $email = new Email;
                $email->email = $employee->email;
                $email->name = $employee->name;
                $email->remarks = "This is email from HR Portal, No email address found at ESS";
                $email->save();
            }
        }
        
        
    }
    public function resignEmployee(Request $request)
    {
        // dd($request->all());
        $user = User::findOrFail($request->userid);
        $upload_pdf = new UploadPdf;
        $upload_pdf->user_id = $request->userid;
        $upload_pdf->action_by = auth()->user()->id;
        $upload_pdf->type = $request->type_resign;
        $upload_pdf->last_day = $request->effectivedate;
        $upload_pdf->save();
        $resign_data = $request->effectivedate;
        
        $attachment = $request->file('uploadFile');
        $original_name = $attachment->getClientOriginalName();
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/upload_pdf/', $name);
        $file_name = URL::asset('/upload_pdf/'.$name);
        $data = new Letter;
        $data->upload_pdf_url   = $file_name;
        $data->upload_pdf_name  = $original_name;
        $data->upload_pdf_id    = $upload_pdf->id;
        $data->save();
        
        $resign_employee = new ResignEmployee;
        $resign_employee->upload_pdf_id = $upload_pdf->id;
        $resign_employee->last_date = $request->effectivedate;
        $resign_employee->remarks = $request->remarks;
        $resign_employee->action_by = auth()->user()->id;
        $resign_employee->save();
        
        
        $employee_info = UploadPdf::where('upload_pdfs.id','=',$upload_pdf->id)
        ->leftJoin('hr_portal.users as hr_users','upload_pdfs.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('hr_users.name','hr_users.email','employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','upload_pdfs.*','upload_pdfs.created_at as upload_date')
        ->first();
        
        $accounts = Account::where('role_id', 'like', '%4%')
        ->leftJoin('hr_portal.users as users','accounts.user_id','=','users.id')
        ->select('users.*')
        ->get();
        
        // $employee_info->notify(new AcceptResignation($employee_info));
        // dd($employee_info);
        foreach($accounts as $account)
        {
            $account->notify(new ApproveResignation($employee_info,$account));
        }
        if(($request->type_resign == "Resign"))
        {
            $user->notify(new UploadNotif($user,$resign_data));
            $request->session()->flash('status',$user->name.' can now complete exit interview & personal contact details.');
        }
        else 
        {
            
            $request->session()->flash('status',$user->name.' is separated from the company. You may now start the clearance.');
        }
        return back();
    }
    public function enableAd(Request $request, $employeeID)
    {
        $employeeInfo = User::findOrfail($employeeID);
        $user = strstr($employeeInfo->email, '@', true);
        $adServer = "ldap://10.96.4.200";
        $username = 'dfs.bgc';
        $password = 'Welcome1';
        
        $ldap = ldap_connect($adServer);
        
        
        $ldaprdn = 'lfuggoc' . "\\" . $username;
        
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        
        $bind = @ldap_bind($ldap, $ldaprdn, $password);
        if ($bind) {
            $a = 1;
            $filter="(sAMAccountName=".$user.")";
            $result = ldap_search($ldap,"dc=LFUGGOC,dc=NET",$filter);
            $info = ldap_get_entries($ldap, $result);
            $dn=$info[0]["dn"];
            $ac = $info[0]["useraccountcontrol"][0];
            $disable=($ac | 2); // set all bits plus bit 1 (=dec2)
            $enable =($ac & ~2); // set all bits minus bit 1 (=dec2)
            
            $userdata=array();
            if ($a==1) $new=$enable; else $new=$disable; 
            
            
            $userdata["useraccountcontrol"][0]=$new;
            ldap_modify($ldap,$dn, $userdata);
            $result = ldap_search($ldap,"dc=LFUGGOC,dc=NET",$filter);
            $info = ldap_get_entries($ldap, $result);
            $ac = $info[0]["useraccountcontrol"][0];
            if (($ac & 2)==2) $status=0; else $status=1;
            ldap_close($ldap);
            $request->session()->flash('status',$employeeInfo->name.' enabled account.');
            return back();     
        }
        else {
            $msg = "Invalid email address / password";
            echo $msg;
        }
        
    }
    public function disableAD(Request $request, $employeeID)
    {
        $employeeInfo = User::findOrfail($employeeID);
        $user = strstr($employeeInfo->email, '@', true);
        $adServer = "ldap://10.96.4.200";
        $username = 'dfs.bgc';
        $password = 'Welcome1';
        
        $ldap = ldap_connect($adServer);
        
        $ldaprdn = 'lfuggoc' . "\\" . $username;
        
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        
        $bind = @ldap_bind($ldap, $ldaprdn, $password);
        if ($bind) {
            $a = 0;
            $filter="(sAMAccountName=".$user.")";
            $result = ldap_search($ldap,"dc=LFUGGOC,dc=NET",$filter);
            $info = ldap_get_entries($ldap, $result);
            $dn=$info[0]["dn"];
            $ac = $info[0]["useraccountcontrol"][0];
            $disable=($ac | 2); // set all bits plus bit 1 (=dec2)
            $enable =($ac & ~2); // set all bits minus bit 1 (=dec2)
            
            $userdata=array();
            if ($a==1) $new=$enable; else $new=$disable; 
            
            
            $userdata["useraccountcontrol"][0]=$new;
            ldap_modify($ldap,$dn, $userdata);
            $result = ldap_search($ldap,"dc=LFUGGOC,dc=NET",$filter);
            $info = ldap_get_entries($ldap, $result);
            $ac = $info[0]["useraccountcontrol"][0];
            if (($ac & 2)==2) $status=0; else $status=1;
            ldap_close($ldap);
            $request->session()->flash('status',$employeeInfo->name.' disabled account.');
            return back();     
        }
        else {
            $msg = "Invalid email address / password";
            echo $msg;
        }
        
    }
    public function resetPassword(Request $request,$employeeID)
    {
        $employeeInfo = User::findOrfail($employeeID);
        $user = strstr($employeeInfo->email, '@', true);
        $adServer = "ldap://10.96.4.20";
        $username = 'dfs.bgc';
        $password = 'Welcome1';
        
        $ldap = ldap_connect($adServer);
        
        $ldaprdn = 'lfuggoc' . "\\" . $username;
        
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        
        $bind = @ldap_bind($ldap, $ldaprdn, $password);
        if ($bind) {
            $filter="(sAMAccountName=".$user.")";
            $result = ldap_search($ldap,"dc=LFUGGOC,dc=NET",$filter);
            $info = ldap_get_entries($ldap, $result);
            dd($info);
            
        }
        else {
            $msg = "Invalid email address / password";
            echo $msg;
        }
        
    }
}

