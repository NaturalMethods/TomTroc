<link rel="stylesheet" type="text/css" href="./css/detailbook.css">

<article class="twocolumnscreen">

    <div class="bookimg">
        <img src="./img/books/<?= $book->getBookImg(); ?>" alt="<?= $book->getTitle(); ?>">
    </div>

    <div class="bookinfo flex-col">

        <div class="bookheader flex-col">
            <h1><?= $book->getTitle(); ?></h1>
            <span class="lightgreytext">par <?= $book->getAuthor(); ?></span>

            <svg class="line" width="28" height="1" viewBox="0 0 28 1" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.5 0.5L27.5 0.500002" stroke="#A6A6A6" stroke-linecap="round"/>
            </svg>
        </div>
        <span class="titledesc capitalblacktext">DESCRIPTION</span>
        <div class="bookdesc flex-col">
            <p><?= $book->getDescription(); ?></p>
        </div>

        <span class="titleowner capitalblacktext">PROPRIÉTAIRE</span>

        <a href="index.php?action=account&id=<?= $book->getIdOwner(); ?>">
            <div class="bookowner flex-row">

                <img class="ownerimg" src="./img/david-lezcano.png">
                <p class="ownerusername"><?= $book->getOwner(); ?></p>


            </div>
        </a>
        <form action="index.php" method="get">
            <button type="submit" name="action" value="books" class="msgbutton greenButton">Envoyer un message</button>
        </form>
    </div>


</article>