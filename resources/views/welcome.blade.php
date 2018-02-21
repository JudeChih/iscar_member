<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="google-signin-scope" content="profile email">
        <meta name="google-signin-client_id" content="344630307125-hohabs1ktlmagr9f2lokergoruui2tdq.apps.googleusercontent.com">

        <title>測試頁面</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
                color: #E16B8C;
                animation: mymove 3s infinite;
            }
            @keyframes mymove {
                0%   {color: #E16B8C;}
                16%  {color: #F05E1C;}
                32%  {color: #FC9F4D;}
                48%  {color: #BEC23F;}
                64%  {color: #69B0AC;}
                80%  {color: #1E88A8;}
                96%  {color: #8A6BBE;}
                100%  {color: #E16B8C;}
            }

            .echoTable{
                
            }
            .margin-top{
                margin-top:5px;
            }
        </style>

        <script type="text/javascript" src="app/libs/jquery/dist/jquery-1.11.3.min.js"></script>
        {{-- <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script> --}}
        <script src="https://apis.google.com/js/platform.js" async defer></script>

        <script type="text/javascript">
            $(function(){
                var mod = location.hash;
                console.log(mod);
                var href = location.href;
                console.log(href);
                var href_new = href.replace('#','?');
                console.log(href_new);
                if(href_new != href){
                    location.href = href_new;
                }
            })
        </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Don't Touch My Shoulders
                </div>
                <div class="echoTable">
                    @if(isset($error))
                        {{$error}}
                    @endif
                </div>

                {{-- <div class="g-signin2" data-onsuccess="onSignIn"></div>
                <a class="btn btn-success" href="#" onclick="signOut();">登出</a> --}}

                <div class="links">
                    {{-- <form action="{{$posturi}}" method="post">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="passwordold">old password</label>
                            <div>
                                <input type="text" id="passwordold" name="passwordold">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passwordnew">new password</label>
                            <div>
                                <input type="text" id="passwordnew" name="passwordnew">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passwordnewconfirm">password confirm</label>
                            <div>
                                <input type="text" id="passwordnewconfirm" name="passwordnewconfirm">
                            </div>
                        </div>
                        <button type="submit">送出</button>
                    </form> --}}
                </div>
            </div>
        </div>
    </body>
</html>
