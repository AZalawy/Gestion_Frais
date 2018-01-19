<?php

header('Content-type: application/pdf');
include ('fpdf/fpdf.php');

$pdf = new FPDF();

$pdf->AddPage();
$titre = "REMBOURSEMENT DE FRAIS ENGAGES";
$pdf->SetFont('Helvetica', 'B', 18);
$w = $pdf->GetStringWidth($titre) + 4;
$pdf->SetX((150 - $w) / 2);
$pdf->SetTextColor(47, 22, 188);
$pdf->Cell(30);
$pdf->SetLineWidth(1);
$pdf->Cell($w, 15, $titre, 1, 1);
$pdf->Ln(15);
$pdf->SetFont('Helvetica', '', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(40, 10, 'Visiteur : ' . $_SESSION['visiteurASelectionner']);
$pdf->Ln(10);
$pdf->SetFont('Helvetica', '', 16);
$pdf->Cell(40, 10, 'Nom : ' . $prenom . ' ' . $nom);
$pdf->Ln(10);
$pdf->SetFont('Helvetica', '', 16);
$pdf->Cell(40, 10, 'Mois : ' . $numMois . ' - ' . $numAnnee);
$pdf->Ln(10);

$position = 0;
if ($pdf->getY() > $position) {
    $position = $pdf->getY();
}
$pdf->SetXY(10, $position + 10);
$pdf->Ln(10);
/////////////////////////////////////////////////////////////
//
//  Frais forfaitaires
//
////////////////////////////////////////////////////////////
$pdf->SetFont('Times', 'I', 14);
$titreFraisForfait = array("Frais forfaitaires", "Quantité", "Montant unitaire", "Total");
foreach ($titreFraisForfait as $col) {
    $pdf->SetTextColor(95, 151, 254);
    $pdf->SetLineWidth(0);
    $pdf->Cell(45, 7, $col, 0, 0, 'C');
}
$pdf->Ln();

foreach ($lesFraisForfait as $unFraisForfait) {
    $libelle = $unFraisForfait['libelle'];
    $quantite = $unFraisForfait['quantite'];
    $montant = $unFraisForfait['montant'];
    $total = $unFraisForfait['total'];

    $fraisforfait = $libelle . $quantite . $montant . $total;

    for ($hf = 0; $hf < count($fraisforfait); $hf++) {
        $pdf->SetFont('Times', '');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(37, 73, 241);
        $pdf->SetFillColor(255, 255, 255);
        $w = array(45, 45, 45, 45);
        $pdf->SetLineWidth(0);
        $pdf->Cell($w[$hf], 7, utf8_decode($libelle), 1, 0, 'C', TRUE);
        $pdf->Cell($w[$hf], 7, $quantite, 1, 0, 'C', TRUE);
        $pdf->Cell($w[$hf], 7, $montant, 1, 0, 'C', TRUE);
        $pdf->Cell($w[$hf], 7, $total, 1, 0, 'C', TRUE);
        $pdf->Ln();   
    }
}
$pdf->Ln(10);


/////////////////////////////////////////////////////////////
//
//  Autre frais forfait
//
////////////////////////////////////////////////////////////
$pdf->Cell(80);
$pdf->SetFont('Times', 'I', 14);
$pdf->SetTextColor(37, 73, 241);
$pdf->Cell(40, 10, 'Autres frais');
$pdf->Ln(10);

$titreHorsForfait = array("Date", "Libellé", "Montant");
$w = array(50, 100, 50);
foreach ($titreHorsForfait as $col) {
    $pdf->SetTextColor(95, 151, 254);
    $pdf->SetLineWidth(0);
    $pdf->Cell(60, 7, $col, 0, 0, 'C');
}
$pdf->Ln();

foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
    $date = $unFraisHorsForfait['date'];
    $libelle = $unFraisHorsForfait['libelle'];
    $montant = $unFraisHorsForfait['montant'];
    $horsForfait = $date . $libelle . $montant;


    for ($hf = 0; $hf < count($horsForfait); $hf++) {
        $pdf->SetFont('Times', '');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(37, 73, 241);
        $pdf->SetFillColor(255, 255, 255);
        $w = array(60, 60, 60);
        $pdf->SetLineWidth(0);
        $pdf->Cell($w[$hf], 8, $date, 1, 0, 'C', TRUE);
        $pdf->Cell($w[$hf], 8, $libelle, 1, 0, 'C', TRUE);
        $pdf->Cell($w[$hf], 8, $montant, 1, 0, 'C', TRUE);
        $pdf->Ln();
    }
}
$pdf->Ln(10);

$pdf->Cell(80);
$dateTotal = "Total " . $unMois['numMois'] . "/" . $unMois['numAnnee'];
$montantLesFraisForfait = $totauxFraisForfait['totalFF'];
$montantLesFraisHorsForfait = $totauxFraisHorsForfait['totalFHF'];
$totalFraisEngages = $montantLesFraisForfait + $montantLesFraisHorsForfait ;
$piedPage = array($dateTotal, $totalFraisEngages);
foreach ($piedPage as $col) {
    $pdf->SetTextColor(37, 73, 241);
    $pdf->SetLineWidth(0);
    $pdf->Cell(50, 8, $col, 1, 0, 'C',TRUE);
}
$pdf->Ln(20);

$pdf->Cell(80);
$pdf->SetFont('Times', 'I', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(40, 10, 'Fait à Paris, le ');
$pdf->Ln(5);
$pdf->Cell(80);
$pdf->SetFont('Times', 'I', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(40, 10, 'Vu l\'agent comptable');
$pdf->Ln(5);


ob_clean();
$pdf->Output();
?>