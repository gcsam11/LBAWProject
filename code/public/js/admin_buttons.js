function block(id) {
    const $id = id;
    const buttonBlock = document.getElementById($id);
    if (buttonBlock.className === "not-clicked") {
        buttonBlock.className = "clicked";
        buttonBlock.innerHTML = 'Unblock';
        fetch('/profile/block', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => {
            if (response.status === 200) {
                console.log('Successfully blocked the user');
            } else {
                console.log('Failed to block the user');
            }
        })
        .catch(error => {
            console.log('Failed to block the user:', error);
        });
    }
    else {
        buttonBlock.className = "not-clicked";
        buttonBlock.innerHTML = 'Block';
        fetch('/profile/unblock', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => {
            if (response.status === 200) {
                console.log('Successfully unblocked the user');
            } else {
                console.log('Failed to unblock the user');
            }
        })
        .catch(error => {
            console.log('Failed to unblock the user:', error);
        });
    }
}