<h1 align="center">API Controle Financeiro Alura</h1>

![Badge Produção](http://img.shields.io/static/v1?label=STATUS&message=PRODUÇÃO&color=dark&style=for-the-badge)
![Badge versão do php](https://img.shields.io/static/v1?label=PHP&message=8.0.14&color=blue&style=for-the-badge&logo=php)
![Badge versão do php](https://img.shields.io/static/v1?label=MYSQL&message=8.0.27&color=blue&style=for-the-badge&logo=mysql)
![Badge versão do php](https://img.shields.io/static/v1?label=LUMEN&message=8.0.14&color=orange&style=for-the-badge&logo=lumen)
![Badge versão do php](https://img.shields.io/static/v1?label=PHPSTORM&message=2021.3.1&color=blue&style=for-the-badge&logo=phpstorm)

## Objetivo

Criar uma API Rest que faça o controle de Receitas e Despesas e gere relatório para controle finaneiro pessoal.

## Demonstração

Para testar as funcionalidades da api poderá ser utilizado o
link <a heref="http://finances-control-api.gilbert.dev.br">http://finances-control-api.gilbert.dev.br </a> seguindo os
padrões de endpoints da documentação!

## Dúvidas/Contato

As dúvidas e solicitações, relacionadas ao acesso da API, devem ser enviadas para o
e-mail <a href="mailto:suporte@gilbert.dev.br">suporte@gilbert.dev.br</a>.

### Configurando o projeto para produção

Copie o arquivo ".env.example" para ".env". <br>

```
APP_NAME="Api Controle Financeiro"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

**Atribua as seguinte modificações no ".env" criado.**

* `APP_ENV=production` projeto estára em modo produção.
* `APP_DEBUG=false`  desliga as mensagens de erro do framework para o usuário final.
* `APP_TIMEZONE=America/Sao_Paulo` altera o timezone para padrão America/São_Paulo.
* `DB_CONNECTION=[Tipo de banco que será utilizado]` exemplo `DB_CONNECTION=sqlite`.
* `DB_HOST=[IP do banco de dados]` ip no qual o banco de dados está disponibilizado.
* `DB_PORT=[PORTA do banco de dados]` porta no qual o banco de dados está disponibilizado.
* `DB_DATABASE=[nome do banco de dados]` nome do banco de dados.
* `DB_USERNAME=[usuário do banco de dados]` nome do usuário do banco de dados.
* `DB_PASSWORD=[senha do banco de dados]` senha do banco de dados.

### Métodos

As requisições para a API devem seguir os padrões:

| Método   | Descrição                                             |
|:---------|-------------------------------------------------------|
| `GET`    | Retorna informações de um ou mais registros.          |
| `POST`   | Utilizado para criar unm novo registro.               |
| `PUT`    | Atualiza dados de um registro ou altera sua situação. |
| `DELETE` | Remove um registro do sistema.                        |

### Respostas

| Código | Descrição                                                                  |
|:-------|----------------------------------------------------------------------------|
| `200`  | Requisição executada com sucesso.                                          |
| `201`  | Recurso cadastrado.                                                        |
| `204`  | Registro pesquisado não encontrado.                                        |
| `404`  | Registro pesquisado não encontrado. (Referente a interação com o recurso). |
| `422`  | Campos não válidos para requisição.                                        |
| `500`  | Erro interno no servidor.                                                  |

# Grupo de Recursos

***

## Cadatro de usuário [/register]

Para o cadastro é necessário:

- Nome do usuário.
- E-mail do usuário.
- Senha do usuário.

### Cadastrar [POST]

+ API endopint
    + `register`
+ Request (/application/json)
    + Body
        ```json
        {
          "name": "Nome do usuário.",
          "email": "Email do usuário.",
          "password": "Senha do usuário."
        }
        ```
+ Response 201 (application/json) <br/>
    ```json
    {
      "access_token": "Token JWT"
    }
    ```
+ Response 422 (application/json) <br/>
    ```json
    {
      "campo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ],
      "OutroCampo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ]
    }
    ```

## Login de usuário [/login]

Para o login é necessário:

- E-mail do usuário.
- Senha do usuário.

### Login [POST]

+ API endopint
    + `login`
+ Request (/application/json)
    + Body
        ```json
        {
          "email": "Email do usuário.",
          "password": "Senha do usuário."
        }
        ```
+ Response 200 (application/json) <br/>
    ```json
    {
      "access_token": "Token JWT"
    }
    ```
+ Response 422 (application/json) <br/>
    ```json
    {
      "campo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ],
      "OutroCampo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ]
    }
    ```

## Receitas [/receitas]

As receitas são todos os ganhos com aplicações financeiras ou qualquer rendimento.

### Listar [GET]

+ API endopint
    + `receitas`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Response 200 (application/json) <br/>
    ```json
    [
      {
        "id": "Id da receita",
        "description": "Descrição da receita",
        "value": "Valor da receita",
        "date": "data da receita"
      },
      {
        "id": "Id da receita.",
        "description": "Descrição da receita.",
        "value": "Valor da receita.",
        "date": "data da receita."
      }
    ]
    ```

### Cadastrar [POST]

+ API endopint
    + `receitas`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Request (/application/json)
    + Body
        ```json
        {
          "description": "Descrição da receita.",
          "value": "Valor da receita. (Ex.: 150.50).",
          "date": "Data da receita (Ex.: 2022-01-13)."
        }
        ```
+ Response 201 (application/json) <br/>
    ```json
    {
      "id": "Id da receita cadastrada.",
      "description": "Descrição da receita cadastrada.",
      "value": "Valor da receita cadastrada.",
      "date": "data da receita cadastrada."
    }
    ```
+ Response 422 (application/json) <br/>
    ```json
    {
      "campo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ],
      "OutroCampo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ]
    }
    ```

### Buscar por Ano e Mês [GET]

+ API endopint
    + `receitas/{ano}/{mes}`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Response 200 (application/json) <br/>
    ```json
    [
      {
        "id": "Id da receita",
        "description": "Descrição da receita",
        "value": "Valor da receita",
        "date": "data da receita"
      },
      {
        "id": "Id da receita.",
        "description": "Descrição da receita.",
        "value": "Valor da receita.",
        "date": "data da receita."
      }
    ]
    ```
+ Response 204 (application/json) <br/>
  O body da resposta é retornada vazia.

### Detalhar [GET]

+ API endopint
    + `receitas/{id}`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Response 200 (application/json) <br/>
    ```json
    {
      "id": "Id da receita cadastrada.",
      "description": "Descrição da receita cadastrada.",
      "value": "Valor da receita cadastrada.",
      "date": "data da receita cadastrada."
    }
    ```
+ Response 204 (application/json) <br/>
  O body da resposta é retornada vazia.

### Editar [PUT]

+ API endopint
    + `receitas/{id}`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Request (/application/json)
    + Body
      ```json
      {
        "description": "Descrição da receita.",
        "value": "Valor da receita. (Ex.: 150.50).",
        "date": "Data da receita (Ex.: 2022-01-13)."
      }
      ```
+ Response 200 (application/json) <br/>
    ```json
    {
      "id": "Id da receita editada.",
      "description": "Descrição da receita editada.",
      "value": "Valor da receita editada.",
      "date": "data da receita editada."
    }
    ```
+ Response 404 (application/json) <br/>
    ```json
    {
      "error": "Receita não encontrada!"
    }
    ```
+ Response 422 (application/json) <br/>
    ```json
    {
      "campo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ],
      "OutroCampo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ]
    }

### Remover [DELETE]

+ API endopint
    + `receitas/{id}`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Response 200 (application/json) <br/>
    ```json
    {
      "success": "Receita removida com sucesso!"
    }
    ```
+ Response 404 (application/json) <br/>
    ```json
    {
      "error": "Receita não encontrada!"
    }
    ```

## Despesas [/despesas]

As despesas são todos os gastos com aplicações financeiras ou qualquer outro custo.

### Listar [GET]

+ API endopint
    + `despesas`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Response 200 (application/json) <br/>
    ```json
    [
      {
        "id": "Id da despesa",
        "description": "Descrição da despesa",
        "value": "Valor da despesa",
        "date": "data da despesa"
      },
      {
        "id": "Id da despesa.",
        "description": "Descrição da despesa.",
        "value": "Valor da despesa.",
        "date": "data da despesa."
      }
    ]
    ```

### Cadastrar [POST]

+ API endopint
    + `despesas`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Request (/application/json)
    + Body
        ```json
        {
          "description": "Descrição da despesa.",
          "value": "Valor da despesa. (Ex.: 150.50).",
          "date": "Data da despesa (Ex.: 2022-01-13)."
        }
        ```
+ Response 201 (application/json) <br/>
    ```json
    {
      "id": "Id da despesa cadastrada.",
      "description": "Descrição da despesa cadastrada.",
      "value": "Valor da despesa cadastrada.",
      "date": "data da despesa cadastrada."
    }
    ```
+ Response 422 (application/json) <br/>
    ```json
    {
      "campo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ],
      "OutroCampo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ]
    }
    ```

### Buscar por Ano e Mês [GET]

+ API endopint
    + `despesas/{ano}/{mes}`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Response 200 (application/json) <br/>
    ```json
    [
      {
        "id": "Id da despesa.",
        "description": "Descrição da despesa.",
        "value": "Valor da despesa.",
        "date": "data da despesa."
      },
      {
        "id": "Id da despesa.",
        "description": "Descrição da despesa.",
        "value": "Valor da despesa.",
        "date": "data da despesa."
      }
    ]
    ```
+ Response 204 (application/json) <br/>
  O body da resposta é retornada vazia.

### Detalhar [GET]

+ API endopint
    + `despesas/{id}`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Response 200 (application/json) <br/>
    ```json
    {
      "id": "Id da despesa cadastrada.",
      "description": "Descrição da despesa cadastrada.",
      "value": "Valor da despesa cadastrada.",
      "date": "data da despesa cadastrada."
    }
    ```
+ Response 204 (application/json) <br/>
  O body da resposta é retornada vazia.

### Editar [PUT]

+ API endopint
    + `despesas/{id}`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Request (/application/json)
    + Body
      ```json
      {
        "description": "Descrição da despesa.",
        "value": "Valor da despesa. (Ex.: 150.50).",
        "date": "Data da despesa (Ex.: 2022-01-13)."
      }
      ```
+ Response 200 (application/json) <br/>
    ```json
    {
      "id": "Id da despesa editada.",
      "description": "Descrição da despesa editada.",
      "value": "Valor da despesa editada.",
      "date": "data da despesa editada."
    }
    ```
+ Response 404 (application/json) <br/>
    ```json
    {
      "error": "Despesa não encontrada!"
    }
    ```
+ Response 422 (application/json) <br/>
    ```json
    {
      "campo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ],
      "OutroCampo": [
        "Validação no qual o request não aprovou.",
        "Validação no qual o request não aprovou."
      ]
    }

### Remover [DELETE]

+ API endopint
    + `despesas/{id}`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Response 200 (application/json) <br/>
    ```json
    {
      "success": "Despesa removida com sucesso!"
    }
    ```
+ Response 404 (application/json) <br/>
    ```json
    {
      "error": "Despesa não encontrada!"
    }
    ```

## Resumo Mensal [/resumo/{ano}/{mes}]

O resumo do mês contem as seguintes informações:

- Valor total das receitas no mês.
- Valor total das despesas no mês.
- Saldo final no mês.
- Valor total gasto no mês em cada uma das categorias.

### Listar [GET]

+ API endopint
    + `despesas/{ano}/{mes}`
+ headers
    + Authorization
        + `"Bearer <Inserir o JWT Token>"`
+ Response 200 (application/json) <br/>
    ```json
    {
      "receitas": "Valor total de receitas",
      "despesas": "Valor total de despesas",
      "saldo": "Saldo (receitas - despesas)",
      "gastos": {
        "Alimentação": "Valor total",
        "Saúde": "Valor total",
        "Moradia": "Valor total",
        "Transporte": "Valor total",
        "Educação": "Valor total",
        "Lazer": "Valor total",
        "Imprevistos": "Valor total",
        "Outras": "Valor total"
      }
    }
    ```

# Contribuições

Qualquer sugestão é bem vinda! Fique avontade para propor mudanças ou melhorias.

# Licença

Desenvolvido por <a href="mailto:contato@gilbert.dev.br">@gilbert-oliveira</a>.
