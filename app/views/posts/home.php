<?php require APPROOT . '/views/inc/header.php'; ?>

<br>
<nav aria-label="Page navigation example justify-content-center">
  <ul class="pagination justify-content-center">
    <?php 

    if(($data['currentPage']-1) > 0)
        echo '<li class="page-item"><a class="page-link" href="http://localhost/Camagru/posts/home?page='.($data['currentPage']-1).'">Previous</a></li>';
    else
        echo '<li class="page-item"><a class="page-link">Previous</a></li>';

    for($i =1; $i <= $data['totalPages']; $i++){
        if($i == $data['currentPage'])
            echo '<li class="page-item"><a class="page-link">'.$i.'</a></li>';
        else
            echo '<li class="page-item"><a class="page-link" href="http://localhost/Camagru/posts/home?page='.$i.'">'.$i.'</a></li>';
    }
    if(($data['currentPage']+1) <= $data['totalPages'])
        echo '<li class="page-item"><a class="page-link" href="http://localhost/Camagru/posts/home?page='.($data['currentPage']+1).'">Next</a></li>';
    else
        echo '<li class="page-item"><a class="page-link">Next</a></li>';

    ?>
  </ul>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if(is_array($data['posts'])){
                foreach ($data['posts'] as $post) :?>
              <div class="shadow  mb-5 bg-white rounded ">
              <div class="card">
                <div class="card-header">
                  <div class="d-flex justify-content-between align-items-center">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="mr-2">
                            <img class="rounded-circle" width="50" height="50" src="<?php echo $post->profile_photo;?>" alt="">
                        </div>
                        <div class="ml-2">
                              <div class="h5 m-0">@<?php echo $post->username;?></div>
                              <div class="h7 text-muted"><?php echo $post->firstname; echo " "; echo$post->lastname;?></div>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i><?php echo $post->created_at;?></div>
            
                    <img src="<?php echo $post->path; ?>" class="img img-fluid" width="495" height="400">
                  </div>
                  <div class="card-footer">
                      <?php
                        $liked = false;
                        foreach ($data['likes'] as $like) {
                            if ($like->user_id == $_SESSION['user_id'] && $like->post_id == $post->post_id) {
                                $liked = true; ?>
                                <i class = "fa fa-heart"
                                   data-post_id="<?php echo $post->post_id; ?>" 
                                   data-user_id="<?php echo $_SESSION['user_id']; ?>" 
                                   data-like_nbr="<?php echo $post->like_nbr;?>" 
                                  onclick="like(event)"
                                  id="l_<?php echo $post->post_id;?>"
                                  name="li_<?php echo $post->post_id;?>">    
                                </i>
                                <?php
                            }
                        }
                        if ($liked === false) {?>
                            <i class = "fa fa-heart-o"  
                              data-post_id="<?php echo $post->post_id;?>" 
                              data-like_nbr="<?php echo $post->like_nbr;?>" 
                              data-user_id="<?php echo $_SESSION['user_id'];?>" 
                              onclick="like(event)" id="l_<?php echo $post->post_id;?>"
                              name="li_<?php echo $post->post_id;?>"> 
                            </i>
                        <?php }
                        ?>
                      <a id="li_nb_<?php echo $post->post_id;?>" class="card-link text-muted"><?php echo $post->like_nbr;?></a>
              
                      <a class="card-link"><i class="fa fa-comment"></i> Comments</a>

                      <div class="cardbox-comments mt-2">
                          
                          <textarea name="comment_<?php echo $post->post_id;?>" class="form-control w-100 mb-2" placeholder="write a comment..." rows="1" style="resize:none"></textarea>
                          <button onclick="comment(event)"
                            data-c-user_id="<?php echo $_SESSION['user_id'];?>"
                            data-c-post_id="<?php echo $post->post_id;?>" class="btn btn-secondary pull-right">Add</button>
                        
                          <br>
                      </div>

                      
                      <?php
                        if(is_array($data['comments']))
                        {
                          foreach($data['comments'] as $comment)
                          {
                            if($comment->post_id == $post->post_id)
                            {
                            ?>
                                <hr class="mb-1 mt-4">
                                <ul class="media-list">
                                    <li class="media">                    
                                        <div class="media-body">
                                            <strong class="text-dark">@<?php echo $comment->username;?></strong>
                                            <p><?php echo htmlspecialchars($comment->content);?></p>
                                        </div>
                                    </li>
                                </ul>
                              <?php
                            }
                          }
                        }?>
                  </div>                      
              </div>
              </div>
              <br>
            <?php endforeach; }?>
        </div>
    </div> 
</div>


<br><br>

<nav aria-label="Page navigation example justify-content-center">
  <ul class="pagination justify-content-center">
    <?php 

    if(($data['currentPage']-1) > 0)
        echo '<li class="page-item"><a class="page-link" href="http://localhost/Camagru/posts/home?page='.($data['currentPage']-1).'">Previous</a></li>';
    else
        echo '<li class="page-item"><a class="page-link">Previous</a></li>';

    for($i =1; $i <= $data['totalPages']; $i++){
        if($i == $data['currentPage'])
            echo '<li class="page-item"><a class="page-link">'.$i.'</a></li>';
        else
            echo '<li class="page-item"><a class="page-link" href="http://localhost/Camagru/posts/home?page='.$i.'">'.$i.'</a></li>';
    }
    if(($data['currentPage']+1) <= $data['totalPages'])
        echo '<li class="page-item"><a class="page-link" href="http://localhost/Camagru/posts/home?page='.($data['currentPage']+1).'">Next</a></li>';
    else
        echo '<li class="page-item"><a class="page-link">Next</a></li>';

    ?>
  </ul>
</nav>

<script src="<?php echo URLROOT;?>/js/home.js" type="text/javascript"></script>
<script src="<?php echo URLROOT;?>/js/comment.js" type="text/javascript"></script>


<?php require APPROOT . '/views/inc/footer.php'; ?>