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
    git clone https://github.com/seuusuario/seuprojeto.git
 
 2. Crie um banco de dados e importe a estrutura que se encontra na pasta dataBase
 
 3. Configure seu arquivo .env com base no arquivo .env.example

 4. Rode o Composer para instalar as dependências
    ```bash
    composer install

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


### 1. GET /api/products?page={}&perPage={}  
**Descrição:** Retorna a lista de produtos.

**Paramentros:**

***page***: int.

***perPage***: int | Não obrigatório.

**Exemplo de Resposta - 200**
***Se não for encontados produros a resposta ainda e 200 mais o data estara vazio "data": []***: