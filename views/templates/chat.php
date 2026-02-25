<link rel="stylesheet" type="text/css" href="<?=CSS?>chat.css">


<section class="chatsection flex-row">

    <div class="contactdiv flex-col">
        <div class="contacttitle flex-col">
            <h4>Messagerie</h4>
        </div>
        <div id="contactlist" class="contactlist flex-col"></div>
    </div>
    <div id="chatdiv" class="chatdiv flex-col">

        <div id="chatdisplay" class="chatdisplay ">



        </div>
        <div class="chatbox flex-row">
            <label for="sendBtn" class="sr-only" >Chat bar</label>
            <label for="chatbar" class="sr-only">chat Bar</label><input title="chatbar" id="chatbar" class="chatbar lightgrey14pxtext" type="text" name="chatbar" placeholder="Tapez votre message ici" />
            <button name="send" id="sendBtn" value="send" class="chatsend greenButton">Envoyer</button>
        </div>
    </div>
    <script src="<?=JS?>chat.js"></script>
</section>
