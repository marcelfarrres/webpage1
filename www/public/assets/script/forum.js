function renderForum(forum) {
    const forumList = document.getElementById('forum-list');
    
    const forumItem = document.createElement('li');
    forumItem.innerHTML = `
        <div class="label">
            <p>${forum.title}</p>
            <p>${forum.description}</p>
        </div>
        <div class="actions">
            <button class='button' onclick="deleteForum(${forum.id})">Delete</button>
        </div>
        <a class="button" href="/forums/${forum.id}/posts">Enter</a>
    `;
    forumList.appendChild(forumItem);
}


function renderForums(forums) {
    const forumList = document.getElementById('forum-list');

    forumList.innerHTML = '';
    for (let forum of forums) {
        renderForum(forum); 
    }
}

function fetchForums() {
    fetch('/api/forums')
        .then(res => {
            if (res.status !== 200) {
                console.log("Error! " + res.status);
            }
            return res.json();
        })
        .then(forums => {
            renderForums(forums);
        })
        .catch(err => {
            console.error('Error fetching forums:', err);
            window.location.href = "/";
        });
}

function deleteForum(forumId) {
    fetch(`/api/forums/${forumId}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (response.ok) {
            console.log('Forum deleted successfully');
            fetchForums();
        } else {
            console.error('Failed to delete forum');
        }
    })
    .catch(error => {
        console.error('Error deleting forum:', error);
    });
}

function enterForum(forumId) {
    fetch(`/forums/${forumId}/posts`, {
        method: 'get'
    })
    .then(response => {
        if (response.ok) {
            
        } else {
            console.error('Failed to enter Forum');
        }
    })
    .catch(error => {
        console.error('Error entering Forummm:', error);
    });
}


function createForum(event) {
    event.preventDefault();

    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;

    fetch('/api/forums', {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            title: title,
            description: description
        })
    })
    .then(res => {
        if (res.status !== 201) {
            console.log("Error! " + res.status);
        }
        return res.json();
    })
    .then(newForum => {
        console.log("New forum created:", newForum);
        
        fetchForums();
    })
    .catch(err => {
        console.error('Error creating forum:', err);
        window.location.href = "/";
    });
}

function onLoad() {
    fetchForums();

    const createForumForm = document.getElementById('create-forum-form');
    createForumForm.addEventListener('submit', createForum);
}

window.addEventListener('load', onLoad);
