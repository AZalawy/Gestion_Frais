<div id="contenu">
    <h2>Mes fiches de frais</h2>
    <h3>Mois � s�lectionner : </h3>
    <form action="index.php?uc=etatFrais&action=" method="post">
        <div class="corpsForm">
                        <p>

                <label for="lstVisiteur" accesskey="n">Visiteur : </label>
                <select id="lstVisiteur" name="lstVisiteur" >
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $visiteur = $unVisiteur['id'];
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];

                        if ($visiteur == $visiteurASelectionner) {
                            ?>
                            <option selected value="<?php echo $visiteur ?>"><?php echo $nom . " " . $prenom ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>
            </p>
            
            <p>

                <label for="lstMois" accesskey="n">Mois : </label>
                <select id="lstMois" name="lstMois">
                    <?php
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                            ?>
                            <option selected value="<?php echo $mois ?>"><?php echo $numMois . "/" . $numAnnee ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>
                 
            </p>
        </div>
        <div class="piedForm">
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
            </p> 
        </div>

    </form>