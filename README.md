# MyTabs

## Se connecter sur le serveur de prod
```bash
heroku run bash -a my-bands-app
```

## Modifier un mot de passe de prod à la main
```bash
php bin/console security:encode-password 'my_pwd'
php bin/console doctrine:query:sql 'UPDATE user SET password = "my_encoded_pwd" WHERE email like "%my_email%"'
php bin/console doctrine:query:sql 'SELECT * FROM user WHERE email like "%my_email%"'
```

## Gérer la base de données
```bash
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
del migrations/*
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```