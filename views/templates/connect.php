<link rel="stylesheet" type="text/css" href="./css/register.css">

<section class="registersection twocolumnscreen">

    <div class="register flex-col">
        <div>
            <h1>Connexion</h1>
        </div>
        <div class="registerform flex-col">

            <div class="field flex-col">
                <label for="userMail" class="lightgrey12pxtext">Adresse email</label>
                <input class="mailfield registerfield" id="userMail" type="email" name="userMail" />
            </div>

            <div class="field flex-col">
                <label for="userPassword" class="lightgrey12pxtext">Mot de passe</label>
                <input class="passwordfield registerfield"  id="userPassword" type="password" name="userPassword" />
            </div>

            <form action="index.php" method="get">
                <button type="submit" name="action" value="register" class="registerButton greenButton">Se connecter</button>
            </form>

            <a href="index.php?action=register" data-text="Lien" class="link"><span class="link-text" data-text="Accueil">Pas de compte ? <span class="link-underline">Inscrivez-vous</span></span></a>

        </div>
    </div>
    <div class="registerimg">
        <img src=".\img\marialaura-gionfriddo.png" alt="books">
    </div>

</section>
