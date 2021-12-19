<?php


class Weewx{
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
    public $starttimestamp;
    public $endtimestamp;
    

    

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
     */
    public function creer(){

        // Ecriture de la requête SQL en y insérant le nom de la table
        $sql = "INSERT INTO " . $this->tableuser . " SET  id=:id, username=:username, apikey=:apikey, apisignature=:apisignature, created_at=:created_at";

        // Préparation de la requête
        $query = $this->connexion->prepare($sql);

       

        // Ajout des données protégées
        $query->bindParam(':id', $this->id);
        $query->bindParam(':username', $this->username);
        $query->bindParam(':apikey', $this->apikey);
        $query->bindParam(':apisignature', $this->apisignature);
        $query->bindParam(':created_at', $this->created_at);

        // Exécution de la requête
        if($query->execute()){
            return true;
        }
        return false;
    }


    /*
     * Lecture avec le derner record sur la BDD -- 1 seul donnée 
     *
     * @return void
     */
      public function current(){
        // On écrit la requête
        // On écrit la requête
        $sql = "SELECT * FROM " . $this->table .  " ORDER BY dateTime DESC LIMIT 1";
        
        // On prépare la requête
        $query = $this->connexion->prepare( $sql );
        
        
   
        // On exécute la requête
        $query->execute();

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

