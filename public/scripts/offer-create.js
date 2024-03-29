import {uploadFile} from "./file-upload.js";

let selectedOptionalImages = [];

// initialize all elements
// Tag-Elements
let tagInput = document.querySelector('#tag-grid input[type="text"]');
let tagButton = document.querySelector("#tag-grid button");
let tagGrid = document.querySelector("#tag-grid");
// Required images (front and layout) elements
let frontImageContainer = document.getElementById("front-image");
let frontImageDropArea = document.getElementById("front-image-drop-area");
let frontImageSelectElement = document.getElementById("front-image-input-field");
let layoutImageContainer = document.getElementById("layout-image");
let layoutImageDropArea = document.getElementById("layout-image-drop-area");
let layoutImageSelectElement = document.getElementById("layout-image-input-field");
// Optional images elements
let optionalImageContainer = document.getElementById("optional-images-grid");
let optionalImageDropArea = document.getElementById("optional-image-drop-area");
let optionalImageSelectElement = document.getElementById("optional-image-input-field");


// Create hidden input for form submission
let hiddenInputElement = document.createElement("input");
hiddenInputElement.type = "hidden";
hiddenInputElement.id = "hidden-tags";
hiddenInputElement.name = "tags";
tagGrid.appendChild(hiddenInputElement);

/**
 * Append tag to hidden input
 * @param tagText string
 */
function appendTagInputElement(tagText) {
    if (hiddenInputElement.value.length > 0) {
        hiddenInputElement.value = hiddenInputElement.value + "," + tagText;
    } else {
        hiddenInputElement.value = hiddenInputElement.value + tagText;
    }
}

/**
 * Remove tag from hidden input
 * @param tagText
 */
function reduceTagInputElement(tagText) {
    hiddenInputElement.value = hiddenInputElement.value.split(",").filter((t) => t !== tagText).join(",")
    console.debug("removed tag '" + tagText + "' from element. Current tags:", hiddenInputElement.value);
}

/**
 * Add tag to tag grid
 */
function addTag(tag) {
    let tagText = tag ?? tagInput.value.trim();
    let tags = tagText.split(",")
    tags.forEach((tag) => {
    if (tag) {
        // Create new tag pill
        let newTagElement = document.createElement("div");
        newTagElement.className = "tag-pill";
        newTagElement.tabIndex = 0;
        newTagElement.innerHTML = `<span class="tag-text">${tag}</span>
                                       <span class="delete-text"> <i class="fa-solid fa-xmark"></i></span>`;

        // Append tag to hidden input
        appendTagInputElement(tag);

        // Append new tag and hidden input to the tag grid
        tagGrid.appendChild(newTagElement);

        // Clear the input field
        tagInput.value = "";

        // Add event listeners to remove tag pill and hidden input on click or enter
        newTagElement.addEventListener("click", () => {
            tagGrid.removeChild(newTagElement);
            reduceTagInputElement(tag);
        });
        newTagElement.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                tagGrid.removeChild(newTagElement);
                reduceTagInputElement(tag);
            }
        });
    }
    });
}

/**
 * update optional image input field
 * @param files FileList with all files
 */
function updateOptionalImageInputField(files) {
    let dataTransfer = new DataTransfer();
    files.forEach((file) => {
        dataTransfer.items.add(file);
    });
    optionalImageSelectElement.files = dataTransfer.files;

    if (optionalImageSelectElement.files.length >= 3) {
        optionalImageDropArea.classList.add("hidden");
    } else {
        optionalImageDropArea.classList.remove("hidden");
    }
}

/**
 * Add optional image to the form
 * @param files
 */
function addOptionalImage(files) {
    console.trace("Element changed!", files);
    // Check if a file already exists
    if (selectedOptionalImages.find((f) =>
        Array.from(files).find((file) =>
            file.name === f.name))) {
        alert("min one File already exists!");
        return;
    }

    // Check if the user tries to upload more than 3 images
    if (selectedOptionalImages.length >= 3 || files.length + selectedOptionalImages.length > 3) {
        alert("Es sind maximal 3 optionale Bilder erlaubt!");
        return;
    }

    // Append new files to the selectedFiles array
    for (let i = 0; i < files.length; i++) {
        selectedOptionalImages.push(files[i]);
    }

    // Display all selected images
    for (let i = 0; i < files.length; i++) {
        // let file = selectedFiles[i];
        let file = files[i];

        let container = document.createElement("div");
        container.classList.add("image-container");

        let img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        img.onload = function () {
            URL.revokeObjectURL(this.src);
        }

        // add the image for the preview
        let closeButton = document.createElement("div");
        closeButton.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        closeButton.classList.add("delete-btn");
        closeButton.addEventListener("click", function () {
            container.remove();

            // remove file from selectedFiles array
            selectedOptionalImages = selectedOptionalImages.filter((f) => f.name !== file.name);
            selectedOptionalImages = selectedOptionalImages.filter((f) => f.name !== file.name);

            updateOptionalImageInputField(selectedOptionalImages);
        });

        // add image and button to container
        container.appendChild(img);
        container.appendChild(closeButton);

        // add container to image container
        optionalImageContainer.insertBefore(container, optionalImageDropArea);
    }

    updateOptionalImageInputField(selectedOptionalImages);
}


