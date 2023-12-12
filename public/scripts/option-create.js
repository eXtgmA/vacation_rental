document.addEventListener('DOMContentLoaded', (event) => {
    let dropArea = document.getElementById('drop-area');
    let selectElement = document.getElementById('option-image-input'); // Get the input field

    function preventDefaults(e) {
        e.preventDefault()
        e.stopPropagation()
    }

    function highlight(e) {
        dropArea.classList.add('highlight')
    }

    function unhighlight(e) {
        dropArea.classList.remove('highlight')
    }

    // Add event listeners
    ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false)
    })
    ;['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false)
    })
    ;['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false)
    })

    dropArea.addEventListener('drop', handleDrop, false);
    selectElement.addEventListener('change', handleDialog, false);

    function handleDrop(e) {
        let dt = e.dataTransfer
        let files = dt.files
        handleFiles(files)
    }
    function handleDialog(e) {
        let files = e.target.files // Get the selected files
        handleFiles(files)
    }

    function handleFiles(files) {
        // just take the first file
        uploadFile(([...files])[0]);
    }

    /**
     *
     * @param file
     */
    function uploadFile(file) {
        let url = URL.createObjectURL(file)
        let preview = document.createElement('img')
        preview.src = url

        // add the image to the input field
        selectElement.src = url;

        // add the image for the preview
        let closeButton = document.createElement('div')
        closeButton.innerHTML = '<i class="fa-solid fa-xmark"></i>' // Use Font Awesome icon
        closeButton.classList.add('close-btn')
        closeButton.addEventListener('click', function() {
            preview.remove()
            closeButton.remove()
            dropArea.classList.remove('hidden');
            selectElement.src = null;
        });
        document.getElementById('option-image').appendChild(preview);
        document.getElementById('option-image').appendChild(closeButton);
        dropArea.classList.add('hidden');
    }
});