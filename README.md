

- Codeigniter 3
- Docker 
  * PHP 8.1
  * mysql 8.0


## Setup

Precisa do docker instalado no ambiente. Após instalar o docker, execute o comando na linha de comando do projeto

`docker compose up --build -d`

Adicione o composer ao seu projeto.

`composer install`

Acesse essa url para que seja geradas a migrações.

`http://localhost:7000/migrate`

Obs: caso as migrations não sejam criadas, rode esse comando na raiz do projeto `sudo chmod -R 775 .`

Contêineres e migrations criadas, agora precisa acessar o container do sistema para rodar os testes unitários. 

Entre no container

`docker exec -it ci3-app /bin/bash`

### Testes unitários.

Ainda dentro do container app, rode os teste através do comando abaixo. 

#### Obs: É importante rodar os testes antes de usar api. 

`vendor/bin/phpunit -c application/tests`


Acesso a api online com documentação

https://documenter.getpostman.com/view/3052887/2s9YR6atpf

---
### Opcionais
Acessar container do mysql

`docker exec -it ci3-db /bin/bash` 

Para acessar o banco utilize das seguintes configurações:

```
usuário: root
password: root
database: ezoom
serverhost: 82.180.136.89
porta: 33064
```
