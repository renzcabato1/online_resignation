<?php

namespace App\Http\Controllers;
use App\Employee;
use App\UploadPdf;
use App\ManageUser;
use App\Signature;
use App\User;
use App\Account;
use App\Clearance;
use App\UserAccountability;
use App\PersonalInfo;
use App\ClearanceSignatory;
use Illuminate\Http\Request;
use App\Notifications\SignatoryNotif;
use App\Notifications\ChangeEffectiveDate;
use App\Notifications\ChangeEffectiveDateSupervisor;
use App\ResignEmployee;
use PDF;
class ClearanceController extends Controller
{
    //
    public function to_clearance(Request $request,$upload_pdf_id)
    {
        // dd($upload_pdf_id);
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();
        
        $employee = Uploadpdf::where('upload_pdfs.id',$upload_pdf_id)
        ->leftJoin('hr_portal.users as hr_users','upload_pdfs.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','departments.id as department_id','upload_pdfs.last_day')
        ->first();
        
        $personal_info_data = PersonalInfo::where('user_id',$employee->user_id)->first();
        $manage_accounts = ManageUser::where('manage_users.user_id','=',$employee->user_id)
        ->leftJoin('hr_portal.users as hr_users','manage_users.approver_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','hr_users.name as user_name')
        ->orderBy('employees.last_name','asc')
        ->get();


        $employees = User::leftJoin('hr_portal.employees as employee_data','users.id','=','employee_data.user_id')
        ->leftJoin('hr_portal.department_employee as department_employee','employee_data.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('users.*','employee_data.*','departments.id as department_id','departments.name as department_name')
        ->where('employee_data.status','!=','Inactive')
        ->orderBy('users.email','asc')
        ->get();
        
        $signatures = Signature::leftJoin('hr_portal.departments as departments','signatures.department_id','=','departments.id')
        ->select('departments.*')
        ->get();
        return view('view_clearance',array(
            'name' => $name,
            'employee' => $employee,
            'manage_accounts' => $manage_accounts,
            'signatures' => $signatures,
            'employees' => $employees,
            'upload_pdf_id' => $upload_pdf_id,
            'personal_info_data' => $personal_info_data,
        ));

    }
    public function create_clearance(Request $request,$upload_id)
    {
        // dd($request->all());
        $employee = Uploadpdf::where('id',$upload_id)->first();
        
        $new_clearance = new Clearance;
        $new_clearance->upload_pdf_id = $upload_id;
        $new_clearance->effective_date = $request->last_day;
        $new_clearance->remarks = $request->remarks;
        $new_clearance->personal_email = $request->personal_email;
        $new_clearance->contact_number = $request->contact_number;
        $new_clearance->created_by = auth()->user()->id;
        $new_clearance->mailing_address =  $request->mailing_address;
        $new_clearance->landline = $request->landline;
        $new_clearance->phone_number_landline =  $request->personal_landline;
        $new_clearance->last_date_work = $request->last_day_work;
        $new_clearance->save();

        if($employee->last_day != $request->last_day)
        {
            $user = UploadPdf::where('upload_pdfs.id',$upload_id)
            ->leftJoin('hr_portal.users as users_a','upload_pdfs.user_id','=','users_a.id')
            ->select('users_a.*')
            ->first();
            $user->notify(new ChangeEffectiveDate($user,$new_clearance,$request));
            $supervisors = ManageUser::where('manage_users.user_id',$user->id)
            ->leftJoin('hr_portal.users as users_a','manage_users.approver_id','=','users_a.id')
            ->select('users_a.*')
            ->get();
            foreach($supervisors as $supervisor)
            {
                $supervisor->notify(new ChangeEffectiveDateSupervisor($supervisor,$new_clearance,$request,$user));
            }
        }
        
        $employee = Uploadpdf::where('upload_pdfs.id',$upload_id)
        ->leftJoin('hr_portal.users as hr_users','upload_pdfs.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('hr_users.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','departments.id as department_id','employees.position')
        ->first();
        
        foreach($request->name as $name)
        {
         $name_explode = explode('-',$name);
         if (array_key_exists("1",$name_explode))
            {
                $new_signatories = new ClearanceSignatory;
                $new_signatories->user_id = $name_explode[0];
                $new_signatories->department_id = $name_explode[1];
                $new_signatories->clearance_id = $new_clearance->id;
                $new_signatories->save();
                $user = User::findOrfail($name_explode[0]);
                $user->notify(new SignatoryNotif($user,$request,$employee));
            }
         else
            {
                $new_signatories = new ClearanceSignatory;
                $new_signatories->user_id = $name_explode[0];
                $user = User::findOrfail($name_explode[0]);
                $new_signatories->clearance_id = $new_clearance->id;
                $new_signatories->save();
                $user = User::findOrfail($name_explode[0]);
                $user->notify(new SignatoryNotif($user,$request,$employee));
            }
        }
        $request->session()->flash('status','Sucessfully Created Clearance!');
        return redirect('/resigned-employee');
    }
    public function view_clearance($upload_pdf_id)
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();
        
        $employee = Clearance::where('clearances.upload_pdf_id',$upload_pdf_id)
        ->leftJoin('upload_pdfs','clearances.upload_pdf_id','upload_pdfs.id')
        ->leftJoin('hr_portal.users as hr_users','upload_pdfs.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','departments.id as department_id','clearances.id as clearance_id','clearances.*')
        ->first();
 
        // dd($employee);
        $department_signatories = ClearanceSignatory::where('clearance_id',$employee->clearance_id)
        ->leftJoin('hr_portal.departments as departments','clearance_signatories.department_id','=','departments.id')
        ->select('clearance_signatories.department_id as department_id','departments.name as department_name')
        ->orderBy('clearance_signatories.id','asc')
        ->groupBy('department_id','department_name')->get(['department_id','department_name']);

        $signatories = ClearanceSignatory::where('clearance_id',$employee->clearance_id)
        ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
        ->select('hr_users.name as hr_user_name','clearance_signatories.*')
        ->get();
        
        $signatories_id = collect($signatories->pluck('department_id'))->toArray(); 
        return view('view_clearance_status',array(
            'name' => $name,
            'employee' => $employee,
            'department_signatories' => $department_signatories,
            'signatories' => $signatories,
            'signatories_id' => $signatories_id,
          
        ));
    }
    public function edit_view_clearance($upload_pdf_id)
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();
        
        $employee = Clearance::where('clearances.upload_pdf_id',$upload_pdf_id)
        ->leftJoin('upload_pdfs','clearances.upload_pdf_id','upload_pdfs.id')
        ->leftJoin('hr_portal.users as hr_users','upload_pdfs.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','departments.id as department_id','clearances.id as clearance_id','clearances.*')
        ->first();

        $employees_data = User::leftJoin('hr_portal.employees as employee_data','users.id','=','employee_data.user_id')
        ->select('users.*','employee_data.*')
        ->where('employee_data.status','!=','Inactive')
        ->orderBy('users.email','asc')
        ->get();
        
        // dd($employee);
        $department_signatories = ClearanceSignatory::where('clearance_id',$employee->clearance_id)
        ->leftJoin('hr_portal.departments as departments','clearance_signatories.department_id','=','departments.id')
        ->select('clearance_signatories.department_id as department_id','departments.name as department_name')
        ->orderBy('clearance_signatories.id','asc')
        ->groupBy('department_id','department_name')->get(['department_id','department_name']);

        $signatories = ClearanceSignatory::where('clearance_id',$employee->clearance_id)
        ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
        ->select('hr_users.name as hr_user_name','clearance_signatories.*')
        ->get();
        $signatures = Signature::leftJoin('hr_portal.departments as departments','signatures.department_id','=','departments.id')
        ->select('departments.*')
        ->get();
        $signatories_id = collect($signatories->pluck('department_id'))->toArray(); 
        return view('edit_clearance',array(
            'name' => $name,
            'employee' => $employee,
            'employees_data' => $employees_data,
            'department_signatories' => $department_signatories,
            'signatories' => $signatories,
            'signatories_id' => $signatories_id,
            'signatures' => $signatures,
            'upload_pdf_id' => $upload_pdf_id,
          
        ));
    }
    public function view_clearance_pdf($pdf_id)
    {
        
        $employee = Clearance::where('clearances.upload_pdf_id',$pdf_id)
        ->leftJoin('upload_pdfs','clearances.upload_pdf_id','upload_pdfs.id')
        ->leftJoin('hr_portal.users as hr_users','upload_pdfs.user_id','=','hr_users.id')
        ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','departments.id as department_id','clearances.id as clearance_id','clearances.*','upload_pdfs.type as resign_type')
        ->first();
        $employee_id = Employee::where('user_id',$employee->user_id)->first();
        // dd($employee);
        $department_signatories = ClearanceSignatory::where('clearance_id',$employee->clearance_id)
        ->leftJoin('hr_portal.departments as departments','clearance_signatories.department_id','=','departments.id')
        ->select('clearance_signatories.department_id as department_id','departments.name as department_name')
        ->orderBy('clearance_signatories.id','asc')
        ->groupBy('department_id','department_name')->get(['department_id','department_name']);

        $signatories = ClearanceSignatory::where('clearance_id',$employee->clearance_id)
        ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
        ->select('hr_users.name as hr_user_name','clearance_signatories.*')
        ->get();
        $signatories_id = collect($signatories->pluck('department_id'))->toArray(); 
        $signatories_finance =ClearanceSignatory::where('clearance_id',$employee->clearance_id)
        ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
        ->where('department_id','=',15)
        ->select('hr_users.name as hr_user_name','clearance_signatories.*')
        ->first();
        
        $account = Account::where('hr_head',1)
        ->leftJoin('hr_portal.users as hr_user','accounts.user_id','=','hr_user.id')
        ->select('hr_user.name')
        ->first();
        $pdf = PDF::loadView('view_clearance_pdf',array
        (
            'employee' => $employee,
            'employee_id' => $employee_id,
            'department_signatories' => $department_signatories,
            'signatories' => $signatories,
            'signatories_id' => $signatories_id,
            'signatories_finance' => $signatories_finance,
            'account' => $account,
        )); 
        return $pdf->stream('clearance.pdf');
    }
    public function clearance_list()
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();
        $accountabilities = UserAccountability::orderBy('name','asc')->get();
        if(auth()->user()->role() != null)
        {
            $roles = auth()->user()->role()->role_id;
            $roles_array = json_decode($roles);
            if((in_array(1,$roles_array)) || (in_array(4,$roles_array)))
            {
            $signatories = ClearanceSignatory::
            with('clearance_info.upload_pdf_info.user_info.companies')
            ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
            ->leftJoin('clearances','clearance_signatories.clearance_id','=','clearances.id')
            ->leftJoin('upload_pdfs','clearances.upload_pdf_id','=','upload_pdfs.id')
            ->leftJoin('hr_portal.users as hr_u','upload_pdfs.user_id','=','hr_u.id')
            ->where('status','=',null)
            ->leftJoin('hr_portal.departments as departments','clearance_signatories.department_id','=','departments.id')
            ->select('hr_users.name as hr_user_name','clearance_signatories.*','departments.name as department_name','hr_u.name as hr_u_name','hr_u.name as hr_u_name','upload_pdfs.id as upload_id','upload_pdfs.user_id as user_id_pdf','upload_pdfs.type as type')
            ->orderBy('clearances.effective_date','asc')
            ->get();
            // return $signatories;
            }
            elseif((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(5,$roles_array)) )
            {
            $signatories = ClearanceSignatory:: with('clearance_info.upload_pdf_info.user_info.companies')
            ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
            ->leftJoin('clearances','clearance_signatories.clearance_id','=','clearances.id')
            ->leftJoin('upload_pdfs','clearances.upload_pdf_id','=','upload_pdfs.id')
            ->leftJoin('hr_portal.users as hr_u','upload_pdfs.user_id','=','hr_u.id')
            ->leftJoin('hr_portal.departments as departments','clearance_signatories.department_id','=','departments.id')
            ->where('status','=',null)
            
            ->where('clearance_signatories.user_id','=',auth()->user()->id)->orWhere('upload_pdfs.user_id','=',auth()->user()->id)
            ->select('hr_users.name as hr_user_name','clearance_signatories.*','departments.name as department_name','hr_u.name as hr_u_name','hr_u.name as hr_u_name','upload_pdfs.id as upload_id','upload_pdfs.user_id as user_id_pdf','upload_pdfs.type as type')
            ->orderBy('clearances.effective_date','asc')
            ->get();
            }
        }
        else 
        {
            $signatories = ClearanceSignatory:: with('clearance_info.upload_pdf_info.user_info.companies')
            ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
            ->leftJoin('clearances','clearance_signatories.clearance_id','=','clearances.id')
            ->leftJoin('upload_pdfs','clearances.upload_pdf_id','=','upload_pdfs.id')
            ->leftJoin('hr_portal.users as hr_u','upload_pdfs.user_id','=','hr_u.id')
            ->leftJoin('hr_portal.departments as departments','clearance_signatories.department_id','=','departments.id')
            ->where('status','=',null)
            ->where(function ($query) {
                $query->where('clearance_signatories.user_id','=',auth()->user()->id)
                      ->orWhere('upload_pdfs.user_id','=',auth()->user()->id);
            })
            ->select('hr_users.name as hr_user_name','clearance_signatories.*','departments.name as department_name','hr_u.name as hr_u_name','hr_u.name as hr_u_name','upload_pdfs.id as upload_id','upload_pdfs.user_id as user_id_pdf','upload_pdfs.type as type')
            ->orderBy('clearances.effective_date','asc')
            ->get();
        }
        return view('view_clearance_list',array(
            'name' => $name,
            'signatories' => $signatories,
            'accountabilities' => $accountabilities,
        ));
    }
    public function verify_clearance(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $signatory = ClearanceSignatory::findOrfail($id);
        $signatory->status = 1;
        $signatory->remarks = $request->remarks;
        $signatory->date_verified = date('Y-m-d');
        $signatory->accountabilities = $request->accountabilities;
        $signatory->amount = $request->amount;
        if($request->hasFile('attachment'))
        {
            $attachment = $request->file('attachment');
            $original_name = str_replace(' ', '',$attachment->getClientOriginalName());
            $name = time().'_'.$original_name;
            $attachment->move(public_path().'/upload_attachments/', $name);
            $file_name = '/upload_attachments/'.$name;
            $signatory->attachment = $file_name;
        }
        $signatory->save();
        return $id;
    }
    public function remove_signatory(Request $request,$clerance_id)
    {
        $remove = ClearanceSignatory::findOrfail($clerance_id);
        $remove->delete();
        $request->session()->flash('status','Sucessfully Remove!');
        return back();
    }
    public function add_signatory (Request $request,$clearance_id)
    {
        $clearance = Clearance::where('upload_pdf_id',$clearance_id)->orderBy('updated_at','desc')->first();
        foreach($request->name as $name)
        {
            $new_signatories = new ClearanceSignatory;
            $new_signatories->user_id = $name;
            $new_signatories->department_id = $request->department;
            $new_signatories->clearance_id = $clearance->id;
            $new_signatories->save();
        }   
        $request->session()->flash('status','Sucessfully Add New Signatory!');
        return back();
    }
    public function save_edit_info (Request $request,$clearance_id)
    {
        $new_clearance = Clearance::where('upload_pdf_id',$clearance_id)->orderBy('updated_at','desc')->first();
        if($new_clearance->effective_date != $request->last_day)
        {
            $user = UploadPdf::where('upload_pdfs.id',$new_clearance->upload_pdf_id)
            ->leftJoin('hr_portal.users as users_a','upload_pdfs.user_id','=','users_a.id')
            ->select('users_a.*')
            ->first();
           

            $user->notify(new ChangeEffectiveDate($user,$new_clearance,$request));
            $supervisors = ManageUser::where('manage_users.user_id',$user->id)
            ->leftJoin('hr_portal.users as users_a','manage_users.approver_id','=','users_a.id')
            ->select('users_a.*')
            ->get();
            // $supervisors  = User::where('email','=','renzchristian.cabato@lafilgroup.com')->get();
            // dd($supervisor);
            foreach($supervisors as $supervisor)
            {
                $supervisor->notify(new ChangeEffectiveDateSupervisor($supervisor,$new_clearance,$request,$user));
            }
        }

        $upload_pdf = UploadPdf::findOrfail($new_clearance->upload_pdf_id);
        $upload_pdf->last_day = $request->last_day;
        $upload_pdf->save();

        $new_clearance->effective_date = $request->last_day;
        $new_clearance->remarks = $request->remarks;
        $new_clearance->personal_email = $request->personal_email;
        $new_clearance->contact_number = $request->contact_number;
        $new_clearance->created_by = auth()->user()->id;
        $new_clearance->mailing_address =  $request->mailing_address;
        $new_clearance->landline = $request->landline;
        $new_clearance->phone_number_landline =  $request->personal_landline;
        $new_clearance->last_date_work = $request->last_day_work;
        $new_clearance->save();
        $request->session()->flash('status','Sucessfully Update Info!');
        return back();
    }
    public function clearance_report(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        
        $clearance_employee = ResignEmployee::whereHas('clearance_info')
        ->with([
            'upload_pdf.user_info.companies',
            'upload_pdf.user_info.departments',
            'upload_pdf.letter_info',
            'clearance_info',
            'clearance_info.clearance_signatories',
            'clearance_info.clearance_signatories.department_info'
            ])
        ->whereHas('upload_pdf', function ($query) 
        {
             $query->where('cancel', '=', null);
        })
        ->get();

        return view('clearance_report',array(
            'from' => $from,
            'to' => $to,
            'clearance_employees' => $clearance_employee,
        ));
    }
    public function expired_employee (Request $request)
    {
        $date_today = date('Y-m-d');
        $expired_employees = UploadPdf::where('cancel',null)->where('last_day','<=',$date_today)->where('type','!=','Transfer')->get();
        foreach($expired_employees as $expired)
        {
                $employee = Employee::where('user_id',$expired->user_id)->first();
                $employee->status = "Inactive";
                $employee->date_resigned = $expired->last_day;
                $employee->save();
        }
        return "successful";
    }
    public function cleared  (Request $request)
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();
        $accountabilities = UserAccountability::orderBy('name','asc')->get();
        if(auth()->user()->role() != null)
        {
            $roles = auth()->user()->role()->role_id;
            $roles_array = json_decode($roles);
            if((in_array(1,$roles_array)) || (in_array(4,$roles_array)))
            {
            $signatories = ClearanceSignatory::
            with('clearance_info.upload_pdf_info.user_info.companies')
            ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
            ->leftJoin('clearances','clearance_signatories.clearance_id','=','clearances.id')
            ->leftJoin('upload_pdfs','clearances.upload_pdf_id','=','upload_pdfs.id')
            ->leftJoin('hr_portal.users as hr_u','upload_pdfs.user_id','=','hr_u.id')
            ->where('status','=',1)
            ->leftJoin('hr_portal.departments as departments','clearance_signatories.department_id','=','departments.id')
            ->select('hr_users.name as hr_user_name','clearance_signatories.*','departments.name as department_name','hr_u.name as hr_u_name','hr_u.name as hr_u_name','upload_pdfs.id as upload_id','upload_pdfs.user_id as user_id_pdf','upload_pdfs.type as type')
            ->orderBy('clearances.effective_date','asc')
            ->get();
            // return $signatories;
            }
            elseif((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(5,$roles_array)) )
            {
            $signatories = ClearanceSignatory:: with('clearance_info.upload_pdf_info.user_info.companies')
            ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
            ->leftJoin('clearances','clearance_signatories.clearance_id','=','clearances.id')
            ->leftJoin('upload_pdfs','clearances.upload_pdf_id','=','upload_pdfs.id')
            ->leftJoin('hr_portal.users as hr_u','upload_pdfs.user_id','=','hr_u.id')
            ->leftJoin('hr_portal.departments as departments','clearance_signatories.department_id','=','departments.id')
            ->where('status','=',1)
            
            ->where('clearance_signatories.user_id','=',auth()->user()->id)->orWhere('upload_pdfs.user_id','=',auth()->user()->id)
            ->select('hr_users.name as hr_user_name','clearance_signatories.*','departments.name as department_name','hr_u.name as hr_u_name','hr_u.name as hr_u_name','upload_pdfs.id as upload_id','upload_pdfs.user_id as user_id_pdf','upload_pdfs.type as type')
            ->orderBy('clearances.effective_date','asc')
            ->get();
            }
        }
        else 
        {
            $signatories = ClearanceSignatory:: with('clearance_info.upload_pdf_info.user_info.companies')
            ->leftJoin('hr_portal.users as hr_users','clearance_signatories.user_id','=','hr_users.id')
            ->leftJoin('clearances','clearance_signatories.clearance_id','=','clearances.id')
            ->leftJoin('upload_pdfs','clearances.upload_pdf_id','=','upload_pdfs.id')
            ->leftJoin('hr_portal.users as hr_u','upload_pdfs.user_id','=','hr_u.id')
            ->leftJoin('hr_portal.departments as departments','clearance_signatories.department_id','=','departments.id')
            ->where('status','=',1)
            ->where(function ($query) {
                $query->where('clearance_signatories.user_id','=',auth()->user()->id)
                      ->orWhere('upload_pdfs.user_id','=',auth()->user()->id);
            })
            ->select('hr_users.name as hr_user_name','clearance_signatories.*','departments.name as department_name','hr_u.name as hr_u_name','hr_u.name as hr_u_name','upload_pdfs.id as upload_id','upload_pdfs.user_id as user_id_pdf','upload_pdfs.type as type')
            ->orderBy('clearances.effective_date','asc')
            ->get();
        }
        return view('cleared',array(
            'name' => $name,
            'signatories' => $signatories,
            'accountabilities' => $accountabilities,
        ));

    }
    public function print_clearance(Request $request)
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();
       
        return view('cleared',array(
            'name' => $name,
            'signatories' => $signatories,
            'accountabilities' => $accountabilities,
        ));
    }
}