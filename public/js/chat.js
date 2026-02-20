const USER_IMG = "./public/img/users_images/"
let contactList = document.getElementById('contactlist');
let activeContactElement;
let activeContact;
let lastMessageId = 0;

const contactsMap = new Map();

fetch('index.php?action=getSenderList')
    .then(res => res.json()) // transforme la chaîne JSON en objet JS
    .then(chatlist => {

        addChatListArticles(chatlist);
        pollNewMessages();
    })


/**
 * Create each article for chat list on the left
 * @param chatlist
 */
function addChatListArticles(chatlist) {

    chatlist.forEach((chat, index) => {

        if (!chat.userPic) chat.userPic =  "damiers.png";

        let article = addOrUpdateContact(chat);

        if (index === 0) selectContactChat(article, chat);
    });

    setSendMessageListener();
}

/**
 * Create and return a contact article for chat list
 * @param chat
 * @returns {HTMLElement}
 */
function createContactArticle(chat) {
    const article = document.createElement('article');
    article.className = 'contactwrapper flex-col';

    article.innerHTML = `
        <div class="contact flex-row">
            <img class="userroundedimg" src="${chat.userPic}" alt="user image">
            <div class="contacttext flex-col">
                <div class="contactheader flex-row">
                    <span class="text contactusername">${chat.username}</span>
                    <span class="text contactSentAt">${chat.sentAt}</span>
                </div>
                <p class="contactlastmsg lightgrey12pxtext">${chat.lastMessage}</p>
            </div>
        </div>
    `;

    article.refs = {
        username: article.querySelector('.contactusername'),
        lastMsg: article.querySelector('.contactlastmsg'),
        sentAt: article.querySelector('.contactSentAt'),
        img: article.querySelector('.userroundedimg')
    };

    article.addEventListener('click', () => selectContactChat(article, chat));

    return article;
}

/**
 * Format date received from server H:i (ex: 15:05)
 * @param date
 * @returns {string}
 */
function formatDate(date) {

    return new Date(date.replace(' ', 'T'))
        .toLocaleTimeString('fr-FR', {
            hour: '2-digit', minute: '2-digit', hour12: false
        });
}

/**
 * Add or Update a contact article with his infos in the chat list
 * @param chat
 * @returns {HTMLElement|any}
 */
function addOrUpdateContact(chat) {

    if (contactsMap.has(chat.userID)) {

        const article = contactsMap.get(chat.userID);
        article.refs.username.textContent = chat.username;
        article.refs.lastMsg.textContent = chat.lastMessage;
        article.refs.sentAt.textContent = formatDate(chat.sentAt);

        if (!chat.userPic) chat.userPic = "damiers.png";
        article.refs.img.src = USER_IMG + (chat.userPic.includes("damiers.png") ? "damiers.png" : chat.userPic);

        contactList.append(article);
        return article;
    } else {

        const article = createContactArticle(chat);
        contactList.append(article);
        contactsMap.set(chat.userID, article);
        return article;
    }
}

/**
 * Set global infos about the contact for which the chat is displayed
 * @param chat
 */
function setNewActiveContact(chat) {
    activeContact = chat;
    if (activeContact.userPic && !activeContact.userPic.startsWith(USER_IMG)) {
        activeContact.userPic = USER_IMG + activeContact.userPic;
    }
}

/**
 * Clear chat display et display the new selected contact's chat
 * @param article
 * @param chat
 */
function selectContactChat(article, chat) {

    let chatdiv = document.getElementById('chatdiv');

    removeOldChatHeader(chatdiv)
    setOldContactArticleInactive();

    clearChatDisplay();

    setNewActiveContact(chat);

    setNewContactArticleActive(article);

    fetchChatMessagesWithSenderID(activeContact['userID']);

    addNewChatHeader(chatdiv);
}

/**
 * Remove active CSS class from elements
 */
function setOldContactArticleInactive() {

    let oldArticle = activeContactElement;
    if (oldArticle != null) {
        oldArticle.classList.remove('activecontact');
        let img = oldArticle.querySelector('.userroundedimg.activeimg');
        img.classList.remove('activeimg');
    }
}

/**
 * Add active CSS class on elements
 * @param article
 */
function setNewContactArticleActive(article) {
    activeContactElement = article;
    article.classList.add('activecontact');
    let img = article.querySelector('.userroundedimg');
    img.classList.add('activeimg');
}

/**
 * Remove chat header elements from the chat div
 * @param chatdiv
 */
function removeOldChatHeader(chatdiv) {
    const oldChatHeader = chatdiv.querySelector(".chatheader")
    if (oldChatHeader != null) oldChatHeader.remove();
}

/**
 * Add chat header elements from the chat div
 * @param chatdiv
 */
function addNewChatHeader(chatdiv) {

    const chatheader = document.createElement('div');
    chatheader.className = 'chatheader flex-row';


    chatheader.innerHTML = `<img class="userroundedimg" src="${activeContact['userPic']}" alt="user image"/>
                      <span class="capital14blacktext">${activeContact['username']}</span>       `;

    chatdiv.prepend(chatheader);
}

/**
 * Fetch all message between contact and user connected
 * @param senderID
 */
function fetchChatMessagesWithSenderID(senderID) {

    fetch(`index.php?action=getMessages&id=${senderID}`)
        .then(res => res.json()) // transforme la chaîne JSON en objet JS
        .then(messages => {
            displayMessages(messages);
        });

}

