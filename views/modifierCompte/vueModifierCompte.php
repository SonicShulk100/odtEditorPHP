<div class="container">
    <nav>
        <?php
            //Importation du header.
            require_once "views/haut.php";
        ?>
    </nav>
    <section>
        <h2>Modification du compte</h2>
        <form action="../../index.php?action=enregistrerModificationCompte" method="post">
            <label for="newNom">Insérez votre nouveau nom : </label>
            <input type="text" name="newNom" id="newNom" required>
            <br>
            <label for="newPrenom">Insérez votre nouveau Prénom</label>
            <input type="text" name="newPrenom" id="newPrenom" required/>
            <br>
            <label for="newMail">Insérez votre nouveau e-mail : </label>
            <input type="text" name="newMail" id="newMail" required/>
            <br>
            <label for="newPass">Insérez votre nouveau MDP : </label>
            <input type="password" name="newPass" id="newPass" required/>
            <br>
            <button type="submit" class="button" name="validation" value="true">Enregistrer les modifications</button>
            <button type="submit" class="button" name="annulation" value="true">Annuler</button>
            <script>
                //Initialisation de l'avertissement.
                document.querySelectorAll("button[type='submit']").forEach(button =>{

                    /*
                    Pour chaque bouton, on ajoute un évènement qui affiche le fait qu'on doit être sûr que l'utilisateur
                    A bien l'intention de modifier ou annuler.
                     */
                   button.addEventListener("click", function(e){
                       const confirmation = confirm("Êtes-vous sûr de vouloir continuer ?");

                       //Validé ?
                       if(!confirmation && this.name==="validation"){
                           e.preventDefault();
                       }
                   });
                });
            </script>
        </form>
    </section>
    <?php
        //Importation du footer.
        require_once "views/bas.php";
    ?>
</div>
