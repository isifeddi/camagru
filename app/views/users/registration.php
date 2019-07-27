<?php require APPROOT . '/views/inc/header.php'; ?>

<br><br><br>
<div class="container">
	<div class="row justify-content-center">
		<div class="card">
			<header class="card-header">
				<?php flash('email_fail', 'Error sending confirmation email, please retry', 'alert alert-danger');?>
				<a href="<?php echo URLROOT;?>/users/login" class="float-right btn btn-outline-success mt-1">Login</a>
				<h4 class="card-title mt-2">Register</h4>
			</header>
			<article class="card-body">
				
					<form  action="<?php echo URLROOT;?>/users/registration" method="post">
						<div class="form-row">
							<div class="col form-group">
								<label for="firstname">First name <sup>*</sup></label>
            					<input type="text" name="firstname" class="form-control form-control-lg <?php echo (!empty($data['firstname_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['firstname']; ?>">
            					<span class="invalid-feedback"><?php echo $data['firstname_err']; ?></span>
							</div>

							<div class="col form-group">

								<label for="lastname">Last name <sup>*</sup></label>

            					<input type="text" name="lastname" class="form-control form-control-lg <?php echo (!empty($data['lastname_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['lastname']; ?>">
            					
            					<span class="invalid-feedback"><?php echo $data['lastname_err']; ?></span>
							</div>	
						</div>

						<div class="form-group">

								<label for="username">Username<sup>*</sup></label>

								<input type="text" name="username" class="form-control form-control-lg <?php echo(!empty($data['username_err'])) ? 'is-invalid' : '';?>" value="<?php echo $data['username'];?>">
								
								<span class="invalid-feedback"><?php echo $data['username_err'];?></span>
						</div>

						<div class="form-group">
								<label for="email">Email<sup>*</sup></label>
								<input type="Email" name="email" class="form-control form-control-lg <?php echo(!empty($data['email_err'])) ? 'is-invalid' : '';?>" value="<?php echo $data['email'];?>">
								<span class="invalid-feedback"><?php echo $data['email_err'];?></span>
						</div>
						<div class="form-row">
							<div class="col form-group">
								<label for="password">Password <sup>*</sup></label>
            					<input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
            					<span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
							</div>
							<div class="col form-group">
								<label for="confirm_pass">Confirm password<sup>*</sup></label>
								<input type="password" name="confirm_pass" class="form-control form-control-lg <?php echo(!empty($data['confirm_pass_err'])) ? 'is-invalid' : '';?>" value="<?php echo $data['confirm_pass'];?>">
								<span class="invalid-feedback"><?php echo $data['confirm_pass_err'];?></span>
							</div>
						</div>
						<br>
						
						<input type="submit" name="submit" value="registration" class="btn btn-lg btn-success btn-block">
					</form>
			</article>		
		</div>
	
	</div>
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>