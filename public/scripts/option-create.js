import {uploadFile} from "./file-upload.js";

let dropArea = document.getElementById("image-drop-area");
let selectElement = document.getElementById("option-image-input");
let container = document.getElementById("option-image");

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight(e) {
    dropArea.classList.add("highlight");
}

function unhighlight(e) {
    dropArea.classList.remove("highlight");
}

// Add event listeners
["dragenter", "dragover", "dragleave", "drop"].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false)
});
["dragenter", "dragover"].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false)
});
["dragleave", "drop"].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false)
})

dropArea.addEventListener('drop', (e) => uploadFile(([...e.dataTransfer.files])[0], container, dropArea, selectElement), false);
selectElement.addEventListener("change", (e) => uploadFile(([...e.target.files])[0], container, dropArea, selectElement), false);


// preload the image
if (imageUuid) {
    fetch(imageUuid)
        .then(response => response.blob())
        .then(blob => uploadFile(new File([blob], "<?php echo $option->getOptionImage() ?>", {type: "image"}), container, dropArea, selectElement))
        .catch(error => console.error(error));
}