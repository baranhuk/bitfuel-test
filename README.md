# Teste para Vaga Programador de Back-End PHP Pleno

## Descrição

Desafio sugerido:  
Crie uma API simples em PHP (sem framework), com conexão ao PostgreSQL ou MySQL, que permita:

- Cadastrar produtos com os seguintes dados: nome, código SKU, preço e quantidade em estoque  
- Listar todos os produtos  
- Atualizar a quantidade de estoque (incrementar ou reduzir)  
- Consultar o produto por SKU  

*A API deve retornar os dados em JSON.*

Além disso, crie um HTML simples com JavaScript puro (ou jQuery) que permita consultar um SKU e exibir o resultado na tela.

---

## Requisitos Técnicos

- Código organizado (pode ser disponibilizado em uma pasta ZIP ou repositório no GitHub)  
- Scripts SQL de criação da tabela  

---

## Tecnologias Utilizadas

- PHP (versão 8.3, sem frameworks)  
- Banco de Dados: MySQL  
- Frontend: HTML, CSS e jQuery   

---

## Como Rodar o Projeto
1. Clone o repositório

    ```bash
    git clone https://github.com/baranhuk/bitfuel-test.git
    ```
 
 2. Crie um banco de dados e importe a estrutura que se encontra na pasta dataBase
 
 3. Configure seu arquivo .env com base no arquivo .env.example

 4. Rode o Composer para instalar as dependências
    ```bash
    composer install
    ```
### Não se esqueça de informar a sua URL base no arquivo .env

### Observaçao 
Em ambientes de localhost, como Laragon, WampServer, XAMPP ou outros, quando o projeto estiver em uma subpasta, é necessário informar o caminho da subpasta no arquivo index.php em 

```php
    Router::dispatch();
```
***Exemplo***.
```php
    Router::dispatch("subpasta/");
```
Se estiver usando VirtualHost, como o suporte oferecido pelo Laragon, não é necessário informar a subpasta. O mesmo se aplica para containers Docker.

O projeto já possui uma estrutura para container, basta rodar o comando.
```bash
    docker compose up
```


## Rotas do projeto

### 1. GET /api/products?page={}&perPage={}  
**Descrição:** Retorna a lista de produtos.

**Paramentros:**

***page***: int.

***perPage***: int | Não obrigatório.

**Exemplo de Resposta - 200**

***Se não for encontados produros a resposta ainda e 200 mais o data estara vazio "data": []***:

```json
{
    "data": [
        {
            "id": 20,
            "name": "Alternador",
            "sku": "VEI-ELT-001",
            "cost": 389,
            "created_at": "2025-06-11 00:53:12",
            "updated_at": "2025-06-11 00:53:12"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 1,
        "total": 41,
        "last_page": 41
    }
}
```



### 2. POST /api/products 
**Descrição:** Novo Produto.

**Paramentros body:**

***name***: string.

***sku***: string.

***cost***: double.

***quantity***: double | Não obrigatório | Utiizado para criar o produro e ja realizar uma entrada no estoque.

**Exemplo de Requisição **

```json
 {
  "name": "Unidade de comando eletrônico (ECU)",
  "sku": "VEI-ELT-006",
  "cost": 1150.00,
  "quantity": 1
}
```

**Exemplo de Resposta - 201 **
```json
{
    "id": 42,
    "id_stock": 42,
    "name": "Unidade de comando eletrônico (ECU)",
    "cost": 1150,
    "quantity": 0,
    "sku": "VEI-ELT-006",
    "created_at": "2025-06-11 14:10:00",
    "updated_at": "2025-06-11 14:10:00",
    "stock_updated_at": "2025-06-11 14:10:00"
}
```


**Exemplo de Resposta - 409 **
```json
{
    "message": "Já existe um produto com esse código SKU"
}
```

