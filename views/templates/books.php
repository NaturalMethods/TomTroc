
<link rel="stylesheet" type="text/css" href="<?=CSS?>/books.css">

<section class="books flex-col">

    <div class="booksheader flex-row">

        <div class="bookstitleheader">
            <h1>Nos livres à l'échange</h1>
        </div>
        <div class="bookssearchwrapper">
            <label for="booksSearchBar" class="sr-only">Seach Bar</label>
            <form title="searchBar"  id="searchBar" method="get" action="index.php">
                <input id="hiddenBooks" type="hidden" name="action" value="books">
                <input id="booksSearchBar" class="bookssearchbar lightgrey14pxtext" type="search" name="search" placeholder="Rechercher un livre">
            </form>
        </div>

    </div>
    <div class="bookscards">

        <?php foreach($books as $book) { ?>
            <a href="index.php?action=detailbook&id=<?= $book->getIdBook();?>">
            <article class="bookcard">

                    <img class="bookcardimg" src="<?= BOOKS_IMAGES.$book->getBookImg(); ?>" alt="<?= $book->getTitle(); ?>">
                    <div class="bookcarddesc">
                        <p><?= $book->getTitle(); ?></p>
                        <p><?= $book->getAuthor(); ?></p>
                        <p>Vendu par : <?= $book->getOwner(); ?></p>
                    </div>

            </article>
            </a>
        <?php } ?>

    </div>
</section>


