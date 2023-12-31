<?php

namespace App\Http\Controllers;

use App\Mail\mailSend;
use App\Models\Admins;
use App\Models\Application;
use App\Models\beneficiary;
use App\Models\Report;
use App\Models\Student;
use DateTime;
// use FPDF;
// use Fpdf\Fpdf;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Prompts\Table;
require_once(public_path().'/fpdf183/fpdf.php');

class AdminController extends Controller
{
    //
    public function login(Request $request){
        //used for registering admin
        // $admin = new Admins();
        // $admin->fullname = $request->fullname;
        // $admin->email = $request->email;
        // $admin->phone = $request->phone;
        // $admin->password = Hash::make($request->password);
        // $admin->save();
        // return redirect('/')->with('success','Admin saved successfully');
        $request->validate([
            "email"=>'required',
            "password"=>'required'
        ]);
        $password = ($request->password);
        $req = Admins::where('email',$request->email)->count();
        if($req <=0){
         return redirect('login')->with('message','Email address not registered!');
        }else{
            $admins = DB::table('admins')->select('password')->where('email', $request->email)->get();
            foreach($admins as $ad){
                if($password === $ad->password){
                    $res_admin = session()->get('res_admin',[]);
                    $res_admin = DB::table('admins')->where('email', $request->email)->get();
                    
                    session()->put('res_admin',$res_admin);
                    // print_r(session('res')); 
                    return redirect('index'); 
                    
                }else{
                   return redirect('login')->with('message','Wrong password!!.');
                }
                
            }
           
        }
    }
//     AUTH_TOKEN=$(curl -k https://flightdeck.cplane.cloud/identity/token --request POST --header "Authorization: Bearer sp-fSyXYQpihYZZsHtB_tpDXw")

// # Verify the token
// echo "Token: $AUTH_TOKEN"

// # Make the second curl request with properly formatted JSON data
// curl -X POST -H 'Content-Type: application/json' -H "Authorization: Bearer $AUTH_TOKEN" -d "{\"input\": [{\"name\": \"dan\"}]}" https://carrier.cplane.cloud/apps/hello-world/latest/hello

public function applications(){
    if(!session('res_admin')){
        return redirect('login');
    }else{
        $data = DB::table('applications')->orderBy('id','DESC')->get();
        return view('applications',compact('data'));
    }
}
public function applicants(){
    if(!session('res_admin')){
        return redirect('login');
    }else{
        $data = DB::table('students')->orderBy('id','DESC')->get();
        return view('applicants',compact('data'));
    }
}
public function delete(Request $request,$id){
    $query = Student::findOrFail($id);
    $query->delete();
    return back()->with('message','The student record was deleted successfuly');
}
public function delete_application(Request $request,$id){
    $query = Application::findOrFail($id);
    $query->delete();
    return back()->with('message','The application record was deleted successfuly');
}
public function approve_application(Request $request,$id){
    $res = DB::select("SELECT * FROM applications WHERE id = '".$id."'");
    foreach($res as $data){
        if($data->status == 'Approved'){
            return back()->with('message','The application status is already approved. You cant approve it again.');
        }else{
    DB::update("UPDATE applications SET status ='Approved' WHERE id = '".$id."'");

    $re = DB::select("SELECT * FROM applications WHERE id = '".$id."'");
    foreach($re as $data){
        if($data->school_type == "Secondary School"){
            $total = 5000;
            $query = DB::select("SELECT * FROM parents WHERE student_fullname ='".$data->student_fullname."'");
            foreach($query as $value){
                $total_amount = 0;
                
                // DB::insert("INSERT INTO reports (report_id,student_name,parent,school_level,school_name,location,Amount_awarded,total_amount_awarded)
                // VALUES('$rad','$data->student_fullname','$value->parent_guardian_name','$data->school_type','$data->school_name','$data->location','5000','$total')");
    
                $name = "Congratulations!!!!. You have been selected for the bursary award.\n Kindly visit our offices for the bursary cheques allocation.";
                Mail::to($value->parent_email)->send(new mailSend($name));
            }
        }elseif($data->school_type == "University/College/TVET"){
            $total = 10000;
            $query = DB::select("SELECT * FROM parents WHERE student_fullname ='".$data->student_fullname."'");
            foreach($query as $value){
                $total_amount = 0;
                
                // DB::insert("INSERT INTO reports (report_id,student_name,parent,school_level,school_name,location,Amount_awarded,total_amount_awarded)
                // VALUES('$rad','$data->student_fullname','$value->parent_guardian_name','$data->school_type','$data->school_name','$data->location','5000','$total')");
    
                $name = "Congratulations!!!!. You have been selected for the bursary award.\n Kindly visit our offices for the bursary cheques allocation.";
                Mail::to($value->parent_email)->send(new mailSend($name));
            }
        }
        $query = DB::select("SELECT * FROM parents WHERE student_fullname ='".$data->student_fullname."'");
        foreach($query as $value){
            
            // DB::insert("INSERT INTO reports (report_id,student_name,parent,school_level,school_name,location,Amount_awarded,total_amount_awarded)
            // VALUES('$rad','$data->student_fullname','$value->parent_guardian_name','$data->school_type','$data->school_name','$data->location','5000','$total')");

            $name = "Congratulations!!!!. You have been selected for the bursary award.\n Kindly visit our offices for the bursary cheques allocation.";
            Mail::to($value->parent_email)->send(new mailSend($name));
        }
    }
    $rad ='REP'. random_int(100,999);
            $total_amount +=$total;
            $reports = new Report();
            $reports->report_id = $rad;
            $reports->student_name = $data->student_fullname;
            $reports->parent = $value->parent_guardian_name;
            $reports->school_level = $data->school_type;
            $reports->school_name = $data->school_name;
            $reports->location = $data->location;
            $reports->Amount_awarded = $total;
            $reports->save();
    return back()->with('message','The application status approved successfuly and email has been sent to parent');
}}
  }
  public function reports(){
    if(!session('res_admin')){
        return redirect('login');
    }else{
        // $data = DB::select("SELECT created_at FROM stude")
        return view('reports');
    }
  }
  public function print(Request $request){
    // $re = DB::select("SELECT * FROM students WHERE year = '".$request->year."'");
        $re = Student::where('year', $request->year)->get();
        if(count($re) <=0){
                return back()->with('message','The year is not available in the records.');
        }else{
            foreach($re as $data){
            $res = DB::select("SELECT * FROM students WHERE year = '".$request->year."'");

        $counter = 0; 
    
        $pdf = new \FPDF('P','mm',array(150,250));
        $pdf->AddPage();

        // Set font and text color
        $imagePath = public_path('images/logo.png'); // Replace with the actual image path
        $pdf->Image($imagePath, 65,10,-300); 
        // $pdf->Image("{{asset('images/logo.png')}}",65,10,-300);

  $pdf->cell(50, 20,"", 0,1, '');
$pdf->setFont('Arial','B',12);
  $pdf->cell(120, 6,"COUNTY GOVERNMENT OF NANDI", 0,1, 'C');
  $pdf->setFont('Arial','B',10);
  $pdf->cell(120, 6, "P.O BOX 40-30100", 0, 1, 'C');
  $pdf->cell(120, 6, "info@nandicounty.go.ke", 0, 1, 'C');

  $pdf->setFont('Arial','B',9);

$pdf->cell(50, 3,"", 0,1, '');

$pdf->cell(10, 6,"", 0,0, 'C');

  $pdf->cell(50, 6,"County: Nandi County", 0,0, 'C');

  $pdf->cell(50, 6,"Year Selected: ".$request->year, 0,1, 'C');

$pdf->cell(50, 1,"", 0,1, '');
  
$pdf->setFont('Arial','B',8);
$pdf->cell(1, 4,"", 0,0, '');
$pdf->cell(7, 6,"S/N", 1,0, '');
$pdf->cell(35, 6,"Student Name", 1,0, '');
$pdf->cell(28, 6,"School Name", 1,0, '');
$pdf->cell(32, 6,"School Level", 1,0, '');
$pdf->cell(30, 6,"Date Updated", 1,1, '');
// $pdf->cell(40, 6,"Month Updated", 1,1, '');
$pdf->setFont('Arial','',11);


$pdf->setFont('Arial','',8);
foreach($res as $data){
        // Add content to the PDF
        $counter++;
        $pdf->cell(1, 4,"", 0,0, '');
        $pdf->cell(7, 6,$counter, 1,0, '');
        // $pdf->cell(30, 6,$data->id, 1,0, '');
        $pdf->cell(35, 6,$data->student_fullname, 1,0, '');
        $pdf->cell(28, 6,$data->school_name, 1,0, '');
        $pdf->cell(32, 6,$data->school_level, 1,0, '');
        $pdf->cell(30, 6,$data->updated_at, 1,1, ''); //Output each record, 0 indicates no border, 1 indicates new line
                    // Check if we have printed 10 rows, then start a new page
       
 }
 if ($pdf->GetY() >= 200) {
    $pdf->AddPage();
}
        // Output the PDF (you can choose to save it to a file or send it as a response)
        $pdf->Output();
        // $pdf->Output("NandiCountyBursary.pdf",'D'); //'D' indicated download

        // exit();
                break; // If found, you can break out of the loop to stop further iterations.
            
        }
    }
  }
  public function location_report(){
    if(!session('res_admin')){
        return redirect('login');
    }else{
        // $data = DB::select("SELECT created_at FROM stude")
        return view('location_report');
    }
  }
  public function print_location(Request $request){
    $res = Student::where("location",$request->location)->get();
    
        if(count($res) <=0){
                return back()->with('message','The location is not available in the records.');
            }else{
                foreach($res as $value){
            $query = DB::select("SELECT * FROM students WHERE location = '".$request->location."'");
            
                // if($request->location == $value->location){ 
            $counter = 0; 
            $pdf = new \FPDF('P','mm',array(150,250));
            $pdf->AddPage();
    
            // Set font and text color
            $imagePath = public_path('images/logo.png'); // Replace with the actual image path
            $pdf->Image($imagePath, 65,10,-300); 
            // $pdf->Image("{{asset('images/logo.png')}}",65,10,-300);
    
      $pdf->cell(50, 20,"", 0,1, '');
    $pdf->setFont('Arial','B',12);
      $pdf->cell(120, 6,"COUNTY GOVERNMENT OF NANDI", 0,1, 'C');
      $pdf->setFont('Arial','B',10);
      $pdf->cell(120, 6, "P.O BOX 40-30100", 0, 1, 'C');
      $pdf->cell(120, 6, "info@nandicounty.go.ke", 0, 1, 'C');
    
      $pdf->setFont('Arial','B',9);
    
    $pdf->cell(50, 3,"", 0,1, '');
    
    $pdf->cell(10, 6,"", 0,0, 'C');
    
      $pdf->cell(50, 6,"County: Nandi County", 0,0, 'C');
    
      $pdf->cell(50, 6,"Location Selected: ".$request->location, 0,1, 'C');
    
    $pdf->cell(50, 1,"", 0,1, '');
      
    $pdf->setFont('Arial','B',8);
    $pdf->cell(1, 4,"", 0,0, '');
    $pdf->cell(7, 6,"S/N", 1,0, '');
    $pdf->cell(35, 6,"Student Name", 1,0, '');
    $pdf->cell(28, 6,"School Name", 1,0, '');
    $pdf->cell(32, 6,"School Level", 1,0, '');
    $pdf->cell(30, 6,"Date Updated", 1,1, '');
    // $pdf->cell(40, 6,"Month Updated", 1,1, '');
    $pdf->setFont('Arial','',11);
    
    
    $pdf->setFont('Arial','',8);
    // foreach($query as $data){
        foreach($query as $val){
            // Add content to the PDF
            $counter++;
            $pdf->cell(1, 4,"", 0,0, '');
            $pdf->cell(7, 6,$counter, 1,0, '');
            // $pdf->cell(30, 6,$data->id, 1,0, '');
            $pdf->cell(35, 6,$val->student_fullname, 1,0, '');
            $pdf->cell(28, 6,$val->school_name, 1,0, '');
            $pdf->cell(32, 6,$val->school_level, 1,0, '');
            $pdf->cell(30, 6,$val->updated_at, 1,1, ''); //Output each record, 0 indicates no border, 1 indicates new line
                        // Check if we have printed 10 rows, then start a new page
           
     }
     if ($pdf->GetY() >= 200) {
        $pdf->AddPage();
    }
            // Output the PDF (you can choose to save it to a file or send it as a response)
            $pdf->Output();
            // $pdf->Output("NandiCountyBursary.pdf",'D'); //'D' indicated download
    
            // exit();
                    break;
        }
    }
  }
  public function users(){
    if(!session('res_admin')){
        return redirect('login');
    }else{
    $value = Admins::all();
    return view('users',compact('value'));
  }
}

public function reset(Request $request){
    $ses = session()->get('ses',[]);
    $token = bin2hex(random_bytes(32)); 

    $ses = [
        "token"=>$token,
    ];
 $c = Admins::where('email',$request->email)->count();
 if($c <=0){
    return redirect('login')->with('message','Email address not found.Please enter a valid email address');
 }else{
    $reset ='reset password';
    $url = 'https://bursary-ms.vercel.app/reset/'.$request->email.'/'.$token;
    $name = 'Kindly use the link privided below to reset your password \n\n'  .$url;
    Mail::to($request->email)->send(new mailSend($name));
    session()->put('ses',$ses);
    return redirect('login')->with('message','reset email sent successfully');
 }
}
public function reset_pass(Request $request, $email,$token){
    $email_reset = $email;
    $token_reset = $token;
    if($email_reset != $email){
    return redirect('login')->with('message','Invalid reset token');
    }else{
   
    return view('reset',compact('email_reset','token_reset'));
    }
}
public function pass_reset(Request $request){
$request->validate([
    "password"=>'required',
    "re_password"=>'required'
]);
if($request->re_password != $request->password){
    return back()->with('message','RE-PASSWORD does not match PASSWORD');
}else{
      DB::update("UPDATE admins SET password = '".$request->password."' WHERE email = '".$request->email."'");
    return redirect('login')->with('success','Password changed successfully');
}
}
public function edit_user(Request $request,$id){
    $edit = DB::update("UPDATE admins SET fullname='".$request->name."',email ='".$request->email."',phone='".$request->phone."',role='".$request->role."' WHERE id = '".$id."'");
    return back()->with('message','User details edited successfully');
}
public function change_pass(Request $request, $id){
    $current_pass = DB::select("SELECT password FROM admins WHERE id ='".$id."'");
    foreach($current_pass as $pass){
        // echo $pass->password;
        if($request->current_pass != $pass->password){
            return back()->with('error','The provided current password doesnt match the current admin password');
        }
        elseif($request->re_pass != $request->new_pass){
        return back()->with('error','Re-passworword doesnt match the new password');
    }else{
        DB::update("UPDATE admins SET password='".$request->new_pass."' WHERE id= '".$id."'");
        return back()->with('message','User password changed successfully');
    }
    }
}
public function add_user(Request $request){
    $admin = new Admins();
    $admin->fullname = $request->name;
    $admin->email = $request->email;
    $admin->phone = $request->phone;
    $admin->role = $request->role;
    $admin->password = Str::random(6);
    $admin->save();

    // 
    $url = 'https://bursary-ms.vercel.app/login';
    $name = 'Use This password "'.$admin->password.'" to logon to using your email address  '  .$url;
    Mail::to($request->email)->send(new mailSend($name));
    return back()->with('message','New user added successfully');
}
public function edit_apps(Request $request, $reference_number){
    $today =date('Y-m-d H:i:s');
    DB::update('UPDATE applications SET student_fullname ="'.$request->fullname.'", adm_upi_reg_no = "'.$request->upi_reg.'",school_type = "'.$request->school_type.'",school_name = "'.$request->school_name.'"
    ,location = "'.$request->location.'",bank_name = "'.$request->bank.'",account_no = "'.$request->account.'",updated_at ="'.$today.'"  WHERE reference_number = "'.$reference_number.'"');

    return back()->with('success','Application details for reference number : "'.$reference_number.'" updated successfully');
}
public function update_user(Request $request, $id){
    $today =date('Y-m-d H:i:s');
    DB::update('UPDATE students SET student_fullname ="'.$request->fullname.'", age = "'.$request->age.'", family_status = "'.$request->family_status.'",school_level = "'.$request->school_level.'",school_name = "'.$request->school_name.'"
    ,county = "'.$request->county.'", ward = "'.$request->ward.'",location = "'.$request->location.'",sub_location = "'.$request->sub_location.'",updated_at ="'.$today.'"  WHERE id = "'.$id.'"');
    return back()->with('success','Student details for id : "'.$id.'" updated successfully');
}
public function beneficiary(){
    if(!session('res_admin')){
        return redirect('login');
    }else{
    $value = DB::select("SELECT * FROM beneficiary_upload");
    return view('beneficiaries',compact('value'));
  }
}
public function upload_doc(Request $request){
    $request->validate([
        'document_name'=>'required',
        'document'=>'required'
    ]);
    // if($request->hasFile(document))
    if(session('res_admin'))
    foreach(session('res_admin') as $value)
    $file = $request->file('document');
    $fileName = $file->getClientOriginalName();
    $file-> move(storage_path('/beneficiary_document/'), $fileName);

    // $file = base64_encode(file_get_contents($request->file('document')));
    // $filename = uniqid() . '.jpg';
    // $path = $request->file('document')->move(public_path('student_uploads'), $filename);
    
    // DB::insert("INSERT INTO beneficiary_upload (document_name,document,uploaded_by) VALUES ('$request->document_name','$fileName','$value->email')");

    $doc = new Beneficiary();
    $doc->document_name = $request->document_name;
    $doc->document = $fileName;
    $doc->uploaded_by = $value->email;
    $doc->save();
    return back()->with('success','Document uploaded successfully');
}
public function download($document){
    $filename = $document;
    $doc = storage_path('beneficiary_document/'.$filename);
    return response()->download($doc);
}
public function print_beneficiary(Request $request){
$request->validate([
    "year"=>'required'
]);
$res = Application::where("year",$request->year)->get();
    
if(count($res) <=0){
        return back()->with('message','The year is not available in the records.');
    }elseif(count($res) > 0){
        foreach($res as $value){
            // unfinnished
    $query = DB::select("SELECT * FROM applications WHERE year = '".$request->year."' AND location = '".$request->location."' AND status= 'Approved'");
    if(count($query) > 0){
    $counter = 0; 
    $pdf = new \FPDF('P','mm',array(150,250));
    $pdf->AddPage();

    // Set font and text color
    $imagePath = public_path('images/logo.png'); // Replace with the actual image path
    $pdf->Image($imagePath, 65,10,-300); 
    // $pdf->Image("{{asset('images/logo.png')}}",65,10,-300);

$pdf->cell(50, 20,"", 0,1, '');
$pdf->setFont('Arial','B',12);
$pdf->cell(120, 6,"COUNTY GOVERNMENT OF NANDI", 0,1, 'C');
$pdf->setFont('Arial','B',10);
$pdf->cell(120, 6, "P.O BOX 40-30100", 0, 1, 'C');
$pdf->cell(120, 6, "info@nandicounty.go.ke", 0, 1, 'C');

$pdf->setFont('Arial','B',9);

$pdf->cell(50, 3,"", 0,1, '');

$pdf->cell(35, 6,"County: Nandi County", 0,0, 'C');

$pdf->cell(60, 6,"Year Selected:" .$request->year, 0,0, 'C');

$pdf->cell(30, 6,"Location Selected: ".$request->location, 0,1, 'C');

$pdf->cell(50, 1,"", 0,1, '');

$pdf->setFont('Arial','B',8);
$pdf->cell(1, 4,"", 0,0, '');
$pdf->cell(7, 6,"S/N", 1,0, '');
$pdf->cell(35, 6,"Student Name", 1,0, '');
$pdf->cell(30, 6,"School Name", 1,0, '');
$pdf->cell(32, 6,"School Level", 1,0, '');
$pdf->cell(30, 6,"Date Updated", 1,1, '');
// $pdf->cell(40, 6,"Month Updated", 1,1, '');
$pdf->setFont('Arial','',11);


$pdf->setFont('Arial','',8);
foreach($query as $val){
    // Add content to the PDF
    $counter++;
    $pdf->cell(1, 4,"", 0,0, '');
    $pdf->cell(7, 6,$counter, 1,0, '');
    $pdf->cell(35, 6,$val->student_fullname, 1,0, '');
    $pdf->Cell(30, 6,$val->school_name, 1,0, '');
    $pdf->Cell(32, 6,$val->school_type, 1,0, '');
    $pdf->cell(30, 6,$val->updated_at, 1,1, ''); //Output each record, 0 indicates no border, 1 indicates new line
                // Check if we have printed 10 rows, then start a new page
   
}
if ($pdf->GetY() >= 200) {
$pdf->AddPage();
}
    // Output the PDF (you can choose to save it to a file or send it as a response)
    $pdf->Output();
            break;
}else{
    return back()->with('message','Applications are still pending.');
}
        }
}else{
    // $query = DB::select("SELECT * FROM applications WHERE year = '".$request->year."' AND location = '".$request->location."' AND status= 'Pending...'");
    // return back()->with('message','Applications are still pending.');
}
}
public function scrab(){
    // Make a request to the webpage.
// $response = Http::get('https://newsblaze.co.ke/full-list-of-all-county-secondary-schools-in-kenya-school-code-name-location-and-other-details/');

// // Extract the data from the table column.
// $schoolNames = [];
// preg_match_all('/<td class="school_name">(.*?)<\/td>/i', $response->body(), $schoolNames);

// // Display the school names.
// foreach ($schoolNames[1] as $schoolName) {
//     echo $schoolName . PHP_EOL;
// }


// 
$httpClient = new \GuzzleHttp\Client();

$response = $httpClient->get('https://newsblaze.co.ke/full-list-of-all-county-secondary-schools-in-kenya-school-code-name-location-and-other-details/');

$htmlString = (string) $response->getBody();

// HTML is often wonky, this suppresses a lot of warnings
libxml_use_internal_errors(true);

$doc = new DOMDocument();
$doc->loadHTML($htmlString);

$xpath = new DOMXPath($doc);
$links = $xpath->evaluate('//div[@class="table-responsive"][1]//h3/a');

foreach ($links as $link) {
    echo $link->textContent.PHP_EOL;
}
}
public function amount_report(){
    $total = 0;
    $total_amount = DB::select("SELECT SUM(amount_awarded)AS total FROM reports")[0]->total;

    function formatCurrency($number) {
        $formattedCurrency = number_format($number, 0, '.', ',');
        return str_replace('.', ',', $formattedCurrency);
      }
      
      $number = $total_amount;
      $formattedNumber = formatCurrency($number);
      
    $data = Report::all();
    return view('amount_reports',compact('data','formattedNumber'));
}
public function print_reports(){
    $total = 0;
    $total_amount = DB::select("SELECT SUM(amount_awarded)AS total FROM reports")[0]->total;

    function Currency($number) {
        $formattedCurrency = number_format($number, 0, '.', ',');
        return str_replace('.', ',', $formattedCurrency);
      }
      
      $number = $total_amount;
      $formattedNumber = Currency($number);
      $data = Report::all();


      $counter = 0; 
      $pdf = new \FPDF('P','mm',array(150,250));
      $pdf->AddPage();
  
      // Set font and text color
      $imagePath = public_path('images/logo.png'); // Replace with the actual image path
      $pdf->Image($imagePath, 65,10,-300); 
      // $pdf->Image("{{asset('images/logo.png')}}",65,10,-300);
  
  $pdf->cell(50, 20,"", 0,1, '');
  $pdf->setFont('Arial','B',12);
  $pdf->cell(120, 6,"COUNTY GOVERNMENT OF NANDI", 0,1, 'C');
  $pdf->setFont('Arial','B',10);
  $pdf->cell(120, 6, "P.O BOX 40-30100", 0, 1, 'C');
  $pdf->cell(120, 6, "info@nandicounty.go.ke", 0, 1, 'C');
  
  $pdf->setFont('Arial','B',9);
  
  $pdf->cell(50, 3,"", 0,1, '');
  
  $pdf->cell(35, 6,"County: Nandi County", 0,0, 'C');
  
  $pdf->cell(60, 6,"Year : " .date('Y'), 0,0, 'C');
  
  $pdf->cell(30, 6,"Total Amount: ".$formattedNumber, 0,1, 'C');
  
  $pdf->cell(50, 1,"", 0,1, '');
  
  $pdf->setFont('Arial','B',8);
  $pdf->cell(1, 4,"", 0,0, '');
  $pdf->cell(7, 6,"S/N", 1,0, '');
  $pdf->cell(35, 6,"Student Name", 1,0, '');
  $pdf->cell(30, 6,"School Name", 1,0, '');
  $pdf->cell(32, 6,"School Level", 1,0, '');
  $pdf->cell(30, 6,"Amount", 1,1, '');
  // $pdf->cell(40, 6,"Month Updated", 1,1, '');
  $pdf->setFont('Arial','',11);
  
  
  $pdf->setFont('Arial','',8);
  foreach($data as $val){
      // Add content to the PDF
      $counter++;
      $pdf->cell(1, 4,"", 0,0, '');
      $pdf->cell(7, 6,$counter, 1,0, '');
      $pdf->cell(35, 6,$val->student_name, 1,0, '');
      $pdf->Cell(30, 6,$val->school_name, 1,0, '');
      $pdf->Cell(32, 6,$val->school_level, 1,0, '');
      $pdf->cell(30, 6,$val->Amount_awarded, 1,1, ''); //Output each record, 0 indicates no border, 1 indicates new line
                  // Check if we have printed 10 rows, then start a new page
     
  }
  if ($pdf->GetY() >= 200) {
  $pdf->AddPage();
  }
      // Output the PDF (you can choose to save it to a file or send it as a response)
      $pdf->Output();
        

}
public function print_reports_location(Request $request){
    $total = 0;
    $total_amount = DB::select("SELECT SUM(amount_awarded)AS total FROM reports WHERE location ='".$request->location."'")[0]->total;

    function Separate($number) {
        $formattedCurrency = number_format($number, 0, '.', ',');
        return str_replace('.', ',', $formattedCurrency);
      }
      
      $number = $total_amount;
      $formattedNumber = Separate($number);

      $data = DB::select("SELECT * FROM reports WHERE location ='".$request->location."'");


      $counter = 0; 
      $pdf = new \FPDF('P','mm',array(150,250));
      $pdf->AddPage();
  
      // Set font and text color
      $imagePath = public_path('images/logo.png'); // Replace with the actual image path
      $pdf->Image($imagePath, 65,10,-300); 
      // $pdf->Image("{{asset('images/logo.png')}}",65,10,-300);
  
  $pdf->cell(50, 20,"", 0,1, '');
  $pdf->setFont('Arial','B',12);
  $pdf->cell(120, 6,"COUNTY GOVERNMENT OF NANDI", 0,1, 'C');
  $pdf->setFont('Arial','B',10);
  $pdf->cell(120, 6, "P.O BOX 40-30100", 0, 1, 'C');
  $pdf->cell(120, 6, "info@nandicounty.go.ke", 0, 1, 'C');
  
  $pdf->setFont('Arial','B',9);
  
  $pdf->cell(50, 3,"", 0,1, '');
  
  $pdf->cell(35, 6,"County: Nandi County", 0,0, 'C');
  
  $pdf->cell(60, 6,"Year : " .date('Y'), 0,0, 'C');
  
  $pdf->cell(30, 6,"Total Amount: ".$formattedNumber, 0,1, 'C');
  
  $pdf->cell(50, 1,"", 0,1, '');
  
  $pdf->setFont('Arial','B',8);
  $pdf->cell(1, 4,"", 0,0, '');
  $pdf->cell(7, 6,"S/N", 1,0, '');
  $pdf->cell(35, 6,"Student Name", 1,0, '');
  $pdf->cell(30, 6,"School Name", 1,0, '');
  $pdf->cell(32, 6,"School Level", 1,0, '');
  $pdf->cell(30, 6,"Amount", 1,1, '');
  // $pdf->cell(40, 6,"Month Updated", 1,1, '');
  $pdf->setFont('Arial','',11);
  
  
  $pdf->setFont('Arial','',8);
  foreach($data as $val){
      // Add content to the PDF
      $counter++;
      $pdf->cell(1, 4,"", 0,0, '');
      $pdf->cell(7, 6,$counter, 1,0, '');
      $pdf->cell(35, 6,$val->student_name, 1,0, '');
      $pdf->Cell(30, 6,$val->school_name, 1,0, '');
      $pdf->Cell(32, 6,$val->school_level, 1,0, '');
      $pdf->cell(30, 6,$val->Amount_awarded, 1,1, ''); //Output each record, 0 indicates no border, 1 indicates new line
                  // Check if we have printed 10 rows, then start a new page
     
  }
  if ($pdf->GetY() >= 200) {
  $pdf->AddPage();
  }
      // Output the PDF (you can choose to save it to a file or send it as a response)
      $pdf->Output();
        
}
}
