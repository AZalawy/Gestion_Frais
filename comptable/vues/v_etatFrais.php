<h3>Fiche de frais du mois <?php echo $numMois . "-" . $numAnnee ?> : 
</h3>
<div class="encadre">
    <p>
        Etat : <?php echo $libEtat ?> depuis le <?php echo $dateModif ?> <br> Montant validé : <?php echo $montantValide ?>


    </p>
    <form action="index.php?uc=etatFrais&action=voirEtatFrais" method="post">
    <table class="listeLegere">
        <caption>Eléments forfaitisés </caption>
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $libelle = $unFraisForfait['libelle'];
                echo "<th>" . $libelle . "</th>";
            }
            ?>	
        </tr>
        <br/>
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite'];
                echo '<td class="qteForfait">' . $quantite . '</td>';
            }
            ?>
        </tr>
    </table>
    <table class="listeLegere">
        <caption>Descriptif des éléments hors forfait -<?php echo $nbJustificatifs ?> justificatifs reçus -
        </caption>
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th> 
            <th class='justificatif'>Justificatif</th>
            <th class='valider'>Valider</th>
        </tr>
<?php
foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
    $date = $unFraisHorsForfait['date'];
    $libelle = $unFraisHorsForfait['libelle'];
    $montant = $unFraisHorsForfait['montant'];
    ?>
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <td><a href=<?php echo $document;?>>Document</a></td>
                <td width="80"> 
                    <select size="1" name="situ" >
                        <?php echo choixValidation()?>
                    </select></td>
            </tr>
    <?php
}
?>
    </table>
    <input type="submit" name="validerFraisHorsForfait" value="Valider"/></form>
</div>
</div>














