<style>
    a{
  text-decoration: none;
}

.Instagram-card{
	background: #ffffff;
	border: 1px solid #f2f2f2;
	border-radius: 3px;
	width: 100%;
	max-width: 600px;
	margin: auto;
}

.Instagram-card-header{
	padding: 20px;
	height: 40px;
}

.Instagram-card-user-image{
	border-radius: 50%;
	width: 40px;
	height: 40px;
	vertical-align: middle;
}

.Instagram-card-time{
	float: right;
	width: 80px;
	padding-top:10px;
	text-align: right;
	color: #999;
}

.Instagram-card-user-name{
	margin-left:20px;
	font-weight:bold;
  color: #262626;
}

.Instagram-card-content{
  padding: 20px;
}

.Likes{
  font-weight: bold;
}

.Instagram-card-content-user{
  font-weight: bold;
  color: #262626;
}

.hashtag{
  color: #003569;
}

.comments{
  color:#999;
}

.user-comment{
  color: #003569;
}

.Instagram-card-footer{
  padding: 20px;
  display: flex;
  align-items: center;
}

hr{
  border: none;
  border-bottom: 1px solid #ccc;
  margin-top: 30px;
  margin-bottom: 0px;
  padding-bottom: 0px;
 
}


.footer-action-icons{
  font-size: 16px;
  color: #ccc;
}

.comments-input{
  border: none;
  margin-left: 10px;
  width: 100%;
}
</style>
<br>
<div class="Instagram-card">
    <div class="Instagram-card-image" style="text-align:center">
      <img src= "./images/public/kaysiz0b75ab.png" height=450px/>
    </div>

    <div class="Instagram-card-content">
        <span class="Likes" style="text-align:right">Created by ksiziva on 2018-20-12</span>
      <p >24 Likes | 34 Comments </p>
      <div id="commData">
        <p class="user-comment">[ksiziva] blaaaah blaaaaah bl;aaaah</p>
        <p class="user-comment">[ksiziva] blaaaah blaaaaah bl;aaaah</p>
        <p class="user-comment">[ksiziva] blaaaah blaaaaah bl;aaaah</p>
        <p class="user-comment">[ksiziva] blaaaah blaaaaah bl;aaaah</p>
        <p class="user-comment">[ksiziva] blaaaah blaaaaah bl;aaaah</p>
      </div>
    <hr>
    </div>  

    <div class="Instagram-card-footer">
      <a class="footer-action-icons"href="#"><i class="fa fa-heart-o"></i></a>
      <input class="comments-input" type="text" placeholder="type comment no more than 50 characters..." maxlength="50"/>
      <button onclick="getcomments()">Comment</button>
    </div>

  </div>
  <script>
    var xhttp = new XMLHttpRequest();
    function getcomments() {
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var comments = Object.keys(JSON.parse(this.response)).map((key) => ({ key, value: JSON.parse(this.response)[key] }));
                var comment = document.getElementById('commData');
                if (comments.length > 0) {
                    comments.forEach(element => {
                        comment.innerHTML += '<span class="user-comment">[ '+element.key+' ]&emsp;'+element.value+'</span><br>'; 
                    });
                }else {
                    comment.innerHTML += "No comments yet, be the first to comment";
                }
            }
        };
        xhttp.open("GET", "http://localhost:8080/camagru/includes/funcs.inc.php?comments=true&imgkey=", true);
        xhttp.send();
    }
    function comment() {
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var comments = Object.keys(JSON.parse(this.response)).map((key) => ({ key, value: JSON.parse(this.response)[key] }));
                var comment = document.getElementById('commData');
                if (comments.length > 0) {
                    comments.forEach(element => {
                        comment.innerHTML += '<span class="user-comment">[ '+element.key+' ]&emsp;'+element.value+'</span><br>'; 
                    });
                }else {
                    comment.innerHTML += "No comments yet, be the first to comment";
                }
            }
        };
        xhttp.open("GET", "http://localhost:8080/camagru/includes/funcs.inc.php?comments=true&imgkey=hey", true);
        xhttp.send();
    }
  </script>