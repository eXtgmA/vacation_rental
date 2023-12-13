document.addEventListener('DOMContentLoaded', (event) => {
    let tagInput = document.querySelector('#tag-grid input[type="text"]');
    let tagButton = document.querySelector('#tag-grid button');
    let tagGrid = document.querySelector('#tag-grid');

    // Create hidden input for form submission
    let hiddenInputElement = document.createElement('input');
    hiddenInputElement.type = 'hidden';
    hiddenInputElement.name = 'tags';
    tagGrid.appendChild(hiddenInputElement);

    /**
     * Append tag to hidden input
     * @param tagText string
     */
    function appendTag(tagText) {
        if (hiddenInputElement.value.length > 0) {
            hiddenInputElement.value = hiddenInputElement.value + ',' + tagText;
        } else {
            hiddenInputElement.value = hiddenInputElement.value + tagText;
        }
    }

    /**
     * Remove tag from hidden input
     * @param tagText
     */
    function removeTag(tagText) {
        if (hiddenInputElement.value.startsWith(tagText + ',')) {
            hiddenInputElement.value = hiddenInputElement.value.replace(tagText + ',', '');
        } else {
            hiddenInputElement.value = hiddenInputElement.value.replace(tagText, '');
        }
    }

    function addTag() {
        let tagText = tagInput.value.trim();
        if (tagText) {
            // Create new tag pill
            let newTagElement = document.createElement('div');
            newTagElement.className = 'tag-pill';
            newTagElement.tabIndex = 0;
            newTagElement.innerHTML = `<span class="tag-text">${tagText}</span>
                                       <span class="delete-text">Remove X</span>`;

            // Append tag to hidden input
            appendTag(tagText);

            // Append new tag and hidden input to the tag grid
            tagGrid.appendChild(newTagElement);

            // Clear the input field
            tagInput.value = '';

            // Add event listeners to remove tag pill and hidden input on click or enter
            newTagElement.addEventListener('click', () => {
                tagGrid.removeChild(newTagElement);
                removeTag(tagText);
            });
            newTagElement.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    tagGrid.removeChild(newTagElement);
                    removeTag(tagText);
                }
            });
        }
    }

    // Handle button click and enter key press
    tagButton.addEventListener('click', (e) => {
        e.preventDefault();
        addTag();
        tagButton.disabled = true;
    });
    tagInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTag();
            tagButton.disabled = true;
        }
    });
    // Disable the button if the input field is empty
    tagInput.addEventListener('input', () => {
        tagButton.disabled = !tagInput.value.trim();
    });
});