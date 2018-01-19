<div id="contenu">
    <h2>Mes fiches de frais</h2>
    <h3>Mois ‡† s√©lectionner : </h3>
    <div class="corpsForm">
        <p>
        <form action="index.php?uc=etatFrais&action=recherche" method="post">

            <select id="lstVisiteur" name="lstVisiteur">
                <?php
                foreach ($lesVisiteurs as $unVisiteur) {
                    if ($unVisiteur['id'] == $_SESSION['visiteurASelectionner']) {
                        ?>
                        <option selected value="<?php echo $unVisiteur['id']; ?>"><?php echo $unVisiteur['nom'] . " " . $unVisiteur['prenom']; ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $unVisiteur['id']; ?>"><?php echo $unVisiteur['nom'] . " " . $unVisiteur['prenom']; ?> </option>
                        <?php
                    }
                }
                ?>
            </select>
            <input type="submit" name="validerVisiteur" value="Valider"/>
        </form>

        <form action="index.php?uc=etatFrais&action=voirEtatFrais" method="post">
            <select id="lstMois" name="lstMois">
                <?php
                foreach ($lesMois as $unMois) {
                    if ($unMois['mois'] == $_SESSION['moisASelectionner']) {
                        ?>
                        <option selected value="<?php echo $unMois['mois']; ?>"><?php echo $unMois['numMois'] . "/" . $unMois['numAnnee']; ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $unMois['mois']; ?>"><?php echo $unMois['numMois'] . "/" . $unMois['numAnnee']; ?> </option>
                        <?php
                    }
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
</form>
