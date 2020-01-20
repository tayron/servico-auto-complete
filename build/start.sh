#!/bin/sh

cd /var/www/html

# Senao existir o diretório vendor, baixa as dependencias do projeto
if [ -z "$(ls -A vendor)" ]; then
    echo
    echo "-----------------------------------------------------------"
    echo "BAIXANDO DEPENDÊNCIAS DO PROJETO --------------------------"
    echo "-----------------------------------------------------------"
    composer install -vv
    echo
fi

echo
echo "-----------------------------------------------------------"
echo "INICIALIZANDO SERVIÇO DO CRON -----------------------------"
echo "-----------------------------------------------------------"
service cron stop  && service cron start

echo
echo "-----------------------------------------------------------"
echo "SETANDO VIRTUAL HOST conexao_externa NO /etc/hosts --------"
echo "-----------------------------------------------------------"
netstat -nr | grep '^0\.0\.0\.0' | awk '{print $2 " conexao_externa"}' >> /etc/hosts
cat /etc/hosts

echo
echo "-----------------------------------------------------------"
echo "STARTANDO PHP e NGINX -------------------------------------"
echo "-----------------------------------------------------------"
php-fpm -D && nginx -g 'daemon off;'