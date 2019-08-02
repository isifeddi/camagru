<?php
class Users extends Controller{
    public function __construct(){

        $this->userModel = $this->model('User');
    }


    public function registration(){
    	//Check for post
    	if($_SERVER['REQUEST_METHOD'] == 'POST')
    	{
    		//Process form

            //Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            //Init data
            $data =[
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_pass' => trim($_POST['confirm_pass']),
                'firstname_err' => '',
                'lastname_err' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_pass_err' => '',
                
            ];
            // Validate name username and email
            if(empty($data['firstname'])){
                $data['firstname_err'] = 'Please enter firstname';
            }
            if(empty($data['lastname'])){
                $data['lastname_err'] = 'Please enter lastname';
            }
            if(empty($data['username'])){
                $data['username_err'] = 'Please enter username';
            } else {
                //Check email
                if($this->userModel->findUserByUsername($data['username'])){
                    $data['username_err'] = 'Username is already taken';
                }
            }
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } elseif($this->userModel->findUserByEmail($data['email'])){
                //Check email
                $data['email_err'] = 'Email is already taken';
            }else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Email is not valid';
            }
            

            //Validate pass
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }else if (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            //Confirm pass
            if(empty($data['confirm_pass'])){
                $data['confirm_pass_err'] = 'Please confirm password';
            }else{
                if ($data['password'] != $data['confirm_pass'] ){
                    $data['confirm_pass_err'] = 'Passwords does not match';
                }
            }

            //Make sure errors are empty
            if(empty($data['firstname_err']) && empty($data['lastname_err']) && empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_pass_err'])){
                //Validated
                

                //Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                //Register user
                if($this->userModel->register($data)){
                    if($this->c_send_email($data['email']))
                    {
                        flash('register_success', 'You are registred and can log in, after verifying your account');
                        redirect('users/verification');
                    }
                    else
                    {
                        flash('email_fail', 'Error sending confirmation email, please retry', 'alert alert-danger');
                        $this->view('users/registration',$data);
                    }

                }else {
                    die('Something went WRONG');
                }

            }else{
                $this->view('users/registration',$data);
            }

    	}
    	else 
    	{
    		//Init data
    		$data =[
    			'firstname' => '',
    			'lastname' => '',
    			'username' => '',
    			'email' => '',
    			'password' => '',
    			'confirm_pass' => '',
    			'firstname_err' => '',
    			'lastname_err' => '',
    			'username_err' => '',
    			'email_err' => '',
    			'password_err' => '',
    			'confirm_pass_err' => '',
    		];
    	
        	$this->view('users/registration',$data);
    	}
    }

    public function login(){
    	//Check for post
    	if($_SERVER['REQUEST_METHOD'] == 'POST')
    	{
    		//Process form
            //Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            //Init data
            $data =[     
                'username' => trim($_POST['username']),             
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => '',
            ];

            if(empty($data['username'])){
                $data['username_err'] = 'Please enter username';
            }

            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }

            //Check for user
            if($this->userModel->findUserByUsername($data['username'])){
                //User found
            }else {
                //User not found
                $data['username_err'] = 'No user found';
            }

            //Make sure errors are empty
            if(empty($data['username_err']) && empty($data['password_err'])){
                //Validated
                //Check and set logged in user
                
                    $loggedInUser = $this->userModel->login($data['username'], $data['password']);
                    if($loggedInUser){
                        if($this->userModel->is_verified($data))
                           $this->createUserSession($loggedInUser);   
                        else
                        {
                            flash('not_verified', 'You are not verified, enter the code sent in your email', 'alert alert-danger');
                            $this->view('users/verification', $data);
                        }
                                          
                    }else {
                        $data['password_err'] = 'Password incorrect';
                        $this->view('users/login', $data);
                    }


            }else
                    $this->view('users/login',$data);
    	}
    	else 
    	{
    		//Init data
    		$data =[
    			'username' => '',
    			'password' => '',
    			'username_err' => '',
    			'password_err' => '',
    		];
    	
        	$this->view('users/login',$data);
    	}
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_firstname'] = $user->firstname;
        $_SESSION['user_lastname'] = $user->lastname;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_password'] = $user->password;

        redirect('pages/index');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_firstname']);
        unset($_SESSION['user_lastname']);
        unset($_SESSION['user_email']);
        session_destroy();
        redirect('users/login');
    }

    public function isloggedIn($user){
        if(isset($_SESSION['user_id'])){
            return true;
        }else {
            return false;
        }
    }

    public function profile(){
        $this->view('users/profile');
    }


    public function edit()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $_SESSION['user_id'],
                'edit_username' => trim($_POST['edit_username']),
                'edit_lastname' => trim($_POST['edit_lastname']),
                'edit_firstname' => trim($_POST['edit_firstname']),
                'edit_email' => trim($_POST['edit_email']),
                'edit_new_password' => $_POST['edit_new_password'],
                'edit_password' => $_POST['edit_password'],
                'checkbox_username' => $_POST['checkbox_username'],
                'checkbox_lastname' => $_POST['checkbox_lastname'],
                'checkbox_firstname' => $_POST['checkbox_firstname'],
                'checkbox_email' => $_POST['checkbox_email'],
                'checkbox_new_password' => $_POST['checkbox_new_password'],
                'sem' => 0,
                'edit_username_err' => '',
                'edit_lastname_err' => '',
                'edit_firstname_err' => '',
                'edit_email_err' => '',
                'edit_new_password_err' => '',
                'edit_password_err' => '',

            ];
            
            if(isset($data['checkbox_username']))
            {
                if(empty($data['edit_username']))
                    $data['edit_username_err'] = 'Enter username';
                else if($this->userModel->findUserByUsername($data['edit_username']))
                    $data['edit_username_err'] = 'Username is already taken';
            }else
                $data['edit_username'] = $_SESSION['user_username'];

            if(isset($data['checkbox_lastname']))
            {
                if(empty($data['edit_lastname']))
                    $data['edit_lastname_err'] = 'Enter lastname';
            }else
                $data['edit_lastname'] = $_SESSION['user_lastname'];

            if(isset($data['checkbox_firstname']))
            {
                if(empty($data['edit_firstname']))
                    $data['edit_firstname_err'] = 'Enter firstname';
            }else
                $data['edit_firstname'] = $_SESSION['user_firstname'];
        
            if(isset($data['checkbox_email']))
            {
                if(empty($data['edit_email']))
                    $data['edit_email_err'] = 'Enter email';
                else if($this->userModel->findUserByEmail($data['edit_email'])){
                    $data['edit_email_err'] = 'Email is already taken';
                }
                else if (!filter_var($data['edit_email'], FILTER_VALIDATE_EMAIL)) {
                    $data['edit_email_err'] = 'Email is not valid';
                }
                else if(!empty($data['edit_email'])){
                    $data['sem'] = 1;
                }
            } else
                $data['edit_email'] = $_SESSION['user_email'];


            if(isset($data['checkbox_new_password']))
            {
                if(empty($data['edit_new_password']))
                     $data['edit_new_password_err'] = 'Enter new password';
                else if(strlen($data['edit_new_password']) < 6)
                    $data['edit_new_password_err'] = 'Password must be at least 6 characters';
            } else
                $data['edit_new_password'] = $data['edit_password'];

            if(empty($data['edit_password']))
                $data['edit_password_err'] = 'Enter current password';

            if(empty($data['edit_firstname_err']) && empty($data['edit_lastname_err']) && empty($data['edit_username_err']) && empty($data['edit_email_err']) && empty($data['edit_new_password_err']) && empty($data['edit_password_err']))
            {

                if($this->userModel->edit($data)){
                    $this->view('users/edit', $data);
                    if($data['sem'] == 1){
                        if($this->c_send_email($data['edit_email']))
                        {
                            $this->userModel->activation($data['edit_username'], 0);
                            flash('edit_send_success', 'You should verify your account before logging in next time');
                        }
                        else
                        {
                            flash('edit_email_fail', 'Error sending confirmation email, please retry', 'alert alert-danger');
                            $this->view('users/edit',$data);
                        }
                    }
                }else{
                    $data['edit_password_err'] = 'Incorrect password';
                    $this->view('users/edit', $data);
                }
            }
            else
                $this->view('users/edit', $data);

        }else{
            $data = [
                'id' => '',
                'edit_username' => '',
                'edit_lastname' => '',
                'edit_firstname' => '',
                'edit_email' => '',
                'edit_new_password' => '',
                'edit_password' => '',
                'checkbox_username' => '',
                'checkbox_lastname' => '',
                'checkbox_firstname' => '',
                'checkbox_email' => '',
                'checkbox_new_password' => '',
                'sem' => 0,
                'edit_username_err' => '',
                'edit_lastname_err' => '',
                'edit_firstname_err' => '',
                'edit_email_err' => '',
                'edit_new_password_err' => '',
                'edit_password_err' => '',

            ];
            $this->view('users/edit', $data);
        }

    }

    public function verification(){

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'verify_username' => trim($_POST['verify_username']),
                'code' => trim($_POST['code']),
                'verify_username_err' => '',
                'code_err' => '',
            ];

            if(empty($data['verify_username']))
                $data['verify_username_err'] = 'Enter username';
            else if(!($this->userModel->findUserByUsername($data['verify_username'])))
                $data['verify_username_err'] = 'No user found';

            if(empty($data['code'])){
                $data['code_err'] = 'Please enter code';
            
            }

            if(empty($data['verify_username_err']) && empty($data['code_err'])){
                //Validated
                if($this->userModel->verify($data)){
                    //Verified
                    flash('verification_success', 'Verified successfully, you can login to your account');
                    redirect('users/login');          
                }else
                    $data['code_err'] = 'Incorrect code';
            }
                $this->view('users/verification', $data);
        }
        else{
            $data = [
                'verify_username' => '',
                'code' => '',
                'verify_username_err' => '',
                'code_err' => '',
            ];
            $this->view('users/verification', $data);
        }
    }

    public function c_send_email($email){
        $destinataire = $email;
        $sujet = "Activate your account" ;
        $str = "cv89tyui56opa4sdfg*-_+bqwer123hjklzxnm7";
        $cle = str_shuffle($str);
 
        $message = '
        <p>Welcome to Camagru,
            <br /><br />
            To verify your account, Please copy/paste the code below in the verification page :
        </p>
        <p>
            <br/>
            Code = '.$cle.'
        </p>
        <p>
            <br />--------------------------------------------------------
            <br />This is an automatic mail , please do not reply.
        </p> ';
        // Always set content-type when sending HTML email_fail
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <isifeddi@Camagru.ma>' . "\r\n";

        mail($destinataire, $sujet, $message, $headers); // Envoi du mail
        if($this->userModel->update_code($email, $cle))
            return true;
        else
            return false;

    }

    public function l_send_email($email){
        $destinataire = $email;
        $sujet = "Reset your password" ;
        $str = "cv89tyui56opa4sdfg*-_+bqwer123hjklzxnm7";
        $cle = str_shuffle($str);
 
        $message = '
        <p>Welcome to Camagru,
            <br /><br />
            To reset your password, Please click on the link below or copy/paste it in your navigator :
        </p>
        <p>
            <br/>
            To recover your account click here 
            <a href="http://localhost/Camagru/users/reset_password/?email='.urlencode($email).'&cle='.urlencode($cle).'">
            <button type="button" class="btn btn-primary">Change Password</button>
        </a>
        </p>
        <p>
            <br />--------------------------------------------------------
            <br />This is an automatic mail , please do not reply.
        </p> ';

        // Always set content-type when sending HTML email_fail
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <isifeddi@Camagru.ma>' . "\r\n";

        mail($destinataire, $sujet, $message, $headers); // Envoi du mail
        if($this->userModel->update_r_code($email, $cle))
            return true;
        else
            return false;

    }

    public function forgot_password(){

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [

                'forgot_email' => trim($_POST['forgot_email']),
                'forgot_email_err' => '',
           ];

            if(empty($data['forgot_email']))
                $data['forgot_email_err'] = 'Enter email';
            else if($this->userModel->findUserByEmail($data['forgot_email']))
                $data['forgot_email_err'] = '';
            else if(!$this->userModel->findUserByEmail($data['forgot_email']))
                $data['forgot_email_err'] = 'Email does not exist';      
            else if (!filter_var($data['forgot_email'], FILTER_VALIDATE_EMAIL))
                $data['forgot_email_err'] = 'Email is not valid';

            if(empty($data['forgot_email_err']))
            {
                if($this->l_send_email($data['forgot_email']))
                {
                    flash('email_sent_success', 'Reset link is sent to your email');
                }
                else
                {
                    flash('email_fail', 'Error sending email, please retry', 'alert alert-danger');
                    $this->view('users/forgot_password',$data);
                }
            }
                $this->view('users/forgot_password',$data);

        }else{
            $data = [

                'forgot_email' => '',
                'forgot_email_err' => '',
           ];
            $this->view('users/forgot_password', $data);
        }
    }

    public function reset_password(){

        if(isset($_GET['email']) && isset($_GET['cle']))
        {
            $email = $_GET['email'];
            $cle = $_GET['cle'];

            if($this->userModel->verify_get_cle($email, $cle))
            {
                if($_SERVER['REQUEST_METHOD'] == 'POST')
                {
                    $data = [
                       'get_email' => $email,
                        'get_cle' => $cle,
                        'reset_password' => $_POST['reset_password'],
                        'conf_reset_password' => $_POST['conf_reset_password'],
                       'reset_password_err' => '',
                        'conf_reset_password_err' => '',
                    ];

                   if(empty($data['reset_password'])){
                       $data['reset_password_err'] = 'Please enter password';
                    }else if (strlen($data['reset_password']) < 6) {
                        $data['reset_password_err'] = 'Password must be at least 6 characters';
                   }

                    //Confirm pass
                    if(empty($data['conf_reset_password'])){
                        $data['conf_reset_password_err'] = 'Please confirm password';
                    }else{
                        if ($data['reset_password'] != $data['conf_reset_password'] ){
                            $data['conf_reset_password_err'] = 'Passwords does not match';
                        }
                    }
                
                    if(empty($data['reset_password_err']) && empty($data['conf_reset_password_err']))
                    {
                        $data['reset_password'] = password_hash($data['reset_password'], PASSWORD_DEFAULT);
                        if($this->userModel->reset($data))
                        {
                            flash('reset_success', 'Your password has been reset successfully');
                            redirect('users/login');
                            $c = 'x';
                            $this->userModel->update_r_code($data['get_email'], $c);

                        }
                        else{
                            die('reset failed');
                        }
                    }
                else
                    $this->view('users/reset_password', $data);
                }
                else
                {
                    $data = [
                    
                        'reset_password' => '',
                       'conf_reset_password' => '',
                       'reset_password_err' => '',
                        'conf_reset_password_err' => '',
                    ];

                    $this->view('users/reset_password', $data);
                }
            }else
                die('Wrong parameters');
        }
    }

}