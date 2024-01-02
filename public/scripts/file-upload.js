/**
 * Upload a file with a preview to a parent element
 *
 * @param file
 * @param parentElement
 * @param dropArea
 * @param selectElement
 */
export function uploadFile(file, parentElement, dropArea, selectElement) {
    let url = file.type ? URL.createObjectURL(file) : file;
    let preview = document.createElement("img");
    preview.src = url;

    // add the image to the input field
    selectElement.src = url;

    let dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    selectElement.files = dataTransfer.files;

    // add the image for the preview
    let closeButton = document.createElement("div");
    closeButton.innerHTML = '<i class="fa-solid fa-xmark"></i>';
    closeButton.classList.add("delete-btn");
    closeButton.addEventListener("click", function () {
        preview.remove();
        closeButton.remove();
        dropArea.classList.remove("hidden");
        selectElement.src = null;
    });

    // add image and button to parentElement
    parentElement.appendChild(preview);
    parentElement.appendChild(closeButton);
    dropArea.classList.add("hidden");
}
