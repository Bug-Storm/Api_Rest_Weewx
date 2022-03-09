<?php

// On inclut les fichiers de configuration et d'accès aux données

// On inclut les fichiers de configuration et d'accès aux données
include_once './Database.php';
include_once './weewx.php';

// On instancie la base de données
$database = new Database();
$db = $database->getConnection();

// On instancie les produits
$produit = new Weewx($db);

$produit->table();

//Bienvenue    
echo "---------------------------------------------------------------------------------------------------------------------------- \n";
echo "Bienvenue, ce script vous permet de creer un user avec un Id et une Api key & Signature  \n";
echo "---------------------------------------------------------------------------------------------------------------------------- \n";
echo "Veuillez taper 'enter'";

//On va lancer la procedure pour creer un nouveau user via la fenetre de commande 

$handle = fopen("php://stdin", "r");
$line = fgets($handle);
//Creation d'un Nom pour l'user 
if (trim($line) == '') {

  //GET NAME//
  echo "set name: ";
  $name = fread(STDIN, 20);
  //Remove space 
  $name = preg_replace('/\s+/', '', $name);


  //GET NAME_Station//
  echo "set Station: ";
  $station = fread(STDIN, 20);
  //Remove space 
  $station = preg_replace('/\s+/', '', $station);

  //GET LAT//
  echo "set Latitude: ";
  $latitude = fread(STDIN, 50);
  //Remove space 
  $latitude = preg_replace('/\s+/', '', $latitude);
  //GET LONG//
  echo "set Longitude: ";
  $longitude = fread(STDIN, 50);
  //Remove space 
  $longitude = preg_replace('/\s+/', '', $longitude);

  //Creation d'un ID random
  $id = rand(10, 20);

  // Creation d'une key random  
  $bytes1 = random_bytes(5);
  $apisignature = (bin2hex($bytes1));

  // Creation d'une key random  
  $bytes = random_bytes(5);
  $apikey = (bin2hex($bytes));

   //TIMEZONE
   $gmdate= gmdate('e');
   $date= date(' O');
   $time_zone = $gmdate . $date;

  echo "\n\n\n";


  // Methode de DAVIS pour l'api V2 
  $parameters = array(
    "station-id" => $id,
    "api-key" => $apikey,
    "api-secret" => $apiSignature,
    "t" => time()
  );

  /*
      Now we will compute the API Signature.
      The signature process uses HMAC SHA-256 hashing and we will
      use the API Secret as the hash secret key. That means that
      right before we calculate the API Signature we will need to
      remove the API Secret from the list of parameters given to
      the hashing algorithm.
      */

  /*
      First we need to sort the paramters in ASCII order by the key.
      The parameter names are all in US English so basic ASCII sorting is
      safe.
      */
  ksort($parameters);

  /*
      Let's take a moment to print out all parameters for debugging
      and educational purposes.
      */
  foreach ($parameters as $key => $value) {
    echo "Parameter name: \"$key\" has value \"$value\"\n";
  }

  /*
      Save and remove the API Secret from the set of parameters.
      */
  $apiSecret = $parameters["api-secret"];
  unset($parameters["api-secret"]);

  /*
      Iterate over the remaining sorted parameters and concatenate
      the parameter names and values into a single string.
      */
  $data = "";
  foreach ($parameters as $key => $value) {
    $data = $data . $key . $value;
  }

  /*
      Let's print out the data we are going to hash.
      */
  echo "Data string to hash is: \"$data\"\n";

  /*
      Calculate the HMAC SHA-256 hash that will be used as the API Signature.
      */
  $apiSignature = hash_hmac("sha256", $data, $apiSecret);

  /*
      Let's see what the final API Signature looks like.
      */
  echo "API Signature is: \"$apiSignature\"\n";

  /*
      Now that the API Signature is calculated let's see what the final
      v2 API URL would look like for this scenario.
      */
} else {
  //Message d'erreur

  echo "\n";
  echo "Veuillez recommencer :/ \n";
}
//Echo avec les données  
echo "-------------------------------------------------------------------------------------------- \n";

echo "User: " . $name . "\n";
echo "id: " . $id . "\n";
echo "ApiKey: " . $apikey . "\n";
echo "ApiSignature: " . $apisignature . "\n";
echo "Station : " . $station . "\n";
echo "Latitude: " . $latitude . "\n";
echo "Longitude: " . $longitude . "\n";
echo "time_zone:" . $time_zone . "\n";
echo $created = date('Y-m-d H:i:s') . "\n";
echo "-------------------------------------------------------------------------------------------- \n";



if (!empty($name) && !empty($id) && !empty($apikey) && !empty($apisignature)  && !empty($created) && !empty($latitude) && !empty($longitude) && !empty($station) && !empty($time_zone)) {
  // Ici on a reçu les données
  // On hydrate notre objet
  $produit->username = $name;
  $produit->id = $id;
  $produit->apikey = $apikey;
  $produit->apisignature = $apisignature;
  $produit->created_at = $created;
  $produit->station = $station;
  $produit->latitude = $latitude;
  $produit->longitude = $longitude;
  $produit ->time_zone= $time_zone;


  if ($produit->creer()) {
    // Ici la création a fonctionné
    // On envoie un code 201
    http_response_code(201);
    echo json_encode(["message" => "L'ajout a été effectué"], JSON_UNESCAPED_UNICODE);
  } else {
    // Ici la création n'a pas fonctionné
    // On envoie un code 503
    http_response_code(503);
    echo json_encode(["message" => "L'ajout n'a pas été effectué"], JSON_UNESCAPED_UNICODE);
  }
}
