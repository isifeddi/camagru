<nav class="navbar navbar-expand-lg navbar-light bg-secondary">

 	<a class="navbar-brand text-white" href="<?php echo URLROOT;?>/posts/home"><?php echo SITENAME;?></a>

  <button id="btn" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapseNavbar" aria-controls="collapseNavbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

    <div class="collapse navbar-collapse" id="collapseNavbar">
      <div class="navbar-nav ml-auto">
     	  <?php if(isset($_SESSION['user_id'])) : ?>
            <a class="nav-item nav-link active text-white" href="<?php echo URLROOT;?>/posts/image">Camera<span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link text-white" href="<?php echo URLROOT;?>/users/edit">Edit</a>
            
            <a class="nav-item nav-link text-white" href="<?php echo URLROOT;?>/users/logout?token=<?php echo $_SESSION['logout_token'];?>">Logout</a>
     	  <?php else : ?>
      		<a class="nav-item nav-link text-white" href="<?php echo URLROOT;?>/users/login">Login</a>
      		<a class="nav-item nav-link text-white" href="<?php echo URLROOT;?>/users/registration">Register</a>
        <?php endif; ?>
      </div>
    </div>
</nav>
