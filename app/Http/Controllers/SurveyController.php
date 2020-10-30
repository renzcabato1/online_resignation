<?php

namespace App\Http\Controllers;
use App\Employee;
use App\Survey;
use Illuminate\Http\Request;
use App\Excel;
class SurveyController extends Controller
{
    //
    public function view_survey()
    {
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();

        $surveys = Survey::leftJoin('upload_pdfs','surveys.upload_pdf_id','=','upload_pdfs.id')
        ->leftJoin('hr_portal.employees as employees','upload_pdfs.user_id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','departments.id as department_id','surveys.*')
        ->get();
        // dd($surveys);

        return view('view_survey_list',array(
            'name' => $name,
            'surveys' => $surveys,
        ));
    }
    public function survey_report(Request $request)
    {
        
        $from = $request->from;
        $to = $request->to;
        $name = Employee::where('user_id',auth()->user()->id)
        ->leftJoin('department_employee','employees.id','=','department_employee.employee_id')
        ->select('employees.*','department_employee.department_id')
        ->first();

        $surveys = Survey::leftJoin('upload_pdfs','surveys.upload_pdf_id','=','upload_pdfs.id')
        ->leftJoin('hr_portal.employees as employees','upload_pdfs.user_id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        // ->where('upload_pdfs.cancel','=',null)
        ->whereBetween('upload_pdfs.last_day',[$from,$to])
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','departments.id as department_id','surveys.*','surveys.created_at as survey_created_at','upload_pdfs.last_day as effectivity_date')
        ->orderBy('upload_pdfs.last_day','asc')
        ->get();


        return view('survey_report',array(
            'name' => $name,
            'surveys' => $surveys,
            'from' => $from,
            'to' => $to,
        ));
    }
    public function export_survey()
    {
        $surveys = Survey::leftJoin('upload_pdfs','surveys.upload_pdf_id','=','upload_pdfs.id')
        ->leftJoin('hr_portal.employees as employees','upload_pdfs.user_id','=','employees.user_id')
        ->leftJoin('hr_portal.employee_location as employee_location','employees.id','=','employee_location.employee_id')
        ->leftJoin('hr_portal.locations as locations','employee_location.location_id','=','locations.id')
        ->leftJoin('hr_portal.company_employee as company_employee','employees.id','=','company_employee.employee_id')
        ->leftJoin('hr_portal.companies as companies','company_employee.company_id','=','companies.id')
        ->leftJoin('hr_portal.department_employee as department_employee','employees.id','=','department_employee.employee_id')
        ->leftJoin('hr_portal.departments as departments','department_employee.department_id','=','departments.id')
        ->select('employees.*','locations.name as location_name','companies.name as company_name','departments.name as department_name','departments.id as department_id','surveys.*','surveys.created_at as survey_created_at')
        ->get();

        $survey_header[] = [
            'Name',
             'Company',
             'Department',
             'Position',
             'No. of months / years worked with the company',
             'Age','Primary Reason for leaving',
             ' If primary reason is others, please indicate reason',
             'If primary reason is work, please indicate main consideration',
             'If primary reason is others, please indicate reason',
             '1. My immediate superior regularly gives me feedback and coaching.',
             'Remarks',
             '2. My team works well together and promotes a harmonious and positive working environment for me.',
             'Remarks',
             '3. My job responsibilities and performance expectations are clear.',
             'Remarks',
             '4. My job responsibilities and performance expectations are clear.',
             'Remarks',
             '5. The company provides trainings and other opportunities for learning and development.',
             'Remarks',
             '6. The companys systems and processes are clear and efficient.',
             'Remarks',
             '7. The company provides a competitive compensation and benefits package.',
             'Remarks',
             '8. The companys culture is generally positive.',
             'Remarks',
             '9. The companys culture is generally positive.',
             'Remarks',
             '10. The companys leaders display good leadership qualities and expertise in their field.',
             'Remarks',
             '11. I am aware of and appreciate the companys engagement programs.',
             'Remarks',
             '12. I would recommend this company to others exploring career opportunities.',
             'Remarks',
             'Other suggestions for improvement.',
             'Remarks',
            ];

            foreach ($surveys as $survey) {
                $survey_header[] = $survey->toArray();
            }
            Excel::create('payments', function($excel) use ($excel_mo) {

                // Set the spreadsheet title, creator, and description
                $excel->setTitle('Survey');
                $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
                $excel->setDescription('payments file');
        
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('sheet1', function($sheet) use ($survey_header) {
                    $sheet->fromArray($survey_header, null, 'A1', false, false);
                });
        
            })->download('xlsx');
    }
}
