<?php require APPROOT . '/views/inc/header.php'; ?>
<br>

<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6 mr-auto">
			<div class="card border-0">
				<div class="p-2 shadow  mb-5  rounded h-100  bg-secondary text-white ">
					<br><h1 style="text-align: center;">Camera</h1>
		            <hr class="bg-white mt-2 mb-5">
					
		    			<img src="" id="imgfilter" class="img-fluid" width="25%" height="25%" style="display: none; position: absolute; left: 5%; top: 15%;">
		    			<video id="video" class="img-fluid border border-warning"  width="400" height="300" autoplay></video>
		    			
					
		    		<br>
					<div class="form-check form-check-inline">
		  				<input class="form-check-input" type="radio" name="filter" id="mask" value="../public/img/face-mask.png">
		  				<img src="../public/img/face-mask.png" width="64" height="64">
					</div>
					<div class="form-check form-check-inline">
		  				<input class="form-check-input" type="radio" name="filter" id="binary" value="../public/img/binary.png">
		  				<img src="../public/img/binary.png" width="64" height="64">
					</div>
					<div class="form-check form-check-inline">
		  				<input class="form-check-input" type="radio" name="filter" id="sunny" value="../public/img/sunny.png">
		  				<img src="../public/img/sunny.png" width="64" height="64">
					</div>
					<div class="form-check form-check-inline">
		  				<input class="form-check-input" type="radio" name="filter" id="rabbit5" value="../public/img/camera.png">
		  				<img src="../public/img/camera.png" width="64" height="64">
					</div>

					<br>
					<br>
					<button id="snap"  class="btn btn-lg btn-warning btn-block" disabled>Take photo</button><br>

						<input id="file" type="file"  name="file" class="btn btn-lg btn-warning btn-block" accept="image/jpg, image/jpeg, image/png"></input><br>

						
							<img src="" id="canvasf" class="img-fluid" width="25%" height="25%" style="display: none; position: absolute; left: 5%; top: 68%;">
			    			<canvas class="img-fluid border border-warning" id="canvas" width="400" height="300"></canvas>
						<br>

						<button id="save" type="submit" name="submit"  class="btn btn-lg btn-primary btn-block" >Save</button><br>

						<button id="clear" class="btn btn-lg btn-primary btn-block">Clear</button>
					
				</div>
			</div>
		</div>
		
	
		<div class="col-md-4">
			<div class="card border-0">
				<div class="shadow  mb-5  rounded  h-100 bg-secondary text-white ">
					<br><h1 style="text-align: center;">Photos</h1>
		            <hr class="bg-white mt-2 mb-5">
		    		<div style="width:100%;height: 800px; overflow-y:auto; overflow-x:hidden;">
			            <div class="row text-center text-lg-left">
			            
			            <?php if(is_array($data)){
			                    foreach($data as $posts):?>
			                
			                    <a class="d-block mb-4">
			                    <img class="img-fluid img-thumbnail" src="<?php echo $posts->path;?>" height="100%" width="100%">
			                    <form action="<?php echo  URLROOT;?>/posts/deletePost" method="POST">
			                    <button type="submit" name="submit1" class="btn btn-danger w-100">Delete</button>
			                    <input  name="postId" type="hidden" value="<?php echo $posts->post_id;?>">
			                    </form>
			                    <form action="<?php echo  URLROOT;?>/posts/profilePic" method="POST">
			                    <button type="submit" name="submit2" class="btn btn-info  w-100">set as profile picture</button>
			                    <input  name="path" type="hidden" value="<?php echo $posts->path;?>">
			                    </form>
			                    </a><br>
			            

			            <?php endforeach;}?>
			            </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo URLROOT;?>/js/image.js"></script>

<?php require APPROOT . '/views/inc/footer.php'; ?>