<link rel="stylesheet" type="text/css" href="./css/register.css">

<section class="registersection twocolumnscreen">

    <form class="register flex-col" method="POST" action="index.php?action=connectUser">
        <div>
            <h1>Connexion</h1>
        </div>
        <div class="registerform flex-col">

            <span class="redtext redbox" <?= $errorMessage ?>>Les identifiants n'ont pas permis de vous identifier</span>

            <div class="field flex-col">
                <label for="userMail" class="lightgrey12pxtext">Adresse email</label>
                <input class="mailfield registerfield" id="userMail" type="email" name="userMail" required/>
            </div>

            <div class="field flex-col">
                <label for="userPassword" class="lightgrey12pxtext">Mot de passe</label>
                <input class="passwordfield registerfield"  id="userPassword" type="password" name="userPassword" required/>
            </div>

            <div>
                <button type="submit" name="connectButton" value="register" class="registerButton greenButton">Se connecter</button>
            </div>

            <a href="index.php?action=register" data-text="Lien" class="link"><span class="link-text" data-text="Accueil">Pas de compte ? <span class="link-underline">Inscrivez-vous</span></span></a>

        </div>
    </form>
    <div class="registerimg">
        <img src=".\img\marialaura-gionfriddo.png" alt="books">
    </div>

</section>
