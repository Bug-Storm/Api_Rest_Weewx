<?php

// Version : 0.3 Bêta

//Name: Api_Rest_Weewx
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
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
    if (isset($_GET['t']) && isset($_GET['id']) && isset($_GET['apikey']) && isset($_GET['apisignature']) && isset($_GET['starttimestamp'])  && isset($_GET['endtimestamp'])) {
        $id = trim($_GET['id']); //Get l'id via la query string

        $apikey = trim($_GET['apikey']); //Get l'api Key via la query string

        $apisignature = trim($_GET['apisignature']); //Get l'api Signature via la query string

        $t = trim($_GET['t']); //Get le Time  via la query string

        $timestampnow = time(); //Timestamp Now

        $timestamp = $timestampnow - $t; // On calc la difference entre le timestamp now et celui de la query string


        $starttimestamp = trim($_GET['starttimestamp']); //Get le StartTimestamp via la query string

        $endtimestamp = trim($_GET['endtimestamp']); //Get le EndTimestamp via la query string

        /*

        On modifie le Timestamp en Date Time  

        Puis on va calculer la différence pour voir si les 24h non pas étaient dépassés

        */

        $verify24hStarTimestamp = date('Y-m-d H:i:s ', $starttimestamp);

        $verify24hEndTimestamp = date('Y-m-d H:i:s', $endtimestamp);

        $ts1 = strtotime($verify24hEndTimestamp);
        $ts2 = strtotime($verify24hStarTimestamp);
        $seconds_diff = $ts2 - $ts1;
        $verify24hTimestamp = ($seconds_diff / 3600);



        if ($timestamp <= 300 and $verify24hTimestamp <= 24) { // Si le $timestamp est == ou < a 300s ou 5m  et le  $verify24hTimestamp est <= 24h  la requete est bonne on continnue sinon un erreur 404 est envoyé 

            // On récupère les données du user 
            $produit->getuser();

            if (!empty($produit->id == $id and $produit->apikey == $apikey and $produit->apisignature == $apisignature and  $produit->starttimestamp = $starttimestamp  and $produit->endtimestamp = $endtimestamp)) {
                // On verifie si les données de la query string sont correctes

                // On récupère les données
                $stmt = $produit->historic();

                // On vérifie si on a au moins 1 produit
                if ($stmt->rowCount() > 0) {
                    // On initialise un tableau associatif

                    // On parcourt les produits
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);


                        //Convert to Metric units//

                        if ($usUnits == 1) {

                            //Rain//

                            $rainRate =  is_null($rainRate) ? NULL : (float) round($rainRate * 25.4, 3);
                            $rain =      is_null($rain) ? NULL : (float) round($rain * 25.4, 3);



                            //Temp//

                            $appTemp =   is_null($appTemp) ? NULL : (float) round(($appTemp - 32) / 1.8, 3);
                            $dewpoint =  is_null($dewpoint) ? NULL : (float) round(($dewpoint - 32) / 1.8, 3);
                            $outTemp =   is_null($outTemp) ? NULL : (float) round(($outTemp - 32) / 1.8, 3);
                            $inTemp =    is_null($inTemp) ? NULL : (float) round(($inTemp - 32) / 1.8, 3);
                            $inDewpoint = is_null($inDewpoint) ? NULL : (float) round(($inDewpoint - 32) / 1.8, 3);
                            $heatindex = is_null($heatindex) ? NULL : (float) round(($heatindex - 32) / 1.8, 3);
                            $humidex =   is_null($humidex) ? NULL : (float) round(($humidex - 32) / 1.8, 3);
                            $windchill = is_null($windchill) ? NULL : (float) round(($windchill - 32) / 1.8, 3);


                            //ExtraTemp//
                            $extraTemp1 = is_null($extraTemp1) ? NULL : (float)round(($extraTemp1 - 32) / 1.8, 3);
                            $extraTemp2 = is_null($extraTemp2) ? NULL : (float) round(($extraTemp2 - 32) / 1.8, 3);
                            $extraTemp3 = is_null($extraTemp3) ? NULL : (float) round(($extraTemp3 - 32) / 1.8, 3);
                            $extraTemp4 = is_null($extraTemp4) ? NULL : (float)round(($extraTemp4 - 32) / 1.8, 3);
                            $extraTemp5 = is_null($extraTemp5) ? NULL : (float)round(($extraTemp5 - 32) / 1.8, 3);
                            $extraTemp6 = is_null($extraTemp6) ? NULL : (float)round(($extraTemp6 - 32) / 1.8, 3);
                            $extraTemp7 = is_null($extraTemp7) ? NULL : (float)round(($extraTemp7 - 32) / 1.8, 3);
                            $extraTemp8 = is_null($extraTemp8) ? NULL : (float)round(($extraTemp8 - 32) / 1.8, 3);

                            //LeafTemp//
                            $leafTemp1 = is_null($leafTemp1) ? NULL : (float)round(($leafTemp1  - 32) / 1.8, 3);
                            $leafTemp2 = is_null($leafTemp2) ? NULL : (float) round(($leafTemp2 - 32) / 1.8, 3);



                            //Wind//
                            $windGust = is_null($windGust) ? NULL : (float) round($windGust * 1.60934, 3);
                            $windSpeed = is_null($windSpeed) ? NULL : (float) round($windSpeed * 1.60934, 3);



                            //Pressure//
                            $altimeter = is_null($altimeter) ? NULL : (float) round($altimeter * 33.8639, 3);
                            $barometer = is_null($barometer) ? NULL : (float) round($barometer * 33.8639, 3);
                            $pressure = is_null($pressure) ? NULL : (float) round($pressure * 33.8639, 3);

                            //Humidity 
                            $outHumidity = is_null($outHumidity) ? NULL : (float) round($outHumidity, 1);
                            $inHumidity = is_null($inHumidity) ? NULL : (float) round($inHumidity, 1);

                            //leafWet//

                            $leafWet1 = is_null($leafWet1) ? NULL : (float) round($leafWet1, 1);
                            $leafWet2 = is_null($leafWet2) ? NULL : (float) round($leafWet2, 1);

                            //soilMoist//
                            $soilMoist1 = is_null($soilMoist1) ? NULL : (float) round($soilMoist1, 1);
                            $soilMoist2 = is_null($soilMoist2) ? NULL : (float) round($soilMoist2, 1);
                            $soilMoist3 = is_null($soilMoist3) ? NULL : (float) round($soilMoist3, 1);
                            $soilMoist4 = is_null($soilMoist4) ? NULL : (float) round($soilMoist4, 1);


                            //ExtraHumid//
                            $extraHumid1 = is_null($extraHumid1) ? NULL : (float) round($extraHumid1, 1);
                            $extraHumid2 = is_null($extraHumid2) ? NULL : (float) round($extraHumid2, 1);
                            $extraHumid3 = is_null($extraHumid3) ? NULL : (float) round($extraHumid3, 1);
                            $extraHumid4 = is_null($extraHumid4) ? NULL : (float) round($extraHumid4, 1);
                            $extraHumid5 = is_null($extraHumid5) ? NULL : (float) round($extraHumid5, 1);
                            $extraHumid6 = is_null($extraHumid6) ? NULL : (float) round($extraHumid6, 1);
                            $extraHumid7 = is_null($extraHumid7) ? NULL : (float) round($extraHumid7, 1);
                            $extraHumid8 = is_null($extraHumid8) ? NULL : (float) round($extraHumid8, 1);
                        } elseif ($usUnits == 17) {

                            //Rain//

                            $rainRate = is_null($rainRate) ? NULL : (float) round($rainRate, 3);
                            $rain =     is_null($rain) ? NULL : (float) round($rain, 3);


                            //Temp//
                            $appTemp =  is_null($appTemp) ? NULL : (float)round($appTemp, 3);
                            $windchill = is_null($windchill) ? NULL : (float) round($windchill, 3);
                            $heatindex = is_null($heatindex) ? NULL : (float) round($heatindex, 3);
                            $dewpoint = is_null($dewpoint) ? NULL : (float) round($dewpoint, 3);
                            $outTemp =  is_null($outTemp) ? NULL : (float) round($outTemp, 3);
                            $inTemp =   is_null($inTemp) ? NULL : (float) round($inTemp, 3);
                            $inDewpoint = is_null($inDewpoint) ? NULL : (float) round($inDewpoint, 3);
                            $humidex =  is_null($humidex) ? NULL : (float) round($humidex, 3);
                            $windchill = is_null($windchill) ? NULL : (float)round($windchill, 3);

                            //ExtraTemp//
                            $extraTemp1 = is_null($extraTemp1) ? NULL : (float) round($extraTemp1, 3);
                            $extraTemp2 = is_null($extraTemp2) ? NULL : (float) round($extraTemp2, 3);
                            $extraTemp3 = is_null($extraTemp3) ? NULL : (float) round($extraTemp3, 3);
                            $extraTemp4 = is_null($extraTemp4) ? NULL : (float) round($extraTemp4, 3);
                            $extraTemp5 = is_null($extraTemp5) ? NULL : (float) round($extraTemp5, 3);
                            $extraTemp6 = is_null($extraTemp6) ? NULL : (float)round($extraTemp6, 3);
                            $extraTemp7 = is_null($extraTemp7) ? NULL : (float) round($extraTemp7, 3);
                            $extraTemp8 = is_null($extraTemp8) ? NULL : (float) round($extraTemp8, 3);

                            //LeafTemp//
                            $leafTemp1 = is_null($leafTemp1) ? NULL : (float) round($leafTemp1, 3);
                            $leafTemp2 = is_null($leafTemp2) ? NULL : (float) round($leafTemp2, 3);

                            //Wind//
                            $windGust = is_null($windGust) ? NULL : (float) round($windGust * 3.6, 3);
                            $windSpeed = is_null($windSpeed) ? NULL : (float) round($windSpeed * 3.6, 3);

                            //Pressure//
                            $altimeter = is_null($altimeter) ? NULL : (float) round($altimeter, 3);
                            $barometer = is_null($barometer) ? NULL : (float) round($barometer, 3);
                            $pressure = is_null($pressure) ? NULL : (float) round($pressure, 3);

                            //Humidity 
                            $outHumidity = is_null($outHumidity) ? NULL : (float) round($outHumidity, 1);
                            $inHumidity = is_null($inHumidity) ? NULL : (float) round($inHumidity, 1);

                            //leafWet//

                            $leafWet1 = is_null($leafWet1) ? NULL : (float) round($leafWet1, 1);
                            $leafWet2 = is_null($leafWet2) ? NULL : (float) round($leafWet2, 1);

                            //soilMoist//
                            $soilMoist1 = is_null($soilMoist1) ? NULL : (float) round($soilMoist1, 1);
                            $soilMoist2 = is_null($soilMoist2) ? NULL : (float) round($soilMoist2, 1);
                            $soilMoist3 = is_null($soilMoist3) ? NULL : (float) round($soilMoist3, 1);
                            $soilMoist4 = is_null($soilMoist4) ? NULL : (float) round($soilMoist4, 1);

                            //ExtraHumid//
                            $extraHumid1 = is_null($extraHumid1) ? NULL : (float) round($extraHumid1, 1);
                            $extraHumid2 = is_null($extraHumid2) ? NULL : (float) round($extraHumid2, 1);
                            $extraHumid3 = is_null($extraHumid3) ? NULL : (float) round($extraHumid3, 1);
                            $extraHumid4 = is_null($extraHumid4) ? NULL : (float) round($extraHumid4, 1);
                            $extraHumid5 = is_null($extraHumid5) ? NULL : (float) round($extraHumid5, 1);
                            $extraHumid6 = is_null($extraHumid6) ? NULL : (float) round($extraHumid6, 1);
                            $extraHumid7 = is_null($extraHumid7) ? NULL : (float) round($extraHumid7, 1);
                            $extraHumid8 = is_null($extraHumid8) ? NULL : (float) round($extraHumid8, 1);
                        } elseif ($usUnits == 16) {


                            $rainRate = is_null($rainRate) ? NULL : (float) round($rainRate * 10, 3);
                            $rain = is_null($rain) ? NULL : (float) round($rain * 10, 3);



                            //Temp//
                            $appTemp = is_null($appTemp) ? NULL : (float) round($appTemp, 3);
                            $windchill = is_null($windchill) ? NULL : (float) round($windchill, 3);
                            $heatindex = is_null($heatindex) ? NULL : (float) round($heatindex, 3);
                            $dewpoint = is_null($dewpoint) ? NULL : (float) round($dewpoint, 3);
                            $outTemp = is_null($outTemp) ? NULL : (float) round($outTemp, 3);
                            $inTemp = is_null($inTemp) ? NULL : (float) round($inTemp, 3);
                            $inDewpoint = is_null($inDewpoint) ? NULL : (float) round($inDewpoint, 3);
                            $humidex = is_null($humidex) ? NULL : (float) round($humidex, 3);
                            $windchill = is_null($windchill) ? NULL : (float) round($windchill, 3);


                            //ExtraTemp//
                            $extraTemp1 = is_null($extraTemp1) ? NULL : (float) round($extraTemp1, 3);
                            $extraTemp2 = is_null($extraTemp2) ? NULL : (float) round($extraTemp2, 3);
                            $extraTemp3 = is_null($extraTemp3) ? NULL : (float) round($extraTemp3, 3);
                            $extraTemp4 = is_null($extraTemp4) ? NULL : (float) round($extraTemp4, 3);
                            $extraTemp5 = is_null($extraTemp5) ? NULL : (float) round($extraTemp5, 3);
                            $extraTemp6 = is_null($extraTemp6) ? NULL : (float) round($extraTemp6, 3);
                            $extraTemp7 = is_null($extraTemp7) ? NULL : (float) round($extraTemp7, 3);
                            $extraTemp8 = is_null($extraTemp8) ? NULL : (float) round($extraTemp8, 3);

                            //LeafTemp//
                            $leafTemp1 = is_null($leafTemp1) ? NULL : (float) round($leafTemp1, 3);
                            $leafTemp2 = is_null($leafTemp2) ? NULL : (float) round($leafTemp2, 3);

                            //soilTemp//
                            $soilTemp1 = is_null($soilTemp1) ? NULL : (float) round($soilTemp1, 1);
                            $soilTemp2 = is_null($soilTemp2) ? NULL : (float) round($soilTemp2, 1);
                            $soilTemp3 = is_null($soilTemp3) ? NULL : (float) round($soilTemp3, 1);
                            $soilTemp4 = is_null($soilTemp4) ? NULL : (float) round($soilTemp4, 1);


                            //Wind//
                            $windGust = is_null($windGust) ? NULL : (float) round($windGust, 3);
                            $windSpeed = is_null($windSpeed) ? NULL : (float) round($windSpeed, 3);


                            //Pressure//
                            $altimeter = is_null($altimeter) ? NULL : (float) round($altimeter, 3);
                            $barometer = is_null($barometer) ? NULL : (float) round($barometer, 3);
                            $pressure = is_null($pressure) ? NULL : (float) round($pressure, 3);


                            //Humidity 
                            $outHumidity = is_null($outHumidity) ? NULL : (float) round($outHumidity, 1);
                            $inHumidity = is_null($inHumidity) ? NULL : (float) round($inHumidity, 1);

                            //leafWet//

                            $leafWet1 = is_null($leafWet1) ? NULL : (float) round($leafWet1, 1);
                            $leafWet2 = is_null($leafWet2) ? NULL : (float) round($leafWet2, 1);

                            //soilMoist//
                            $soilMoist1 = is_null($soilMoist1) ? NULL : (float) round($soilMoist1, 1);
                            $soilMoist2 = is_null($soilMoist2) ? NULL : (float) round($soilMoist2, 1);
                            $soilMoist3 = is_null($soilMoist3) ? NULL : (float) round($soilMoist3, 1);
                            $soilMoist4 = is_null($soilMoist4) ? NULL : (float) round($soilMoist4, 1);


                            //ExtraHumid//
                            $extraHumid1 = is_null($extraHumid1) ? NULL : (float) round($extraHumid1, 1);
                            $extraHumid2 = is_null($extraHumid2) ? NULL : (float) round($extraHumid2, 1);
                            $extraHumid3 = is_null($extraHumid3) ? NULL : (float) round($extraHumid3, 1);
                            $extraHumid4 = is_null($extraHumid4) ? NULL : (float) round($extraHumid4, 1);
                            $extraHumid5 = is_null($extraHumid5) ? NULL : (float) round($extraHumid5, 1);
                            $extraHumid6 = is_null($extraHumid6) ? NULL : (float) round($extraHumid6, 1);
                            $extraHumid7 = is_null($extraHumid7) ? NULL : (float) round($extraHumid7, 1);
                            $extraHumid8 = is_null($extraHumid8) ? NULL : (float) round($extraHumid8, 1);
                        }




                        //Archive//
                        $prod = [

                            $user = [

                                "station"                        => is_null($station) ? NULL : (string)$station,
                                "latitude"                       => is_null($latitude) ? NULL : (float) $latitude,
                                "longitude"                      => is_null($longitude) ? NULL : (float) $longitude,
                                "time_zone"                      => is_null(preg_replace('/\s+/', ' ', $time_zone)) ? NULL  : (string) preg_replace('/\s+/', ' ', $time_zone),


                                "datetime"                       => is_null($dateTime) ? NULL : (float)$dateTime,
                                "interval"                       => is_null($interval) ? NULL : (float)$interval,
                                "altimeter_hPa"                 => is_null($altimeter) ? NULL : (float) $altimeter,
                                "appTemp_C"                      => is_null($appTemp) ? NULL : (float) $appTemp,
                                "bar_sea_level_hPa"             => is_null($barometer) ? NULL : (float)$barometer,
                                "dew_point_C"                    => is_null($dewpoint) ? NULL : (float)$dewpoint,
                                "heat_index_C"                   => is_null($heatindex) ? NULL : (float)$heatindex,
                                "humidex"                        => is_null($humidex) ? NULL : (float)$humidex,
                                "temp_C"                         => is_null($outTemp) ? NULL : (float) $outTemp,
                                "hum"                            => is_null($outHumidity) ? NULL : (float)$outHumidity,
                                "bar_absolute_hPa"              => is_null($pressure) ? NULL : (float)$pressure,

                                "rainfall_last_24_hr_mm"         => is_null($rain) ? NULL : (float) $rain,
                                "rain_rate_last_mm"              => is_null($rainRate) ? NULL : (float) $rainRate,

                                "wind_chill_C"                   => is_null($windchill) ? NULL : (float) $windchill,
                                "wind_dir_last"                  => is_null($windDir) ? NULL : (float) $windDir,
                                "wind_speed_hi_last_10_min_km"  => is_null($windGust) ? NULL : (float) $windGust,
                                "wind_gust_dir_last"             => is_null($windGust) ? NULL : (float) $windGustDir,
                                "wind_speed_avg_last_10_min_km" => is_null($windSpeed)  ? NULL : (float) $windSpeed,
                                "uv_index"                       => is_null($UV) ? NULL : (float)$UV,
                                "solar_rad"                      => is_null($radiation) ? NULL : (float)$radiation,
                                "ET"                             => is_null($ET) ? NULL : (float)$ET,


                                //ExtraTemp//
                                "temp_extra_1_C"                 => is_null($extraTemp1) ? NULL : (float)$extraTemp1,
                                "temp_extra_2_C"                 => is_null($extraTemp2) ? NULL : (float)$extraTemp2,
                                "temp_extra_3_C"                 => is_null($extraTemp3) ? NULL : (float)$extraTemp3,
                                "temp_extra_4_C"                 => is_null($extraTemp4) ? NULL : (float)$extraTemp4,
                                "temp_extra_5_C"                 => is_null($extraTemp5) ? NULL : (float)$extraTemp5,
                                "temp_extra_6_C"                 => is_null($extraTemp6) ? NULL : (float)$extraTemp6,
                                "temp_extra_7_C"                 => is_null($extraTemp7) ? NULL : (float)$extraTemp7,
                                "temp_extra_8_C"                 => is_null($extraTemp8) ? NULL : (float)$extraTemp8,

                                //leafwet//
                                "leaf_wetness_1"                => is_null($leafWet1) ? NULL : (float)$leafWet1,
                                "leaf_wetness_2"                => is_null($leafWet2) ? NULL : (float)$leafWet2,

                                //soilMoist//
                                "soil_moisture_1"               => is_null($soilMoist1) ? NULL : (float)$soilMoist1,
                                "soil_moisture_2"               => is_null($soilMoist2) ? NULL : (float)$soilMoist2,
                                "soil_moisture_3"               => is_null($soilMoist3) ? NULL : (float)$soilMoist3,
                                "soil_moisture_4"               => is_null($soilMoist4) ? NULL : (float)$soilMoist4,

                                //soilTemp//
                                "soil_Temp_1_C"                 => is_null($soilTemp1) ? NULL : (float)$soilTemp1,
                                "soil_Temp_2_C"                  => is_null($soilTemp2) ? NULL : (float)$soilTemp2,
                                "soil_Temp_3_C"                  => is_null($soilTemp3) ? NULL : (float)$soilTemp3,
                                "soil_Temp_4_C"                  => is_null($soilTemp4) ? NULL : (float)$soilTemp4,

                                //ExtraHumid/
                                "humid_extra_1"                  => is_null($extraHumid1) ? NULL : (float)$extraHumid1,
                                "humid_extra_2"                  => is_null($extraHumid2) ? NULL : (float)$extraHumid2,
                                "humid_extra_3"                  => is_null($extraHumid3) ? NULL : (float)$extraHumid3,
                                "humid_extra_4"                  => is_null($extraHumid4) ? NULL : (float)$extraHumid4,
                                "humid_extra_5"                  => is_null($extraHumid5) ? NULL : (float)$extraHumid5,
                                "humid_extra_6"                  => is_null($extraHumid6) ? NULL : (float)$extraHumid6,
                                "humid_extra_7"                  => is_null($extraHumid7) ? NULL : (float)$extraHumid7,
                                "humid_extra_8"                  => is_null($extraHumid8) ? NULL : (float)$extraHumid8,


                                //Inside//
                                "temp_in_C"                      => is_null($inTemp) ? NULL : (float) $inTemp,
                                "dew_point_in_C"                 => is_null($inDewpoint) ? NULL : (float) $inDewpoint,
                                "hum_in"                         => is_null($inHumidity) ? NULL : (float)$inHumidity,
                                "Last_record"                    => is_null($echo =  str_replace("/", "/", date('d-m-Y H:i', $dateTime))) ? NULL : (string) $echo =  str_replace("/", "/", date('d-m-Y H:i', $dateTime)),
                            ],
                        ];


                        $tableauProduits['Weewx']['sensors'][]['data'] = $prod;
                    }


                    // On envoie le code réponse 200 OK
                    http_response_code(200);

                    // On encode en json et on envoie
                    echo json_encode($tableauProduits);
                }
            } else {
                //if (!empty($produit->id == $id and $produit->apikey == $apikey ......

                // 404 Not found
                http_response_code(404);

                echo json_encode(array("message" => "Votre requête ne pas bonne ou les relevés n'existent pas, veuillez ressayer ."), JSON_UNESCAPED_UNICODE);
            }
        } else {
            // if ($timestamp <= 300 and $verify24hTimestamp <= 24)
            // 405 Method Not Allowed
            http_response_code(405);
            echo json_encode(["message" => "Vous avez dépassé le limit de 5m ou 24h"], JSON_UNESCAPED_UNICODE);
        }
    } else {
        // if (isset($_GET['t']) && isset($_GET['id']) && isset($_GET['apikey'])..... 
        // 405 Method Not Allowed
        http_response_code(405);

        echo json_encode(array("message" => "La méthode n'est pas autorisée."), JSON_UNESCAPED_UNICODE);
    }
} else {

    // ($_SERVER['REQUEST_METHOD'] == 'GET')
    // 405 Method Not Allowed 
    http_response_code(405);

    echo json_encode(array("message" => "La méthode n'est pas autorisée."), JSON_UNESCAPED_UNICODE);
}
