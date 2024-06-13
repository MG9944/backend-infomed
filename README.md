
 - podniesienie dockera
```sh
docker-compose up
```
 - instalujemy projekt
```sh
docker exec -it infomed-php-fpm bash -c "composer install"
```
 - instalujemy bazę
```sh
docker exec -it infomed-php-fpm bash -c "php bin/console doctrine:schema:create"
```
 - tworzymy domyślnych użytkowników
```sh
docker exec -it infomed-php-fpm bash -c "php bin/console doctrine:fixtures:load"
```
- generujemy klucze SSL JWT
```sh
docker exec -it infomed-php-fpm bash -c "php bin/console lexik:jwt:generate-keypair"
```
- jeżeli została zrobiona aktualizacja z wersji symfony 4.4 do 5.4 wymagane jest na nowo wygenerowanie kluczy
```sh
docker exec -it infomed-php-fpm bash -c "php bin/console lexik:jwt:generate-keypair --overwrite"
```


## Baza
 - 192.168.222.102:3306 lub localhost:9901
 - login/passwd: infomed/infomed


 - jeśli występuje problem z bazą danych, nalezy wykonać poniższe komendy
```sh
docker exec -it infomed-php-fpm bash -c "php bin/console doctrine:database:drop --force"
```
```sh
docker exec -it infomed-php-fpm bash -c "php bin/console doctrine:database:create"
```
```sh
docker exec -it infomed-php-fpm bash -c "php bin/console doctrine:schema:create"
```
```sh
docker exec -it infomed-php-fpm bash -c "php bin/console doctrine:fixtures:load"
```
