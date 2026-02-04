<link rel="stylesheet" type="text/css" href="<?=CSS?>chat.css">


<section class="chatsection flex-row">

    <div class="contactdiv flex-col">
        <div class="contacttitle flex-col">
            <h4>Messagerie</h4>
        </div>
        <div class="contactlist flex-col">

            <article class="contactwrapper flex-col">
                <div class="contact flex-row">

                    <img class="userroundedimg" src="<?=IMG?>/damiers.png">
                    <div class="contacttext flex-col">
                        <div class="contactheader flex-row">
                            <span class="text contactusername">Alexlecture</span>
                            <span class="text">15:42</span>
                        </div>
                        <p class="contactlastmsg lightgrey12pxtext">Lorem ipsum dolor sit amet, consectetur .adipiscing elit, sed do eiusmod tempor </p>
                    </div>
                </div>

            </article>


        </div>
    </div>
    <div class="chatdiv flex-col">
        <div class="chatheader flex-row">

            <img class="userroundedimg" src="<?=IMG?>/damiers.png">
            <span class="capital14blacktext">Alexlecture</span>
        </div>
        <div class="chatdisplay">


        </div>
        <div class="chatbox flex-row">

            <input id="chatbar" class="chatbar lightgrey14pxtext" type="text" name="chatbar" placeholder="Tapez votre message ici" />
            <button name="send" value="send" class="chatsend greenButton">Envoyer</button>
        </div>

    </div>

</section>
