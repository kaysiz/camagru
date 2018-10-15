<div class="top-container">
    <video id="video">Stream unavailable</video>
    <button id="photo-button">Take photo</button>
    <select id="photo-filter">
        <option value="none">Normal</option>
        <option value="grayscale(100%)">Grayscale</option>
        <option value="sepia(100%)">Sepia</option>
        <option value="invert(100%)">Invert</option>
        <option value="hue-rotate(90deg)">Hue</option>
        <option value="blur(10px)">Blur</option>
        <option value="contrast(200%)">Contrast</option>
        <option value="drop-shadow(16px 16px 20px blue)">Drop Shadow</option>
    </select>
    <button id="clear-button">Clear</button>
    <canvas id="canvas"></canvas>
</div>
<div class="bottom-container">
    <div id="photos"></div>
</div>
<script>
    //Global Variable
    let width = 500;
    let height = 0;
    let filter = 'none';
    let streaming = false;

    //DOM Elements
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photos = document.getElementById('photos');
    const photoButton = document.getElementById('photo-button');
    const clearButton = document.getElementById('clear-button');
    const photoFilter = document.getElementById('photo-filter');

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
            height = video.videoHeight / (video.videoWidth / width);
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
        video.style.filter = filter;
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
            img.setAttribute('class', "img");
            img.style.filter = filter;

            photos.appendChild(img);
        }
    }
</script>