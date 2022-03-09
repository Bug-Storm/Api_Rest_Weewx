<?php


class Weewx
{
    // Connexion
    private $connexion;
    private $table = "archive";
    private $tableuser = "users";

    // object properties
    public $bar_trend;
    public $rainmonth;
    public $rainyear;
    public $dateTime;
    public $usUnits;
    public $altimeter;
    public $appTemp;
    public $barometer;
    public $dewpoint;
    public $heatindex;
    public $humidex;
    public $outTemp;
    public $outHumidity;
    public $pressure;
    public $rain;
    public $rainRate;
    public $windchill;
    public $windDir;
    public $windGust;
    public $windSpeed;
    public $windGustDir;
    public $id;
    public $apikey;
    public $apisignature;
    public $starttimestamp;
    public $endtimestamp;
    public $latitude;
    public $longitude;
    public $station;
    public $time_zone;
    public $extraHumid1;
    public $extraHumid2;
    public $extraHumid3;
    public $extraHumid4;
    public $extraHumid5;
    public $extraHumid6;
    public $extraHumid7;
    public $extraHumid8;
    public $soilMoist1;
    public $soilMoist2;
    public $soilMoist3;
    public $soilMoist4;
    public $leafTemp1;
    public $leafTemp2;
    public $soilTemp1;
    public $soilTemp2;
    public $soilTemp3;
    public $soilTemp4;
    public $extraTemp1;
    public $extraTemp2;
    public $extraTemp3;
    public $extraTemp4;
    public $extraTemp5;
    public $extraTemp6;
    public $extraTemp7;
    public $extraTemp8;
    public $ET;
    public $Rain_max;
    public $Tx;
    public $Radiation_max;
    public $Gust_max;





    /**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db)
    {
        $this->connexion = $db;
    }


    //public function table() Cela nous permet de verifier si la tabler "users" existe ou pas 

    public function table()
    {
        // On écrit la requête

        $sql = "SELECT 1 FROM " . $this->tableuser . " LIMIT 1";



        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On attache l'id
        $query->bindParam(1, $this->id);

        // On exécute la requête
        $query->execute();

        // on récupère la ligne
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->id = $row['id']  ?? 'default';
        if ($row !== FALSE) {
        } else {

            // Si la table "users" n'existe pas, on va la creer.

            $sql = "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(50) NOT NULL,
            `apikey` varchar(255) NOT NULL,
            `apisignature` varchar(255) NOT NULL,
            `station` varchar(20) NOT NULL,
            `latitude` varchar(255) NOT NULL,
            `longitude` varchar(255) NOT NULL,
            `time_zone` varchar(255) NOT NULL,
            `created_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `username` (`username`)
          ) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;
          COMMIT";


            // Préparation de la requête
            $query = $this->connexion->prepare($sql);

            // Exécution de la requête
            if ($query->execute()) {
                return true;
            }
            return false;
        }
    }


    //public function getuser() nous permet de recuperer l'user avec les parametres 
    //$this->id = $row['id'];
    //$this-> apikey  = $row ['apikey'];
    //$this-> apisignature = $row ['apisignature'];


    public function getuser()
    {
        // On écrit la requête
        $sql = "SELECT * FROM " . $this->tableuser . " LIMIT 1";

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On attache l'id
        $query->bindParam(1, $this->id);

        // On exécute la requête
        $query->execute();

        // on récupère la ligne
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->id = $row['id'];
        $this->apikey  = $row['apikey'];
        $this->apisignature = $row['apisignature'];
        $this->latitude = $row['latitude'];
        $this->longitude = $row['longitude'];
        $this->station = $row['station'];
        $this->time_zone = $row['time_zone'];
    }

    /**
     * Créer un user
     *
     * @return void
     */
    public function creer()
    {

        // Ecriture de la requête SQL en y insérant le nom de la table
        $sql = "INSERT INTO " . $this->tableuser . " SET  id=:id, username=:username, apikey=:apikey, apisignature=:apisignature, created_at=:created_at, station=:station, latitude=:latitude, longitude=:longitude, time_zone=:time_zone";

        // Préparation de la requête
        $query = $this->connexion->prepare($sql);



        // Ajout des données protégées
        $query->bindParam(':id', $this->id);
        $query->bindParam(':username', $this->username);
        $query->bindParam(':apikey', $this->apikey);
        $query->bindParam(':apisignature', $this->apisignature);
        $query->bindParam(':station', $this->station);
        $query->bindParam(':latitude', $this->latitude);
        $query->bindParam(':longitude', $this->longitude);
        $query->bindParam(':time_zone', $this->time_zone);
        $query->bindParam(':created_at', $this->created_at);

        // Exécution de la requête
        if ($query->execute()) {
            return true;
        }
        return false;
    }


    /*
     * Lecture avec le derner record sur la BDD -- 1 seul donnée 
     *
     * @return void
     */


    public function rainmonth()
    {
        // On recup le 1er jour du mois et le dernier jour du mois en cours  

        $datestart = strtotime(date('Y-m-01'));
        $dateend = strtotime(date('Y-m-t'));
        // On écrit la requête
        $sql = "SELECT sum(rain) AS rainmonth FROM " . $this->table . " WHERE dateTime BETWEEN " . $datestart . " AND " . $dateend . " ";

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        $this->rainmonth = $row['rainmonth'];
        // on récupère la ligne
        return $query;
    }



