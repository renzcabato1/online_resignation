<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ManageUser;
use App\Employee;
use App\User;
use App\Account;
use App\UploadPdf;
use App\CancelResignation;
use App\Notifications\ApproveCancelResignation;
use App\Notifications\DisapproveCancelResignation;
class CancelResignationController extends Controller
{
    //
    
    public function view_cancel_request ()
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();

       $employees = UploadPdf::with('user_info.companies','user_info.locations','user_info.departments','cancelled_by')->where('cancel','=','1')->get();
        return view('cancel_resignations_view',array(
            'employees' => $employees,
            'name' => $name,
        ));
    }
    public function approve_cancel(Request $request,$id)
    {

        $approve_cancel = CancelResignation::findOrfail($id);
        $approve_cancel->status = 'Approved';
        $approve_cancel->status_by = auth()->user()->id;
        $approve_cancel->status_date = date('Y-m-d');
        $approve_cancel->save();

        $cancel_rl = UploadPdf::where('id','=',$approve_cancel->upload_pdf_id)->where('cancel','=',null)->first();
        
        $cancel_rl->cancel  = 1;
        $cancel_rl->cancel_date = date('Y-m-d');
        $cancel_rl->cancel_by =  auth()->user()->id;
        $cancel_rl->save();
        $user = User::findOrfail($cancel_rl->user_id);
        $accounts = Account::where('role_id', 'like', '%4%')
        ->leftJoin('hr_portal.users as users','accounts.user_id','=','users.id')
        ->select('users.*')
        ->get();
        
        foreach($accounts as $account)
        {
            $account->notify(new ApproveCancelResignation($account,$user));
        }
        $request->session()->flash('status','Sucessfully Approved his/her request!');
        return back();


    }
    public function disapprove_cancel(Request $request,$id)
    {
       $disapprove = CancelResignation::findOrfail($id);
       $disapprove->status = 'Disapproved';
       $disapprove->status_by = auth()->user()->id;
       $disapprove->status_date = date('Y-m-d');
       $disapprove->save();
       $cancel_rl = UploadPdf::where('id','=',$disapprove->upload_pdf_id)->where('cancel','=',null)->first();
       $user = User::findOrfail($cancel_rl->user_id);
       $accounts = Account::where('role_id', 'like', '%4%')
       ->leftJoin('hr_portal.users as users','accounts.user_id','=','users.id')
       ->select('users.*')
       ->get();
       foreach($accounts as $account)
        {
            $account->notify(new DisapproveCancelResignation($account,$user));
        }
       $request->session()->flash('status','Sucessfully Disapproved his/her request!');
       return back();
    }
}
