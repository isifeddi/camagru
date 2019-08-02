<?php require APPROOT . '/views/inc/header.php'; ?>

<br><br><br>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
				<header class="card-header">
					<?php flash('email_fail');?>
					<?php flash('email_sent_success'); ?>
					<?php flash('reset_link_fail'); ?>
					<h4 class="card-title mt-2">Forgot password</h4>
				</header>
				<article class="card-body">
					<div class="col-lg-3"></div>
					<div class="col-lg-6"></div>
					<form   method="post">
						<div class="form-group">
								<label for="forgot_email">Email<sup>*</sup></label>
								<input type="text" name="forgot_email" class="form-control form-control-lg <?php echo(!empty($data['forgot_email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['forgot_email'];?>">
								<span class="invalid-feedback"><?php echo $data['forgot_email_err'];?></span>
						</div>
						<br>
						<input type="submit" name="submit" value="Send email" class="btn btn-lg btn-success btn-block">
					</form>
				</article>		
			</div>
		</div>
	</div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>