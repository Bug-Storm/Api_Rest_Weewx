# Api_Rest_Weewx



# Préambule  

__Description__  

 Ce script permet d'importer  les  données de votre base de données afin de pouvoir exporter en format JSON.  Ce script est fait pour les stations fonctionnant sous le logiciel WeeWX ou d'autres ayant une base de données MySQL.  


 __Requis__ 

 * Une station météo fonctionnant déjà avec une base des données MySQL.
 * Un accès en ligne de commande à votre Raspberry Pi. Si vous avez installé WeeWX ce ne devrait pas être un souci.   



# __Installation__


 __Copie des fichiers__
 
 Se placer dans un premier temps dans le répertoire ou l'on veut copier le script, puis cloner le répertoire.  

 ` cd /var/www/html `  

 ` git clone https://github.com/Bug-Storm/Api_Rest_Weewx `    

 __Configuration__

On peut maintenant se placer dans le répertoire du script afin de modifier le fichier de configuration.

 ` cd /var/www/html/Api_Rest_Weewx `  
 
 ` nano Database.php `

__Creation de la table ` users`__

En premier vous allez rentrer dans `PHPMYADMIN`, vous allez sélectionner la BDD `Weewx` et vous allez dans ` Import `.  

Vous allez sélectionner le fichier ` users.sql`.

Patienter quelques secondes puis vérifié que la table `user` a été bien créée.  

 __Connection a la base des données__ 

 * Si vous avez une base de données MySQL, il va falloir renseigner les paramètres de connexion à la base :

  
  ` private $host = 'localhost'; `    
   `private $db_name = '';`     
   `private $username = ''; `    
   `private $password = ''; `  
   

----------------------------------------------------------------------------------------------------------------------------------------

  `* private $host:`   qui est l'adresse de l'hôte de la base de données. Probablement localhost si la base de données est hébergée sur votre Raspberry Pi.  

 `* private $db_name:`   le nom de la base de données. Par défaut WeeWX la nomme ` weewx `.  

 `* private $username:`   le nom d'utilisateur qui a accès à la BDD.

 `* private $password:`   le mot de passe de cet utilisateur.  

 
 __Creation d'un nouveau user__
 
 Pour créer un nouveau user il suffit d'ouvrir une ligne de commande et de taper:

 ` php newuser.php `

![Simplon.co](https://i.imgur.com/tsw3Hqe.gif)


puis vous allez rentrer le Nom que vous voulez.  Le script va donc créer un Id + une API Key et une API Signature.  

Une fois le nouveau user créer, vous pouvez laisser la ligne de commande ouverte!!


__Recuperation des données__

Pour que l'api puisse bien récupérer les données de la BDD, vous avez besoin de 6 paramètres: 


t =  ` Timestamp(valable 5m)`

id = ` L'id correspondent au user `

api key = ` L'api Key qu'a été crée avec l'user `

api signature = ` L'api signature qu'a été crée avec l'user `

start timestamp = ` la Date/L'heure du début que vous voulez récupérer  `

end timestamp = ` la Date/L'heure de la fin  que vous voulez récupérer  `



Vous allez devrez avoir l'url comme ça: 

`https://mydnsadresse/Api_Rest_Weewx/api.php?t=1613422447&id=1&apikey=555&apisignature=555&starttimestamp=1613343600&endtimestamp=1613419937 `


