<?php



class weewx{
    // Connexion
    private $connexion;
    private $table = "archive";
    private $tableuser = "users";

    // object properties
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
    

    /**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db){
        $this->connexion = $db;
    }


    public function getuser(){
        // On écrit la requête
        $sql = "SELECT id, apikey, apisignature FROM " .$this->tableuser. " LIMIT 1";

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
    $this-> apikey  = $row ['apikey'];
    $this-> apisignature = $row ['apisignature'];

    }

    /**
     * Créer un produit
     *
     * @return void
    
    
     * Lecture des weewx
     *
     * @return void
     */
    public function lire(){
        // On écrit la requête
        $sql = "SELECT dateTime, usUnits, altimeter, appTemp, barometer, dewpoint, heatindex,humidex, outHumidity, outTemp, pressure, rain,rainRate,windchill,windDir,windGust,windGustDir,windSpeed FROM " . $this->table . "";

        // On prépare la requête
        $query = $this->connexion->prepare($sql);
        

           // On attache l'id
           $query->bindParam(1, $this->id);

           // On exécute la requête
           $query->execute();
   
           // on récupère la ligne
           $row = $query->fetch(PDO::FETCH_ASSOC);
    }

    
    /**
     * Lire un produit
     *
     * @return void
     */
    public function lireUn(){
        // On écrit la requête
        $sql = "SELECT dateTime, usUnits, altimeter, appTemp, barometer, dewpoint, heatindex,humidex, outHumidity, outTemp, pressure, rain,rainRate,windchill,windDir,windGust,windGustDir,windSpeed FROM " . $this->table .  " LIMIT 1";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        // On attache l'id
        $query->bindParam(1, $this->dateTime);

        // On exécute la requête
        $query->execute();

        // on récupère la ligne
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->dateTime = $row['dateTime'];
        $this-> usUnits  = $row ['usUnits'];
        $this-> altimeter = $row ['altimeter'];
        $this-> appTemp = $row ['appTemp'];
        $this-> barometer = $row ['barometer'];
        $this-> dewpoint = $row ['dewpoint'];
        $this-> heatindex = $row ['heatindex'];
        $this-> humidex = $row ['humidex'];
        $this-> outTemp = $row ['outTemp'];
        $this-> outHumidity = $row ['outHumidity'];
        $this-> pressure = $row ['pressure'];
        $this-> rain = $row ['rain'];
        $this-> rainRate = $row ['rainRate'];
        $this-> windchill = $row ['windchill'];
        $this-> windDir = $row ['windDir'];
        $this-> windGust = $row ['windGust'];
        $this-> windGustDir = $row ['windGustDir'];
        $this-> windSpeed = $row['windSpeed'];
    }

}