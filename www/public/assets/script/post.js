function renderPost(post) {
    const postList = document.getElementById('post-list');
    const postItem = document.createElement('li');
    postItem.innerHTML = `
        <h3 class="label">${post.title}</h3>
        <p class="label">${post.contents}</p>
    `;
    postList.appendChild(postItem);
}

function fetchForumDetails(forumId) {
    console.log(forumId);
    fetch(`/api/forums/${forumId}`)
        .then(res => {
            if (res.status !== 200) {
                console.log("Error! " + res.status);
            }
            return res.json();
        })
        .then(forum => {
           
            // Render forum name and description
            const forumName = forum['title'];
            const forumDescription = forum['description'];
            document.getElementById('forum-details').innerHTML += `
                <h2 class="label" >Forum: ${forumName}</h2>
                <p class="label" >Description: ${forumDescription}</p>
            `;
           
        })
        .catch(err => {
            console.error("Error fetching forum details:", err);
        });
}



function fetchPosts(forumId) {
    fetch(`/api/forums/${forumId}/posts`)
        .then(res => {
            if (res.status !== 200) {
                console.log("Error! " + res.status);
            }
            return res.json();
        })
        .then(posts => {
            const postList = document.getElementById('post-list');
            postList.innerHTML = '';
            
            // Render posts
            for (let post of posts) {
                renderPost(post);
            }
        })
        .catch(err => {
            console.error("Error fetching forum details:", err);
        });
}

function createPost(event) {
    event.preventDefault();

    var forumId = document.getElementById('forumId').getAttribute('data-forum-id');
    forumId = parseInt(forumId);

    const title = document.getElementById('title').value;
    const contents = document.getElementById('contents').value;
    

    fetch(`/api/forums/${forumId}/posts`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            title: title,
            contents: contents
        })
    })
    .then(res => {
        if (res.status !== 201) {
            console.log("Error! " + res.status);
        }
        return res.json();
    })
    .then(newPost => {
        console.log("New post created:", newPost);
        
        fetchPosts(forumId);
    })
    .catch(err => {
        console.error('Error creating post:', err);
       
    });
}




function onLoad() {
    var forumId = document.getElementById('forumId').getAttribute('data-forum-id');
    forumId = parseInt(forumId);
   
    fetchForumDetails(forumId);
    fetchPosts(forumId);

    const createPostForm = document.getElementById('create-post-form');
    createPostForm.addEventListener('submit', createPost);
}

window.addEventListener('load', onLoad);