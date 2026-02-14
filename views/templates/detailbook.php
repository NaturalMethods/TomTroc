<link rel="stylesheet" type="text/css" href="<?=CSS?>/detailbook.css">

<article class="dividescreen">

    <div class="bookimg">
        <img src="<?= BOOKS_IMAGES.$book->getBookImg(); ?>" alt="<?= $book->getTitle(); ?>">
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

                <img class="userroundedimg" src="<?= USERS_IMAGES.$userPic ?>" alt="user image">
                <p class="ownerusername"><?= $book->getOwner(); ?></p>


            </div>
        </a>
        <form action="index.php" method="get">
            <input type="hidden" name="ownerID" value="<?= $book->getIdOwner(); ?>">
            <button type="submit" name="action" value="addContact" class="msgbutton greenButton">Envoyer un message</button>
        </form>
    </div>


</article>