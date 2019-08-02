<?php require APPROOT . '/views/inc/header.php'; ?>

<br><br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
    <div class="card">
        <div class="card-header bg-secondary ">
            <strong>Profile</strong>
        </div>
        <div class="card-body">
            
                <strong >username</strong>
                <div class="form-group form-control form-control-lg">
                <label><?php echo $_SESSION['user_username']?></label>
            </div>
            <strong>lastname</strong>
            <div class="form-group form-control form-control-lg">
                <label><?php echo $_SESSION['user_lastname']?></label>
            </div>
            <strong>firstname</strong>
            <div class="form-group form-control form-control-lg">
                <label> <?php echo $_SESSION['user_firstname']?></label>
            </div>
            <strong>email</strong>
            <div class="form-group form-control form-control-lg">
                <label> <?php echo $_SESSION['user_email']?></label>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>