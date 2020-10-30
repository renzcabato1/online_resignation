<?php

namespace App\Http\Controllers;
use App\Employee;
use App\ManageUser;
use App\UploadPdf;
use App\Clearance;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $name = Employee::where('user_id',auth()->user()->id)->first();

            if(auth()->user()->role() != null)
            {
                $roles = auth()->user()->role()->role_id;
                $roles_array = json_decode($roles);
                if((in_array(1,$roles_array)) || (in_array(4,$roles_array)))
                {
                    $employees = Employee::leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
                    ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
                    ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
                    ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
                    ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
                    ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
                    ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name')
                    ->where('employees.status','!=','Inactive')
                    ->count();
                    
                    $employees_get = ManageUser::get();
                    $date_from = date('Y-m').'-01';
                    $date_to = date('Y-m-d',strtotime(date("Y-m-d", strtotime($date_from)) . " +1 month"));
                    $employees_id = collect($employees_get)->pluck('user_id')->toArray();
        
                    $total_resigned = UploadPdf::with('user_info','user_info.companies','user_info.departments','user_info.locations','letter_info','acceptance_info','clearance_info','approver_id.approver')
                    ->with('clearance_info.clearance_signatories_count')
                    ->where('cancel','=',null)->get();
                    // dd($total_resigned);
                    $resigned_monthy = UploadPdf::whereIn('user_id',$employees_id)->whereBetween('created_at',[$date_from,$date_to])->count();

                    $pending_clearance = Clearance::get();
                }
                elseif((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(5,$roles_array)) )
                {
                    $employees = ManageUser::where('approver_id','=',auth()->user()->id)->count();
                    $employees_get = ManageUser::where('approver_id','=',auth()->user()->id)->get();
        
                    $date_from = date('Y-m').'-01';
                    $date_to = date('Y-m-d',strtotime(date("Y-m-d", strtotime($date_from)) . " +1 month"));
                    $employees_id = collect($employees_get)->pluck('user_id')->toArray();
        
                    $total_resigned = UploadPdf::with('user_info','user_info.companies','user_info.departments','user_info.locations','letter_info','acceptance_info','clearance_info.clearance_signatories','approver_id.approver')
                    ->with('clearance_info.clearance_signatories_count')
                    ->whereIn('user_id',$employees_id)->where('cancel','=',null)->get();
                    $resigned_monthy = UploadPdf::whereIn('user_id',$employees_id)->whereBetween('created_at',[$date_from,$date_to])->count();
                    $pending_clearance = Clearance::get();
                }
            }
            else 
            {
                $employees = ManageUser::where('approver_id','=',auth()->user()->id)->count();
                $employees_get = ManageUser::where('approver_id','=',auth()->user()->id)->get();
    
                $date_from = date('Y-m').'-01';
                $date_to = date('Y-m-d',strtotime(date("Y-m-d", strtotime($date_from)) . " +1 month"));
                $employees_id = collect($employees_get)->pluck('user_id')->toArray();
    
                $total_resigned = UploadPdf::with('user_info','user_info.companies','user_info.departments','user_info.locations','letter_info','acceptance_info','clearance_info.clearance_signatories','approver_id.approver')
                ->with('clearance_info.clearance_signatories_count')
                ->whereIn('user_id',$employees_id)->where('cancel','=',null)->get();
                $resigned_monthy = UploadPdf::whereIn('user_id',$employees_id)->whereBetween('created_at',[$date_from,$date_to])->count();
                $pending_clearance = Clearance::get();
            }
        return view('home',
        array(
            'name' => $name,
            'employees' => $employees,
            'resigned_monthy' => $resigned_monthy,
            'total_resigned' => $total_resigned,
            'pending_clearance' => $pending_clearance,
        ));
    }
}
