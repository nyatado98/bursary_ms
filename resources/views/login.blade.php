<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src={{ asset('bootstrap/jquery/jquery-3.5.1.min.js') }}></script>
    <title>Login page:</title>
</head>
<body>
    <div class="container col-md-8" style="margin-top: 20vh">
        <div class="row" style="border: .1px solid black">
        <div class="col-md-6">
            
            <div class="card-header jalign-item-center" style="background-image: url('images/logo.png');background-position:center;background-repeat:no-repeat;height:50vh">
                
                <h4 class="text-center font-weight-bold">LOGIN HERE</h4>
            </div>
            </div>
            <div class="col-md-6" style="margin-top: 6vh">
            <div class="card-body">
                <form method="POST" action="{{url('login')}}">
                    @csrf
                <label class="font-weight-bold">Enter Phone no :</label>
                <input type="number" name="phone" class="form-control" id="">
                <label class="font-weight-bold">Enter Password :</label>
                <input type="password" name="password" class="form-control" id="">
                <input type="submit" class="btn btn-primary mt-2" value="LOGIN">
                </form>
                <div class="row justify-content-between">
                   <p class="mt-5">If not Registered Click <a href="{{url('register')}}"> Here</a></p>
                   <a href="#" data-toggle="modal" data-target="#Modal" class="mt-5">Forgot Password?</a>
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
                <form method="get" action="">
                    @csrf
                <label class="font-weight-bold">Enter Email :</label>
                 <input type="email" name="email" class="form-control" id="">
                 <input type="submit" value="Reset" name="reset" class="btn btn-primary mt-2">
                </form>
             </div>
         </div>
     </div>
 </div>
</body>
<script src={{asset('bootstrap/js/bootstrap.min.js')}}></script>
<script src={{asset('bootstrap/popper/popper.min.js')}}></script>
<script src={{asset('bootstrap/js/bootstrap.js')}}></script>
</html>