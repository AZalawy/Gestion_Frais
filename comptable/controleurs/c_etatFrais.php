<?php

include("vues/v_sommaire.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
switch ($action) {

    case 'recherche': {
            $lesVisiteurs = $pdo->getListeDesVisiteurs();
            if (isset($_POST['lstVisiteur']) && !empty($_POST['lstVisiteur'])) {
                $lesMois = $pdo->getLesMoisDisponibles($_POST['lstVisiteur']);
                $_SESSION['visiteurASelectionner'] = $_POST['lstVisiteur'];
                include ("vues/v_validerFiche.php");
            }
            break;
        }

    case 'voirEtatFrais': {
            $_SESSION['moisASelectionner'] = $_REQUEST['lstMois'];
            $lesMois = $pdo->getLesMoisDisponibles($_SESSION['visiteurASelectionner']);
            include("vues/v_validerFiche.php");
            $lesVisiteurs = $pdo->nomPrenomVisiteur($_SESSION['visiteurASelectionner']);
            $nom = $lesVisiteurs['nom'];
            $prenom = $lesVisiteurs['prenom'];
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_SESSION['visiteurASelectionner'], $_SESSION['moisASelectionner']);
            $lesFraisForfait = $pdo->getLesFraisForfait($_SESSION['visiteurASelectionner'], $_SESSION['moisASelectionner']);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($_SESSION['visiteurASelectionner'], $_SESSION['moisASelectionner']);
            $numAnnee = substr($_SESSION['moisASelectionner'], 0, 4);
            $numMois = substr($_SESSION['moisASelectionner'], 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $totauxFraisForfait = $pdo->getLesFraisForfaitTotaux($_SESSION['visiteurASelectionner'], $_SESSION['moisASelectionner']);
            $totauxFraisHorsForfait = $pdo->getLesFraisHorsForfaitTortaux($_SESSION['visiteurASelectionner'], $_SESSION['moisASelectionner']);
            $lesDocuments = $pdo->getLesInfosDocumentsFF($_SESSION['visiteurASelectionner'], $_SESSION['moisASelectionner']);
            $document = $lesDocuments['Document'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);

            if (isset($_POST['validerMois']) && $_POST['validerMois'] == 'Valider') {
                include("vues/v_etatFrais.php");
            } else if (isset($_POST['envoiPdf']) && $_POST['envoiPdf'] == 'Valider') {
                include("vues/v_ficheFraisPDF.php");
            }

            if ((isset($_POST['situ']) && !empty($_POST['situ']))) {
                $lesFraisHorsForfaitDate = $pdo->getLesFraisHorsForfaitDate($_SESSION['visiteurASelectionner'], $_SESSION['moisASelectionner']);
                //$uneDate = $lesFraisHorsForfaitDate['date'];
                //$date = $lesFraisHorsForfait['date'];
                $pdo->majSituationFHF($_POST['situ'], $_SESSION['moisASelectionner'], $_SESSION['visiteurASelectionner']);
            }
            break;
        }

    case 'validerFiche': {
            //$unVisiteur= $pdo->getInfosVisiteurs();
            $lesVisiteurs = $pdo->getListeDesVisiteurs();
            include 'vues/v_validerFiche.php';
            break;
        }

    case 'enregistrerValidation' : {
            /* if((isset($_POST['situ']) && !empty($_POST['situ']))){
              $unVisiteur = $pdo->getListeDesVisiteurs();
              $date = $lesFraisHorsForfait['date'];
              $pdo->majSituationFHF($_POST['situ'],$date, $unVisiteur);
              } */
        }

    case 'chercherVisiteur': {


            $lesClients = $pdo->getListeDesVisiteurs();
            
            if (isset($_POST['visiteur']) && !empty($_POST['visiteur'])) {
                $lesDates = $pdo->getLesMoisDisponibles($_POST['visiteur']);
                              
            }
            include 'vues/v_validerFichier.php';  
            


              /*  $visiteur = null;
                $date = null;
                $etat = null;
                if (isset($_POST['visiteur'])) {
                    $visiteur = $_POST['visiteur'];
                }
                if (isset($_POST['date'])) {
                    $date = $_POST['date'];
                }
                if (isset($_POST['etat'])) {
                    $etat = $_POST['etat'];
                }

                $lesFiches = $pdo->getFicheFraisComptable($visiteur, $date, $etat);

                $lesDates = $pdo->getDate();
                $lesEtats = $pdo->getEtat();*/


                break;
            }
        }
?>