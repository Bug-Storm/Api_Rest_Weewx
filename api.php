<?php
// Headers requis
header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 300");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // On inclut les fichiers de configuration et d'accès aux données
    include_once './Database.php';
    include_once './weewx.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    // On instancie les weewx
    $produit = new weewx($db);


    //Make sure that our query string parameters exist.
    if (isset($_GET['t']) && isset($_GET['id']) && isset($_GET['apikey']) && isset($_GET['apisignature'])) {
        $id = trim($_GET['id']); //Get l'id via la query string
        $apikey = trim($_GET['apikey']); //Get l'api Key via la query string
        $apisignature = trim($_GET['apisignature']); //Get l'api Signature via la query string
        $t = trim($_GET['t']); //Get le Time  via la query string
        $timestampnow = time(); //Timestamp Now
        $timestamp = $timestampnow - $t; // On calc la difference entre le timestamp now et celui de la query string


        if ($timestamp <= 300) { // Si le $timestamp est == ou < a 300s ou 5m la requete est bonne  sinon un erreur 404 est envoyé 
            $produit->getuser();

            if ($produit->id == $id and $produit->apikey == $apikey and $produit->apisignature == $apisignature) {
                // On récupère le produit
                $produit->lireUn();

                // On vérifie si le produit existe
                if ($produit->dateTime != null) {

                    $prod = [
                        "datetime" => $produit->dateTime,
                        "usUnits"  => $produit->usUnits,
                        "altimeter" => $produit->altimeter,
                        "appTemp" => $produit->appTemp,
                        "barometer" => $produit->barometer,
                        "dewpoint" => $produit->dewpoint,
                        "heatindex" => $produit->heatindex,
                        "humidex" => $produit->humidex,
                        "outTemp" => $produit->outTemp,
                        "outHumidity" => $produit->outHumidity,
                        "pressure" => $produit->pressure,
                        "rain" => $produit->rain,
                        "rainRate" => $produit->rainRate,
                        "windchill" => $produit->windchill,
                        "windDir" => $produit->windDir,
                        "windGust" => $produit->windGust,
                        "windGustDir" => $produit->windGustDir,
                        "windSpeed" => $produit->windSpeed
                    ];
                    // On envoie le code réponse 200 OK
                    http_response_code(200);

                    // On encode en json et on envoie
                    echo json_encode($prod);
                } else {
                    //Token is not valid.

                    // 404 Not found
                    http_response_code(404);

                    echo json_encode(array("message" => "Vous avez depasser le limit de 24h!."));
                }
            } else {
                // On gère l'erreur
                http_response_code(405);
                echo json_encode(["message" => "La méthode n'est pas autorisée"]);
            }
        } else {
            // 404 Not found
            http_response_code(404);

            echo json_encode(array("message" => "Vous avez dépasse le limit de 5m."));
        }
    }
}
