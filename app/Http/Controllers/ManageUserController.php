<?php

namespace App\Http\Controllers;
use App\Account;
use App\Employee;
use App\ManageUser;
use App\AssignHead;
use App\User;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    //
    public function view_list_accounts()
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();
        
        $accounts = Account::leftJoin('hr_portal.users as hr_users','accounts.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employee_data','hr_users.id','=','employee_data.user_id')
        ->leftJoin('hr_portal.department_employee as department_employee','employee_data.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('accounts.*','hr_users.*','employee_data.*','accounts.id as account_id','departments.name as department_name','hr_users.id as user_id')
        ->orderBy('hr_users.email','asc')
        ->get();
        
        $account_id = collect($accounts->pluck('user_id'))->toArray();

        $users = ManageUser::whereIn('approver_id',$account_id)
        ->leftJoin('hr_portal.users as hr_users','manage_users.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->select('employees.id','manage_users.approver_id','hr_users.name')
        ->orderBy('employees.last_name','asc')
        ->get();
        $user_id = collect($users->pluck('approver_id'))->toArray();
        return view('view_manage_accounts',array
        (
            'name' => $name,
            'accounts' => $accounts,
            'users' => $users,
            'user_id' => $user_id,
        ));
        
    }
    public function manage_account($account_id)
    {
        $account = Account::findOrFail($account_id);
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();
        
        $info = Account::leftJoin('hr_portal.users as hr_users','accounts.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employee_data','hr_users.id','=','employee_data.user_id')
        ->leftJoin('hr_portal.department_employee as department_employee','employee_data.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->where('hr_users.id',$account->user_id)
        ->select('accounts.*','hr_users.*','employee_data.*','accounts.id as account_id','departments.name as department_name')
        ->orderBy('hr_users.email','asc')
        ->first();
        
        $accounts = ManageUser::where('approver_id','=',$info->user_id)
        ->leftJoin('hr_portal.users as hr_users','manage_users.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','manage_users.id as manage_user_id')
        ->orderBy('employees.last_name','asc')
        ->get();

        $account_id = collect($accounts->pluck('user_id'))->toArray();
        $employees = User::leftJoin('hr_portal.employees as employee_data','users.id','=','employee_data.user_id')
        ->select('users.*','employee_data.*')
        ->where('employee_data.status','!=','Inactive')
        ->whereNotIn('employee_data.user_id',$account_id)
        ->where('employee_data.user_id','!=',$info->user_id)
        ->orderBy('users.email','asc')
        ->get();
        
        return view('manage_user',array
        (
            'name' => $name,
            'info' => $info,
            'account' => $account,
            'employees' => $employees,
            'accounts' => $accounts,
        ));
    }
    public function new_employee(Request $request,$account_id)
    {
        foreach($request->name as $name)
        {
            $manage_user = new Manageuser;
            $manage_user->user_id = $name;
            $manage_user->approver_id = $account_id;
            $manage_user->add_by = auth()->user()->id;
            $manage_user->save();
        }
        $request->session()->flash('status','New Employee has been added!');
        return back();
    }
    public function remove_employee(Request $request, $id)
    {
        $data = ManageUser::findOrfail($id);
        $data->delete();
        $request->session()->flash('status','Sucessfully Remove');
        return back();
    }
    public function getallapprover($user_id)
    {
        $foo = array();
        $self = Employee::findOrfail($user_id);

        $direct_reporting_to = AssignHead::where('employee_id',$user_id)->with([
            'employee_info',
            'approver_info',
            'approver_of_approver.approver_of_approver',
            ])
            ->where('head_id',3)
            ->orderBy('id','asc')->select('employee_id','employee_head_id','head_id')->first();
 
        $underInfo = AssignHead::where('employee_head_id',$user_id)
        
        ->with([
            'employee_info',
            'under_info.under_info',
            ]
            )
            ->where('head_id',3)
            ->orderBy('id','asc')->select('employee_id','employee_head_id','head_id')->get();
   
        if($underInfo != null)
        {   
            foreach($underInfo as $under)
            {
                // return $under;
                array_push($foo, (object)[
                    "id" => $under->employee_id,
                    "head_id" => $under->head_id,
                    'pid' => $under->employee_head_id,
                    'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                    'position' => $under->employee_info->position,
                    'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$under->employee_info->id.".png",

                ]);    
                if($under->under_info != null)
                {
                    foreach($under->under_info as $under)
                    {
                        array_push($foo, (object)[
                            "id" => $under->employee_id,
                            'pid' => $under->employee_head_id,
                            "head_id" => $under->head_id,
                            'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                            'position' => $under->employee_info->position,
                            'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$under->employee_info->id.".png",
                        ]); 
                        if($under->under_info != null)
                        {
                            foreach($under->under_info as $under)
                            {
                                array_push($foo, (object)[
                                    "id" => $under->employee_id,
                                    'pid' => $under->employee_head_id,
                                    "head_id" => $under->head_id,
                                    'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                                    'position' => $under->employee_info->position,
                                    'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$under->employee_info->id.".png",
                                ]); 
                                if($under->under_info != null)
                                {
                                    foreach($under->under_info as $under)
                                    {
                                        array_push($foo, (object)[
                                            "id" => $under->employee_id,
                                            'pid' => $under->employee_head_id,
                                            "head_id" => $under->head_id,
                                            'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                                            'position' => $under->employee_info->position,
                                            'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$under->employee_info->id.".png",
                                        ]); 
                                        
                                    }
                                }
                            }
                        }
                           
                    }
                }
            }
        }
        if($direct_reporting_to != null)
        {
            // return $direct_reporting_to;
            array_push($foo, (object)[
                'id' => $direct_reporting_to->employee_id,
                'pid' => $direct_reporting_to->employee_head_id,
                "head_id" => $direct_reporting_to->head_id,
                'name' => $direct_reporting_to->employee_info->first_name.' '.$direct_reporting_to->employee_info->last_name,
                'position' => $direct_reporting_to->employee_info->position,
                'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->employee_info->id.".png",
            ]); 
         
            if($direct_reporting_to->approver_of_approver != null)
            {
                array_push($foo, (object)[
                    'id' => $direct_reporting_to->approver_of_approver->employee_id,
                    'pid' => $direct_reporting_to->approver_of_approver->employee_head_id,
                    "head_id" => $direct_reporting_to->approver_of_approver->head_id,
                    'name' => $direct_reporting_to->approver_of_approver->employee_info->first_name." ".$direct_reporting_to->approver_of_approver->employee_info->last_name,
                    'position' => $direct_reporting_to->approver_of_approver->employee_info->position,
                    'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->approver_of_approver->employee_info->id.".png",
                ]); 
              
                if($direct_reporting_to->approver_of_approver->approver_of_approver != null)
                {
                    array_push($foo, (object)[
                        'id' => $direct_reporting_to->approver_of_approver->approver_of_approver->employee_id,
                        'pid' => $direct_reporting_to->approver_of_approver->approver_of_approver->employee_head_id,
                        'name' => $direct_reporting_to->approver_of_approver->approver_of_approver->employee_info->first_name." ".$direct_reporting_to->approver_of_approver->approver_of_approver->employee_info->last_name,
                        'position' => $direct_reporting_to->approver_of_approver->approver_of_approver->employee_info->position,
                        'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->approver_of_approver->approver_of_approver->employee_info->id.".png",
                    ]); 
                    
                    if($direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver != null)
                    {
                        array_push($foo, (object)[
                            'id' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->employee_id,
                            'pid' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->employee_head_id,
                            'name' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->employee_info->first_name." ".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->employee_info->last_name,
                            'position' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->employee_info->position,
                            'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->employee_info->id.".png",
                        ]); 
                      
                        if($direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver != null)
                        {
                            array_push($foo, (object)[
                                'id' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->employee_id,
                                'pid' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->employee_head_id,
                                'name' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->employee_info->first_name." ".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->employee_info->last_name,
                                'position' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->employee_info->position,
                                'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->employee_info->id.".png",
                            ]); 
                            array_push($foo, (object)[
                                'id' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->employee_head_id,
                                'pid' => null,
                                'name' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->approver_info->first_name." ".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->approver_info->last_name,
                                'position' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->approver_info->position,
                                'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_of_approver->approver_info->id.".png",
                            ]); 
            
                        }
                        else
                        {
                            array_push($foo, (object)[
                                'id' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->employee_head_id,
                                'pid' => null,
                                'name' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_info->first_name." ".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_info->last_name,
                                'position' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_info->position,
                                'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_of_approver->approver_info->id.".png",
                            ]); 
                        }
                    }
                    else
                    {
                        array_push($foo, (object)[
                            'id' => $direct_reporting_to->approver_of_approver->approver_of_approver->employee_head_id,
                            'pid' => null,
                            'name' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_info->first_name." ".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_info->last_name,
                            'position' => $direct_reporting_to->approver_of_approver->approver_of_approver->approver_info->position,
                            'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->approver_of_approver->approver_of_approver->approver_info->id.".png",
                        ]); 
                    }
                }
                else 
                {
                    array_push($foo, (object)[
                        'id' => $direct_reporting_to->approver_of_approver->employee_head_id,
                        'pid' => null,
                        'name' => $direct_reporting_to->approver_of_approver->approver_info->first_name." ".$direct_reporting_to->approver_of_approver->approver_info->last_name,
                        'position' => $direct_reporting_to->approver_of_approver->approver_info->position,
                        'img' => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->approver_of_approver->approver_info->id.".png",
                    ]);     
                }

            }
            else
            {
                array_push($foo, (object)[
                    "id" => $direct_reporting_to->employee_head_id,
                    "pid" => null,
                    "name" => $direct_reporting_to->approver_info->first_name." ".$direct_reporting_to->approver_info->last_name,
                    "position" => $direct_reporting_to->approver_info->position,
                    "img" => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$direct_reporting_to->approver_info->id.".png",
                ]); 
            }
        }
        else {
            array_push($foo, (object)[
                "id" => $self->id,
                "pid" => null,
                "name" => $self->first_name." ".$self->last_name,
                "position" => $self->position,
                "img" => "http://10.96.4.40:8441/hrportal/public/id_image/employee_image/".$self->id.".png",
            ]);
        }
        
        return $foo;
    }
}
