
const USER_IMG ="./public/img/users_images/"

fetch('index.php?action=getSenderList')
    .then(res => res.json()) // transforme la chaîne JSON en objet JS
    .then(chatlist=> {

        addChatListArticles(chatlist)
    });
//TODO pollNewMessages pour tout les contacts et rajouter  les nouveaux contacts de msg
pollNewMessages();


function addChatListArticles(chatlist){

    let contactlist = document.getElementById('contactlist');


    chatlist.forEach((chat,index) => {
        // Créer l'article
        let article = getContactArticleOfChatList(chat);


        // Ajouter à ton container
        contactlist.appendChild(article);
        if(index===0)
            selectContactChat(article,chat);
    });

    setSendMessageListener();
}

function getContactArticleOfChatList(chat){

    const article = document.createElement('article');
    article.className = 'contactwrapper flex-col';

    article.addEventListener('click', () => {
        selectContactChat(article,chat)
    });


    if(chat.userPic.includes("damiers.png")) chat.userPic = "damiers.png"

    article.innerHTML = `
        
            <div class="contact flex-row">
                <input type="hidden" id="userID" class="userID" value="${chat.userID}">
                <img class="userroundedimg" src="${USER_IMG+chat.userPic}">
                <div class="contacttext flex-col">
                    <div class="contactheader flex-row">
                        <span class="text contactusername">${chat.username}</span>
                        <span class="text">${chat.sentAt}</span>
                    </div>
                    <p class="contactlastmsg lightgrey12pxtext">${chat.lastMessage}</p>
                </div>
            </div>
    `;
    return article;

}

function selectContactChat(article,chat){

    let chatdiv = document.getElementById('chatdiv');

    removeOldChatHeader(chatdiv)
    setOldContactArticleInactive();

    clearChatDisplay();
    fetchChatMessagesWithSenderID(chat['userID']);


    setNewContactArticleActive(article);
    sendReadMark();
    addNewChatHeader(chatdiv,chat);
}

function setOldContactArticleInactive(){

    let oldArticle = document.querySelector('.activecontact');
    if(oldArticle != null) {
        oldArticle.classList.remove('activecontact');
        let img = oldArticle.querySelector('.userroundedimg.activeimg');
        img.classList.remove('activeimg');
    }
}
function setNewContactArticleActive(article){
    article.classList.add('activecontact');
    let img = article.querySelector('.userroundedimg');
    img.classList.add('activeimg');
}

function removeOldChatHeader(chatdiv){
    const oldChatHeader = chatdiv.querySelector(".chatheader")
    if(oldChatHeader != null)
        oldChatHeader.remove();
}

function addNewChatHeader(chatdiv,chat){

    const chatheader = document.createElement('div');
    chatheader.className = 'chatheader flex-row';

    chatheader.innerHTML = `<img class="userroundedimg" src="${USER_IMG+chat['userPic']}"/>
                      <span class="capital14blacktext">${chat['username']}</span>       `;

    chatdiv.prepend(chatheader);
}

function fetchChatMessagesWithSenderID(senderID){

    fetch(`index.php?action=getMessages&id=${senderID}`)
        .then(res => res.json()) // transforme la chaîne JSON en objet JS
        .then(messages=> {
            displayMessages(messages);
        });

}

function clearChatDisplay(){

    let chatDisplay = document.getElementById("chatdisplay");
    chatDisplay.innerHTML='';
}

function displayMessages(messages){

    messages.forEach(message=>{

        addMessageToChatDisplay(message);

    });

}

function addMessageToChatDisplay(message){

    let contactlist = document.getElementById('contactlist');
    let activeuserID = contactlist.querySelector('.activecontact').querySelector('.userID').value;
    let contactimg = contactlist.querySelector('.userroundedimg.activeimg');

    const msgbubble = document.createElement('div');


    if(isMessageFromContact(activeuserID,message['idSender'].toString())) {
        msgbubble.className = "msg flex-end flex-col";
        msgbubble.innerHTML = `<div class="msgheader flex-row">
                                    <input type="hidden" id="idMsg" class="idMsg" value="${message.idMessage}">
                                    <span class="msgdate textalignright  lightgrey12pxtext">${message["sentAt"]}</span>
                                </div>
                                <div class="msgbubble">
                                    <p class="msgcontent text12px" >${message["message"]}</p>
                                </div>      `;
    } else {
        msgbubble.className = "msg flex-col";

        msgbubble.innerHTML = `<div class="msgheader flex-row">
                                    <input type="hidden" id="idMsg" class="idMsg" value="${message.idMessage}">
                                    <img class="littleuserroundedimg" src="${contactimg.src}"/>
                                    <span class="msgdate lightgrey12pxtext">${message["sentAt"]}</span>
                                </div>
                                <div class="msgbubble">
                                    <p class="msgcontent text12px" >${message["message"]}</p>
                                </div>      `;
    }


    chatdisplay.appendChild(msgbubble);
    chatdisplay.scrollTop = 0;
}

