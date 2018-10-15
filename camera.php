<?php include "./includes/header.inc.php"; ?>
<section>
    <div class="navbar">
        <h1>Camagru</h1>
    </div>
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
</section>
<?php include "./includes/footer.inc.php";?>