/**
 * Prevent default behaviour of the event
 * @param e event
 */
function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

/**
 * Handle the drop event of the front image drop area
 * @param e
 * @param element
 */
function highlight(e, element) {
    element.classList.add("highlight");
}

/**
 * Remove highlight from element
 * @param e
 * @param element
 */
function unhighlight(e, element) {
    element.classList.remove("highlight");
}

//
// Add Event Listeners
//

// Handle button click and enter key press
tagButton.addEventListener("click", (e) => {
    e.preventDefault();
    addTag();
    tagButton.disabled = true;
});

tagInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        addTag();
        tagButton.disabled = true;
    }
});

// Disable the button if the input field is empty
tagInput.addEventListener("input", () => {
    tagButton.disabled = !tagInput.value.trim() || tagInput.value.trim().length > 45;
});

// Add event listeners for hover effects
["dragenter", "dragover", "dragleave", "drop"].forEach(eventName => {
    optionalImageDropArea.addEventListener(eventName, preventDefaults, false);
    frontImageDropArea.addEventListener(eventName, preventDefaults, false);
    layoutImageDropArea.addEventListener(eventName, preventDefaults, false);
    if (["dragenter", "dragover"].includes(eventName)) {
        optionalImageDropArea.addEventListener(eventName, (e) => highlight(e, optionalImageDropArea), false);
        frontImageDropArea.addEventListener(eventName, (e) => highlight(e, frontImageDropArea), false);
        layoutImageDropArea.addEventListener(eventName, (e) => highlight(e, layoutImageDropArea), false);
    } else {
        optionalImageDropArea.addEventListener(eventName, (e) => unhighlight(e, optionalImageDropArea), false);
        frontImageDropArea.addEventListener(eventName, (e) => unhighlight(e, frontImageDropArea), false);
        layoutImageDropArea.addEventListener(eventName, (e) => unhighlight(e, layoutImageDropArea), false);
    }
});

frontImageDropArea.addEventListener('drop', (e) => uploadFile(([...e.dataTransfer.files])[0], frontImageContainer, frontImageDropArea, frontImageSelectElement), false);
frontImageSelectElement.addEventListener("change", (e) => uploadFile(([...e.target.files])[0], frontImageContainer, frontImageDropArea, frontImageSelectElement), false);
layoutImageDropArea.addEventListener("drop", (e) => uploadFile(([...e.dataTransfer.files])[0], layoutImageContainer, layoutImageDropArea, layoutImageSelectElement), false);
layoutImageSelectElement.addEventListener("change", (e) => uploadFile(([...e.target.files])[0], layoutImageContainer, layoutImageDropArea, layoutImageSelectElement), false);

// Handle the drop event of the optional image drop area
optionalImageDropArea.addEventListener("drop", function (e) {
    console.trace("Element dropped!", e.dataTransfer.files);
    optionalImageSelectElement.files = e.dataTransfer.files;

    const event = new Event("change");
    optionalImageSelectElement.dispatchEvent(event);
});

// Handle the change event of the optional image input field
optionalImageSelectElement.addEventListener("change", (e) => addOptionalImage(e.target.files), false);


// preload the frontImage
if (frontImageUuid) {
    fetch(frontImageUuid)
        .then(response => response.blob())
        .then(blob => uploadFile(new File([blob], "<?php echo $house->getFrontimage() ?>", {type: "image"}), frontImageContainer, frontImageDropArea, frontImageSelectElement))
        .catch(error => console.error(error));
}

// preload the layoutImage
if (layoutImageUuid) {
    fetch(layoutImageUuid)
        .then(response => response.blob())
        .then(blob => uploadFile(new File([blob], "<?php echo $house->getLayoutImage() ?>", {type: "image"}), layoutImageContainer, layoutImageDropArea, layoutImageSelectElement))
        .catch(error => console.error(error));
}

// preload the optional images
// Fetch all optional images associated with the house
if (optionalImagesUuids?.length > 0) {
    Promise.all(optionalImagesUuids.map(image =>
        fetch(image)
            .then(response => response.blob())
            .then(blob => new File([blob], image, {type: "image"}))
            .catch(error => console.error(error))
    )).then(files => addOptionalImage(files)).catch(error => console.error(error));
}

// preload the tags
// Add all given tags to the tags card
if (houseTags) {
    houseTags.forEach(tag => {
        addTag(tag);
    });
}