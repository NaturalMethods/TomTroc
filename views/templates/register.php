<link rel="stylesheet" type="text/css" href="<?=CSS?>/register.css">

<section class="registersection twocolumnscreen">

    <div class="register flex-col">
        <div>
            <h1>Inscription</h1>
        </div>
        <form class="registerform flex-col" method="POST" action="index.php?action=registerUserInfos" >

            <span class="redtext redbox" <?= $errorMessage['emptyFieldMessage'] ?>>Tous les champs sont obligatoires</span>
            <span class="redtext redbox" <?= $errorMessage['emailExistsMessage'] ?>>Cette adresse mail est déjà utilisée</span>
            <span class="redtext redbox" <?= $errorMessage['usernameExistsMessage'] ?>>Ce pseudo est déjà utilisée</span>

            <div class="field flex-col">
                <label for="username" class="lightgrey12pxtext">Pseudo</label>
                <input class="usernamefield registerfield" id="username" type="text" name="username" />
            </div>

            <div class="field flex-col">
                <label for="userMail" class="lightgrey12pxtext">Adresse email</label>
                <input class="mailfield registerfield" id="userMail" type="email" name="userMail" />
            </div>

            <div class="field flex-col">
                <label for="userPassword" class="lightgrey12pxtext">Mot de passe</label>
                <input class="passwordfield registerfield"  id="userPassword" type="password" name="userPassword" />
            </div>

            <div>
                <button type="submit" name="registerButton" class="registerButton greenButton">S'inscrire</button>
            </div>

            <a href="index.php?action=connect" data-text="Lien" class="link"><span class="link-text" data-text="Accueil">Déjà inscrit ? <span class="link-underline">Connectez-vous</span></span></a>

        </form>
    </div>
    <div class="registerimg">
        <img src="<?=IMG?>marialaura-gionfriddo.png" alt="books">
    </div>

</section>
