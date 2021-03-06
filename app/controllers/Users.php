<?php
class Users extends Controller{
    public function __construct(){
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index(){
      redirect('posts/home'); 
      
    }
    
    public function registration(){
    	
       if(!isLoggedIN()){ 
    	if($_SERVER['REQUEST_METHOD'] == 'POST')
    	{
    		
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
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
                'confirm_pass_err' => ''
                
            ];
            
            if(empty($data['firstname'])){
                $data['firstname_err'] = 'Please enter firstname';
            }else if(strlen($data['firstname']) > 30)
                $data['firstname_err'] = 'Too long';
            if(empty($data['lastname'])){
                $data['lastname_err'] = 'Please enter lastname';
            }else if(strlen($data['lastname']) > 30)
                $data['lastname_err'] = 'Too long';
            if(empty($data['username'])){
                $data['username_err'] = 'Please enter username';
            }else if(strlen($data['username']) > 30)
                $data['username_err'] = 'Too long';
            else {
                
                if($this->userModel->findUserByUsername($data['username'])){
                    $data['username_err'] = 'Username is already taken';
                }
            }
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } elseif($this->userModel->findUserByEmail($data['email'])){
                
                $data['email_err'] = 'Email is already taken';
            }else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Email is not valid';
            }
            

            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }else if (strlen($data['password']) < 6)
                $data['password_err'] = 'Password must be at least 6 characters';
            else if (!preg_match('@[A-Z]@', $data['password']))
                $data['password_err'] = 'Password must contain an upper case';
            else if (!preg_match('@[a-z]@', $data['password']))
                $data['password_err'] = 'Password must contain a  lower case';
            else if (!preg_match('@[0-9]@', $data['password']))
                $data['password_err'] = 'Password must contain a number';

            
            if(empty($data['confirm_pass'])){
                $data['confirm_pass_err'] = 'Please confirm password';
            }else{
                if ($data['password'] != $data['confirm_pass'] ){
                    $data['confirm_pass_err'] = 'Passwords does not match';
                }
            }

