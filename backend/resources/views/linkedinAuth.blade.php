<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>

<div class="container">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">

                <div class="panel-heading">Login</div>

                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">

                        {!! csrf_field() !!}


                        <div class="form-group">

                            <label class="col-md-4 control-label">E-Mail Address</label>


                            <div class="col-md-6">

                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                            </div>

                        </div>


                        <div class="form-group">

                            <label class="col-md-4 control-label">Password</label>


                            <div class="col-md-6">

                                <input type="password" class="form-control" name="password">

                            </div>

                        </div>


                        <div class="form-group">

                            <div class="col-md-6 col-md-offset-4">

                                <div class="checkbox">

                                    <label>

                                        <input type="checkbox" name="remember"> Remember Me

                                    </label>

                                </div>

                            </div>

                        </div>


                        <div class="form-group">

                            <div class="col-md-6 col-md-offset-4">


                                <a href="{{ url('auth/linkedin') }}" class="btn btn-primary">

                                    <strong>Login With Linkedin</strong>

                                </a>


                                <button type="submit" class="btn btn-primary">

                                    Login

                                </button>


                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
<script type="text/javascript">
    $(document).ready(function(){
        window.MyLib = "Omar";
    });
</script>
</html>