# Projet OAuth 2.0

## Installation

### Base de données

#### Création la base "oauth2" ainsi que les tables nécessaires

```

CREATE DATABASE IF NOT EXISTS `oauth2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `oauth2`;

CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL,
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`access_token`)
);

CREATE TABLE IF NOT EXISTS `oauth_authorization_codes` (
  `authorization_code` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `expires` timestamp NOT NULL,
  `scope` varchar(4000) DEFAULT NULL,
  `id_token` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`authorization_code`)
);

CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `client_id` varchar(80) NOT NULL,
  `client_secret` varchar(255) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `grant_types` varchar(80) DEFAULT NULL,
  `scope` varchar(4000) DEFAULT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
);

CREATE TABLE IF NOT EXISTS `oauth_jwt` (
  `client_id` varchar(80) NOT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `public_key` varchar(2000) NOT NULL
);

CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL,
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
);

CREATE TABLE IF NOT EXISTS `oauth_scopes` (
  `scope` varchar(80) NOT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`scope`)
);

CREATE TABLE IF NOT EXISTS `oauth_users` (
  `username` varchar(80) NOT NULL,
  `password` varchar(80) DEFAULT NULL,
  `first_name` varchar(80) DEFAULT NULL,
  `last_name` varchar(80) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT NULL,
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`username`)
);

```

### Création d'un client

Afin de pouvoir faire fonctioner les tests unitaires, le client "testclient" devra être inséré en base de données.

#### Génération du mot de passe pour le client "testclient"

```

php -r "echo password_hash('testpass', PASSWORD_ARGON2I, ['time_cost' => 1000]);"

```

#### Insertion du client "testclient" dans la table "oauth_clients" avec le mot de passe généré

```

INSERT INTO oauth_clients (client_id, client_secret) VALUES ('testclient', 'le_mot_de_passe_généré')

```

### Mise en place des fichiers

Créez un répertoire contenant le projet et clonez le projet :

```

mkdir oauth2
cd oauth2
git clone https://github.com/jeanfab123/OAuth2-Client-Server.git -b master

```

## Mise en place des serveurs

### Lancer le serveur Formetris Client

Dans /Formetris-Client-Server, lancer la commande :

```

php -S localhost:8500 -t public/ -ddisplay_errors=1 -dznet_extension=xdebug.so

```

### Lancer le serveur Formetris Resources 

Dans /Formetris-Resources-Server, lancer la commande :

```

php -S localhost:9000 -t public/ -ddisplay_errors=1 -dznet_extension=xdebug.so

```

### Lancer le serveur Formetris OAuth2

Dans /Formetris-OAuth2-Server, lancer la commande :

```

php -S localhost:8000 -t public/ -ddisplay_errors=1 -dznet_extension=xdebug.so

```

## Tests

### Tests unitaires

Dans /Formetris-Resources-Server :

```

composer test

```

### Tests fonctionnels

Dans /Formetris-Integration-Testing :

```

composer test

```