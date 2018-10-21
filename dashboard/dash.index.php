<div class="column left">
<div class="top-container" style="width:50%;position: relative;">
    <video id="video">Stream not available...</video>
    <img src="" alt="" id="overlay" style="position:absolute;bottom:194px; right:175px;width:400px;height:300px;">
    <button id="photo-button" class="btn btn-dark">
    Take Photo
    </button>
    <select id="photo-filter" class="select">
        <option value="none">Normal</option>
        <option value="./images/posableimgs/minions.png">Minions</option>
        <option value="./images/posableimgs/xmas.png">Xmas</option>
    </select>
    <button id="clear-button" class="btn btn-light">Clear</button>
    <canvas id="canvas"></canvas>
</div>
</div>
<div class="column right" style="padding-left:50px;">
    <div class="bottom-container">
        <div id="photos"></div>
    </div>
</div>


<script>
    //Global Variable
    let width = 400;
    let height = 400;
    let filter = 'none';
    let streaming = false;

    //DOM Elements
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photos = document.getElementById('photos');
    const photoButton = document.getElementById('photo-button');
    const clearButton = document.getElementById('clear-button');
    const photoFilter = document.getElementById('photo-filter');
    var overlay = document.getElementById('overlay');

    //Get media stream
    navigator.mediaDevices.getUserMedia({
            video: true,
            audio: false
        }).then((stream) => {
            video.srcObject = stream;
            video.play();
        })
        .catch((err) => {
            console.log(`Error: ${err}`);
        });

    video.addEventListener('canplay', (e) => {
        if (!streaming) {
            // height = video.videoHeight / (video.videoWidth / width);
            video.setAttribute('width', width);
            video.setAttribute('height', height);
            canvas.setAttribute('width', width);
            canvas.setAttribute('height', height);

            streaming = true;
        }
    }, false);

    photoButton.addEventListener('click', (e) => {
        takePicture();

        e.preventDefault();
    }, false);

    photoFilter.addEventListener('change', (e) => {
        filter = e.target.value;
        overlay.src = filter;
        if(filter != 'none')
            photoButton.setAttribute('disabled');

        e.preventDefault();
    }, false);

    clearButton.addEventListener('click', (e) => {
        photos.innerHTML = '';
        filter = 'none';
        video.style.filter = filter;
        photoFilter.selectedIndex = 0;
    });

    function takePicture() {
        const context = canvas.getContext('2d');
        if (width && height) {
            canvas.width = width;
            canvas.height = height;
            context.drawImage(video, 0, 0, width, height);

            const imgUrl = canvas.toDataURL('image/png');
            const img = document.createElement('img');
            img.setAttribute('src', imgUrl);
            img.setAttribute('class', "temp-img");
            img.setAttribute('data-imgkey', imgUrl);
            img.setAttribute('onclick',"myFunction(this)");
            img.style.filter = filter;

            photos.appendChild(img);
        }
    }
    function myFunction(d){
        if (confirm("Press OK to save image, or cancel to delete it.")) {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "http://localhost:8080/camagru/includes/funcs.inc.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("img=true&key="+encodeURIComponent(d.getAttribute("data-imgkey")));
        }else{
            var parent = document.getElementById('photos');
            parent.removeChild(d);
        }
        console.log(d.getAttribute("data-imgkey"));
    }
</script>