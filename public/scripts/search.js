// get the tag input field
let tagInput = document.querySelector('#tag-grid input[type="text"]');
// get the element storing the actual tags
let tagGrid = document.querySelector("#tag-grid");

/**
 * Input a new tag a confirm with "ENTER" key
 */
tagInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") { // on enter
        e.preventDefault();
        addTag();  // add tag to grid
    }
});


/**
 * Add tag to tag grid
 */
function addTag(tag, safe=true) {
    let tagText = tag ?? tagInput.value.trim();
    if (tagText) {
        // Create new tag pill (the orange bordered thingy)
        let newTagElement = document.createElement("div");
        newTagElement.className = "tag-pill";  // add class for style
        newTagElement.tabIndex = 0; // add it at first position
        newTagElement.innerHTML = `<span class="tag-text">${tagText}</span>
                                   <span class="delete-text"> <i class="fa-solid fa-xmark"></i></span>`; // add tag text

        tagGrid.appendChild(newTagElement); // appending the new tag-pill to the grid
        filterResults(); // after adding a new tag, perform the filter action again to include the new tag
        // Clear the input field after adding to have room for a new tag
        tagInput.value = "";

        // Add event listeners to remove tag pill and hidden input on click or enter
        newTagElement.addEventListener("click", () => { // remove on click of pill
            tagGrid.removeChild(newTagElement);
            saveFilter()
            filterResults(); // after removing perform filter to fit to given tags excluding the newly removed
        });
        if(safe==true){
            saveFilter()
        }
    }
}

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
    });
    filterResults() // refilter results after emptying all the filter values
}

/**
 * Run this after the page is loaded completely
 */
window.onload=function (){
    resetFilter()
    // onload, clear all old set tags and filter (no deleting in backend)
    getFilter()
    // fetch the wanted tags and filters and prefill

}

/**
 * completely empty the filters
 * front and backend
 */
function clearFilter() {
    // clear all set filter and tags
    resetFilter()
    // store permanent
     saveFilter()
}

/**
 * This method is called within the getFilter API methd
 * Its purpose is to prefill all the wanted tags
 * and to check all features Checkboxes that have to be prefilled
 *
 * @param data
 */
function setFilterOnLoad(data) {
    // get data from backend
    tags = data['filter']['tags']
    features = data['filter']['features']
    // set tags
    tags.forEach(function (tag) {
        addTag(tag,false) // using the prepared method in search.js but send no saving of filters,
    //     to avoid overwriting of checkboxes when adding the tag pills (f5 hardhitting)
    })
    // set features
    // iterating through all checkboxes
    featureCheckboxes = document.getElementsByClassName('feature-name')
    Array.from(featureCheckboxes).map(function (item) {
        // if there is hit an a checkbox and fetched value, mark box as checked
        features.forEach(function (feature) {
            if (item.value == feature) {
                item.checked = true
            }
        })
    })
    filterResults()
}

/**
 * Api method for fetching tags and features
 */
function getFilter() {
    fetch('/offer/filter')
        .then(response => {
            if (!response.ok) {
                throw new Error('Fehler bei der Anfrage: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            setFilterOnLoad(data)
        })
        .catch(error => {
            console.error(error);
        });
}

/**
 * api method to store the currently used
 * tags and feature of a filter
 */
function saveFilter() {
    let tags = tagGrid.getElementsByClassName('tag-text')
    let tagArray = generateArrayFromTags(tags)
    let features = document.getElementsByClassName('feature-name')
    let checkedFeatures = Array.from(features).filter(function (checkbox) {
        return checkbox.checked;
    }).map(function (checkbox) {
        return checkbox.value
    });
    fetch('/offer/storefilter', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            tags: tagArray,
            features: checkedFeatures
        }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Fehler bei der Anfrage: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // console.log(data);
        })
        .catch(error => {
            console.error(error);
        });
}

/**
 * this method filters the fetched results by
 * the present set filter values (tags and features)
 *
 */
function filterResults() {
    // fetch actual set tags and convert them to an array
    let tagArray = generateArrayFromTags(tagGrid.getElementsByClassName('tag-text'))
    // get all generated house results, including their tags features and options
    let houses = document.getElementsByClassName('result')
    // get all feature items from the filter
    let features = document.getElementsByClassName('feature-name')
    // pick only the checked features (we only need to remember the checked ones)
    let checkedFeatures = Array.from(features).filter(function (checkbox) {
        return checkbox.checked;
    });
    let resultCount = 0;

    // test for every house
    for (i = 0; i < houses.length; i++) {
        isFound = true //initial found status
        let house = houses[i] // a single house

        featuresString = house.querySelector('.features').innerHTML // every house has a ',' separated string of features
        checkedFeatures.forEach(function (feature) {    // iterate through every checked feature if it is the house features
            if (!featuresString.includes(feature.value)) {
                isFound = false;    // if there is no hit in features, there is no need to go on, because the house does not fit in the filter
                return; // stop searching after first not fitting feature to be most performant
            }
        })
        if (isFound == true) {  // if house has all searched features, go on and compare tags
            let tagstring = house.querySelector('.tags').innerHTML // every house has ',' separated string
            // of options features and tags (tag search should check all of these)
            for (j = 0; j < tagArray.length; j++) {  // check for every set tag word
                if (!tagstring.includes(tagArray[j])) {
                    isFound = false // break on first missing of tag, cause the house doesnt fit in the filter
                    break;
                }
            }
        }
        if (isFound == false) {
            house.style.display = 'none' // hide house if it doesnt fit in filter
        } else {
            house.style.display = 'block' // show house if it fits in the filter
            resultCount++;
        }
    }
    document.getElementById("filter-result-count").innerHTML = String(resultCount);
}

function generateArrayFromTags(tags) {
    let result = []
    length = tags.length
    for (i = 0; i < length; i++) {
        result.push(tags[i].innerHTML)
    }
    return result
}

function useCheckbox(){
//     refresh filter results after clicking
 filterResults()
//     instantly store the filter in the backend
    saveFilter()
}


// ----------- Style of the Filter ------------------------------------------

const filterList = document.getElementById("filter-list");
const collapseButton = document.getElementById("collapse-filter-btn");

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
 * rearrange the filters appereance after resizing
 *
 */
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