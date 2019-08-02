<?php require APPROOT . '/views/inc/header.php'; ?>

<br><br><br>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
				<header class="card-header">
					<?php flash('register_success'); ?>
					<?php flash('not_verified'); ?>
					<h4 class="card-title mt-2">Verify</h4>
				</header>
				<article class="card-body">
					<div class="col-lg-3"></div>
					<div class="col-lg-6"></div>
					<form  action="<?php echo URLROOT;?>/users/verification" method="post">

						<div class="form-group">
								<label for="verify_username">Username<sup>*</sup></label>
								<input type="text" name="verify_username" class="form-control form-control-lg <?php echo(!empty($data['verify_username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['verify_username'];?>">
								<span class="invalid-feedback"><?php echo $data['verify_username_err'];?></span>
						</div>
						<div class="form-group">
            				<label for="code">Verification Code: <sup>*</sup></label>
            				<input type="text" name="code" class="form-control form-control-lg <?php echo (!empty($data['code_err'])) ? 'is-invalid' : ''; ?>">
            				<span class="invalid-feedback"><?php echo $data['code_err'];?></span>
          				</div>
							
						<br>
						<input type="submit" name="submit" value="Verify" class="btn btn-lg btn-success btn-block">
					</form>
				</article>		
			</div>
		</div>
	</div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>