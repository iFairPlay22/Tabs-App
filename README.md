# MyTabs

# Se connecter sur le serveur de prod
```
heroku run bash -a my-bands-app
```

# Modifier un mot de passe de prod à la main
```
php bin/console security:encode-password 'my_pwd'
php bin/console doctrine:query:sql 'UPDATE user SET password = "my_encoded_pwd" WHERE email like "%my_email%"'
php bin/console doctrine:query:sql 'SELECT * FROM user WHERE email like "%my_email%"'
````

