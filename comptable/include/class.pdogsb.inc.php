<?php

/**
 * Classe d'accès aux données. 

 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe

 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */
class PdoGsb {

    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=gsbfrais_atetrel';
    //private static $serveur='mysql:host=localhost';
    //private static $bdd='dbname=gsb_atetrel';   		
    private static $user = 'root';
    private static $mdp = '';
    private static $monPdo;
    private static $monPdoGsb = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct() {
        PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
        PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
    }

    public function _destruct() {
        PdoGsb::$monPdo = null;
    }

    /* $pdo = new PDO($serveur.';'.$bdd, $user, $mdp);
      $pdo->query("SET CHARACTER SET utf8");

      set_time_limit(0);
      creationFichesFrais($pdo);
      creationFraisForfait($pdo);
      creationFraisHorsForfait($pdo);
      majFicheFrais($pdo); */

    /**
     * Fonction statique qui crée l'unique instance de la classe

     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();

     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb() {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;
    }

    /**
     * Retourne les informations d'un visiteur

     * @param $login 
     * @param $mdp
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
     */
    public function getInfosVisiteur($login, $mdp) {
        $req = "select Visiteur.id as id, Visiteur.nom as nom, Visiteur.prenom as prenom from Visiteur 
		where Visiteur.login='$login' and Visiteur.mdp='$mdp'";
        $rs = PdoGsb::$monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    public function getInfosVisiteurs() {
        $req = "select id, nom, prenom from Visiteur";
        $rs = PdoGsb::$monPdo->query($req);
        $lesVisiteurs = array();
        $laLigne = $res->fetch();
        while ($laLigne != null) {
            $idVisiteur = $laLigne['id'];
            $nom = $laLigne['nom'];
            $prenom = $laLigne['prenom'];
            $lesVisiteurs["$idVisiteur"] = array("nom" => "$nom", "prenom" => "$prenom", "id" => "$idVisiteur");
            $laLigne = $res->fetch();
        }
        return $lesVisiteurs;
    }

    /**
     * Retourne les informations d'un comptable

     * @param $login 
     * @param $mdp
     * @return l'id, le nom et le prÃ©nom sous la forme d'un tableau associatif 
     */
    public function getInfosComptable($login, $mdp) {
        $req = "select Comptable.id as id, Comptable.nom as nom, Comptable.prenom as prenom from Comptable 
		where Comptable.login='$login' and Comptable.mdp='$mdp'";
        $rs = PdoGsb::$monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
     * concernées par les deux arguments

     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois) {
        $req = "select * from LigneFraisHorsForfait where LigneFraisHorsForfait.idVisiteur ='$idVisiteur' 
		and LigneFraisHorsForfait.mois = '$mois' ";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    public function getLesFraisHorsForfaitTortaux($idVisiteur, $mois) {
        $req = "select SUM(montant) as totalFHF from LigneFraisHorsForfait where LigneFraisHorsForfait.idVisiteur ='$idVisiteur' 
		and LigneFraisHorsForfait.mois = '$mois' ";
        $res = PdoGsb::$monPdo->query($req);
        $uneLigne = $res->fetch();
        return $uneLigne;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return le nombre entier de justificatifs 
     */
    public function getNbjustificatifs($idVisiteur, $mois) {
        $req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
     * concernées par les deux arguments

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
     */
    public function getLesFraisForfait($idVisiteur, $mois) {
        $req = "select FraisForfait.id as idfrais, FraisForfait.libelle as libelle, FraisForfait.montant as montant,
		LigneFraisForfait.quantite as quantite, (montant*quantite) as total
                from LigneFraisForfait inner join FraisForfait 
		on FraisForfait.id = LigneFraisForfait.idfraisforfait
		where LigneFraisForfait.idVisiteur ='$idVisiteur' and LigneFraisForfait.mois='$mois' 
		order by LigneFraisForfait.idFraisForfait";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    public function getLesFraisForfaitTotaux($idVisiteur, $mois) {
        $req = "select SUM(FraisForfait.montant * LigneFraisForfait.quantite) as totalFF
                from LigneFraisForfait inner join FraisForfait 
                on FraisForfait.id = LigneFraisForfait.idfraisforfait
		where LigneFraisForfait.idVisiteur ='$idVisiteur' and LigneFraisForfait.mois='$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $uneLigne = $res->fetch();
        return $uneLigne;
        //$total = mysql_result($req, 0, 'total_operations');
        //$lesLignes = $res->fetchAll();
        //return $lesLignes;
    }

    /**
     * Retourne tous les id de la table FraisForfait

     * @return un tableau associatif 
     */
    public function getLesIdFrais() {
        $req = "select FraisForfait.id as idfrais from FraisForfait order by FraisForfait.id";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    /**
     * Met à jour la table ligneFraisForfait

     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
     * @return un tableau associatif 
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais) {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
            PdoGsb::$monPdo->exec($req);
        }
    }

    /**
     * met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs) {
        $req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return vrai ou faux 
     */
    public function estPremierFraisMois($idVisiteur, $mois) {
        $ok = false;
        $req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        if ($laLigne['nblignesfrais'] == 0) {
            $ok = true;
        }
        return $ok;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur

     * @param $idVisiteur 
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur) {
        $req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés

     * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
     * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois) {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
        PdoGsb::$monPdo->exec($req);
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $uneLigneIdFrais) {
            $unIdFrais = $uneLigneIdFrais['idfrais'];
            $req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
            PdoGsb::$monPdo->exec($req);
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @param $libelle : le libelle du frais
     * @param $date : la date du frais au format français jj//mm/aaaa
     * @param $montant : le montant
     */
    public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant) {
        $dateFr = dateFrancaisVersAnglais($date);
        $req = "insert into lignefraishorsforfait 
		values('','$idVisiteur','$mois','$libelle','$dateFr','$montant')";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument

     * @param $idFrais 
     */
    public function supprimerFraisHorsForfait($idFrais) {
        $req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Retourner les kilom�tres dont l'id, le cvFiscal et l'�nergie
     * @param $idFrais 
     */
    public function afficherKilometres() {
        $req = 'select id,chevalFiscal, energie from PuissanceFiscale';
        $res = PdoGsb::$monPdo->query($req);
        $cv = array();
        $laLigne = $res->fetch();
        $i = 0;
        while ($laLigne != null) {
            $cvFiscal = $laLigne['chevalFiscal'];
            $energie = $laLigne['energie'];
            $id = $laLigne['id'];
            $cv[$i] = array("chevalFiscal" => "$cvFiscal", "energie" => "$energie", "id" => "$id");
            $i++;
            $laLigne = $res->fetch();
        }
        return $cv;
    }

    /**
     * Retourne les visiteurs pour lesquels un des visiteurs qui ont les fiches de frais de chaque période

     * @param $idVisiteur 
     * @return un tableau associatif de clÃ© un id -aaaamm- et de valeurs nom et penom correspondant
     */
    public function getListeDesVisiteurs() {
        $req = "select visiteur.id as id , visiteur.nom as nom, visiteur.prenom as prenom from  visiteur order by visiteur.nom ";
        $res = PdoGsb::$monPdo->query($req);
        $lesVisiteurs = array();
        $laLigne = $res->fetch();
        while ($laLigne != null) {
            $idVisiteur = $laLigne['id'];
            $nom = $laLigne['nom'];
            $prenom = $laLigne['prenom'];
            $lesVisiteurs["$idVisiteur"] = array("nom" => "$nom", "prenom" => "$prenom", "id" => "$idVisiteur");
            $laLigne = $res->fetch();
        }
        return $lesVisiteurs;
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais

     * @param $idVisiteur 
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
     */
    /* public function getLesMoisDisponibles($idVisiteur) {
      $req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur'
      order by fichefrais.mois desc ";
      $res = PdoGsb::$monPdo->query($req);
      $lesMois = array();
      $laLigne = $res->fetch();
      while ($laLigne != null) {
      $mois = $laLigne['mois'];
      $numAnnee = substr($mois, 0, 4);
      $numMois = substr($mois, 4, 2);
      $lesMois["$mois"] = array(
      "mois" => "$mois",
      "numAnnee" => "$numAnnee",
      "numMois" => "$numMois"
      );
      $laLigne = $res->fetch();
      }
      return $lesMois;
      } */
    public function getLesMoisDisponibles($id) {
        $req = "select mois 
            from  fichefrais 
            where idVisiteur= '" . $id . "'
		order by fichefrais.mois desc ";
        $res = PdoGsb::$monPdo->query($req);
        $lesMois = array();
        $laLigne = $res->fetch();
        while ($laLigne != null) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois["$mois"] = array(
                "mois" => "$mois",
                "numAnnee" => "$numAnnee",
                "numMois" => "$numMois"
            );
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois) {
        $req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
			fichefrais.montantValide as montantValide, Etat.libelle as libEtat from  fichefrais inner join Etat on fichefrais.idEtat = Etat.id 
			where fichefrais.idVisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais

     * Modifie le champ idEtat et met la date de modif à aujourd'hui
     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat) {
        $req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    public function afficherLesInfosPuissanceFiscale() {
        $req = "Select id, chevalFiscal, energie From PuissanceFiscale";
        PdoGsb::$monPdo->query($req);
    }

    public function creerDocument($libelle, $mois, $idFrais) {
        $req = "INSERT INTO Document (id, libelle, date, idLigneFHF) values('','$libelle','$mois','$idFrais')";
        PdoGsb::$monPdo->exec($req);
    }

    public function insererSituationFHF($situation) {
        $req = "INSERT INTO LigneFraisHorsForfait (id, idVisiteur, mois, libelle, date, montant, justificatif, valider) values ('', '', '','', '', '', '', '$situation')";
        PdoGsb::$monPdo->exec($req);
    }

    public function nomPrenomVisiteur($idVisiteur) {
        $req = "select Visiteur.id as id, Visiteur.nom as nom, Visiteur.prenom as prenom from Visiteur 
		where Visiteur.id='$idVisiteur'";
        $rs = PdoGsb::$monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    public function totalFraisForfait($idVisiteur) {
        $req = "SELECT SUM(montant*quantite) AS TOTAL,id, idVisiteur
                FROM fraisforfait as FF, lignefraisforfait as LFF
                WHERE FF.id = LFF.idFraisforfait
                AND LFF.idVisiteur='$idVisiteur'
                Group by id;";
        $rs = PdoGsb::$monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    public function getLesInfosDocumentsFF($idVisiteur, $mois) {
        $req = "select fichefrais.idVisiteur, fichefrais.mois, lignefraishorsforfait.libelle, document.libelle as Document
                from  lignefraishorsforfait
                inner join fichefrais on lignefraishorsforfait.idVisiteur = fichefrais.idVisiteur
                inner join document on lignefraishorsforfait.id = document.idLigneFHF
                where fichefrais.idVisiteur = '$idVisiteur' AND fichefrais.mois = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne;
    }

    public function majSituationFHF($situation, $mois, $idVisiteur) {
        $req = "update lignefraishorsforfait set lignefraishorsforfait.valider = '$situation'
			where lignefraishorsforfait.mois = '$mois' and 
                            lignefraishorsforfait.idVisiteur = '$idVisiteur'";
        PdoGsb::$monPdo->exec($req);
    }

    public function getFicheFraisComptable($visiteur, $mois, $etat) {
        $req = "select idVisiteur, nom , mois, nbJustificatifs, montantValide, dateModif, libelle, e.id as idE
                from fichefrais as ff , etat as e , visiteur as v where e.id = ff.idEtat and ff.idVisiteur = v.id ";
        if ($visiteur != null) {
            foreach ($visiteur as $unVisiteur) {
                $req .= "and idVisiteur = '" . $unVisiteur . "' ";
            }
        }
        if ($mois != null) {
            foreach ($mois as $unMois) {
                $req .= "and mois = '" . $unMois . "' ";
            }
        }
        if ($etat != null) {
            foreach ($etat as $unEtat) {
                $req .= "and idEtat = '" . $unEtat . "' ";
            }
        }
        $res = PdoGsb::$monPdo->query($req);
        $fiche = array();
        $laLigne = $res->fetch();
        $i = 0;
        while ($laLigne != null) {
            $idVisiteur = $laLigne['idVisiteur'];
            $nom = $laLigne['nom'];
            $mois = $laLigne['mois'];
            $nbJustificatif = $laLigne['nbJustificatifs'];
            $montant = $laLigne['montantValide'];
            $etat = $laLigne['libelle'];
            $fiche[$i] = array("idVisiteur" => "$idVisiteur", "nom" => "$nom", "mois" => "$mois", "nbJustificatifs" => "$nbJustificatif", "montant" => "$montant", "etat" => "$etat");
            $laLigne = $res->fetch();
            $i++;
        }
        return fiche;
    }

    public function getVisiteur() {
        $req = "select id, nom from visiteur";
        $res = PdoGsb::$monPdo->exec($req);
        $tab = array();
        $ligne = $res->fetch();
        while ($ligne != null) {
            $id = $ligne['id'];
            $nom = $ligne['nom'];
            $tab[$id] = array("id" => "$id", "nom" => "$nom");
            $ligne = $res->fetch();
        }
        return tab;
    }

    public function getDate() {
        $req = "select mois from fichefrais group by mois";
        $res = PdoGsb::$monPdo->exec($req);
        $ligne = $res->fetch();
        $tab = array();
        while ($ligne != null) {
            $mois = $ligne['mois'];
            $tab[$mois] = $mois;
            $ligne = $res->fetch();
        }
        return tab;
    }

    public function getEtat() {
        $req = "select libelle, id from etat";
        $res = PdoGsb::$monPdo->exec($req);
        $ligne = $res->fetch();
        $tab = array();
        while ($ligne != null) {
            $libelle = $ligne['libelle'];
            $id = $libelle['id'];
            $tab[$id] = array("libelle" => "$libelle", "id" => $id);
            $ligne = $res->fetch();
        }
        return tab;
    }

    public function getLesFraisHorsForfaitDate($idVisiteur, $mois) {
        $req = "select LigneFraisHorsForfait.date as date 
                from LigneFraisHorsForfait where LigneFraisHorsForfait.idVisiteur ='$idVisiteur' 
		and LigneFraisHorsForfait.mois = '$mois' ";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

}

?>