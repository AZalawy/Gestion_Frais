<?php
include ('fpdf/fpdf.php');

$pdf = new FPDF();

$pdf->AddPage();
$pdf->SetFont('Times','B',16);
$pdf->Cell(40,10,'Fiche de frais du mois : '.$numMois .'-'.$numAnnee);
$pdf->Ln(10);
$pdf->SetFont('Times','',14);
$pdf->Cell(20,10,'Etat : '.utf8_decode($libEtat).' depuis le '.$dateModif);
$pdf->Ln(10);
$pdf->Cell(20,10,'Montant'.  utf8_decode(" validé").' : '.$montantValide);
$pdf->Ln(10);
$pdf->SetFont('Times','',14);
$pdf->Cell(40,10, utf8_decode('Eléments forfaitisés '));

$position=0;
	if($pdf->getY()>$position){
		$position=$pdf->getY();
	}
	$pdf->SetXY(10,$position+20);
       

foreach ($lesFraisForfait as $unFraisForfait) {
                
        $libelle = $unFraisForfait['libelle'];
        $pdf->SetFillColor(18,101,254);
        for($i=0;$i<count($libelle);$i++)
                
             $w=array(60,60,60);
             $pdf->Cell($w[$i],7,  utf8_decode($libelle),1,0,'C',true);  
             
             $pdf->SetLineWidth(.1);
}
$pdf->Ln();

foreach ($lesFraisForfait as $unFraisForfait) {        
        $quantite = $unFraisForfait['quantite'];
                //echo '<td class="qteForfait">' . $quantite . '</td>';
        for($i=0;$i<count($quantite);$i++){
                $pdf->Cell($w[$i],7,  $quantite,1,0,'C');                
        }

}
$pdf->Ln(10);
$pdf->SetFont('Times','',14);
$pdf->Cell(40,10, utf8_decode('Descriptif des éléments hors forfait ' .$nbJustificatifs. ' justificatifs reçus -'));
$pdf->Ln(10);

$titreHorsForfait =array("Date","Libellé","Montant");
$pdf->SetFillColor(18,101,254);
 foreach($titreHorsForfait as $col){
            $pdf->Cell(50,7,  utf8_decode($col),1,0,'C',TRUE);
 }
$pdf->Ln();

foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = $unFraisHorsForfait['libelle'];
            $montant = $unFraisHorsForfait['montant'];
            $horsForfait = $date.$libelle.$montant;
            
            for ($hf = 0; $hf< count($horsForfait); $hf++){
                $w=array(50,50,50);
                $pdf->Cell($w[$hf],7, $date,1,0,'C');
                $pdf->Cell($w[$hf],7, $libelle,1,0,'C');
                $pdf->Cell($w[$hf],7, $montant,1,0,'C');
 
            }
        }

ob_clean();
$pdf->Output();

?>