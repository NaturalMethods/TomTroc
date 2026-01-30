
<link rel="stylesheet" type="text/css" href="./css/books.css">

<section class="books flex-col">

    <div class="booksheader flex-row">

        <div class="bookstitleheader">
            <h1>Nos livres à l'échange</h1>
        </div>
        <div class="bookssearchwrapper">
            <label for="bookssearchbar" hidden></label>
            <form method="get" action="index.php">
                <input type="hidden" name="action" value="books">
                <input id="bookssearchbar" class="bookssearchbar lightgrey14pxtext" type="search" name="search" placeholder="Rechercher un livre" />
            </form>
        </div>

    </div>
    <div class="bookscards">

        <?php foreach($books as $book) { ?>
            <a href="index.php?action=detailbook&id=<?= $book->getIdBook();?>">
            <article class="bookcard">

                    <img class="bookcardimg" src="./img/books/<?= $book->getBookImg(); ?>" alt="Book 1">
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


