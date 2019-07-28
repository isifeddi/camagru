<?php require APPROOT . '/views/inc/header.php'; ?>
<br><br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white btn">
                    <?php flash('edit_send_success');?>
                    <?php flash('edit_email_fail'); ?>
                    <strong>Profile</strong>
                </div>
                <div class="card-body" >
                <form action="<?php echo URLROOT ;?>/users/edit" method="post">

                    <strong>Username</strong>
                    <div class="row">
                        <div class="col-md-10">
                            <input placeholder="<?php echo $_SESSION['user_username'];?>" value="<?php echo $data['edit_username']; ?>" disabled id="0" name="edit_username" class="form-control form-control-lg <?php echo (!empty($data['edit_username_err'])) ? 'is-invalid' : ''; ?>" >
                            <span class="invalid-feedback"><?php echo $data['edit_username_err']; ?>
                            </span>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="checkbox" name="checkbox_username" onclick="enable_text(this.checked, 0)">
                                <label class="form-check-label" for="exampleCheck1">
                            </div>
                        </div>
                    </div>


            
                    <strong>Lastname</strong>
                    <div class="row">
                        <div class="col-md-10">
                            <input placeholder="<?php echo $_SESSION['user_lastname'];?>" value="<?php echo $data['edit_lastname']; ?>" disabled id="1" name="edit_lastname" class="form-control form-control-lg <?php echo (!empty($data['edit_lastname_err'])) ? 'is-invalid' : ''; ?>" >
                            </input>
                            <span class="invalid-feedback"><?php echo $data['edit_lastname_err']; ?>
                            </span>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="checkbox" name="checkbox_lastname" onclick="enable_text(this.checked, 1)">
                                <label class="form-check-label" for="exampleCheck1">
                            </div>
                        </div>
                    </div>

                    <strong>Firstname</strong>
                    <div class="row">
                        <div class="col-md-10">
                            <input placeholder="<?php echo $_SESSION['user_firstname'];?>" value="<?php echo $data['edit_firstname']; ?>" disabled id="2" name="edit_firstname" class="form-control form-control-lg <?php echo (!empty($data['edit_firstname_err'])) ? 'is-invalid' : ''; ?>">
                            </input>
                            <span class="invalid-feedback"><?php echo $data['edit_firstname_err']; ?></span>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="checkbox" name="checkbox_firstname" onclick="enable_text(this.checked, 2)">
                                <label class="form-check-label" for="exampleCheck1">
                            </div>
                        </div>
                    </div>

                    <strong>Email</strong>
                    <div class="row">
                        <div class="col-md-10">
                            <input  placeholder="<?php echo $_SESSION['user_email'];?>" value="<?php echo $data['edit_email']; ?>" disabled id="3" name="edit_email" class="form-control form-control-lg <?php echo (!empty($data['edit_email_err'])) ? 'is-invalid' : ''; ?>" >
                            </input>
                            <span class="invalid-feedback"><?php echo $data['edit_email_err']; ?></span>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="checkbox" name="checkbox_email" onclick="enable_text(this.checked, 3)">
                                <label class="form-check-label" for="exampleCheck1">
                            </div>
                        </div>
                    </div>

                    <strong>Change password</strong>
                    <div class="row">
                        <div class="col-md-10">
                            <input  disabled id="4" name="edit_new_password" class="form-control form-control-lg <?php echo (!empty($data['edit_new_password_err'])) ? 'is-invalid' : ''; ?>" type="password">
                            </input>
                            <span class="invalid-feedback"><?php echo $data['edit_new_password_err']; ?></span>
                        </div>
                        <div class="col-md-2">
                          <div class="form-check">
                                <input type="checkbox" name="checkbox_new_password" onclick="enable_text(this.checked, 4)">
                                <label class="form-check-label" for="exampleCheck1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <strong>Current password</strong>
                        <input class="form-control form-control-lg <?php echo (!empty($data['edit_password_err'])) ? 'is-invalid' : ''; ?>" name="edit_password" type="password">
                        </input>
                        <span class="invalid-feedback"><?php echo $data['edit_password_err']; ?></span>
                    </div>
                
                    <div class="form-group">
                            <button type="submit"  href="<?php echo URLROOT ;?>/users/edit" name="edit" value="edit" class="btn btn-block btn-dark"> Edit </button>
                    </div>

                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>