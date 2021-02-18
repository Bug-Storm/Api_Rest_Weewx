# Api_Rest_Weewx



# Préambule  

_Description_  

 Ce script permet d'importer  les  données de votre base de données afin de pouvoir exporter en format JSON.  Ce script est fait pour les stations fonctionnant sous le logiciel WeeWX ou d'autres ayant une base de données MySQL.  


 _Requis_ 

 * Une station météo fonctionnant déjà avec une base des données MySQL.
 * Un accès en ligne de commande à votre Raspberry Pi. Si vous avez installé WeeWX ce ne devrait pas être un souci.   



# _Installation_


 _Copie des fichiers_

 ` cd /var/www/html `  
 ` git clone https://github.com/Bug-Storm/Api_Rest_Weewx `    

 _Configuration_

 ` cd /var/www/html/Api_Rest_Weewx `  
 ` nano Database.php `

 _Connection a la base des données_ 

 * Si vous avez une base de données MySQL, il va falloir renseigner les paramètres de connexion à la base :
 ` private $host = 'localhost';  
   private $db_name = '';   
   private $username = '';   
   private $password = ''; `

  ` *private $host: `   qui est l'adresse de l'hôte de la base de données. Probablement localhost si la base de données est hébergée sur votre Raspberry Pi.  

 ` *private $db_name:  `   le nom de la base de données. Par défaut WeeWX la nomme ` weewx `.  

 ` *private $username:  `   le nom d'utilisateur qui a accès à la BDD.

 ` *private $password:  `   le mot de passe de cet utilisateur.  
 