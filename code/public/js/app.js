function addEventListeners() {
  let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
  [].forEach.call(itemCheckers, function (checker) {
    checker.addEventListener('change', sendItemUpdateRequest);
  });

  let itemCreators = document.querySelectorAll('article.card form.new_item');
  [].forEach.call(itemCreators, function (creator) {
    creator.addEventListener('submit', sendCreateItemRequest);
  });

  let itemDeleters = document.querySelectorAll('article.card li a.delete');
  [].forEach.call(itemDeleters, function (deleter) {
    deleter.addEventListener('click', sendDeleteItemRequest);
  });

  let cardDeleters = document.querySelectorAll('article.card header a.delete');
  [].forEach.call(cardDeleters, function (deleter) {
    deleter.addEventListener('click', sendDeleteCardRequest);
  });

  let cardCreator = document.querySelector('article.card form.new_card');
  if (cardCreator != null)
    cardCreator.addEventListener('submit', sendCreateCardRequest);
}

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


function sendItemUpdateRequest() {
  let item = this.closest('li.item');
  let id = item.getAttribute('data-id');
  let checked = item.querySelector('input[type=checkbox]').checked;

  sendAjaxRequest('post', '/api/item/' + id, { done: checked }, itemUpdatedHandler);
}

function sendDeleteItemRequest() {
  let id = this.closest('li.item').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/item/' + id, null, itemDeletedHandler);
}

function sendCreateItemRequest(event) {
  let id = this.closest('article').getAttribute('data-id');
  let description = this.querySelector('input[name=description]').value;

  if (description != '')
    sendAjaxRequest('put', '/api/cards/' + id, { description: description }, itemAddedHandler);

  event.preventDefault();
}

function sendDeleteCardRequest(event) {
  let id = this.closest('article').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/cards/' + id, null, cardDeletedHandler);
}

function sendCreateCardRequest(event) {
  let name = this.querySelector('input[name=name]').value;

  if (name != '')
    sendAjaxRequest('put', '/api/cards/', { name: name }, cardAddedHandler);

  event.preventDefault();
}

function itemUpdatedHandler() {
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  let input = element.querySelector('input[type=checkbox]');
  element.checked = item.done == "true";
}

function itemAddedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);

  // Create the new item
  let new_item = createItem(item);

  // Insert the new item
  let card = document.querySelector('article.card[data-id="' + item.card_id + '"]');
  let form = card.querySelector('form.new_item');
  form.previousElementSibling.append(new_item);

  // Reset the new item form
  form.querySelector('[type=text]').value = "";
}

function itemDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  element.remove();
}

function cardDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);
  let article = document.querySelector('article.card[data-id="' + card.id + '"]');
  article.remove();
}

function cardAddedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);

  // Create the new card
  let new_card = createCard(card);

  // Reset the new card input
  let form = document.querySelector('article.card form.new_card');
  form.querySelector('[type=text]').value = "";

  // Insert the new card
  let article = form.parentElement;
  let section = article.parentElement;
  section.insertBefore(new_card, article);

  // Focus on adding an item to the new card
  new_card.querySelector('[type=text]').focus();
}

function createCard(card) {
  let new_card = document.createElement('article');
  new_card.classList.add('card');
  new_card.setAttribute('data-id', card.id);
  new_card.innerHTML = `
  
    <header>
      <h2><a href="cards/${card.id}">${card.name}</a></h2>
      <a href="#" class="delete">&#10761;</a>
    </header>
    <ul></ul>
    <form class="new_item">
      <input name="description" type="text">
    </form>`;

  let creator = new_card.querySelector('form.new_item');
  creator.addEventListener('submit', sendCreateItemRequest);

  let deleter = new_card.querySelector('header a.delete');
  deleter.addEventListener('click', sendDeleteCardRequest);

  return new_card;
}

function createItem(item) {
  let new_item = document.createElement('li');
  new_item.classList.add('item');
  new_item.setAttribute('data-id', item.id);
  new_item.innerHTML = `
    <label>
      <input type="checkbox"> <span>${item.description}</span><a href="#" class="delete">&#10761;</a>
    </label>
    `;

  new_item.querySelector('input').addEventListener('change', sendItemUpdateRequest);
  new_item.querySelector('a.delete').addEventListener('click', sendDeleteItemRequest);

  return new_item;
}

addEventListeners();


