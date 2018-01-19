<div id="contenu">
    <h2>Renseigner ma fiche de frais du mois <?php echo $numMois . "-" . $numAnnee ?></h2>

    <form method="POST"  action="index.php?uc=gererFrais&action=validerMajFraisForfait">
        <div class="corpsForm">

            <fieldset>
                <legend>Eléments forfaitisés
                </legend>
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = $unFrais['libelle'];
                    $quantite = $unFrais['quantite'];
                    ?>
                    <p>
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>" >
                    </p>

                    <?php
                }
                ?>
                <p><label>Kilometre</label><input type="text" name="kms" size="10" maxlength="5" value="0"/>
                    <select name="cheval" size="1"><?php ?>
                    <?php foreach ($infoKm as $info) {
                         echo'<option value="'.$info['id'].'">'. $info['chevalFiscal'] .' '. $info['energie'].'</option>';
                    } ?>
                    </select>

            </fieldset>
        </div>
        <div class="piedForm">
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
            </p> 
        </div>

    </form>
