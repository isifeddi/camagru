<?php

class Post{
	private $db;

	public function __construct(){
		$this->db = new Database;
	}

	public function save($data){
		$this->db->query('INSERT INTO posts (user_id, path, created_at) VALUES(:user_id, :path, NOW())');

		$this->db->bind(':user_id', $data['user_id']);
		$this->db->bind(':path', $data['path']);

		if($this->db->execute()){
			return true;
		}else {
			return false;
		}
	}

	public function get_posts($depart, $postsPerPage){
		$this->db->query('SELECT *,
							posts.post_id as postId,
							users.id as userId
							FROM `posts`
							INNER JOIN users 
							ON posts.user_id = users.id
							ORDER BY posts.created_at DESC LIMIT '.$depart.','.$postsPerPage.'');

		$r = $this->db->resultSet();
		if($r)
			return $r;
		else
			return false;
	}
  
	public function getUserPost($user_id){
       $this->db->query('SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC');
       $this->db->bind(':user_id', $user_id);
       $result = $this->db->resultSet();
       if($result)
       	return ($result);
       else
       	return false;
   }

	public function count_posts(){
		$this->db->query('SELECT count(*) FROM posts');

		$c = $this->db->ftchColumn();
		if($c)
			return $c;
		else
			return false;
	}

	public function deletePost($postId, $user_id){
       $this->db->query('DELETE FROM posts WHERE post_id = :post_id AND user_id = :user_id');
       $this->db->bind(':post_id', $postId);
       $this->db->bind(':user_id', $user_id);
       if($this->db->execute())
           return true;
       else
           return false;
  }


   public function getlikes(){
        $this->db->query('SELECT * FROM likes');
        $result = $this->db->resultSet();
        return ($result);
    }

   public function addLike($data)
    {
        $this->db->query('INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)');
        $this->db->bind(':user_id',$data['user_id']);
        $this->db->bind(':post_id',$data['post_id']);

        if($this->db->execute())
        {
            return true;
        }else
            return false;
    }
    public function deleteLike($data)
    {
        $this->db->query('DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id');
        $this->db->bind(':user_id',$data['user_id']);
        $this->db->bind(':post_id',$data['post_id']);

        if($this->db->execute())
        {
            return true;
        }else
            return false;
    }

  public function like_nbr($data)
  {
    $this->db->query('UPDATE posts SET like_nbr = :like_nbr WHERE post_id = :post_id');

    $this->db->bind(':like_nbr', $data['like_nbr']);
    $this->db->bind(':post_id', $data['post_id']);

    if($this->db->execute()){
      return true;
    }else {
      return false;
    }
  }

  public function getcomments()
  {
    $this->db->query('SELECT *,
                      comments.id as commentId,
                      users.id as userId
                     FROM `comments`
                     INNER JOIN users
                     ON comments.user_id = users.id');

    $result = $this->db->resultSet();
    if($result)
      return ($result);
    else
      return false;
  }

  public function addComment($data)
  {
      $this->db->query('INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)');
        $this->db->bind(':user_id',$data['user_id']);
        $this->db->bind(':post_id',$data['post_id']);
        $this->db->bind(':content',$data['content']);

        if($this->db->execute())
        {
            return true;
        }else
            return false;
  }

  public function getUserByPostId($postId){
    $this->db->query('SELECT * FROM posts WHERE post_id = :p');
    $this->db->bind(':p',$postId);

    $result = $this->db->single();
    if($result)
      return ($result);
    else
      return false;
  } 

}