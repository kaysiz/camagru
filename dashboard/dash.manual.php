<?php
	var_dump($_POST);
?>
<div class="different">
	<div style="height:77vh;">
		<h3>Choose Image</h3>
		<form action="./includes/funcs.inc.php" method="POST" enctype="multipart/form-data">
			<p>
				<input id="files-upload" type="file" name="image" accept="image/png">
				<input type="hidden" id="poverlay" name="manual">
			</p>
			<ul id="file-list">
				<li class="no-items">(no files uploaded yet)</li>
				<img src="" alt="" id="overlay" style="position:absolute;width:300px;height:200px;">
			</ul>
			<p>
				<select id="photo-filter" class="select" name="imgoverlay">
					<option value="none">Normal</option>
					<option value="./images/posableimgs/blackhearts.png">Black Hearts</option>
					<option value="./images/posableimgs/devil.png">Devil Horns</option>
					<option value="./images/posableimgs/fox.png">Fox</option>
					<option value="./images/posableimgs/dog.png">Dog</option>
				</select>
			</p>
			<p>
				<button type="submit">Save</button>
			</p>
		</form>
	</div>
</div>
<div class='push'></div>
<script>
    var overlay = document.getElementById("overlay");
	const photoFilter = document.getElementById('photo-filter');
	var poverlay = document.getElementById("poverlay");
    photoFilter.addEventListener('change', (e) => {
        filter = e.target.value;
        overlay.src = filter;
        e.preventDefault();
    }, false);
    (function () {
	var filesUpload = document.getElementById("files-upload"),
		dropArea = document.getElementById("drop-area"),
		fileList = document.getElementById("file-list");
	
	function uploadFile (file) {
		var li = document.createElement("li"),
			div = document.createElement("div"),
			img,
			reader;
		/*
			If the file is an image and the web browser supports FileReader,
			present a preview in the file list
		*/
		if (typeof FileReader !== "undefined" && (/image/i).test(file.type)) {
			img = document.createElement("img");
            img.setAttribute('width', 300);
            img.setAttribute('height', 200);
			li.appendChild(img);
			reader = new FileReader();
			reader.onload = (function (theImg) {
				return function (evt) {
					theImg.src = evt.target.result;
				};
			}(img));
			reader.readAsDataURL(file);
		}
		
		fileList.appendChild(li);
	}
	
	function traverseFiles (files) {
		if (typeof files !== "undefined") {
			for (var i=0, l=files.length; i<l; i++) {
				uploadFile(files[i]);
			}
		}
		else {
			fileList.innerHTML = "No support for the File API in this web browser";
		}	
	}
	
	filesUpload.addEventListener("change", function () {
		traverseFiles(this.files);
	}, false);								
})();	

</script>