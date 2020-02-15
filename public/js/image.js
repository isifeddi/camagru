var video = document.getElementById('video'),
    full_canvas = document.getElementById('canvas'),
    take_pic = document.getElementById("snap"),
    context = canvas.getContext('2d'),
    h = 300,
    w = 400,
    emoticon,
    full_canvas = 0,
    filter_checked = 0,
    camera_allowed = 0,
    canvasfilter = document.getElementById('canvasf'),
    imgfilter = document.getElementById('imgfilter'),
    placefilter = imgfilter,
    filter = document.getElementsByName('filter'),
    upload_img = document.getElementById('file'),
    save = document.getElementById("save");

 
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)
{
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream)
    {
      try {
            video.src = window.URL.createObjectURL(stream);
      } catch (error) {
            video.srcObject = stream;
          }
        video.play();
        camera_allowed = 1;
    }
    );
} else if(navigator.webkitGetUserMedia) {
   navigator.webkitGetUserMedia({ video: true }, function(stream){
       try {
               video.src = window.URL.createObjectURL(stream);
           } catch (error) {
                video.srcObject = stream;
           }
       video.play();
       camera_allowed = 1;
   }, function(err) {
        console.log("The following error occurred: " + err.name);
     });
}


take_pic.addEventListener("click", function()
{
    context.drawImage(video, 0, 0, w, h);
    full_canvas = 1;       
  }
);

upload_img.addEventListener("click", function()
{
  if(filter_checked == 1){
    imgfilter.src = "";
  }
  emoticon = "";
  placefilter = canvasfilter;
  filter_checked = 1;
  take_pic.disabled = true;    
  }
);


document.getElementById('clear').addEventListener("click", clearcanvas);
function clearcanvas(){
   context.clearRect(0, 0, w, h);
   imgfilter.src = "";
   canvasfilter.src = "";
   full_canvas = 0;
   placefilter = imgfilter;
};


for (var j= 0; j <= 3; j++)
{
  filter[j].onclick = function(event) {
  placefilter.style.display = 'block';
  emoticon = this.value;
  filter_checked = 1;
  placefilter.src = emoticon;
  take_pic.disabled = false;
}
}


function isImage(file)
{
   const validImageTypes = ['image/jpg', 'image/jpeg', 'image/png'];
   const fileType = file['type'];
   if (validImageTypes.indexOf(fileType))
       return true;
   else
       return false;
}


window.addEventListener('DOMContentLoaded', uploadimg);
 function uploadimg(){
     upload_img.addEventListener('change', function(ev) {
      var file = ev.target.files[0];
         var img = new Image;
         img.onload = function () {
              context.drawImage(img, 0, 0, w, h);
              full_canvas = 1;
         }
         if(file && isImage(file))
         {
          img.src = URL.createObjectURL(file);
         }
     });
 }


  save.addEventListener("click", function() {
    var imgData = canvas.toDataURL("image/png");
      var params = "imgBase64=" + imgData + "&emoticon=" + emoticon;
   var xhr = new XMLHttpRequest();
   xhr.open('POST', 'http://localhost/Camagru/Posts/s_image');

   xhr.withCredentialfull_canvas = true;
   xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   if(full_canvas == 1 && filter_checked == 1 && camera_allowed == 1)
   {
    xhr.send(params);
    setInterval(function(){ window.location.reload(); }, 50);
   }
      
});
