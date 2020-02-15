<?php
class Posts extends Controller{

	public function __construct(){
    
    $this->postModel = $this->model('Post');
    $this->userModel = $this->model('User');
	}

  public function index(){
      redirect('posts/home'); 
    }


  public function home(){
    $postsPerPage = 5;
    $totalPosts = $this->postModel->count_posts();
    $totalPages = ceil($totalPosts/$postsPerPage);

    if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $totalPages){

      $_GET['page'] = intval($_GET['page']);
      $currentPage = $_GET['page'];    
    }else
      $currentPage = 1;

    $depart = ($currentPage - 1) * $postsPerPage;
    
      $posts = $this->postModel->get_posts($depart, $postsPerPage);
      $likes = $this->postModel->getlikes();
      $comments = $this->postModel->getcomments();
      $data =[
        'comments'=> $comments,
        'likes' => $likes,
        'posts' => $posts,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
        'depart' => $depart,
      ];
      $this->view('posts/home',$data);
  }

	public function image(){
    
    if(isLoggedIN()){
      $data = $this->postModel->getUserPost($_SESSION['user_id']);
      if($this->s_image()){
        $this->view("posts/image", $data);
      }
      else
		    $this->view("posts/image", $data);
    }else
      redirect('users/login');
	 }

	public function s_image(){
		if(isset($_POST['imgBase64']) && isset($_POST['emoticon']))
    {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $upload_dir = "../public/img/";
      $img = $_POST['imgBase64'];
      $emo = $_POST['emoticon'];
      $img = str_replace('data:image/png;base64,', '', $img);
      $img = str_replace(' ', '+', $img);
      $d = base64_decode($img);
      $file = $upload_dir.mktime().'.png';
      file_put_contents($file, $d);

      list($srcWidth, $srcHeight) = getimagesize($emo);
      $src = imagecreatefrompng($emo);
      $dest = imagecreatefrompng($file);
      imagecopy($dest, $src, 11,11, 0, 0, $srcWidth, $srcHeight);
      imagepng($dest, $file, 9);
      move_uploaded_file($dest, $file);

      $data =[
        'user_id'  => $_SESSION['user_id'],
        'path' => $file,
      ];
      if($this->postModel->save($data)){
        
      }else
        return false;	  
		}
	}

  public function deletePost()
  {
       $data = $this->postModel->getUserPost($_SESSION['user_id']);
       if(isset($_POST['submit1']))
        {
           $postId = $_POST['postId'];
           if($this->postModel->deletePost($postId, $_SESSION['user_id']))
           {
               redirect('posts/image');
           }
           else
               die('ERROR');
       }
       $this->view('posts/image',$data);
   }

   public function profilePic()
   {

        if(isset($_POST['submit2']))
        {
            $path = $_POST['path'];
            if($this->userModel->profilePic($path, $_SESSION['user_id']))
            {
              
              $this->deletePost();
            }
            else
              die('ERROR');
        }
   }

   public function Like(){
        
        if(isset($_POST['post_id']) && isset($_POST['user_id']) && isset($_POST['c']) && isset($_POST['like_nbr']) && isLoggedIN())
        {
            $data = [
                'post_id'=> $_POST['post_id'],
                'user_id' => $_POST['user_id'],
                'c' => $_POST['c'],
                'like_nbr' => $_POST['like_nbr']
            ];
             $this->postModel->like_nbr($data);
            if($data['c'] == 'fa fa-heart')
            {
              
              if($this->postModel->deleteLike($data))
              {

              }
              else
              {
                die('wa noud');
              }
            }
            else if($data['c'] == 'fa fa-heart-o')
            {
              
              if($this->postModel->addLike($data))
              {
              }
              else
              {
                die('wa noud');
              }
            }
               
        }
    }

    public function comment(){

        if(isset($_POST['c_post_id']) && isset($_POST['c_user_id']) && isset($_POST['content']) && strlen($_POST['content']) <= 255 && isLoggedIN())
        {
            $data = [
                'post_id'=> $_POST['c_post_id'],
                'user_id' => $_POST['c_user_id'],
                'content' => $_POST['content'],
            ];
            $com = $this->userModel->get_commenter($data['user_id']);
            $uid = $this->postModel->getUserByPostId($data['post_id']);
            $d = $this->userModel->get_dest($uid->user_id);
            if($this->postModel->addComment($data) && $d->notif == 1)
            {
              
                $this->c_send_email($com, $d);
            }
        }
    }

    public function c_send_email($com, $d){
        $destinataire = $d->email;
        $sujet = "Your post has been commented" ;
        $message = '
        <p>Hi,
            <br /><br />
            @'.$com->username.', commented your post .
        </p>
        <p>
            <br />--------------------------------------------------------
            <br />This is an automatic mail , please do not reply.
        </p> ';
  
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: <isifeddi@Camagru.ma>' . "\r\n";

        mail($destinataire, $sujet, $message, $headers); // Envoi du mail
    }
    

}