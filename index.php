<?php
 /* On définit une fonction de chargement des 
* classes qui va prendre le nom de la classe
* changer les \ en / puis faire un require
* de ce nom de classe (le nom de classe contient
* le namespace, donc normalement c'est bon)
  */
function myLoader($className){
    $class = str_replace('\\', '/', $className);
    require($class . '.php');
}

//On donne à la fonction d'autochargement notre
//fonction de chargement de classe myLoader.
//Cette fonction se déclenchera dès qu'elle
//rencontrera un use ou une classe pour la require
spl_autoload_register('myLoader');

//Lorsqu'on utilise une classe d'un autre namespace
//il faut indiquer qu'on l'utilise avec le
//mot clef use
use entities\SmallDoggo;
var_dump(new SmallDoggo('test', 'test', new DateTime(), false));

// mysql://localhost:3306/first_db => ça c'est pas important

/* Le try-catch permet d'exécuter un bloc de code en surveillant
 * une levée d'erreur spécifique indiquée dans le catch. Si jamais
 * le bloc  try déclenche une erreur du type indiquée, le code
 * ne plantera pas mais l'execution du bloc try s'arretera pour passer 
 * à l'interieur du bloc catch
 */

try{
    
// Lien pour acceder au base de donnees avec le php
// 'root' et les guillements à coté c'est le nom et mot de pass par default
// On cree une nouvelle instance de PDO en lui fournissant le domaine ou se 
// trouve notre bdd mysql, on lui indique le nom de la bdd a laquelle on se 
// connecte avec dbname puis on lui donne le username et le password 
// en deuxieme et troisieme argument

$db = new PDO('mysql:host=localhost;dbname=first_db', 'selma', 'ppp');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// on utilise la méthode query de notre db PDO qui attend en argument une 
// requette SQL classique.
// ici on séléctionne tous les small_doggo.
$query = $db->query('SELECT * FROM small_doggo');

// on lui dit ensuite d'executer la requette
//$query->execute();

// on affiche le nom de notre premier chien de la table small_doggo avec fetch
// on utilise le fetch() pour positionner le cursor sur la ligne de 
// résultat suivant



// $query->fetchAll() renvoie tous les resultats en tableau foreach($query 
// as $value);  {} fonctionne 
//$query->fetchAll();
//foreach($query as $value){
//echo $value;



// on le fait à l'interieur d'une boucle while afin de recuperer tous les 
// resultats de notre requete
$doggos = [];
while($ligne = $query->fetch()){
    
    // creer des instances de chien a partir des lignes
    
    $doggo = new SmallDoggo($ligne['name'], 
                            $ligne['race'], 
                            new DateTime($ligne['birthdate']),
                            $ligne['is_good'], 
                            $ligne['id']);
        $doggos[]= $doggo;
}
echo '<pre>';
var_dump($doggos);
echo '</pre>';


$id = 1;
// prepare a la place de query, c'est la meme chose mais ça s'execute pas toute 
// suite
// :id c'est une placeholder
$queryId = $db->prepare('SELECT * FROM small_doggo WHERE id = :id');
$queryId->bindParam('id', $id, PDO::PARAM_INT);
$queryId->execute();
    
    if($queryId->rowCount() == 1){
        $ligneid = $queryId->fetch();
        $doggo = new SmallDoggo($ligneid['name'], 
                            $ligneid['race'], 
                            new DateTime($ligneid['birthdate']),
                            $ligneid['is_good'], 
                            $ligneid['id']);
        var_dump($doggo);
    }
    
    
    $name = 'bobi';
    $race = 'berge-allemand';
    $birthdate = '2006-12-04';
    $isGood = true;
    
// on prepare notre requete avec ses parametres en placeholder   
$queryInsert = $db->prepare('INSERT INTO small_doggo '
                           . '(name, race, birthdate, is_good) ' 
                           . 'VALUES (:name,:race,:birthdate,:isGood)');

// on assigne les parametres 
$queryInsert->bindValue('name', $name, PDO::PARAM_STR);
$queryInsert->bindValue('race', $race, PDO::PARAM_STR);
$queryInsert->bindValue('birthdate', $birthdate, PDO::PARAM_STR);
$queryInsert->bindValue('isGood', $isGood, PDO::PARAM_BOOL);

// on execute la requete 
$queryInsert->execute();

// on recupere l'id de la ligne qui vient d'etre ajoutee
echo $db->lastInsertId();

} catch(PDOException $exeption){
    echo $exeption->getMessage();
}