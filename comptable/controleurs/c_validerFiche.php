<?php
include("vues/v_sommaire.php");
$action=$_REQUEST['action'];
switch ($action) {
    case 'recherche':{
        if(isset($_POST['ok']) && $_POST['ok'] == 'Submit'){
            $unVisiteur = $_POST['lstVisiteur'];
            $lesMois = $pdo->getLesMoisDisponibles($unVisiteur);
            $lesCles = array_keys($lesMois);
            include ("vues/v_validerFiche.php");
        }
       // $visiteur= $pdo->getInfosVisiteurs();
       // include ("vues/v_validerFiche.php");
        break;
    }
}
?>
