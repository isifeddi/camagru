<?php require APPROOT . '/views/inc/header.php'; ?>

<br><br><br>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
				<header class="card-header">
					<?php flash('reset_link'); ?>
					<?php flash(''); ?>
					<h4 class="card-title mt-2">Reset password</h4>
				</header>
				<article class="card-body">
					<div class="col-lg-3"></div>
					<div class="col-lg-6"></div>
					<form  method="post">

						<div class="form-group">
								<label for="reset_password">New password<sup>*</sup></label>
								<input type="password" name="reset_password" class="form-control form-control-lg <?php echo(!empty($data['reset_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['reset_password'];?>">
								<span class="invalid-feedback"><?php echo $data['reset_password_err'];?></span>
						</div>
						<div class="form-group">
            				<label for="conf_reset_password">Confirm new password<sup>*</sup></label>
            				<input type="password" name="conf_reset_password" class="form-control form-control-lg <?php echo (!empty($data['conf_reset_password_err'])) ? 'is-invalid' : ''; ?>">
            				<span class="invalid-feedback"><?php echo $data['conf_reset_password_err'];?></span>
          				</div>
							
						<br>
						<input type="submit" name="submit" value="Reset password" class="btn btn-lg btn-success btn-block">
					</form>
				</article>		
			</div>
		</div>
	</div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>