<div class="different">
	<div style="height:77vh;">
		<h3>Choose Image</h3>
		<p>
			<input id="files-upload" type="file" multiple>
		</p>
		<ul id="file-list">
			<li class="no-items">(no files uploaded yet)</li>
			<img src="" alt="" id="overlay" style="position:absolute;width:300px;height:200px;">
		</ul>
		<form action="">
		<p>
			<select id="photo-filter" class="select">
				<option value="none">Normal</option>
				<option value="./images/posableimgs/minions.png">Minions</option>
				<option value="./images/posableimgs/xmas.png">Xmas</option>
			</select>
		</p>
		<p>
			<button>Save</button>
		</p>
		</form>
	</div>
</div>
<script>
    var overlay = document.getElementById("overlay");
    const photoFilter = document.getElementById('photo-filter');
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
			progressBarContainer = document.createElement("div"),
			progressBar = document.createElement("div"),
			reader,
			xhr,
			fileInfo;
			
		li.appendChild(div);
		
		progressBarContainer.className = "progress-bar-container";
		progressBar.className = "progress-bar";
		progressBarContainer.appendChild(progressBar);
		li.appendChild(progressBarContainer);
		
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