**Exemplo de Resposta - 422 **
```json
{
    "message": "Erro de validação",
    "error": "O valor deve ser do tipo double ou int"
}
```

**Exemplo de Resposta - 500 **
```json
{
    "message": "Ocorreu um erro ao registrar a entrada do produto. Tente novamente mais tarde.",    
}
```

### 3. GET /api/products/sku?code={}
**Descrição:** Busca um Produto pelo código SKU.

**Paramentros:**

***code***: string.

**Exemplo de Resposta - 200 **
```json
{
    "data": {
        "id": 35,
        "id_stock": 35,
        "name": "Coxim do motor",
        "cost": 110,
        "quantity": 10,
        "sku": "VEI-MOT-007",
        "created_at": "2025-06-11 00:55:11",
        "updated_at": "2025-06-11 00:55:11",
        "stock_updated_at": "2025-06-11 00:55:11"
    }
}
```

**Exemplo de Resposta - 404 **
```json
{
    "message": "Produto não encontrado"
}
```

**Exemplo de Resposta - 422 **
```json
{
    "message": "Erro de validação",
    "error": "Informe o código SKU"
}
```

### 4. PUT /api/products/update-stock?id={}
**Descrição:** Atualiza a quantidade de um produto, incrementando ou decrementando.

**Paramentros:**
***id***: string.

**Paramentros body:**

***type***: string | Tipos são: E para entrada e S para saída.

***quantity***: double.


**Exemplo de Resposta - 200 **
```json
{  
    "type": "S",
    "quantity": 1
}
```

**Exemplo de Resposta - 201 **
```json
{
    "message": "Estoque atualizado com sucesso",
    "data": {
        "stock": {
            "id": 1,
            "product_id": 1,
            "quantity": 9,
            "updated_at": "2025-06-11 14:29:55"
        },
        "movement": {
            "id": 15,
            "product_id": 1,
            "quantity": 1,
            "type": "S",
            "date": "2025-06-11 14::55"
        }
    }
}
```

**Exemplo de Resposta - 404 **
```json
{
    "message": "Estoque não encontrado para o produto informado",
}
```

**Exemplo de Resposta - 422 **
```json
{
    "message": "Erro de validação",
    "error": "Tipo de movimentação inválido"
}
```

**Exemplo de Resposta - 500 **
```json
{
    "message": "Erro ao registrar a movimentação estoque não atualizado",
}
```

### 5. GET /products/sku
**Descrição:** Página de pesquisa de produto pelo códico sku.



### --------Bonus--------

### 1. GET /api/products/stock-movements?code={}
**Descrição:** Lista as movimentações dos últimos 30 dias de um produto no estoque.

**Paramentros:**
***code***: string.

**Exemplo de Resposta - 200 **
```json
{
    "id": 1,
    "id_stock": 1,
    "name": "Filtro de óleo",
    "cost": 25.9,
    "quantity": 9,
    "sku": "VEI-MOT-001",
    "created_at": "2025-06-11 00:45:31",
    "updated_at": "2025-06-11 00:45:31",
    "stock_updated_at": "2025-06-11 14:29:55",
    "movements": [      
        {
            "id": 13,
            "product_id": 1,
            "type": "S",
            "quantity": 1,
            "date": "2025-06-11 01:24:00"
        },
        {
            "id": 12,
            "product_id": 1,
            "type": "E",
            "quantity": 1,
            "date": "2025-06-11 01:02:00"
        },
        {
            "id": 1,
            "product_id": 1,
            "type": "E",
            "quantity": 10,
            "date": "2025-06-11 00:31:00"
        }
    ]
}
```
**Exemplo de Resposta - 404 **
```json
{
    "message": "Produto não encontrado"
}
```

**Exemplo de Resposta - 422 **
```json
{
    "message": "Erro de validação",
    "error": "Informe o código SKU"
}
```


### 2. GET products/stock-movements/recent
**Descrição:** Página das movimentações dos últimos 30.