function upvote(id) {
  const buttonUpvote = document.getElementById(`${id}upvoteButton`);
  const buttonDownvote = document.getElementById(`${id}downvoteButton`);
  const upvotesElement = document.querySelector(`[data-id="${id}"] .upvotes`);
  const downvotesElement = document.querySelector(`[data-id="${id}"] .downvotes`);
  if (buttonUpvote.className === "not-clicked") {
    buttonUpvote.className = "clicked";
    buttonUpvote.innerHTML = "Upvoted";
    sendAjaxRequest('post', '../post/upvote', { id: id }, function (response) {
      upvotesElement.innerHTML = '<p class="upvotes" data-id="{{ $post->id }}"><strong>Upvotes: </strong>' + response + '</p>';
    });
    if (buttonDownvote.className === "clicked") {
      buttonDownvote.className = "not-clicked";
      buttonDownvote.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/></svg>`;
      sendAjaxRequest('post', '../post/undodownvote', { id: id }, function (response) {
        downvotesElement.innerHTML = '<p class="downvotes" data-id="{{ $post->id }}"><strong>Downvotes: </strong>' + response + '</p>';
      });
    }
  }
  else {
    buttonUpvote.className = "not-clicked";
    buttonUpvote.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"> \
    <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5"/> \
    </svg>';
    sendAjaxRequest('post', '../post/undoupvote', { id: id }, function (response) {
      upvotesElement.innerHTML = '<p class="upvotes" data-id="{{ $post->id }}"><strong>Upvotes: </strong>' + response + '</p>';
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
    buttonDownvote.innerHTML = "Downvoted";
    sendAjaxRequest('post', '../post/downvote', { id: id }, function (response) {
      downvotesElement.innerHTML = '<p class="downvotes" data-id="{{ $post->id }}"><strong>Downvotes: </strong>' + response + '</p>';
    });
    if (buttonUpvote.className === "clicked") {
      buttonUpvote.className = "not-clicked";
      buttonUpvote.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"> \
      <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5"/> \
      </svg>';
      sendAjaxRequest('post', '../post/undoupvote', { id: id }, function (response) {
        upvotesElement.innerHTML = '<p class="upvotes" data-id="{{ $post->id }}"><strong>Upvotes: </strong>' + response + '</p>';
      });
    }
  }
  else {
    buttonDownvote.className = "not-clicked";
    buttonDownvote.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/></svg>`;
    sendAjaxRequest('post', '../post/undodownvote', { id: id }, function (response) {
      downvotesElement.innerHTML = '<p class="downvotes" data-id="{{ $post->id }}"><strong>Downvotes: </strong>' + response + '</p>';
    });
  }
}

const pusher = new Pusher(pusherAppKey, {
  cluster: pusherCluster,
  encrypted: true
});

const channel = pusher.subscribe('lbaw2374');
channel.bind('notification-upvote', function (data) {

  const notification = document.getElementById('event');
  const closeButton = document.getElementById('closeButton');
  const notificationText = document.getElementById('eventText');
  notificationText.textContent = data.message;
  notification.classList.add('show');

  closeButton.addEventListener('click', function () {
    notification.classList.remove('show');
  });

  setTimeout(function () {
    notification.classList.remove('show');
  }, 5000);
});

channel.bind('notification-undoupvote', function (data) {

  const notification = document.getElementById('event');
  const closeButton = document.getElementById('closeButton');
  const notificationText = document.getElementById('eventText');
  notificationText.textContent = data.message;
  notification.classList.add('show');

  closeButton.addEventListener('click', function () {
    notification.classList.remove('show');
  });

  setTimeout(function () {
    notification.classList.remove('show');
  }, 5000);
});

channel.bind('notification-downvote', function (data) {

  const notification = document.getElementById('event');
  const closeButton = document.getElementById('closeButton');
  const notificationText = document.getElementById('eventText');
  notificationText.textContent = data.message;
  notification.classList.add('show');

  closeButton.addEventListener('click', function () {
    notification.classList.remove('show');
  });

  setTimeout(function () {
    notification.classList.remove('show');
  }, 5000);
});

channel.bind('notification-undodownvote', function (data) {

  const notification = document.getElementById('event');
  const closeButton = document.getElementById('closeButton');
  const notificationText = document.getElementById('eventText');
  notificationText.textContent = data.message;
  notification.classList.add('show');

  closeButton.addEventListener('click', function () {
    notification.classList.remove('show');
  });

  setTimeout(function () {
    notification.classList.remove('show');
  }, 5000);
});