            if(empty($data['firstname_err']) && empty($data['lastname_err']) && empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_pass_err'])){
 

                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if($this->userModel->register($data)){
                    if($this->c_send_email($data['email']))
                    {
                        redirect('users/login');
                        flash('register_success', 'You are registred and can log in, after verifying your account');
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
    			'confirm_pass_err' => ''
    		];
    	
        	$this->view('users/registration',$data);
    	}
      }
      else
        redirect('posts/home');
    }

    public function login(){
      if(!isLoggedIN()){
    	
    	if($_SERVER['REQUEST_METHOD'] == 'POST')
    	{
    		
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
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

            
            if($this->userModel->findUserByUsername($data['username'])){
                
            }else {
                
                $data['username_err'] = 'No user found';
            }

            
            if(empty($data['username_err']) && empty($data['password_err'])){
                
                    $loggedInUser = $this->userModel->login($data['username'], $data['password']);
                    if($loggedInUser){
                        if($this->userModel->is_verified($data))
                           $this->createUserSession($loggedInUser); 
                        else
                        {
                            redirect('users/login');
                            flash('not_verified', 'You are not verified, go to your email', 'alert alert-danger');
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
    		$data =[
    			'username' => '',
    			'password' => '',
    			'username_err' => '',
    			'password_err' => ''
    		];
    	
        	$this->view('users/login',$data);
    	}
       }else
        redirect('posts/home');
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_firstname'] = $user->firstname;
        $_SESSION['user_lastname'] = $user->lastname;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_password'] = $user->password;
        $_SESSION['notif'] = 1;
        $_SESSION['logout_token'] = $user->cle;
        redirect('posts/index');
    }

    public function logout(){
        if(isset($_GET['token']))
        {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_username']);
            unset($_SESSION['user_firstname']);
            unset($_SESSION['user_lastname']);
            unset($_SESSION['user_email']);
            session_destroy();
            redirect('users/login');
        }
        else
            redirect('posts/index');
    }

    public function isloggedIn(){
        if(isset($_SESSION['user_id'])){
            return true;
        }else {
            return false;
        }
    }

    public function edit()
    {
      if(isLoggedIN()){
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
                'checkbox_send_notif' => $_POST['checkbox_send_notif'],
                'sem' => 0,
                'edit_username_err' => '',
                'edit_lastname_err' => '',
                'edit_firstname_err' => '',
                'edit_email_err' => '',
                'edit_new_password_err' => '',
                'edit_password_err' => ''

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
                else if (!preg_match('@[A-Z]@', $data['edit_new_password']))
                    $data['edit_new_password_err'] = 'Password must contain an upper case';
                else if (!preg_match('@[a-z]@', $data['edit_new_password']))
                    $data['edit_new_password_err'] = 'Password must contain a  lower case';
                else if (!preg_match('@[0-9]@', $data['edit_new_password']))
                    $data['edit_new_password_err'] = 'Password must contain a number';
            }else
                $data['edit_new_password'] = $data['edit_password'];

            if(empty($data['edit_password']))
                $data['edit_password_err'] = 'Enter current password';

            if(empty($data['edit_firstname_err']) && empty($data['edit_lastname_err']) && empty($data['edit_username_err']) && empty($data['edit_email_err']) && empty($data['edit_new_password_err']) && empty($data['edit_password_err']))
            {

                if(isset($data['checkbox_send_notif']))
                {   
                    $data['checkbox_send_notif'] = 1;
                }
                else
                    $data['checkbox_send_notif'] = 0;

                if($this->userModel->edit($data)){
                    
                    if($data['sem'] == 1){
                        if($this->c_send_email($data['edit_email']))
                        {
                            $this->userModel->activation($data['edit_email'], 0);
                            redirect('users/edit');
                            flash('edit_send_success', 'You should verify your account before logging in next time');
                        }
                        else
                        {
                            $this->view('users/edit', $data);
                            flash('edit_email_fail', 'Error sending confirmation email, please retry', 'alert alert-danger');
                            
                        }
                    }
                    else
                       $this->view('users/edit', $data); 
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
        else
            redirect('users/login');

    }

    public function verification()
    {
        if(isset($_GET['email']) && isset($_GET['cle']))
        {
            $email = $_GET['email'];
            $cle = $_GET['cle'];

            if($this->userModel->verify($email, $cle))
            {
                redirect('users/login');
                flash('verification_success', 'Verified successfully, you can login to your account');
                $c = str_shuffle('cv89tyui56opa4sdfg*-_+bqwer123hjklzxnm7');
                $this->userModel->update_code($email, $c);
            }
            else
                die('incorrect');
        }
        else
            die('error verifying');   
    }

    public function c_send_email($email)
    {
        $destinataire = $email;
        $sujet = "Verify your account" ;
        $str = "cv89tyui56opa4sdfg*-_+bqwer123hjklzxnm7";
        $cle = str_shuffle($str);
 
        $message = '
        <p>Welcome to Camagru,
            <br /><br />
            <br/>
            To verify your account click here 
            <a href="http://localhost/Camagru/users/verification/?email='.urlencode($email).'&cle='.urlencode($cle).'">
                <button type="button" class="btn btn-primary">Verify account</button>
            </a>
        </p>
        <p>
            <br />--------------------------------------------------------
            <br />This is an automatic mail , please do not reply.
        </p> ';

        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <isifeddi@Camagru.ma>' . "\r\n";

        mail($destinataire, $sujet, $message, $headers); 
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

        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <isifeddi@Camagru.ma>' . "\r\n";

        mail($destinataire, $sujet, $message, $headers);
        if($this->userModel->update_r_code($email, $cle))
            return true;
        else
            return false;

    }

    public function forgot_password(){
      if(!isLoggedIN()){
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
            }else
                $this->view('users/forgot_password',$data);

        }else{
            $data = [

                'forgot_email' => '',
                'forgot_email_err' => '',
           ];
            $this->view('users/forgot_password', $data);
        }
      }
       else
        redirect('posts/home');
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
                            $c = str_shuffle('cv89tyui56opa4sdfg*-_+bqwer123hjklzxnm7');
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
            }else{
                flash('reset_link_fail', 'Please retry', 'alert alert-danger');
                redirect('users/forgot_password');
            }
        }else
            redirect('posts/home');
    }

}