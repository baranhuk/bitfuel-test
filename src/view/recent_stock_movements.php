<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Movimentação Dos Últimos 30 Dias De Um Produtos</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 40px;
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        h1 {
            color: #333;
        }

        .search-container {
            margin-top: 30px;
        }

        input[type="text"] {
            padding: 10px;
            width: 250px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            margin-left: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .resultado {
            flex: 1;
            /* Ocupa o restante do espaço disponível */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            /* cor clara */
            border-top: 8px solid #007bff;
            /* cor do spinner (azul) */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        .alert-error {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px;
            width: 100%;
            max-width: 600px;
            background: #F8D7DA;
            color: #721c24;
            border-radius: 8px;

        }

        /* Animação de rotação */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .produto-detalhe {
            width: 60%;
            /* Define a largura como 60% da tela */
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            font-family: Arial, sans-serif;
        }

        .produto-detalhe h2 {
            margin: 0 0 15px;
            font-size: 22px;
            color: #333;
        }

        .produto-detalhe table {
            width: 100%;
            border-collapse: collapse;
        }

        .produto-detalhe th,
        .produto-detalhe td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .produto-detalhe th {
            width: 40%;
            color: #666;
            font-weight: normal;
        }
    </style>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <h1>Consulta Movimentação dos Últimos 30 Dias de um Produtos</h1>

    <div class="search-container">
        <input type="text" id="codigo-sku" placeholder="Digite o código SKU do produto">
        <button class="btn-search" id="btn-search">Buscar</button>
    </div>
    <div class="resultado">


        <script>
            const spinner = `<div id="loading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(255,255,255,0.7); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div class="spinner"></div>
</div>`;
            $(document).ready(function() {
                $('#btn-search').on('click', function() {
                    const codigoSku = $("#codigo-sku").val();
                    if (codigoSku) {
                        const divResultado = $(".resultado");
                        const btnSearchElem = $('#btn-search');
                        const codigoSkuElem = $("#codigo-sku");
                        $.ajax({
                            url: '/api/products/stock-movements',
                            contentType: 'application/json',
                            type: 'GET',
                            data: {
                                code: codigoSku
                            },
                            headers: {
                                'Accept': 'application/json'
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                divResultado.html(spinner);
                                codigoSkuElem.prop('disabled', true);
                                btnSearchElem.prop('disabled', true);

                            },
                            success: function(resposta) {

                                tableView(resposta, divResultado);
                            },
                            error: function(xhr, status, erro) {
                                console.log(xhr);
                                const json = xhr.responseJSON;
                                var msg_error = '';

                                if (json) {
                                    if (json.message) {
                                        msg_error = json.message;
                                    }
                                    if (json.error) {
                                        msg_error += `<br>${json.error}`;
                                    }
                                }


                                divResultado.html(`<div class="alert-error">${msg_error}</div>`);

                            },
                            complete: function() {
                                codigoSkuElem.prop('disabled', false);
                                btnSearchElem.prop('disabled', false);
                            }
                        });

                    } else {
                        alert("Informe o código SKU")
                    }
                });
            });

            function tableView(json, divResultado) {
                const cost = new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format(json.cost);



                const createdAtFormatada = new Date(json.created_at).toLocaleDateString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                const updatedAtFormatada = new Date(json.updated_at).toLocaleDateString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                const stockUpdatedAtFormatada = new Date(json.stock_updated_at).toLocaleDateString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

                var table_movements = `
               
                <h3>Movimentações</h3>
                    
                <table>
                <thead>                    
                    <tr>
                        <th>Quantidade</th>
                        <th>Movimento</th>
                        <th>Data</th>
                    </tr>
                </thead>`;

                if (json.movements.length > 0) {
                    table_movements += `<tbody> `;
                    json.movements.map((m) => {
                        var moviment = (m.type == "E" ? "Entrada" : (m.type == "S" ? "Saída" : "---"));
                        const dateFormatada = new Date(m.date).toLocaleDateString('pt-BR', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                        table_movements += `
                        <tr>
                            <td>${m.quantity}</td>
                            <td>${moviment}</td>
                            <td>${dateFormatada}</td>
                        </tr>`;
                    });
                    table_movements += `</tbody>`;
                }
                table_movements += `</table>`;


                const details = `
                        <div class="produto-detalhe">
                        <h2>${json.name} <small>(SKU: ${json.sku})</small></h2>
                        <table>
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>${json.id}</td>
                                </tr>
                                
                                <tr>
                                    <th>Custo</th>
                                    <td>${cost}</td>
                                </tr>
                                <tr>
                                    <th>Quantidade</th>
                                    <td>${json.quantity}</td>
                                </tr>
                                <tr>
                                    <th>Criado em</th>
                                    <td>${createdAtFormatada}</td>
                                </tr>
                                <tr>
                                    <th>Atualizado em</th>
                                    <td>${updatedAtFormatada}</td>
                                </tr>
                                <tr>
                                    <th>Estoque atualizado em</th>
                                    <td>${stockUpdatedAtFormatada}</td>
                                </tr>
                            </tbody>
                        </table>
                            ${table_movements}
                    </div>
                </div>
            `
                divResultado.html(details);
            }
        </script>
</body>

</html>