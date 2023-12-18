function upvote(id) {
    const buttonUpvote = document.getElementById(`${id}upvoteButton`);
    const buttonDownvote = document.getElementById(`${id}downvoteButton`);
    const upvotesElement = document.querySelector(`[data-id="${id}"] .upvotes`);
    const downvotesElement = document.querySelector(`[data-id="${id}"] .downvotes`);
    if (buttonUpvote.className === "not-clicked") {
        buttonUpvote.className = "clicked";
        buttonUpvote.innerHTML = '<i class="fa-solid fa-circle-up"></i>';
        sendAjaxRequest('post', '../post/upvote', { id: id }, function (response) {
            const upvotes = parseInt(response);
            const downvotes = parseInt(downvotesElement.textContent);
            const difference = upvotes - downvotes;
            upvotesElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${difference}</strong><br></div>`;
        });
        if (buttonDownvote.className === "clicked") {
            buttonDownvote.className = "not-clicked";
            buttonDownvote.innerHTML = '<i class="fa-regular fa-circle-up"></i>';
            sendAjaxRequest('post', '../post/undodownvote', { id: id }, function (response) {
                const upvotes = parseInt(upvotesElement.textContent);
                const downvotes = parseInt(response);
                const difference = upvotes - downvotes;
                downvotesElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${difference}</strong><br></div>`;
            });
        }
    }
    else {
        buttonUpvote.className = "not-clicked";
        buttonUpvote.innerHTML = '<i class="fa-regular fa-circle-up"></i>';
        sendAjaxRequest('post', '../post/undoupvote', { id: id }, function (response) {
            const upvotes = parseInt(response);
            const downvotes = parseInt(downvotesElement.textContent);
            const difference = upvotes - downvotes;
            upvotesElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${difference}</strong><br></div>`;
        });
    }
}

function downvote(id) {
    const buttonUpvote = document.getElementById(`${id}upvoteButton`);
    const buttonDownvote = document.getElementById(`${id}downvoteButton`);
    const upvotesElement = document.querySelector(`[data-id="${id}"] .upvotes`);
    const downvotesElement = document.querySelector(`[data-id="${id}"] .downvotes`);
        if (buttonDownvote.className === "not-clicked") {
                buttonDownvote.className = "clicked";
                buttonDownvote.innerHTML = '<i class="fa-solid fa-circle-down"></i>';
                sendAjaxRequest('post', '../post/downvote', { id: id }, function (response) {
                        const upvotes = parseInt(upvotesElement.textContent);
                        const downvotes = parseInt(response);
                        const difference = upvotes - downvotes;
                        downvotesElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${difference}</strong><br></div>`;
        }   );
        if (buttonUpvote.className === "clicked") {
                buttonUpvote.className = "not-clicked";
                buttonUpvote.innerHTML = '<i class="fa-regular fa-circle-down"></i>';
                sendAjaxRequest('post', '../post/undoupvote', { id: id }, function (response) {
                        const upvotes = parseInt(response);
                        const downvotes = parseInt(downvotesElement.textContent);
                        const difference = upvotes - downvotes;
                        upvotesElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${difference}</strong><br></div>`;
                });
        }
    }
    else {
        buttonDownvote.className = "not-clicked";
        buttonDownvote.innerHTML = '<i class="fa-regular fa-circle-down"></i>';
        sendAjaxRequest('post', '../post/undodownvote', { id: id }, function (response) {
            const upvotes = parseInt(upvotesElement.textContent);
            const downvotes = parseInt(response);
            const difference = upvotes - downvotes;
            downvotesElement.innerHTML = `<div class="upvotes-downvotes" data-id="${id}"><strong>${difference}</strong><br></div>`;
        });
    }
}