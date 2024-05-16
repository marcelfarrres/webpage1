function renderHtml(html, element) {

    const dummy = document.createElement('div')

    dummy.innerHTML = html



    element.appendChild(dummy.firstChild)

}



function render(songs) {

    for (let song of songs) {

        renderHtml(

            '<div>' +

            `<p>${song.title}</p>` +

            `<p>${song.duration}</p>` +

            '</div>',

            document.getElementById('songs')

        )

    }

}



function onLoad() {

    fetch('/api/songs')

        .then(res => {

            if (res.status !== 200) {

                console.log("Error! " + res.status)

            }



            res.json()

                .then(obj => {

                    render(obj)

                })



        })

        .catch(err => {

            window.location.href = "/"

        })



    const button = document.getElementById('button')

    button.addEventListener('click', evt => {

        evt.preventDefault()



        fetch('/api/songs', {

            method: 'POST',

            headers: {

                "Content-Type": "application/json",

            },

            body: JSON.stringify({

                title: 'Test'

            })

        })

            .then(res => {

                res.json()

                    .then(obj => {

                        console.log(obj)

                    })



            })

            .catch(err => {

                window.location.href = "/"

            })

    })

}



window.addEventListener('load', onLoad)

[PWII] API Example
From: Pol Muñoz Pastor <pol.munoz@salle.url.edu>
Subject: [PWII] API Example
Date: 30 April 2024 at 16:59:17 CEST
To: Pol Muñoz Pastor <pol.munoz@salle.url.edu>

Hi,

If you’re receiving this message is because you signed up to get the example we worked on during today’s class. I’m attaching the following files:

www/config/routing.php
www/public/assets/js/songs.js
www/src/Controller/SongController.php
www/templates/base.twig
www/templates/songs.twig

Kind regards,
Pol

Pol Muñoz Pastor