    public function rainyear()
    {

        // On recup le 1er jour de l'année  et le dernier jour de  l'année en cours  
        $datestart = strtotime(date('Y-01-01'));
        $dateend = strtotime(date('Y-m-d'));

        // On écrit la requête
        $sql = "SELECT sum(rain) AS rainyear FROM " . $this->table . " WHERE dateTime BETWEEN " . $datestart . " AND " . $dateend . "";


        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->rainyear = $row['rainyear'];

        // on récupère la ligne
        return $query;
    }





    public function max_06UTC()
    {   
        date_default_timezone_set('UTC');
        

        $datenow = gmdate('H:i');


        if ($datenow >= '06:01') {
            $date = strtotime(gmdate('d-m-Y 06:00:00 ', strtotime(' + 1 days')));
        } else {
            $date = gmdate('d-m-Y H:i:00');
        }

        $datestart = $date;
        $datestart5m = ceil(($datestart / 300) * 300) - (60 * 5);


        if ($datenow >= '00:00' && $datenow <= '06:00') {
            $date_end = strtotime(gmdate('d-m-Y 06:00:00 ', strtotime(' - 1 days')));
        } else {
            $date_end = strtotime(gmdate('d-m-Y 06:00:00'));
        }

        $date_end = $date_end;


        // On écrit la requête
        $sql = "SELECT dateTime, max(outTemp) as Tx  , max(rain) as Rain_max  FROM " . $this->table . " WHERE dateTime BETWEEN " . $date_end . " AND " . $datestart5m . "";


        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();


        // On hydrate l'objet

        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->Tx = $row['Tx'];
        $this->Rain_max = $row['Rain_max'];
        // On hydrate l'objet
        return $query;
    }


    public function max_00UTC()

    {
        date_default_timezone_set('UTC');
        $datestart = strtotime(gmdate('d-m-Y 00:00:00', strtotime(' - 1 hours + 1 days ')));
        

        $dateend =  strtotime(gmdate('d-m-Y 00:00:00 '));


        // On écrit la requête
        $sql = "SELECT dateTime, max(windGust) as Gust_max ,  max(radiation) as radiation_max FROM " . $this->table . " WHERE dateTime BETWEEN " . $dateend . " AND " . $datestart . "";


        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();


        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->Gust_max = $row['Gust_max'];
        $this->Radiation_max = $row['radiation_max'];

        // On hydrate l'objet
        return $query;
    }



    public function temp_mini()
    {
        date_default_timezone_set('UTC');
        //Date Now//
        $datenow = gmdate('H:i');

        //La on va voir si l'heure est > 18h01 pour pouvoir prendre les données à  j-1//
        if ($datenow >= '18:01') {
            $date = gmdate('d-m-Y 18:00:00');
        } else {
            $date = gmdate('d-m-Y H:i');
        }



        $datestart = strtotime($date);
        //Date en 5m en 5m//
        $datestart = ceil($datestart / 300) * 300;
        $dateend = strtotime(gmdate('d-m-Y 18:00:00 ', strtotime(' - 1 days')));


        // On écrit la requête
        $sql = "SELECT dateTime, min(outTemp) as Tn  FROM " . $this->table . " WHERE dateTime BETWEEN " . $dateend   . " AND " . $datestart . "";


        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->Tn = $row['Tn'];

        // On hydrate l'objet
        return $query;
    }


    public function bar_trend()
    {


        //On récupère l'heure actu puis on va arrondir pour avoir les données par 5m//
        $now = time();
        $next_five = ceil($now / 300) * 300;

        $futureDate = $next_five - (60 * 5); //L'heure du dernier rèlevé
        $pastDate = $next_five - (60 * 180);  //L'heure du dernier rèlevé 3H avant


        // On écrit la requête
        $sql = "SELECT  ROUND(barometer, 10) AS bar_trend FROM " . $this->table . " WHERE dateTime = " . $pastDate . "  OR dateTime =  " . $futureDate . "";

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        // On hydrate l'objet
        return $query;
    }



    public function current()
    {

        // On écrit la requête
        $sql = "SELECT * FROM " . $this->table .  "  NATURAL JOIN " . $this->tableuser . "  ORDER BY dateTime DESC LIMIT 1";

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        // On hydrate l'objet
        return $query;
    }


    
    /*
     * Lecture des historic 
     *
     * @return void
     */
    public function historic(){
        // On écrit la requête
        // On écrit la requête
        $sql = "SELECT * FROM " . $this->table .  " WHERE dateTime BETWEEN :dateTime AND :dateTime1 ";
        
        // On prépare la requête
        $query = $this->connexion->prepare( $sql );
        
        // On attache le dateTime vers le Startimestamp  & Endtimestamp
        $query->bindParam(':dateTime', $this->starttimestamp );
        $query->bindParam(':dateTime1', $this->endtimestamp );
   
        // On exécute la requête
        $query->execute();

        return $query;
    }

}

