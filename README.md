# API Liven
---
## Descrição
Desenvolveimento de uma API HTTP com cadastro e controle de usuários, cadastro de endereços e autenticação com JWT.

## Tecnologias utilizadas:
- Framework: Laravel, versão 10
- Linguagem: PHP, versão 8.2
- Banco de dados: MySQL, versão 8.0.36
- Autenticação: JWT
- Testes automatizados: PHPUnit
- Collection: Insomnia

## Pré-requisitos
Antes de começar, garanta que você realizou os seguintes requisitos:

1. Instalação do Composer de https://getcomposer.org/download/
2. Instalação do Apache (Xampp) para usar o PHP de https://sourceforge.net/projects/xampp/files/
3. Descomente as seguintes linhas no arquivo php.ini removendo o ponto e vírgula ("`;`"):
```
extension=pdo_mysql
extension=zip
```
4. Instale o MySQL (Workbench e Server) e crie um banco para armazenar os dados através do link: https://dev.mysql.com/downloads/workbench/
   - As credenciais serão usadas no arquivo .env

## Instalação do projeto:
Para instalar o projeto, siga as seguintes etapas:

1- Clone o repositório:
```
git clone https://github.com/Jonathanmoreiraa/api-liven.git
```
2- Navegue até o diretório do projeto e instale as dependências: 
 ```
 composer install
 ```

## Inicialização do projeto
1- Renomeie o arquivo ```.env.example``` para ```.env``` e modifique as informações de acordo com seu projeto e banco de dados. Um exemplo com configuração de banco de dados:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3006
DB_DATABASE=liven
DB_USERNAME=root
DB_PASSWORD=root
```
2- Rode as migrations com o comando:
```
php artisan migrate
```
3- Gere uma chave para a aplicação:
```
php artisan key:generate --ansi
```
4- Gere um chave para a verificação e autenticação JWT:
```
php artisan jwt:secret
```
5- Inicie o servidor:
```
php artisan serve --port=8000
```
Observação: o comando acima foi utilizado para inicializar o servidor na porta 8000, o projeto foi desenvolvido nesta porta e a utiliza como padrão, caso seja necessário utilizar outra porta o `servers` deve ser editado no `storage/api-docs/swagger.yaml` e `storage/api-docs/swagger.json` para utilizar o swagger.

## Para utilizar a API
O projeto foi criado junto ao swagger `darkaonline/l5-swagger`, sendo assim, pode ser testado através de uma documentação com swagger que pode ser acessada através do link: http://localhost:8000/api/documentation.

Além do swagger, existe um arquivo chamado `collection.json` que pode ser importado no Insomnia para testar em um ambiente para teste de APIs. Para importar, basta seguir os passos da documentação: https://docs.insomnia.rest/insomnia/import-export-data.

Caso a porta esteja diferente de 8000 e seja optado por testar no insomnia, o `Base Environment` deve ser editado conforme o endereço. Junto ao endereço, o token é adicionado no `Base Environment` (o que facilita o teste das outras rotas), as informações devem ficar semelhantes a imagem abaixo:

![image](https://github.com/user-attachments/assets/4b0938b1-6dcd-4b3c-b10e-eb7b29726651)

## Licença
Este projeto possui a licença MIT.

## Meu linkedin
Fique à vontade para se conectar comigo no linkedin.
* LinkedIn: [Jonathan Moreira](https://www.linkedin.com/in/jonathanmoreira1/)