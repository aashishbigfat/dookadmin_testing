<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;

class SignatureController extends Controller
{
    public function createSignature() {
        return view('signature.upload_signature');
    }

    public function uploadSignature(Request $request) {
        $request->validate([
            'employee_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'employee_signature' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        if ($request->hasFile('employee_image')) {
            $empImg = $request->file('employee_image');
            $empImgName = $empImg->getClientOriginalName();
            $empImg->move(public_path('signature/images'), $empImgName);
        }
    
        if ($request->hasFile('employee_signature')) {
            $empSig = $request->file('employee_signature');
            $empSigName = $empSig->getClientOriginalName();
            $empSig->move(public_path('signature/images/emp_sign'), $empSigName);
            
        }
    
        return redirect()->back()->with('success', 'Files uploaded successfully.');
    }
    public function index(Request $request)
    {
        $jobs = Employee::orderBy('id','DESC')->paginate(25);
        return view('signature.index',compact('jobs'));
    }
   public function store(Request $request)
    {
        $request->validate([
            'emp_id' => 'required',
            'emp_name' => 'required',
            'designation' => 'required',
            'emp_mobile' => 'required',
            'extension_no' => 'required',
            'email' => 'required|email',
            'emp_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'emp_signature' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $dookjob = new Employee;
        $dookjob->emp_id = $request->emp_id;
        $dookjob->emp_name = $request->emp_name;
        $dookjob->designation = $request->designation;
        $dookjob->emp_mobile = $request->emp_mobile;
        $dookjob->hq_office = '304, 3rd Floor World Trade Tower, Sector 16, Noida, Uttar Pradesh - 201301';
        $dookjob->hq_phone = '011 4000 1000';
        $dookjob->extension_no = $request->extension_no;
        $dookjob->mumbai_office = '42, Ground Floor, D Wing, Elco Arcade Hill Road, Bandra West Mumbai - 400051';
        $dookjob->mumbai_phone = $request->mumbai_phone;
        $dookjob->email = $request->email;
        $dookjob->website = 'www.dookinternational.com';
        $dookjob->emp_location = 'India';
        $dookjob->is_europe = '1';
        if ($request->hasFile('emp_img')) {
            $empImg = $request->file('emp_img');
            $empImgName = $empImg->getClientOriginalName();
            $empImg->move(public_path('signature/images'), $empImgName);
        }
        if ($request->hasFile('emp_signature')) {
            $empSig = $request->file('emp_signature');
            $empSigName = $empSig->getClientOriginalName();
            $empSig->move(public_path('signature/images/emp_sign'), $empSigName);
            $dookjob->emp_signature_old = $empSigName;
        }
        $dookjob->emp_image_old = $empImgName;
        $dookjob->save();
        $status = [
            'message' => "Success.!",
        ];

        return response()->json($status);
    }
    public function update(Request $request)
    {

        $request->validate([
            'emp_id' => 'required',
            'emp_name' => 'required',
            'email' => 'required|email',
            'emp_mobile' => 'required',
            'emp_image' => 'nullable|image',
            'emp_signature' => 'nullable|image',
        ]);

        $employee = Employee::find($request->id);

        $employee->emp_id = $request->emp_id;
        $employee->emp_name = $request->emp_name;
        $employee->email = $request->email;
        $employee->designation = $request->designation;
        $employee->emp_mobile = $request->emp_mobile;
        $employee->extension_no = $request->extension_no;

        if ($request->hasFile('emp_image')) {
            $empImg = $request->file('emp_image');
            $empImgName = $empImg->getClientOriginalName();
            $empImg->move(public_path('signature/images'), $empImgName);
            $employee->emp_image_old = $empImgName;
        }

        if ($request->hasFile('emp_signature')) {
            $empSig = $request->file('emp_signature');
            $empSigName = $empSig->getClientOriginalName();
            $empSig->move(public_path('signature/images/emp_sign'), $empSigName);
            $employee->emp_signature_old = $empSigName;
        }
        $employee->save();

        return redirect()->route('signature_upload')->with('success', 'Employee updated successfully');
    }


    
    public function Delete(Request $request, $id)
    {
        $job  = Employee::find($id)->delete();
        return response()->json(['success'=>'Success!']);
    }
}
