function comment(event)
{
  if( !event ) event = window.event;
  var postid = (event.target && event.target.getAttribute('data-c-post_id'));
  var userid = (event.target && event.target.getAttribute('data-c-user_id'));
  var co = document.getElementsByName('comment_'+postid);
  var com = co[0].value;

  if(com.trim() == "" && userid != ""){
      co[0].placeholder = 'Please enter valid comment';
      return ;
  }
  if (userid == "") {
    window.location.replace("http://localhost/Camagru/users/login");
    return;
  }
  var xhttp = new XMLHttpRequest();
  var params = "c_post_id=" + postid + "&c_user_id=" + userid + "&content=" + com;
  xhttp.open('POST', 'http://localhost/Camagru/Posts/comment');
  xhttp.withCredentials = true;
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send(params);
  setInterval(function(){ window.location.reload(); }, 50);
}