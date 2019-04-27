

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.css" rel="stylesheet"  type='text/css'>
        <title>Register</title>
        <style>
            header{
                background-color: #FAFAFA;
                border-bottom: 1px solid #cccccc;
                position: fixed;
                top:0;
                left:0;
                width:100%;
            }
            header a{
                box-sizing: border-box;
                width: 33.3333333%;
                display: inline-block;
                margin-left: -3px;
                text-align: center;
                padding: 12px;
            }
            a i{
                color: rgb(3, 46, 63);
                font-size: 25px;
            }
            body{
                height: 100vh;
                background: #BBD2C5;  
                background: -webkit-linear-gradient(to top, #536976, #BBD2C5);
                background: linear-gradient(to top, #536976, #BBD2C5);

            }
            .login{
                padding: 10px;
                background: rgb(3, 46, 63);
                border-radius: 24px;
                border: 5px solid rgb(127, 146, 146);
                margin: auto;
                width: 300px;
                margin-top:30%;

            }
            label{
                color: white;
                margin-left: 15px;

            }
            .button{
                border:1px solid;
                border-radius: 24px;
                color: white;
                margin-left: 15px;
                width : 270px;
                height: 30px;
                background-color: rgb(127, 146, 146);
            }
            .input1{
                margin-left: 15px;
                width : 270px;
                height: 30px;
                border: none;
                background: transparent;
                border-top: transparent ;
                border-left: transparent ;
                border-right: transparent ;
                border-bottom: 2px solid ;
                border-radius: 5px;
                color: rgb(127, 146, 146);
            }
            h1{
                text-align: center;
                color: white;
            }
            #d{
                margin-left: 15px;
                color:white;
                }
            #s{
                color: rgb(190, 243, 243);
            }
            header{
                background-color: #FAFAFA;
                border-bottom: 1px solid #cccccc;
                position: fixed;
                top:0;
                left:0;
                width:100%;
            }
        </style>
    </head>

    <header>
            <a href="home.html"><i class="fas fa-home"></i></a>
            <a href="camera.html"><i class="fas fa-camera"></i></a>
            <a href="profile.html"><i class="far fa-user"></i></a>
    </header>
    
    <body>
        <div class="login">
        <h1>Sign Up</h1>
        <br />
        <form method="post" action="s.php" >
            <p>
                <label ><strong>First name</strong></label><br/>
                <input class="input1" type="text" name="firstname"  />
                <br />
                <br />
                <label><strong>Last name</strong></label><br/>
                <input class="input1" type="text" name="lastname"  />            
                <br/>
                <br/>
                <label><strong>Birthdate</strong></label><br/>
                <input class="input1" type="date" name="birthdate"  />
                <br/>
                <br/>
                <label><strong>Gender</strong></label><br/>
                <select class="input1" name="gender">
                    <option value="m">Male</option>
                    <option value="f">Female</option>
                </select>
                <br/>
                <br/>
                <label ><strong>Email</strong></label><br/>
                <input class="input1" type="text" name="email"  />
                <br />
                <br />
                <label><strong>Username</strong></label><br/>
                <input class="input1" type="text" name="username"/>            
                <br/>
                <br/>
                <label><strong>Password</strong></label><br/>
                <input class="input1" type="password" name="password"  />
                <br/>
                <br/>
                <label><strong>Confirm password</strong></label><br/>
                <input class="input1" type="password" name="password2"  />
                <br/>
                <br/>
                <input class="button" type="submit" value="Submit" />
                <br/>
                <br/>
                <p id="d">You already have an account ? <a id="s" href="login.html">Sign In</a></p>
            </p>
        </form>
        </div>
    </body>
</html>