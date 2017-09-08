<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace data;

use DateTime;
use entities\SmallDoggo;
use PDO;


class DataDoggo {
   private $db;
   
public function __construct() {
       $this->db = new PDO('mysql:host=localhost;dbname=first_db', 
               'selma', 
               'ppp');
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
/* Méthode qui récupère tous les SmallDoggo en SQL et les
 * renvoie sous forme d'un tableau d'objet chien
 * @return array Une liste de SmallDoggo */
public function getAllDoggo(){
    
//On exécute la requête de sélection
	$query = $this->db->query('SELECT * FROM small_doggo');
        
//On crée un tableau vide qui accueillera nos chiens
	$chiens = [];
        
//On boucle sur les résultats
    while ($ligne = $query->fetch()) {
//On crée une instance de chien avec les valeurs
//de chaque ligne de résultats
	$chien = new SmallDoggo($ligne['name'], $ligne['race'], new DateTime($ligne['birthdate']), $ligne['is_good'], $ligne['id']);
//On ajoute l'instance en question au tableau
	$chiens[] = $chien;
	        }
//On renvoie le tableau	        
    return $chiens;
	}

        
/* Méthode qui récupère un SmallDoggo en SQL via son id
	     * @param int $id l'id du chien à récupèrer
	     * @return SmallDoggo Le chien correspondant à l'id fourni
	     */
public function getDoggoById(int $id): SmallDoggo {
//On prépare la requête de sélection par id avec un
//placeholder pour la valeur de l'id
	$queryId = $this->db->prepare('SELECT * FROM small_doggo WHERE id=:id');
//On assigne la valeur de l'id avec le paramètre
//attendu par la fonction
	$queryId->bindValue('id', $id, PDO::PARAM_INT);
//On exécute la requête
	$queryId->execute();
        
//Si on a bien récupérée une seule ligne
if ($queryId->rowCount() == 1) {
    
//On fetch la ligne en question
	$ligneid = $queryId->fetch();
        
//On crée une instance de chien à partir de cette ligne
	$chien = new SmallDoggo($ligneid['name'], $ligneid['race'], new DateTime($ligneid['birthdate']), $ligneid['is_good'], $ligneid['id']);

        //On retourne l'instance de chien en question
	return $chien;
    }
}

public function addDoggo(SmallDoggo $doge):bool{
// preparation de la requete 
    $queryInsert = $this->db->prepare('INSERT INTO small_doggo '
                           . '(name, race, birthdate, is_good) ' 
                           . 'VALUES (:name,:race,:birthdate,:isGood)');

// on assigne les parametres 
$queryInsert->bindValue('name', $doge->getName(), PDO::PARAM_STR);
$queryInsert->bindValue('race', $doge->getRace(), PDO::PARAM_STR);
$queryInsert->bindValue('birthdate', $doge->getBirthdate()->format('y-m-d'), PDO::PARAM_STR);
$queryInsert->bindValue('isGood', $doge->getIsGood(), PDO::PARAM_BOOL);

// on execute la requete 
if($queryInsert->execute()){
    // on recupere la ligne qui vient d'etre ajoutée;
    $doge->setId(parseInt($this->db->lastInsertId()));
    return true;
    
        }
    // on renvoie false si jamais probleme quelconque 
    return false;
    }
    
    
public function updateDoggo(SmallDoggo $doge):bool{
     $queryInsert = $this->db->prepare('UPDATE small_doggo '
                           . 'SET name=:name, race=:race, birthdate=:birthdate, '
                           . 'is_good) ' 
                           . 'VALUES (:name,:race,:birthdate,:isGood)');

// on assigne les parametres 
$queryInsert->bindValue('name', $doge->getName(), PDO::PARAM_STR);
$queryInsert->bindValue('race', $doge->getRace(), PDO::PARAM_STR);
$queryInsert->bindValue('birthdate', $doge->getBirthdate()->format('y-m-d'), PDO::PARAM_STR);
$queryInsert->bindValue('isGood', $doge->getIsGood(), PDO::PARAM_BOOL);

// on execute la requete 
if($queryInsert->execute()){
    
    return true;
    
    // on renvoie false si jamais probleme quelconque 
    return false;
    }
    }
}  
}


public function deleteDoggo(SmallDoggo $doge):bool{
    $queryDel = $this->db->prepare('DELETE * FROM small_doggo Where id=:id');
    $queryDel->bindValue('id', $doge->getId(), PDO::PARAM_INT);
    return $queryDel->execute();
}