<?php

namespace App\Http\Controllers;
use App\Employee;
use App\ManageUser;
use App\User;
use App\UploadPdf;
use App\Http\Resources\ManageUserResource;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function renztest()
    {
       $renz = ManageUser::where('approver_id','=',auth()->user()->id)->get();

        return ManageUserResource::collection($renz);
    }

    //
    public function view_employee()
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();

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
                ->get();
            }
            elseif((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(5,$roles_array)) )
            {
                $employees = ManageUser::where('approver_id','=',auth()->user()->id)
                ->leftJoin('hr_portal.users as hr_users','manage_users.user_id','=','hr_users.id')
                ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
                ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
                ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
                ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
                ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
                ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
                ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
                // ->leftJoin('upload_pdfs','hr_users.id','=','upload_pdfs.user_id')
                // ->where('upload_pdfs.cancel','=',null)
                ->where('employees.status','!=','Inactive')
                ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','manage_users.id as manage_user_id')
                ->orderBy('employees.last_name','asc')
                ->get();
            }
        }
        else 
        {
            $employees = ManageUser::where('approver_id','=',auth()->user()->id)
            ->leftJoin('hr_portal.users as hr_users','manage_users.user_id','=','hr_users.id')
            ->leftJoin('hr_portal.employees as employees','hr_users.id','=','employees.user_id')
            ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
            ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
            ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
            ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
            ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
            ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
            ->where('employees.status','!=','Inactive')
            // ->leftJoin('upload_pdfs','hr_users.id','=','upload_pdfs.user_id')
            // ->where('upload_pdfs.cancel','=',null)
            ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','manage_users.id as manage_user_id')
            ->orderBy('employees.last_name','asc')
            ->get();
        }
       
        $employee_id = collect($employees->pluck('user_id'))->toArray(); 
        $upload_pdf = UploadPdf::whereIn('user_id',$employee_id)
        ->where('cancel','=',null) 
        ->where('type','!=',"Transfer") 
        ->orderBy( 'id','desc')
        ->get();

        $upload_pdf_id = collect($upload_pdf)->toArray(); 
        return view('employee_view',array(
            'employees' => $employees,
            'name' => $name,
            'upload_pdf' => $upload_pdf,
            'upload_pdf_id' => $upload_pdf_id,
        ));
    }
}
