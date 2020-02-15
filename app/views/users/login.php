<?php require APPROOT . '/views/inc/header.php'; ?>
<br>
<br><br>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
				<header class="card-header">
					<?php flash('verification_success'); ?>
					<?php flash('reset_success'); ?>
					<?php flash('register_success'); ?>
					<?php flash('not_verified'); ?>
					<a href="<?php echo URLROOT;?>/users/registration" class="float-right btn btn-outline-success mt-1">Register</a>
					<h4 class="card-title mt-2">Login</h4>
				</header>
				<article class="card-body">
					<div class="col-lg-3"></div>
					<div class="col-lg-6"></div>
					<form  action="<?php echo URLROOT;?>/users/login" method="post">

						<div class="form-group">
								<label for="username">Username<sup>*</sup></label>
								<input type="text" name="username" class="form-control form-control-lg <?php echo(!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username'];?>">
								<span class="invalid-feedback"><?php echo $data['username_err'];?></span>
						</div>
						<div class="form-group">
            				<label for="password">Password: <sup>*</sup></label>
            				<input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password'];?>">
            				<span class="invalid-feedback"><?php echo $data['password_err'];?></span>
          				</div>
							
						<br>
						<input type="submit" name="submit" value="Login" class="btn btn-lg btn-success btn-block">
					</form>
					<a href="<?php echo URLROOT;?>/users/forgot_password" class="float-right  mt-1">Forgot password?</a>
				</article>		
			</div>
		</div>
	</div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>