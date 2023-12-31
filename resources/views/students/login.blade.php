<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src={{ asset('bootstrap/jquery/jquery-3.5.1.min.js') }}></script>
    <title>Login page-students:</title>
</head>
<body>
    <div class="container col-md-8" style="margin-top: 20vh">
        <div class="row" style="border: .1px light black">
        <div class="col-md-6">
            
            <div class="card-header jalign-item-center" style="background-image: url('images/logo.png');background-position:center;background-repeat:no-repeat;height:50vh">
                
                <h4 class="text-center font-weight-bold">LOGIN HERE</h4>
            </div>
            </div>
            <div class="col-md-6" style="margin-top: 6vh">
            <div class="card-body">
                @if(session()->has('message'))
                <div class="alert alert-warning alert-dismissible fade show text-center"  role="alert" style="position:sticky">
                    <span class="font-weight-bold">{{session()->get('message')}}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                         </button>
                         </div>
                @endif
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show text-center"  role="alert" style="position:sticky">
                    <span class="font-weight-bold">{{session()->get('success')}}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                         </button>
                         </div>
                @endif
                @if($errors->has('email_reset'))
                <div class="alert alert-danger alert-dismissible fade show text-center"  role="alert" style="position:sticky">
                    <span class="font-weight-bold">{{$errors->first('email_reset')}}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                         </button>
                         </div>
                 @endif
                <form method="POST" action="{{url('students/login_req')}}">
                    @csrf
                <label class="font-weight-bold">Enter Parent Email address :</label>
                <input type="email" name="email" class="form-control" id="" placeholder="example@admin.com" value="{{old('email')}}">
                @if($errors->has('email'))
                <span class="text-danger">{{$errors->first('email')}}</span><br>
                @endif
                <label class="font-weight-bold">Enter Password :</label>
                <input type="password" name="password" class="form-control" id="" placeholder="********">
                @if($errors->has('password'))
                <span class="text-danger">{{$errors->first('password')}}</span><br>
                @endif
                <input type="submit" class="btn btn-primary mt-2" value="LOGIN">
                </form>
                <div class="row justify-content-between">
                   <p class="mt-5">If not Registered Click <a href="{{url('students/register')}}"> Here</a></p>
                   <a href="" data-toggle="modal" data-target="#Modal" class="mt-5">Forgot Password?</a>
            </div>
            </div>
        </div>
    </div>

     <!-- reset password modal -->
     <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                <h4 class="text-center">Reset Password</h4>
             </div>
             <div class="modal-body">
                <form method="post" action="{{url('reset_pass')}}">
                    @csrf
                <label class="font-weight-bold">Enter Email :</label>
                 <input type="email" name="email" class="form-control" id="" required>
                 {{-- @if($errors->has('email_reset'))
                 <span>{{$errors->first('email_reset')}}</span><br>
                 @endif --}}
                 <input type="submit" value="Reset" name="reset" class="btn btn-primary mt-2">
                </form>
             </div>
         </div>
     </div>
 </div>
</body>
<script>
    const inputFields = document.querySelectorAll('input');

// Turn on autofill for all input fields.
inputFields.forEach(inputField => {
  inputField.autocomplete = 'on';
});
</script>
<script src={{asset('bootstrap/js/bootstrap.min.js')}}></script>
<script src={{asset('bootstrap/popper/popper.min.js')}}></script>
<script src={{asset('bootstrap/js/bootstrap.js')}}></script>
</html>