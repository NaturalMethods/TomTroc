<link rel="stylesheet" type="text/css" href="<?=CSS?>chat.css">


<section class="chatsection flex-row">

    <div class="contactdiv flex-col">
        <div class="contacttitle flex-col">
            <h4>Messagerie</h4>
        </div>
        <div class="contactlist flex-col">
            <?php foreach($chats as $chat) { ?>
            <article class="contactwrapper flex-col">
                <div class="contact flex-row">

                    <img class="userroundedimg" src="<?= $chat->getSenderUser()->getUserPic(); ?>">
                    <div class="contacttext flex-col">
                        <div class="contactheader flex-row">
                            <span class="text contactusername"><?= $chat->getSenderUser()->getUsername(); ?></span> <!-- -->
                            <span class="text"><?= $chat->getLastMessage()->getSentAt()->format('H:i'); ?></span>
                        </div>
                        <p class="contactlastmsg lightgrey12pxtext"><?= $chat->getLastMessage()->getMessage(); ?></p>
                    </div>
                </div>

            </article>
            <?php } ?>

        </div>
    </div>
    <div class="chatdiv flex-col">
        <?php if($idSender){ ?>
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
        <?php } ?>
    </div>

</section>
