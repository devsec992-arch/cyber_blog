#!/bin/bash

# URL della rotta da attaccare
URL="http://127.0.0.1:8000/articles/3/comments"

# TOKEN
CSRF_TOKEN="mgkT5i8sDa5JMKH9UoZfc4MVRvGIKB5bbBOrYx4X"

# Numero di richieste da inviare
NUM_REQUESTS=20

# COOKIE
SESSION_COOKIE="laravel_session=eyJpdiI6Imh4SzBVWXZxTGVZV2QrbWNUeFhqUEE9PSIsInZhbHVlIjoiandKTnBjZjEySnNjckd6WUVaSVcxeFFpTmNNY1lhK0p6RUZMWndsbGlSb0RkRmNCa3JDSmc4QUp4WThoTFU4bno3dStibTg2cjNnWStsajlWbmdlUTVmeXYrb3kvWURJQVFtZ1BRTEh2djZDTDNBS1FiVldDTmhLWkdhOGtBdGUiLCJtYWMiOiIzNTllMjI3MDQ0NmM3YWVlZDlmNDFiNjcwOTQ5MjU2MzNkMjc4NDczMTFhOWU1M2JjYWI4Yjg0ZDY4ZjMxODgxIiwidGFnIjoiIn0%3D"

# Funzione per eseguire la richiesta
send_request() {
    curl -s -H "Cookie: $SESSION_COOKIE" -X POST -d "content=questo un commento di prova con midd $1&_token=$CSRF_TOKEN" "$URL"
}

echo "Inizio attacco DoS simulato..."

# Loop per inviare tante richieste
for ((i=1; i<=NUM_REQUESTS; i++))
do
    send_request "$i"
    echo "Richiesta $i inviata"
done

echo "Attacco DoS simulato completato!"
