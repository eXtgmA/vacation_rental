let tagInput = document.querySelector('#tag-grid input[type="text"]');
let tagGrid = document.querySelector("#tag-grid");

// Create hidden input for form submission
let hiddenInputElement = document.createElement("input");
hiddenInputElement.type = "hidden";
hiddenInputElement.name = "tags";
tagGrid.appendChild(hiddenInputElement);

tagInput.addEventListener("keydown", (e) => {
    console.log("key down");
    if (e.key === "Enter") {
        e.preventDefault();
        addTag();
    }
});

/**
 * Append tag to hidden input
 * @param tagText string
 */
function appendTagInputElement(tagText) {
    if (hiddenInputElement.value.length > 0) {
        hiddenInputElement.value = hiddenInputElement.value + "," + tagText;    // todo: check if tag already exists, and is , the right separator?
    } else {
        hiddenInputElement.value = hiddenInputElement.value + tagText;
    }
}

/**
 * Remove tag from hidden input
 * @param tagText
 */
function reduceTagInputElement(tagText) {
    if (hiddenInputElement.value.startsWith(tagText + ",")) {
        hiddenInputElement.value = hiddenInputElement.value.replace(tagText + ",", "");
    } else {
        hiddenInputElement.value = hiddenInputElement.value.replace(tagText, "");
    }
}

/**
 * Add tag to tag grid
 */
function addTag(tag) {
    let tagText = tag ?? tagInput.value.trim();
    if (tagText) {
        // Create new tag pill
        let newTagElement = document.createElement("div");
        newTagElement.className = "tag-pill";
        newTagElement.tabIndex = 0;
        newTagElement.innerHTML = `<span class="tag-text">${tagText}</span>
                                   <span class="delete-text"> <i class="fa-solid fa-xmark"></i></span>`;

        // Append tag to hidden input
        appendTagInputElement(tagText);

        // Append new tag and hidden input to the tag grid
        tagGrid.appendChild(newTagElement);

        // Clear the input field
        tagInput.value = "";

        // Add event listeners to remove tag pill and hidden input on click or enter
        newTagElement.addEventListener("click", () => {
            tagGrid.removeChild(newTagElement);
            reduceTagInputElement(tagText);
        });
        newTagElement.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                tagGrid.removeChild(newTagElement);
                reduceTagInputElement(tagText);
            }
        });
    }
}


const filterList = document.getElementById("filter-list");
const collapseButton = document.getElementById("collapse-filter-btn");

function refreshFilterList() {
    if (window.innerWidth > 992) {
        // show filter list
        filterList.style.display = "inherit";
        filterList.style.maxHeight = filterList.scrollHeight + "px";
        // hide button
        collapseButton.style.display = "none";
        collapseButton.textContent = "Filter schließen";

    }
    if (window.innerWidth < 992) {
        // hide filter list
        filterList.style.display = "none";
        filterList.style.maxHeight = null;
        // show button
        collapseButton.style.display = "inherit";
        collapseButton.textContent = "Filter öffnen";
    }
}

/**
 * hide / show filter list on resize
 */
window.addEventListener('resize', refreshFilterList, false);

/**
 * toggle filter list on click
 */
collapseButton.addEventListener("click", function () {
    // this.classList.toggle("active");
    if (filterList.style.maxHeight) {
        this.textContent = "Filter öffnen";

        filterList.style.maxHeight = null;
        filterList.style.display = "none";
    } else {
        this.textContent = "Filter schließen";

        filterList.style.display = "inherit";
        filterList.style.maxHeight = filterList.scrollHeight + "px";
    }
});

/**
 * Reset the filter
 * - uncheck all checkboxes
 * - remove all tags
 */
function resetFilter() {
    // uncheck checkboxes
    document.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = false);
    // remove tags
    document.querySelectorAll('div[class=tag-pill]').forEach(function (el) {
        tagGrid.removeChild(el);
        reduceTagInputElement(el.children[0].innerHTML);
    });
}

/**
 * Add all given tags to the tags card
 *
 * @param tags
 */
function prefillTags(tags) {
    const tagsArray = tags.split(",");
    const count = tagsArray.length;
    for (var i=0; i<count; i++) {
        addTag(String(tagsArray[i]));
    }
}

// initial check
refreshFilterList();