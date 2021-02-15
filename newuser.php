<?php

// On inclut les fichiers de configuration et d'accès aux données
include_once 'Database.php';
include_once 'weewx.php';

//Bienvenue    
echo "---------------------------------------------------------------------------------------------------------------------------- \n";
echo "Bienvenue, ce script vous permet de creer un user avec un Id et une Api key & Signature  \n";
echo "---------------------------------------------------------------------------------------------------------------------------- \n";
echo "Veuillez taper 'enter'";


$handle = fopen ("php://stdin","r");
$line = fgets($handle);
//Creation d'un Nom pour le  user 
if(trim($line) == '' ){
    echo "set name: ";
    $name   = fread(STDIN,20);

    //Creation d'un ID random
        $id   = rand(10, 20);
      	 // Creation d'une key random  
        $bytes1 = random_bytes(5);
        $apisignature=(bin2hex($bytes1));
		// Creation d'une key random  
        $bytes = random_bytes(5);
        $apikey=(bin2hex($bytes));
    echo "\n\n\n";
    
}else{


echo "\n";
echo "Veuillez recommencer :/ \n";
}

echo"-------------------------------------------------------------------------------------------- \n";
echo "User: " .$name."\n";
echo "id: " .$id."\n";
echo "ApiKey: ".$apikey."\n";
echo "ApiSignature: ". $apisignature."\n";
echo $created = date('Y-m-d H:i:s'). "\n";
echo"-------------------------------------------------------------------------------------------- \n";





// On instancie la base de données
$database = new Database();
$db = $database->getConnection();

// On instancie les produits
$produit = new Weewx($db);




if(!empty($name) && !empty($id) && !empty($apikey) && !empty($apisignature)  && !empty($created)){
  // Ici on a reçu les données
  // On hydrate notre objet
  $produit->username = $name;
  $produit->id = $id;
  $produit->apikey = $apikey;
  $produit->apisignature = $apisignature;
  $produit->created_at = $created;

  if($produit->creer()){
      // Ici la création a fonctionné
      // On envoie un code 201
      http_response_code(201);
      echo json_encode(["message" => "L'ajout a été effectué"], JSON_UNESCAPED_UNICODE);
  }else{
      // Ici la création n'a pas fonctionné
      // On envoie un code 503
      http_response_code(503);
      echo json_encode(["message" => "L'ajout n'a pas été effectué"], JSON_UNESCAPED_UNICODE);         
  }
}
