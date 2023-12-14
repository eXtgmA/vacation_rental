document.addEventListener("DOMContentLoaded", (event) => {
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }


    // Tag logic
    let tagInput = document.querySelector('#tag-grid input[type="text"]');
    let tagButton = document.querySelector("#tag-grid button");
    let tagGrid = document.querySelector("#tag-grid");

    // Create hidden input for form submission
    let hiddenInputElement = document.createElement("input");
    hiddenInputElement.type = "hidden";
    hiddenInputElement.name = "tags";
    tagGrid.appendChild(hiddenInputElement);

    /**
     * Append tag to hidden input
     * @param tagText string
     */
    function appendTag(tagText) {
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
    function removeTag(tagText) {
        if (hiddenInputElement.value.startsWith(tagText + ",")) {
            hiddenInputElement.value = hiddenInputElement.value.replace(tagText + ",", "");
        } else {
            hiddenInputElement.value = hiddenInputElement.value.replace(tagText, "");
        }
    }

    function addTag() {
        let tagText = tagInput.value.trim();
        if (tagText) {
            // Create new tag pill
            let newTagElement = document.createElement("div");
            newTagElement.className = "tag-pill";
            newTagElement.tabIndex = 0;
            newTagElement.innerHTML = `<span class="tag-text">${tagText}</span>
                                       <span class="delete-text">Remove X</span>`;

            // Append tag to hidden input
            appendTag(tagText);

            // Append new tag and hidden input to the tag grid
            tagGrid.appendChild(newTagElement);

            // Clear the input field
            tagInput.value = "";

            // Add event listeners to remove tag pill and hidden input on click or enter
            newTagElement.addEventListener("click", () => {
                tagGrid.removeChild(newTagElement);
                removeTag(tagText);
            });
            newTagElement.addEventListener("keydown", (e) => {
                if (e.key === "Enter") {
                    tagGrid.removeChild(newTagElement);
                    removeTag(tagText);
                }
            });
        }
    }

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
        tagButton.disabled = !tagInput.value.trim();
    });


    // Image logic

    // default values
    let selectedFiles = [];

    // required images (front and layout) elements
    let frontImageContainer = document.getElementById("front-image");
    let frontImageDropArea = document.getElementById("front-image-drop-area");
    let frontImageSelectElement = document.getElementById("front-image-input-field");
    let layoutImageContainer = document.getElementById("layout-image");
    let layoutImageDropArea = document.getElementById("layout-image-drop-area");
    let layoutImageSelectElement = document.getElementById("layout-image-input-field");

    // optional images elements
    let optionalImageContainer = document.getElementById("optional-images");
    let optionalImageDropArea = document.getElementById("optional-image-drop-area");
    let optionalImageSelectElement = document.getElementById("optional-image-input-field");

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

    frontImageDropArea.addEventListener('drop', (e) => addPrimaryImage(([...e.dataTransfer.files])[0], frontImageContainer, frontImageDropArea, frontImageSelectElement), false);
    frontImageSelectElement.addEventListener('change', (e) => addPrimaryImage(([...e.target.files])[0], frontImageContainer, frontImageDropArea, frontImageSelectElement), false);
    layoutImageDropArea.addEventListener('drop', (e) => addPrimaryImage(([...e.dataTransfer.files])[0], layoutImageContainer, layoutImageDropArea, layoutImageSelectElement), false);
    layoutImageSelectElement.addEventListener('change', (e) => addPrimaryImage(([...e.target.files])[0], layoutImageContainer, layoutImageDropArea, layoutImageSelectElement), false);


    // Handle the drop event of the optional image drop area
    optionalImageDropArea.addEventListener("drop", function (e) {
        console.log("Element dropped!", e.dataTransfer.files);
        optionalImageSelectElement.files = e.dataTransfer.files;

        const event = new Event("change");
        optionalImageSelectElement.dispatchEvent(event);
    });

    // Handle the change event of the optional image input field
    optionalImageSelectElement.addEventListener("change", function (event) {
        console.log("Element changed!", event);
        // Check if a file already exists
        if (selectedFiles.find((f) =>
            Array.from(this.files).find((file) =>
                file.name === f.name))) {
            alert("min one File already exists!");
            return;
        }

        // Check if the user tries to upload more than 3 images
        if (selectedFiles.length >= 3 || this.files.length + selectedFiles.length > 3) {
            alert("Es sind maximal 3 optionale Bilder erlaubt!");
            return;
        }

        // Append new files to the selectedFiles array
        for (let i = 0; i < this.files.length; i++) {
            selectedFiles.push(this.files[i]);
        }

        // Display all selected images
        for (let i = 0; i < this.files.length; i++) {
            // let file = selectedFiles[i];
            let file = this.files[i];

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
                selectedFiles = selectedFiles.filter((f) => f.name !== file.name);
                selectedFiles = selectedFiles.filter((f) => f.name !== file.name);

                updateOptionalImageInputField(selectedFiles);
            });

            // add image and button to container
            container.appendChild(img);
            container.appendChild(closeButton);

            // add container to image container
            optionalImageContainer.insertBefore(container, optionalImageDropArea);
        }

        updateOptionalImageInputField(selectedFiles);
    });

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

        console.log("optionalImages: ", optionalImageSelectElement.files);
    }

    /**
     * Add the primary image to the form
     * This method is used for the front and layout image
     *
     * @param file
     * @param parentElement
     * @param dropArea
     * @param selectElement
     */
    function addPrimaryImage(file, parentElement, dropArea, selectElement) {
        console.log(file.type);
        console.dir(file);
        let url = file.type ? URL.createObjectURL(file) : file;
        let preview = document.createElement("img");
        preview.src = url;

        // add the image to the input field
        selectElement.src = url;

        // add the image for the preview
        let closeButton = document.createElement("div");
        closeButton.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        closeButton.classList.add('delete-btn');
        closeButton.addEventListener('click', function () {
            preview.remove();
            closeButton.remove();
            dropArea.classList.remove('hidden');
            selectElement.src = null;
        });

        // add image and button to parentElement
        parentElement.appendChild(preview);
        parentElement.appendChild(closeButton);
        dropArea.classList.add('hidden');
    }


});