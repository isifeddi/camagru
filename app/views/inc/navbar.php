<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #a3c9b1;">

 	<a class="navbar-brand" href="<?php echo URLROOT;?>"><?php echo SITENAME;?></a>

  <button id="btn" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapseNavbar" aria-controls="collapseNavbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

    <div class="collapse navbar-collapse" id="collapseNavbar">
      <div class="navbar-nav ml-auto">
     	  <a class="nav-item nav-link active" href="#">Gallery <span class="sr-only">(current)</span></a>
     	  <?php if(isset($_SESSION['user_id'])) : ?>
            <a class="nav-item nav-link" href="<?php echo URLROOT;?>/users/edit">Edit</a>
            <a class="nav-item nav-link" href="<?php echo URLROOT;?>/users/profile"><?php echo $_SESSION['user_username']; ?></a>
            <a class="nav-item nav-link" href="<?php echo URLROOT;?>/users/logout">Logout</a>
     	  <?php else : ?>
      		<a class="nav-item nav-link" href="<?php echo URLROOT;?>/users/login">Login</a>
      		<a class="nav-item nav-link" href="<?php echo URLROOT;?>/users/registration">Register</a>
        <?php endif; ?>
      </div>
    </div>
</nav>