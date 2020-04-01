# Sales points API

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
