# Serviço de Autocomplete

Serviço para cadastro de vendas de produtos por lojas, onde haverá um serviço para 
cadastro das vendas via api, outro que exibe a timeline das compras e um último serviço 
capaz de sincronizar com outra api, buscando as compras já realizadas e trazendo para base de dados;

##  Serviço de inserção de dados

Compreende em 2 apis:
-  POST (http://172.22.0.5/sales): /sales que pode ser usado para cadastrar vendas realizadas 
-  GET (http://172.22.0.5/sales/timeline): /sales/timeline que é usado para recuperar todo histórico de vendas 
por ordem de compra do mais novo para o mais velho.
- GET (http://172.22.0.5/sales/sincronizacao): /sales/sincronizacao url usada para buscar dados de uma api das compras e persistir no banco de dados

Os serviços descritos acima rodam sobre um container PHP (autocomplete_insere_dados) se que comunica com container do banco de dados MySQL (autocomplete_banco_dados) e grava os dados no serviço do Elasticsearch que se encontra no container (autocomplete_elasticsearch);

## Serviço para consulta usado para criação de autocomplete

Comprende em uma única api: http://172.22.0.4/autocomplete?event=comprou (/autocomplete?event=comprou) onde após a query string (event=) pode ser passar o nome do evento da compra, podendo passar apenas parte do nome como co, com ou comprou-produto.

##  Instalação
Deve-se startar os container docker com comentando "docker-compose up --build -d" os seguintes containers devem ser startados

```
CONTAINER ID        IMAGE                                                 COMMAND                  CREATED             STATUS              PORTS                                            NAMES
62b287dcedd2        docker.elastic.co/elasticsearch/elasticsearch:6.5.4   "/usr/local/bin/dock…"   4 hours ago         Up 4 hours          0.0.0.0:9200->9200/tcp, 0.0.0.0:9300->9300/tcp   autocomplete_elasticsearch
0d58b3c4ddf9        autocomplete_php_insere                               "docker-php-entrypoi…"   4 hours ago         Up 4 hours          80/tcp, 9000/tcp                                 autocomplete_insere_dados
21c3c72a605d        autocomplete_php_consulta                             "docker-php-entrypoi…"   4 hours ago         Up 4 hours          80/tcp, 9000/tcp                                 autocomplete_consulta_dados
7dcc14529b46        mysql:5.6                                             "docker-entrypoint.s…"   5 hours ago         Up 4 hours          3306/tcp                                         autocomplete_banco_dados
```

Deve-se acessar o container do mysql 
```docker exec -it autocomplete_banco_dados bash```

Acessar diretório database
```cd /database & mysql -u root -p projeto < script_criacao_banco.sql```

Senha do banco de dados: root

## Testando aplicação
### Inserindo dados através da api: POST http://172.22.0.5/sales enviando os dados como json

```
{
    "events": [
        {
            "event": "comprou-produto",
            "timestamp": "2016-09-22T13:57:32.2311892-03:00",
            "custom_data": [
                {
                    "key": "product_name",
                    "value": "Camisa Azul"
                },
                {
                    "key": "transaction_id",
                    "value": "3029384"
                },
                {
                    "key": "product_price",
                    "value": 100
                }
            ]
        },
        {
            "event": "comprou",
            "timestamp": "2016-09-22T13:57:31.2311892-03:00",
            "revenue": 250,
            "custom_data": [
                {
                    "key": "store_name",
                    "value": "Patio Savassi"
                },
                {
                    "key": "transaction_id",
                    "value": "3029384"
                }
            ]
        },
        {
            "event": "comprou-produto",
            "timestamp": "2016-09-22T13:57:33.2311892-03:00",
            "custom_data": [
                {
                    "key": "product_price",
                    "value": 150
                },
                {
                    "key": "transaction_id",
                    "value": "3029384"
                },
                {
                    "key": "product_name",
                    "value": "Calça Rosa"
                }
            ]
        },
        {
            "event": "comprou-produto",
            "timestamp": "2016-10-02T11:37:35.2300892-03:00",
            "custom_data": [
                {
                    "key": "transaction_id",
                    "value": "3409340"
                },
                {
                    "key": "product_name",
                    "value": "Tenis Preto"
                },
                {
                    "key": "product_price",
                    "value": 120
                }
            ]
        },
        {
            "event": "comprou",
            "timestamp": "2016-10-02T11:37:31.2300892-03:00",
            "revenue": 120,
            "custom_data": [
                {
                    "key": "transaction_id",
                    "value": "3409340"
                },
                {
                    "key": "store_name",
                    "value": "BH Shopping"
                }
            ]
        }
    ]
}
```

A saída deverá ser ```{"flash":{},"message":"success"}```

### Usando api de consulta usando filtro por evento: GET http://172.22.0.4/autocomplete?event=comprou-
A saída deverá ser 
```
{
    "flash": {},
    "message": [
        {
            "event": "comprou-produto",
            "timestamp": "2016-09-22T13:57:33.2311892-03:00",
            "product_price": 150,
            "transaction_id": 3029384,
            "product_name": "Calça Rosa"
        },
        {
            "event": "comprou-produto",
            "timestamp": "2016-10-02T11:37:35.2300892-03:00",
            "product_price": 120,
            "transaction_id": 3409340,
            "product_name": "Tenis Preto"
        },
        {
            "event": "comprou-produto",
            "timestamp": "2016-09-22T13:57:32.2311892-03:00",
            "product_price": 100,
            "transaction_id": 3029384,
            "product_name": "Camisa Azul"
        }
    ]
}
```

### Usando a API para recuperar pipeline GET http://172.22.0.5/sales/timeline

A saída deverá ser
```
{
    "flash": {},
    "message": {
        "timeline": [
            {
                "timestamp": "2016-10-02 11:37:35",
                "revenue": "120.00",
                "transaction_id": "3409340",
                "store_name": "BH Shopping",
                "products": [
                    {
                        "name": "Tenis Preto",
                        "price": "120.00"
                    }
                ]
            },
            {
                "timestamp": "2016-09-22 13:57:33",
                "revenue": "250.00",
                "transaction_id": "3029384",
                "store_name": "Patio Savassi",
                "products": [
                    {
                        "name": "Camisa Azul",
                        "price": "100.00"
                    },
                    {
                        "name": "Calça Rosa",
                        "price": "150.00"
                    }
                ]
            }
        ]
    }
}
```

### URL para sincronização de informações onde se consulta dados de commpra de uma outra api e cadastra localmente
GET http://172.22.0.5/sales/sincronizacao

A saída deverá ser
```
{"flash":{},"message":"success"}
```


Para mais detalhes há um arquivo chamdao documentacao-api.json na raiz do projeto que trás mais detalhe de como fazer cada requisição;
