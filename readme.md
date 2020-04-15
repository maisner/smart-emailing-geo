# Sales points API

### Instalace a spuštění

```
cd docker
docker-compose build
docker-compose up -d
docker exec -it php-apache bash
./script/install.sh 
```
A nyní by měl být dostupný endopoint
http://localhost:8080/sales-point

### Spuštění testů
Testy je potřeba spuštět uvnitř docker kontejneru
```
docker exec -it php-apache bash
```

A poté příkazy 
- `composer build` - spustí PHPStan, PHP CS a všechny testy
- `composer tests` - spustí pouze testy

Další příkazy jsou definovány v `composer.json`

### ENV proměnné
- `DEBUG`
    - ve výchozím stavu je debug režim vypnut. Pro zapnutí nastavit `DEBUG=1`
- `APP_DATETIME_CURRENT`
    - např.: 2020-04-06 16:45:00
- `APP_CLIENT_IP`
- `APP_INSTANCE_NAME`
- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

### Endpoint
- `GET http://localhost:8080/sales-point`
    - Parametry :
        - `only_open` - vrátí pouze aktuálně otevřené prodejní místa. Hodnota parametru může být pouze pouze `1`, ostaní hodnoty se ignorují.
        - `timestamp` - nastaví čas pro filtraci právě otevřených prodejnách míst. Výchozí hodnota je aktuální datum a čas. Hodnota parametru je ve formátu Unix timestamp - https://www.unixtimestamp.com/
        - `sort_by` - Parameter pro řazení výsledku. Může nabývat následujích hodnot:
            - `id_asc` - Řazení vzestupně dle ID
            - `id_desc` - Řazení sestupně dle ID
            - `distance_asc` - Řazení vzestupně dle vzdálenosti od aktuálně nastavené IP.
            - `distance_desc` - Řazení sestupně dle vzdálenosti od aktuálně nastavené IP.
        - `ip` - Parametr pro defininování IP adresy, ze které se bude počítat vzdálenost od prodejních míst. Parametr přijímá ip adresu(např. `80.129.48.124`) nebo řetezec `current`, který nastaví aktuální adresu klienta. Parametr je povinný pro řazení `distance_asc` a `distance_desc`;
    - `http://localhost:8080/sales-point?ip=current&only_open=1&timestamp=1585770057`
