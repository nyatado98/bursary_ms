<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student-bursary summary details</title>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="{{asset('bootstrap/jquery/jquery-3.5.1.min.js')}}"></script>
    <link href="{{asset('css/text.css')}}" rel="stylesheet">
</head>
<body>
    <div class="container-fluid" style="background-image: url('images/photo.jpg');background-repeat:no-repeat;background-size:cover;background-position:center;background-attachment:fixed;opacity:0.8;height:auto">
        <div class="row mx-auto " id="header" >
            <img src="{{asset('images/logo.png')}}" alt="" srcset="" width="10%">
            <h5 class="mt-5 mx-5 font-weight-bold text-white" style="font-size: 30px">Bursary Management System</h5>
        </div>
        <div class="container-fluid">
<div class="row">
    <div class="col-md-12 bg-light mt-5  py-2">
    <ul class="progressbar">
      <li class="active font-weight-bold" style="font-size: 15px;color:green">Student Details</li>
      <li class="active font-weight-bold" style="font-size: 15px;color:green">School/Institution Information</li>
      <li class="active font-weight-bold" style="font-size: 15px;color:green">Bursary details</li>
      <li class="active font-weight-bold" style="font-size: 15px;color:green">Summary/Confirm Details</li>
    </ul>
    </div>
</div>
</div>
<div class="container-fluid col-md-8 mt-5">
    <form method="POST" action="{{route('students_index')}}">
        @csrf
    <div id="accordion">
        @if(session()->has('message'))
        <span class="text-danger font-weight-bold">{{session()->get('message')}}</span>
        @endif
    <div class="card">
        <div class="card-header" id="headingOne">
            {{-- <h4 class="text-center font-weight-bold">Student Details</h4> --}}
            <p class="btn font-weight-bold" style="color: black;text-decoration:underline" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Student Details
            </p>
            @if($errors->has('first_name'))
            <span class="text-danger font-weight-bold">{{$errors->first('first_name')}}</span>
            @endif
        </div>
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
            <label>First Name :</label>
            <input type="text" name="first_name" class="form-control" value="{{$data['first_name']}}" >
            <label>Second Name</label>
            <input type="text" name="second_name" class="form-control" value="{{$data['second_name']}}">
            <label>Full Name</label>
            <input type="text" name="fullname" class="form-control" value="{{$data['fullname']}}">
            <label> Age :</label>
            <input type="text" name="age" class="form-control" value="{{$data['age']}}">
            <label> Gender :</label>
            <input type="text" name="gender" class="form-control" value="{{$data['gender']}}">
            <label>Family Status :</label>
            <input type="text" name="family_status" class="form-control" value="{{$data['family_status']}}">
            <label>Parent/Guardian Full Name :</label>
            <input type="text" name="parent_guardian_name" class="form-control" value="{{$data['parent_guardian_name']}}">
            <label>Phone :</label>
            <input type="text" name="phone" class="form-control" value="{{$data['phone']}}">
            <label>Occupation :</label>
            <input type="text" name="occupation" class="form-control" value="{{$data['occupation']}}">
            <label>County :</label>
            <input type="text" name="county" class="form-control" value="{{$data['county']}}">
            <label>Ward :</label>
            <input type="text" name="ward" class="form-control" value="{{$data['ward']}}">
            <label>Location :</label>
            <input type="text" name="location" class="form-control" value="{{$data['location']}}">
            {{-- <a href="{{url('edit_student_detail')}}" class="btn btn-warning mt-4">EDIT</a> --}}
        </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingOne">
            {{-- <h4 class="text-center font-weight-bold">School/Institution Details</h4> --}}
            <p class="btn font-weight-bold" style="color: black;text-decoration:underline" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                School/Institution Details
            </p>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
            <label>School Type :</label>
            <input type="text" name="school_type" class="form-control" value="{{$data['school_type']}}">
            <label>Reg/UPI/ADM No :</label>
            <input type="text" name="reg_no" class="form-control" value="{{$data['reg_no']}}">
            <label>School Name :</label>
            <input type="text" name="school_name" class="form-control" value="{{$data['school_name']}}">
            <label>Bank Name :</label>
            <input type="text" name="bank_name" class="form-control" value="{{$data['bank_name']}}">
            <label>Account No :</label>
            <input type="text" name="account_no" class="form-control" value="{{$data['account_no']}}">
            {{-- <a href="{{url('edit_school')}}" class="btn btn-warning mt-4">EDIT</a> --}}
        </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingOne">
            {{-- <h4 class="text-center font-weight-bold">Bursary Details</h4> --}}
            <p class="btn font-weight-bold" style="color: black;text-decoration:underline" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseOne">
                Bursary Details
            </p>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
           
            <label>Bursary Name :</label>
            <input type="text" name="bursary_name" class="form-control" value="{{$data['bursary_name']}}">
            <label>Bursary Type :</label>
            <input type="text" name="bursary_type" class="form-control" value="{{$data['bursary_type']}}">
            <label>Disburser :</label>
            <input type="text" name="disburser" class="form-control" value="{{$data['disburser']}}">
            {{-- <a href="{{url('edit_bursary')}}" class="btn btn-warning mt-4">EDIT</a> --}}
            
        </div>
        </div>
    </div>
        
    </div>
    <div class="row justify-content-between">
        <a href="{{url('bursary')}}" name="index" class="btn btn-success mt-5 font-weight-bold">
    {{-- <input type="submit" name="previous" class="btn btn-success mt-5 font-weight-bold" style="float: left" value="PREVIOUS"> --}}
    PREVIOUS
</a>
    <input type="submit" name="continue" class="btn btn-primary mt-5 font-weight-bold" style="" value="SUBMIT DETAILS">
    </div></form>
    </div>
    </div>
</body>
    <script src="{{asset('bootstrap/jquery/jquery-3.5.1.min.js')}}"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('bootstrap/popper/popper.min.js')}}"></script>
<script src="" type="text/javascript">
    $('.collapse').collapse({
toggle: false
})
 </script>
</html>