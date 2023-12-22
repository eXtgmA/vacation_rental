/**
 * Open link with relative path
 * @param href string
 */
function openLink(href) {
    window.location.href = href;
}

/**
 * Send post request
 * @param url string
 * @param data object
 * @param callback function
 */
function sendPostRequest(url, data, callback) {
    let fetchData = {
        method: 'POST',
        body: data,
        headers: new Headers()
    }

    fetch(url, fetchData)
        .then(response => {
            if (response.ok && response.redirected) {
                window.location.href = response.url;
            } else {
                return response.json();
            }
        })
        .then(data => callback(data))
        .catch(error => console.log(error));
}