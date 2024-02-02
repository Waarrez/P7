## Documentation API Bilemo

### Prérequis
- php 8.2
- MYSQL
- Postman
- Composer 

### Initialisation du projet

----------------------------

- #### Instalation du projet

`composer install`

- #### Créer un .env.local pour connecter la BDD

` Exemple : DATABASE_URL=mysql://root:@localhost:3306/P7?charset=utf8mb4`

- #### Importer la structure de la BDD

` php bin/console d:m:m --no-interaction `

- #### Charger les fixtures pour la BDD

` php bin/console d:f:l --no-interaction `

------------------------------------------

### Explications

> Pour vous connecter à l'API, il faut générer un token, chercher un nom d'utiliasteur au hasard dans la BDD, sur Postman entrez l'url suivante "/api/login_check" en méthode **POST** en insérant dans le body en format JSON le nom d'utilisateur ainsi que le mot de passe (exemple {{"username" : "test", "password" : "password"}}). 
> Un token sera généré, vous le copiez, vous pouvez alors vous dirigez sur **/api** en **GET** en mettant dans **Authorization** avec le type **Bearer Token** le token que vous avez copié.
