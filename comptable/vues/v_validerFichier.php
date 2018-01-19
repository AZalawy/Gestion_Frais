<div id="contenu">
    <h2>Mes fiches de frais</h2>
    <h3>Mois ‡† s√©lectionner : </h3>
    <div class="corpsForm">
        <p>
        <form action="index.php?uc=etatFrais&action=chercherVisiteur" method="post">
            <label class="titre">Choisir les visiteurs :</label>
            <select size="1" name="visiteur">
                <?php
                        foreach ($lesClients as $unClient){
                            echo'<option value="'. $unClient['id'] .'">' .$unClient['nom'].' '. $unClient['prenom']. '</option>';
                        }
                    ?>
            </select>
            
            <label class="titre">Choisir les dates :</label>
            <select size="4" name="date">
                <?php
                        foreach ($lesDates as $uneDate){
                            echo'<option value="'. $uneDate .'">' . $uneDate . '</option>';
                        }
                    ?>
            </select>
            <input type="submit" name="validerMois" value="Valider"/>

            </p>
    </div>
    <div class="piedForm">
        <p>
            <input type="submit" name="envoiPdf" target="_blank" value="Valider" size="20" />
            <input id="annuler" type="reset" value="Effacer" size="20" />
        </p>
    </div>
</div>
</form>