/**
 * Remove all HTML elements from chat display
 */
function clearChatDisplay() {
    document.getElementById("chatdisplay").innerHTML = '';
}

/**
 * Display all messages in array in the chat display and send a read mark to server
 * @param messages
 */
function displayMessages(messages) {
    const messagesArray = [].concat(messages || []);

    messagesArray.forEach(msg => {
        addMsgToChatDisplay(msg);

        if (msg.idMessage > lastMessageId) lastMessageId = msg.idMessage;

    });

    sendReadMark();
}

/**
 * Add a message to the chat display
 * @param message
 */
function addMsgToChatDisplay(message) {

    let msgBubble = createMessageBubble(message);

    chatdisplay.prepend(msgBubble);
    chatdisplay.scrollTop = 0;
}

/**
 * Define which side of the chat the msg is displayed contact|user connected (left| right)
 * @param msgBubble
 * @param imgPath
 */
function setSenderSideBubble(msgBubble, imgPath) {

    msgBubble.className = "msg flex-col";

    let img = document.createElement("img");
    img.className = "littleuserroundedimg";
    img.src = imgPath;
    img.alt="user image";
    msgBubble.querySelector('.msgheader').prepend(img);
    msgBubble.querySelector('.msgdate').className = "msgdate lightgrey12pxtext";

}

/**
 * Create a bubble HTML element containing message to display
 * @param message
 * @returns {HTMLDivElement}
 */
function createMessageBubble(message) {

    let msgbubble = document.createElement('div');

    msgbubble.className = "msg flex-end flex-col";
    msgbubble.innerHTML = `<div class="msgheader flex-row">
                                    <input type="hidden" id="idMsg" class="idMsg" value="${message.idMessage}">
                                    <span class="msgdate textalignright  lightgrey12pxtext">${message["sentAt"]}</span>
                                </div>
                                <div class="msgbubble">
                                    <p class="msgcontent text12px" >${message["message"]}</p>
                                </div>      `;

    if (!isMessageFromContact(activeContact['userID'], message['idSender'])) setSenderSideBubble(msgbubble, activeContact['userPic']);

    return msgbubble;
}

/**
 * Return true if the message was sent by the contact
 * @param activeuserID
 * @param senderID
 * @returns {boolean}
 */
function isMessageFromContact(activeuserID, senderID) {
    return activeuserID !== senderID;
}

/**
 * Add listener to the chat bar and send button to send message
 */
function setSendMessageListener() {

    document.querySelector('#sendBtn').addEventListener('click', () => {
        sendMessage();
    });
    document.querySelector('#chatbar').addEventListener('keydown', function (e) {

        if (e.key === "Enter") sendMessage();
    });

}

/**
 * Poll new messages from the serveur every 5 second
 * @returns {Promise<void>}
 */
async function pollNewMessages() {
    try {
        await fetchNewMessages();
    } catch (error) {
        console.error("Erreur lors du polling :", error);
    } finally {
        setTimeout(pollNewMessages, 5000);
    }
}

/**
 * Fetch new messages and chat list to update them
 * @returns {Promise<void>}
 */
async function fetchNewMessages() {
    try {
        const response = await fetch("index.php?action=newMessage&id=" + activeContact['userID'] + "&idMsg=" + lastMessageId);
        let messages = await response.json();
        filterAndDisplayNewMsg(messages);

        console.log("lastID:"+lastMessageId);

        const response2 = await fetch('index.php?action=getSenderList&idMsg=' + lastMessageId);
        let chats = await response2.json();
        chats.forEach(chat => {
            //console.log(chat);
            addOrUpdateContact(chat);
        });
    } catch (error) {
        console.error("Erreur polling :", error);
    }

}

/**
 * Filter to display only new messages send by the server
 * @param messages
 */
function filterAndDisplayNewMsg(messages) {

    const newMessages = [].concat(messages || [])
        .filter(msg => msg.idMessage > lastMessageId);

    if (newMessages.length) {
        displayMessages(newMessages);
    }
}

/**
 * Send a message to the server with the contact id
 */
function sendMessage() {
    const inputMessage = document.querySelector('#chatbar');
    sendMessageToServer(inputMessage.value, activeContact['userID']);
    inputMessage.value = '';
}

/**
 * Send message and Fetch response to the server
 * @param messageText
 * @param receiverID
 */
function sendMessageToServer(messageText, receiverID) {

    if (messageText.length > 0) {

        // Crée les données à envoyer
        const data = new FormData();
        data.append('message', messageText);
        data.append('receiver', receiverID);

        // Appel fetch vers sendMessage.php
        fetch(`index.php?action=sendMessage&id=${receiverID}`, {
            method: 'POST', body: data
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

/**
 * Send read mark to the server
 */
function sendReadMark() {

    // Crée les données à envoyer
    const data = new FormData();
    data.append('senderId', activeContact['userID']);

    // Appel fetch vers sendMessage.php
    fetch(`index.php?action=sendReadMark&id=${activeContact['userID']}`, {
        method: 'POST', body: data
    })
        .then(response => response.json()) // si PHP renvoie du JSON
        .then(result => {
            console.log('ReadMark Sent:', result);
            // Ici tu peux mettre à jour l'UI, vider le champ input, etc.
        })
        .catch(error => {
            console.error('Erreur lors de l\'envoi:', error);
        });

}