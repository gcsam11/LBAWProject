function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    if (handler) {
        request.addEventListener('load', function () {
            if (request.status === 200) {
                var response = JSON.parse(request.responseText);
                handler(response);
            } else {
                console.error('Request failed with status:', request.status);
            }

        });

    }
    request.send(encodeForAjax(data));
}


function upvote(id) {
    console.log("Upvote JS");
    const buttonUpvote = document.getElementById(`${id}upvoteButton`);
    const buttonDownvote = document.getElementById(`${id}downvoteButton`);
    const repElement = document.querySelector(`[data-id="${id}"] .upvotes-downvotes`);
    if (buttonUpvote.className === "not-clicked") {
        buttonUpvote.className = "clicked";
        buttonUpvote.innerHTML = '<i class="fa-solid fa-circle-up"></i>';
        sendAjaxRequest('post', '../post/upvote', { id: id }, function (response) {
            repElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${response}</strong><br></div>`;
        });
        if (buttonDownvote.className === "clicked") {
            buttonDownvote.className = "not-clicked";
            buttonDownvote.innerHTML = '<i class="fa-regular fa-circle-up"></i>';
            sendAjaxRequest('post', '../post/undodownvote', { id: id }, function (response) {
                repElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${response}</strong><br></div>`;
            });
        }
    }
    else {
        buttonUpvote.className = "not-clicked";
        buttonUpvote.innerHTML = '<i class="fa-regular fa-circle-up"></i>';
        sendAjaxRequest('post', '../post/undoupvote', { id: id }, function (response) {
            repElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${response}</strong><br></div>`;
        });
    }
}

function downvote(id) {
    console.log("Downvote JS");
    const buttonUpvote = document.getElementById(`${id}upvoteButton`);
    const buttonDownvote = document.getElementById(`${id}downvoteButton`);
    const repElement = document.querySelector(`[data-id="${id}"] .upvotes-downvotes`);
    if (buttonDownvote.className === "not-clicked") {
        buttonDownvote.className = "clicked";
        buttonDownvote.innerHTML = '<i class="fa-solid fa-circle-down"></i>';
        sendAjaxRequest('post', '../post/downvote', { id: id }, function (response) {
            repElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${response}</strong><br></div>`;
        });
        if (buttonUpvote.className === "clicked") {
            buttonUpvote.className = "not-clicked";
            buttonUpvote.innerHTML = '<i class="fa-regular fa-circle-down"></i>';
            sendAjaxRequest('post', '../post/undoupvote', { id: id }, function (response) {
                repElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${response}</strong><br></div>`;
            });
        }
    }
    else {
        buttonDownvote.className = "not-clicked";
        buttonDownvote.innerHTML = '<i class="fa-regular fa-circle-down"></i>';
        sendAjaxRequest('post', '../post/undodownvote', { id: id }, function (response) {
            repElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${response}</strong><br></div>`;
        });
    }
}