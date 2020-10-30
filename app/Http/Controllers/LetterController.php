<?php

namespace App\Http\Controllers;
use App\Letter;
use App\Account;
use App\Employee;
use App\ManageUser;
use App\CopyEmail;
use App\User;
use App\UploadPdf;
use App\ResignEmployee;
use Illuminate\Http\Request;
use App\Notifications\ApproveResignation;
use App\Notifications\AcceptResignation;
use App\Notifications\DeclinedResignation;
use App\Notifications\UploadLetter;
use App\Notifications\ChangeEffectiveDateV;
use App\Notifications\ChangeEffectiveDateSupervisorV;
use App\Notifications\ApEmailNotif;

class LetterController extends Controller
{
    //
    public function view_letters()
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();

        $employees = ManageUser::where('approver_id','=',auth()->user()->id)
        ->leftJoin('hr_portal.users as hr_users','manage_users.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->leftJoin('upload_pdfs','hr_users.id','=','upload_pdfs.user_id')
        ->leftJoin('clearances','upload_pdfs.id','=','clearances.upload_pdf_id')
        ->leftJoin('personal_infos','upload_pdfs.user_id','=','personal_infos.user_id')
        ->where('upload_pdfs.cancel','=',null)
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','manage_users.id as manage_user_id','upload_pdfs.id as upload_pdf','hr_users.id as hr_user_id','clearances.id as clearance_id','clearances.upload_pdf_id as upload_id','personal_infos.last_date as resignation_date')
        ->orderBy('employees.last_name','asc')
        ->get();

        $pdf_id = collect($employees->pluck('upload_pdf'))->toArray(); 
        $letters = Letter::whereIn('upload_pdf_id',$pdf_id)
        ->orderBy( 'id','desc')
        ->get();
    
        $letters_id = collect($letters)->toArray(); 
        // dd($letters_id);
        $resign_employee = ResignEmployee::get();
        // dd($letters_id);
        $resign_employee_id = collect($resign_employee)->toArray(); 
        return view('upload_letter',array(
            'employees' => $employees,
            'name' => $name,
            'letters' => $letters,
            'letters_id' => $letters_id,
            'resign_employee' => $resign_employee,
            'resign_employee_id' => $resign_employee_id,
        ));
    }
    public function proceed (Request $request,$user_id)
    {
        // dd($user_id);
       
      
        $resign_employee = new ResignEmployee;
        $resign_employee->upload_pdf_id = $user_id;
        $resign_employee->remarks = $request->remarks;
        $resign_employee->action_by = auth()->user()->id;
        $resign_employee->save();
         
        $employee = Uploadpdf::where('id',$user_id)->first();
      
        if($employee->last_day != $request->last_day)
        {
        
            $user = UploadPdf::where('upload_pdfs.id',$user_id)
            ->leftJoin('hr_portal.users as users_a','upload_pdfs.user_id','=','users_a.id')
            ->select('users_a.*')
            ->first();
            // $user->notify(new ChangeEffectiveDateV($user,$employee,$request));
            $supervisors = ManageUser::where('manage_users.user_id',$user->id)
            ->leftJoin('hr_portal.users as users_a','manage_users.approver_id','=','users_a.id')
            ->select('users_a.*')
            ->get();
            // $supervisors = User::where('email','=','renzchristian.cabato@lafilgroup.com')->get();

            foreach($supervisors as $supervisor)
            {
                // $supervisor->notify(new ChangeEffectiveDateSupervisorV($supervisor,$employee,$request,$user));
            }

            $employee->last_day = $request->last_day;
            $employee->save();
        }

        $employee_info = UploadPdf::where('upload_pdfs.id','=',$user_id)
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
    
        $copy_emails = CopyEmail::leftJoin('hr_portal.users as users','copy_emails.user_id','=','users.id')
        ->select('users.*')
        ->get();
        // dd($copy_emails);
        foreach($copy_emails as $copy_email)
        {
            // dd($copy_email);
            $copy_email->notify(new ApEmailNotif($employee_info,$copy_email,$request));
        }
        $accounts = Account::where('role_id', 'like', '%4%')
        ->leftJoin('hr_portal.users as users','accounts.user_id','=','users.id')
        ->select('users.*')
        ->get();

        // $employee_info->notify(new AcceptResignation($employee_info));
        // dd($employee_info);
        foreach($accounts as $account)
        {
            // $account->notify(new ApproveResignation($employee_info,$account));
        }

        $request->session()->flash('status','Successfully sent to hr');
        return back();
    }
    public function view_resigned()
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();

        $employees = ResignEmployee::
        leftJoin('upload_pdfs','resign_employees.upload_pdf_id','=','upload_pdfs.id')
        ->leftJoin('hr_portal.users as hr_users','upload_pdfs.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->leftJoin('clearances','upload_pdfs.id','clearances.upload_pdf_id')
        ->where('upload_pdfs.cancel','=',null)
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','upload_pdfs.id as upload_pdf','upload_pdfs.last_day as upload_pdf_last_day','hr_users.id as hr_user_id','resign_employees.remarks as resign_employees_remarks','resign_employees.id as resign_employees_id'
        ,'resign_employees.status as resign_employees_status','resign_employees.upload_pdf_id as upload_pdf_id','clearances.id as clearance_id')
        ->orderBy('employees.last_name','asc')
        ->get();
        
        $pdf_id = collect($employees->pluck('upload_pdf'))->toArray(); 
        $letters = Letter::whereIn('upload_pdf_id',$pdf_id)
        ->orderBy( 'id','desc')
        ->get();
        $letters_id = collect($letters)->toArray(); 
        // dd($letters_id);
        return view('view_proceed',array(
            'employees' => $employees,
            'name' => $name,
            'letters' => $letters,
            'letters_id' => $letters_id,
        ));
    }
    public function proceed_to_clearance (Request $request, $id)
    {
        $resign_employee = ResignEmployee::findOrfail($id);
        $resign_employee->status = 1;
        $resign_employee->employee_id = $request->employee_id;
        $resign_employee->last_date = $request->last_date;
        $resign_employee->proceed_by = auth()->user()->id;
        $resign_employee->save();
        $request->session()->flash('status','Sucessfully Proceed to Clearance');
        return back();
    }
    public function declined (Request $request,$user_id)
    {
       
        $cancel_rl = UploadPdf::findOrfail($user_id);
        $cancel_rl->cancel  = 1;
        $cancel_rl->cancel_date = date('Y-m-d');
        $cancel_rl->cancel_by =  auth()->user()->id;
        $cancel_rl->save();
        $user = User::findOrfail($cancel_rl->user_id);
        // $accounts = Account::where('role_id', 'like', '%4%')
        // ->leftJoin('hr_portal.users as users','accounts.user_id','=','users.id')
        // ->select('users.*')
        // ->get();
        
        // foreach($accounts as $account)
        // {
        //     $account->notify(new ApproveCancelResignation($account,$user));
        // }
        $user->notify(new DeclinedResignation($user,$cancel_rl));
        $request->session()->flash('status','Sucessfully declined his/her resignation');
        return back();
    }
    public function manual_email(Request $request)
    {
        $letter = UploadPdf::where('id','=',$request->letter_id)->first();
        $user = User::where('id','=',$letter->user_id)->first();
        $approver = User::where('id','=',$request->employee_id)->first();
        $approver->notify(new UploadLetter($user,$approver,$letter));
        return $letter;
    }
}
