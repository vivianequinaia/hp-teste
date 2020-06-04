# HP Teste

Esse projeto é responsável por realizar compras de produtos utilizando a forma de pagamento cartão de crédito. 
Possibilita também todo o gerenciamento de estoque e venda de produtos.

##### Dependências
- docker
- docker-compose
___

## Instalação

1. Faça o clone do repositório com `git clone git@github.com:VivianeQuinaia/hp-teste.git`.
2. Copie o arquivo .env.dist para .env `cp .env.dist .env`.
3. Utilize o comando `docker-compose build` para realizar o pull das imagens.
4. Execute o comando `docker network create nome_da_network` em nome_da_network utilize o valor variável `NETWORK_DEFAULT` do seu .env;
5. Execute seus containers com o comando `docker-compose up -d`.
6. Entre no container do php `make php` e execute o comando `composer install`.
7. Ainda dentro do container do passo 6 execute o comando `php artisan key:generate` para gerar o valor da variável `APP_KEY`.
8. execute o comando `php artisan migrate` para criar as tabelas do banco de dados.
9. Dê permissão para leitura, escrita e esxecução para os arquivos da pastas `/storage`. `sudo chmod 777 -R storage/`.
___

## Documentação dos endpoints
A documentação dos endpoints foi feita utilizando Swagger se encontra nos arquivos:

[swagger.yaml](./storage/docs/swagger.yaml).

[changelog.json](./storage/docs/swagger.json).

Para visualizar você pode colocar o conteúdo do arquivo em [Swagger Editor](https://editor.swagger.io/)

Saiba mais sobre o swagger em: [Swagger](https://swagger.io/)
___

## Postman
A collection para acessar os endpoints via postman se encontram em:

[postman_collection](./storage/Hp_postman_collection.json).

Saiba mais sobre o postman em: [Postman](https://www.postman.com/)
___
## Testes unitários:

Para executar os testes unitários:

`make test`, fora do container ou, `./vendor/bin/phpunit tests/` após executar `make php`.
