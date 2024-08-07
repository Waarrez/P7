## Documentation API Bilemo

### Prérequis
- php 8.2
- MYSQL
- Postman
- Composer
- OpenSSL

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

- ### Générer la clé privé et public JWT
- ` openssl genrsa -out config/jwt/private.pem -aes256 4096 `
- ` openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem `

------------------------------------------

### Explications

> Pour vous connecter à l'API, il faut générer un token, chercher un nom d'utiliasteur au hasard dans la BDD, sur Postman entrez l'url suivante "/api/login_check" en méthode **POST** en insérant dans le body en format JSON le nom d'utilisateur ainsi que le mot de passe (exemple {"username" : "test", "password" : "password"}). 
> Un token sera généré, vous le copiez, vous pouvez alors vous dirigez sur **/api** en **GET** en mettant dans **Authorization** avec le type **Bearer Token** le token que vous avez copié.

### Lien de la documentation

``` /api/doc ```

[![Maintainability](https://api.codeclimate.com/v1/badges/ff8d976a0c90a5053aa2/maintainability)](https://codeclimate.com/github/Waarrez/P7/maintainability)
[![SymfonyInsight](https://insight.symfony.com/projects/ce321f9e-83d7-4588-9bdb-405f6c91c937/big.svg)](https://insight.symfony.com/projects/ce321f9e-83d7-4588-9bdb-405f6c91c937)
[![PHP Version](https://img.shields.io/badge/php-8.0-blue)](https://www.php.net/releases/8.0/en.php)
![Composer Version](https://img.shields.io/badge/Composer-2.6.6-blue)
