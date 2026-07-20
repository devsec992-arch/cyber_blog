unsetopt banghist

URL="http://localhost:8000/articles/2/comments"
CSFR_TOKEN="NNMK5KZA5HDqbSirCRMMywQbT8CvP2ldGP4Fw8xI"
NUM_REQUESTS=50
SESSION_COOKIE="laravel_session=eyJpdiI6IlgrbjdUcHpIMzV6L2xpM3hMOWVPM0E9PSIsInZhbHVlIjoiRCsyODczcXBzbVQzTms0Y0lyZ3ZpNmhkaTF6MjBDZzRLV1U4MzVIQWplMVo5b3FtY1E0ZjY0WXhRZ1hmNTJMSW5OUUVkZDlCQ3o3ZUhBRDJQTTVXWVRzakZPbExESU1YWkNIZ3dkWi9laUd6V2ZuckgzQzUwdU5XTERaU08wMHkiLCJtYWMiOiJkYmI3NDhmODk2NjQxNzI2NjYyMzY3NDc4OGI4Yzg2ODI2NjU4ZjViMGNkMTc4YmM5YzRlMDMzMDg0NWMwMWFiIiwidGFnIjoiIn0%3D%3D"

send_request() {
    local comment_number=$1
    # Single-line curl removes any potential multiline formatting errors
    curl -s -H "Cookie: $SESSION_COOKIE" -X POST -d "comment=Commento $1&_token=$CSFR_TOKEN" "$URL" 
}

for ((i=1; i<=NUM_REQUESTS; i++))
do
    send_request $i
done

wait
echo "Attacco DoS simulato completato!"
