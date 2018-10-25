<section id="gallery">
    <div class="container" style="text-align:center;"  id="imgcont">
        
    </div>
    <div class="container gallery-container">
    <?php if($myimages):foreach($myimages as $image): ?>
        <div class="gallery-item">
            <a href=""><img src="./images/public/<?= $image['imgName'];?>" alt=""></a>
            <div class="gallery-desc">
                <span><a href="<?=($_SESSION['loggedin'] ? 'dashboard.php?comment=true&imgkey='.$image['imgId'] : '#');?>"><i class="far fa-comments fa-2x"></i></a></span>
                <span><a href="#" title="delete" onclick="deletepic('<?=$image['imgId'];?>');"><i class="fa fa-trash fa-2x"></i></a></span>
            </div>
        </div>
        <?php endforeach;else: echo "No images, take your first picture <h3><a href='dashboard.php'>HERE</a></h3>";endif; ?>
    </div>
    <div class='push'></div>
</section>
<script>
    function deletepic(d){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          var imgcont = document.getElementById('imgcont');
          var message = '<p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>Image Deleted Successfully!</p>';
            if (this.readyState == 4 && this.status == 200) {
              imgcont.insertAdjacentHTML('afterbegin',message);
            }
        };
        if (confirm("Press OK to delete image.")) {
            xhttp.open("POST", "http://localhost:8080/camagru/includes/funcs.inc.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("delimg=true&key="+d);
        }
    }
</script>