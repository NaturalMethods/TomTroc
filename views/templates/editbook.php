<link rel="stylesheet" type="text/css" href="<?=CSS?>/editbook.css">

<section class="editbookcontainer">

    <article class="editbook flex-col">

        <h4>Modifier les informations</h4>

        <div class="editbookformcontainer flex-row">

            <div class="editbookleftcol flex-col">

                <span class="lightgrey14pxtext">Photo</span>
                <div class="image-box">
                    <img class="editbookimg" src="<?= BOOKS_IMAGES.$book->getBookImg(); ?>"
                         alt=<?= $book->getTitle(); ?>>
                </div>
                <span class="black12pxtext underline textalignright">Modifier la photo</span>
            </div>

            <div class="editbookrightcol flex-col">

                <form class="centercol form flex-col" action="index.php" method="post">

                    <input  name="bookId" value="<?= $book->getIdBook(); ?>" type="hidden">
                    <div class="field flex-col">
                        <label for="bookTitle" class="lightgrey12pxtext">Titre</label>
                        <input class=" formfield" id="bookTitle" type="text" name="bookTitle" value="<?= $book->getTitle(); ?>"/>
                    </div>

                    <div class="field flex-col">
                        <label for="bookAuthor" class="lightgrey12pxtext">Auteur</label>
                        <input class="formfield" id="bookAuthor" type="text" name="bookAuthor" value="<?= $book->getAuthor(); ?>"/>
                    </div>

                    <div class="field flex-col">
                        <label for="bookCommentary" class="lightgrey12pxtext">Commentaire</label>
                        <textarea class="commentaryarea text" id="bookCommentary"
                                  name="bookCommentary" placeholder="Ecriver votre commentaire ici"
                                  rows="20" maxlength="1200" cols="60"><?= $book->getDescription(); ?></textarea>
                    </div>

                    <div class="field flex-col">
                        <label for="bookDisponibility" class="lightgrey12pxtext">Disponibilité</label>
                        <select class="commentaryarea text" id="bookDisponibility" name="bookDisponibility">
                            <option value=1 <?php if($book->getDisponibility()) echo "selected"; ?>>disponible</option>
                            <option value=0 <?php if(!$book->getDisponibility()) echo "selected"; ?>>indisponible</option>
                        </select>
                    </div>

                    <div class="validatebutton flex-col flexstart">
                        <button type="submit" name="action" value="changeBookInfos" class="greenButton">
                            Valider
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </article>


</section>
