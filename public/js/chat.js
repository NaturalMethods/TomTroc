
fetch('index.php?action=getSenderList')
    .then(res => res.json()) // transforme la chaîne JSON en objet JS
    .then(chatlist=> {

        let contactlist = document.getElementById('contactlist');

        chatlist.forEach(chat => {
            // Créer l'article
            const article = document.createElement('article');
            article.className = 'contactwrapper flex-col';

            article.innerHTML = `
        
            <div class="contact flex-row">
                <img class="userroundedimg" src="${chat.userPic}">
                <div class="contacttext flex-col">
                    <div class="contactheader flex-row">
                        <span class="text contactusername">${chat.username}</span>
                        <span class="text">${chat.sentAt}</span>
                    </div>
                    <p class="contactlastmsg lightgrey12pxtext">${chat.lastMessage}</p>
                </div>
            </div>
    `;

            // Ajouter à ton container
            contactlist.appendChild(article);
        });

    });

