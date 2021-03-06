<?php
class User {
	private $db;

	public function __construct(){
		$this->db = new Database;
	}

	//Register user
	public function register($data){
		$this->db->query('INSERT INTO users (firstname, lastname, username, email, password) VALUES(:firstname, :lastname, :username, :email, :password)');
		$this->db->bind(':firstname', $data['firstname']);
		$this->db->bind(':lastname', $data['lastname']);
		$this->db->bind(':username', $data['username']);
		$this->db->bind(':email', $data['email']);
		$this->db->bind(':password', $data['password']);

		// execute
		if($this->db->execute()){
			return true;
		}else {
			return false;
		}
	}


	//Login user
	public function login($username, $password){
		$this->db->query('SELECT * FROM users WHERE username = :username');
		$this->db->bind(':username', $username);

		$row = $this->db->single();
		$hashed_password = $row->password;
		
		if(password_verify($password, $hashed_password)){
			return $row;
		}else {
			return false;
		}
	}


	//Find user by email
	public function findUserByEmail($email){
		$this->db->query('SELECT * FROM users WHERE email = :email');
		$this->db->bind(':email', $email);

		$row = $this->db->single();
	
	//check row
		if($this->db->rowCount() > 0){
			return $row;
		} else {
			return false;
		}
	}

	public function findUserByUsername($username){
		$this->db->query('SELECT * FROM users WHERE username = :username');
		$this->db->bind(':username', $username);

		$row = $this->db->single();
		
	//check row
		if($this->db->rowCount() > 0){
			return $row;
		} else {
			return false;
		}
	}

	
	public function edit($data){
		$row = $this->findUserById();
		if($row)
		{
			$hashed_password = $row->password;
			if(password_verify($data['edit_password'] ,$hashed_password)){
				$data['edit_password'] = password_hash($data['edit_new_password'], PASSWORD_DEFAULT);
				$_SESSION['user_username'] = $data['edit_username'];
        		$_SESSION['user_firstname'] = $data['edit_firstname'];
        		$_SESSION['user_lastname'] = $data['edit_lastname'];
        		$_SESSION['user_email'] = $data['edit_email'];
        		$_SESSION['user_password'] = $data['edit_password'];
        		$_SESSION['notif'] = $data['checkbox_send_notif'];
				if($this->update($data))
					return true;
				else 
					return false;
			}
			else
				return false;
		}
	}

	public function findUserById(){
		$this->db->query('SELECT * FROM users WHERE id = :id');
		$this->db->bind(':id', $_SESSION['user_id']);

		$row = $this->db->single();
	
		return $row; 
	}

	public function update($data)
	{
		$this->db->query('

			UPDATE `users` SET `firstname`=:edit_firstname,`lastname`=:edit_lastname,`username`=:edit_username,`email`=:edit_email,`password`=:edit_password, `notif`= :n  WHERE id = :id');

		$this->db->bind(':edit_firstname', $data['edit_firstname']);
		$this->db->bind(':edit_lastname', $data['edit_lastname']);
		$this->db->bind(':edit_username', $data['edit_username']);
		$this->db->bind(':edit_email', $data['edit_email']);
		$this->db->bind(':edit_password', $data['edit_password']);
		$this->db->bind(':n', $data['checkbox_send_notif']);
		$this->db->bind(':id', $data['id']);

		if($this->db->execute()){
			return true;
		}else {
			return false;
		}
	}


	////////////////////
	public function verify_user($username){

		$this->db->query('SELECT * FROM users WHERE username = :verify_username');
		$this->db->bind(':verify_username', $username);

		$row = $this->db->single();
		if($row)
			return $row;
		else
			return false;
	}


	public function update_code($email, $code){

		$row = $this->findUserByEmail($email);

		if($row)
		{
			$this->db->query('UPDATE `users` SET `cle`=:code WHERE email= :email');
			$this->db->bind(':code', $code);
			$this->db->bind(':email', $email);

			if($this->db->execute()){
				return true;
			}else {
				return false; 
			}

		}
	}


	public function activation($email , $actif){

		$this->db->query('UPDATE  `users` SET `actif`= :actif WHERE email = :email');
		$this->db->bind(':email', $email);
		$this->db->bind(':actif', $actif);

		if($this->db->execute()){
			return true;
		}else {
			return false;
		}
	}


	public function verify($email, $cle){
		
		$row = $this->findUserByEmail($email);

		if($row && $cle == $row->cle && $email == $row->email)
		{
			if($this->activation($row->email, 1)){
				
				return true;
			}
			else
				return false;
		}
		else
			return false;

	}

	public function is_verified($data)
	{
		$row = $this->findUserByUsername($data['username']);
		if($row && $row->actif == 1)
			return true;
		else
			return false;
	}
	/////////////////////

	public function update_r_code($email, $code){

		$row = $this->findUserByEmail($email);

		if($row)
		{
			$this->db->query('UPDATE `users` SET `r_cle`=:code WHERE email= :email');
			$this->db->bind(':code', $code);
			$this->db->bind(':email', $email);

			if($this->db->execute()){
				return true;
			}else {
				return false; 
			}

		}
	}

	public function verify_get_cle($email, $cle){
		$row = $this->findUserByEmail($email);
		if($row && $email == $row->email && $cle == $row->r_cle)
			return true;
		else
			return false;
	}

	public function reset($data)
	{
		$row = $this->findUserByEmail($data['get_email']);
		if($row)
		{
			$this->db->query('UPDATE `users` SET `password`=:password WHERE email= :email');
			$this->db->bind(':password', $data['reset_password']);
			$this->db->bind(':email', $data['get_email']);

			if($this->db->execute()){
				return true;
			}else {
				return false; 
			}
		}
	}
	///////////////////////////

	public function get_commenter($user_id)
  	{
    $this->db->query('SELECT * FROM users WHERE id = :id');
    $this->db->bind(':id',$user_id);
    $result = $this->db->single();
    if($result)
      return ($result);
    else
      return false;
  	} 

  	public function get_dest($user_id)
  	{
    $this->db->query('SELECT * FROM users WHERE id = :id');
    $this->db->bind(':id',$user_id);
    $result = $this->db->single();
    if($result)
      return ($result);
    else
      return false;
  	} 
  	//////////////////

  	public function profilePic($path, $user_id){
      $this->db->query('UPDATE users SET profile_photo = :p WHERE id = :id');
      $this->db->bind(':id', $user_id);
      $this->db->bind(':p', $path);
      if($this->db->execute())
           return true;
       else
           return false;
   }

}