<link rel="stylesheet" type="text/css" href="<?=CSS?>chat.css">


<section class="chatsection flex-row">

    <script src="<?=JS?>chat.js"></script>

    <div class="contactdiv flex-col">
        <div class="contacttitle flex-col">
            <h4>Messagerie</h4>
        </div>
        <div id="contactlist" class="contactlist flex-col">


        </div>
    </div>
    <div id="chatdiv" class="chatdiv flex-col">

        <div id="chatdisplay" class="chatdisplay flex-col">


        </div>
        <div class="chatbox flex-row">

            <input id="chatbar" class="chatbar lightgrey14pxtext" type="text" name="chatbar" placeholder="Tapez votre message ici" />
            <button name="send" id="sendBtn" value="send" class="chatsend greenButton">Envoyer</button>
        </div>
    </div>

</section>