function addNewMsgToDisplay(message){

    let contactlist = document.getElementById('contactlist');
    let activeuserID = contactlist.querySelector('.activecontact').querySelector('.userID').value;
    let contactimg = contactlist.querySelector('.userroundedimg.activeimg');

    const msgbubble = document.createElement('div');


    if(isMessageFromContact(activeuserID,message['idSender'].toString())) {
        msgbubble.className = "msg flex-end flex-col";
        msgbubble.innerHTML = `<div class="msgheader flex-row">
                                    <input type="hidden" id="idMsg" class="idMsg" value="${message.idMessage}">
                                    <span class="msgdate textalignright  lightgrey12pxtext">${message["sentAt"]}</span>
                                </div>
                                <div class="msgbubble">
                                    <p class="msgcontent text12px" >${message["message"]}</p>
                                </div>      `;
    } else {
        msgbubble.className = "msg flex-col";

        msgbubble.innerHTML = `<div class="msgheader flex-row">
                                    <input type="hidden" id="idMsg" class="idMsg" value="${message.idMessage}">
                                    <img class="littleuserroundedimg" src="${contactimg.src}"/>
                                    <span class="msgdate lightgrey12pxtext">${message["sentAt"]}</span>
                                </div>
                                <div class="msgbubble">
                                    <p class="msgcontent text12px" >${message["message"]}</p>
                                </div>      `;
    }


    chatdisplay.prepend(msgbubble);
    chatdisplay.scrollTop = 0;

}

function isMessageFromContact(activeuserID, senderID){

    return activeuserID !== senderID;

}

function setSendMessageListener(){

    document.querySelector('#sendBtn').addEventListener('click', () => {

        const inputMessage = document.querySelector('#chatbar');
        let receiverId = document.querySelector('.activecontact').querySelector('#userID').value;
        sendMessageToServer(inputMessage.value, receiverId);
        inputMessage.value = ''; // vider le champ
    });

}

async function pollNewMessages() {
    try {
        await fetchNewMessages();
    } catch (error) {
        console.error("Erreur lors du polling :", error);
    } finally {
        setTimeout(pollNewMessages, 5000);
    }
}

async function fetchNewMessages() {
    try {
        if (document.querySelector('.activecontact') !== null) {
            let contactId = document.querySelector('.activecontact').querySelector('#userID').value;
            let msgId = document.querySelector('#chatdisplay').firstElementChild.querySelector('#idMsg').value;

            console.log("IDMSG" + msgId);

            const response = await fetch("index.php?action=newMessage&id=" + contactId + "&idMsg=" + msgId);
            const messages = await response.json();

            if (messages.length > 0) {
                messages.forEach(msg => {
                    console.log("res:" + msg['message']);

                    if (msg['idMessage'] > msgId)
                        addNewMsgToDisplay(msg); // fonction d'affichage
                });
            }
        }
    }
    catch
        (error)
        {
            console.error("Erreur polling :", error);
        }

}

function sendMessageToServer(messageText, receiverID) {

    if(messageText > 0) {

        // Crée les données à envoyer
        const data = new FormData();
        data.append('message', messageText);
        data.append('receiver', receiverID);

        // Appel fetch vers sendMessage.php
        fetch(`index.php?action=sendMessage&id=${receiverID}`, {
            method: 'POST',
            body: data
        })
            .then(response => response.json()) // si PHP renvoie du JSON
            .then(result => {
                console.log('Message envoyé:', result);
                // Ici tu peux mettre à jour l'UI, vider le champ input, etc.
            })
            .catch(error => {
                console.error('Erreur lors de l\'envoi:', error);
            });
        fetchNewMessages();
    }
}

function sendReadMark(){

    let activeContact = document.getElementById('contactlist').querySelector('.activecontact').querySelector('.userID').value;

    // Crée les données à envoyer
    const data = new FormData();
    data.append('message', 'ReadMark');
    data.append('receiver', activeContact);

    // Appel fetch vers sendMessage.php
    fetch(`index.php?action=sendReadMark&id=${activeContact}`, {
        method: 'POST',
        body: data
    })
        .then(response => response.json()) // si PHP renvoie du JSON
        .then(result => {
            console.log('Message envoyé:', result);
            // Ici tu peux mettre à jour l'UI, vider le champ input, etc.
        })
        .catch(error => {
            console.error('Erreur lors de l\'envoi:', error);
        });

}