<?php

// ==========================================
// SEGURANÇA E CONEXÃO
// ==========================================

include("php/protecao_admin.php");
include("php/conexao.php");


// ==========================================
// BUSCAR PACIENTES
// ==========================================

$sql = "
    SELECT *
    FROM pacientes
    ORDER BY nome ASC
";

$resultado = mysqli_query($conexao, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pacientes - MedCare</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <!-- GOOGLE FONT - POPPINS -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        /* ==========================================
           CONFIGURAÇÕES DA PÁGINA
        ========================================== */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f8fc;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }


        /* ==========================================
           CONTAINER
        ========================================== */

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 35px auto;
        }


        /* ==========================================
           TÍTULO
        ========================================== */

        .titulo-pagina {
            margin-bottom: 25px;
        }

        .titulo-pagina h1 {
            margin: 0 0 6px;
            color: #1976D2;
            font-size: 30px;
            font-weight: 700;
        }

        .titulo-pagina p {
            margin: 0;
            color: #666;
            font-size: 15px;
        }


        /* ==========================================
           CARDS
        ========================================== */

        .card-form {
            background: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
            margin-bottom: 25px;
        }

        .card-form h2 {
            margin: 0 0 22px;
            color: #1976D2;
            font-size: 21px;
            font-weight: 700;
        }


        /* ==========================================
           FORMULÁRIO
        ========================================== */

        .grid-formulario {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .grupo {
            display: flex;
            flex-direction: column;
        }

        .grupo label {
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 600;
            color: #444;
        }

        .grupo input,
        .grupo select {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #d5dce5;
            border-radius: 9px;
            background: #fff;
            color: #333;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .grupo input:focus,
        .grupo select:focus {
            border-color: #1976D2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.10);
        }

        .campo-completo {
            grid-column: 1 / -1;
        }


        /* ==========================================
           BOTÃO CADASTRAR
        ========================================== */

        .botao-form {
            margin-top: 22px;
        }

        .botao-form button {
            border: none;
            background: #1976D2;
            color: white;
            padding: 12px 22px;
            border-radius: 9px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .botao-form button:hover {
            background: #0D47A1;
            transform: translateY(-1px);
        }


        /* ==========================================
           TABELA
        ========================================== */

        .tabela-container {
            width: 100%;
            overflow-x: auto;
        }

        .tabela-pacientes {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
        }

        .tabela-pacientes thead {
            background: #1976D2;
            color: white;
        }

        .tabela-pacientes th {
            padding: 14px 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .tabela-pacientes td {
            padding: 13px 12px;
            border-bottom: 1px solid #edf0f4;
            font-size: 13px;
            vertical-align: middle;
        }

        .tabela-pacientes tbody tr {
            transition: 0.2s;
        }

        .tabela-pacientes tbody tr:hover {
            background: #f7fbff;
        }


        /* ==========================================
           AÇÕES
        ========================================== */

        .acoes {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
            white-space: nowrap;
        }

        .btn-alterar,
        .btn-excluir {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-alterar {
            background: #1976D2;
            color: white !important;
        }

        .btn-alterar:hover {
            background: #0D47A1;
            transform: translateY(-1px);
        }

        .btn-excluir {
            background: #dc3545;
            color: white !important;
        }

        .btn-excluir:hover {
            background: #b02a37;
            transform: translateY(-1px);
        }


        /* ==========================================
           TABELA VAZIA
        ========================================== */

        .mensagem-vazia {
            text-align: center !important;
            padding: 35px !important;
            color: #777;
        }


        /* ==========================================
           RESPONSIVIDADE
        ========================================== */

        @media (max-width: 800px) {

            .container {
                width: 94%;
                margin: 25px auto;
            }

            .grid-formulario {
                grid-template-columns: 1fr;
            }

            .campo-completo {
                grid-column: auto;
            }

            .titulo-pagina h1 {
                font-size: 25px;
            }

            .card-form {
                padding: 20px;
            }

        }

    </style>

</head>

<body>


<?php include("php/menu_admin.php"); ?>


<main class="container">


    <!-- ==========================================
         TÍTULO
    =========================================== -->

    <section class="titulo-pagina">

        <h1>👥 Pacientes</h1>

        <p>
            Cadastre e gerencie os pacientes do MedCare.
        </p>

    </section>


    <!-- ==========================================
         CADASTRO DE PACIENTE
    =========================================== -->

    <section class="card-form">

        <h2>➕ Cadastrar paciente</h2>

        <form
            action="salvarPaciente.php"
            method="POST"
        >

            <div class="grid-formulario">


                <!-- NOME -->

                <div class="grupo">

                    <label for="nome">
                        Nome *
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        required
                        placeholder="Nome completo"
                    >

                </div>


                <!-- CPF -->

                <div class="grupo">

                    <label for="cpf">
                        CPF *
                    </label>

                    <input
                        type="text"
                        id="cpf"
                        name="cpf"
                        maxlength="11"
                        required
                        placeholder="Somente números"
                    >

                </div>


                <!-- RG -->

                <div class="grupo">

                    <label for="rg">
                        RG
                    </label>

                    <input
                        type="text"
                        id="rg"
                        name="rg"
                        placeholder="RG do paciente"
                    >

                </div>


                <!-- DATA DE NASCIMENTO -->

                <div class="grupo">

                    <label for="data_nascimento">
                        Data de nascimento
                    </label>

                    <input
                        type="date"
                        id="data_nascimento"
                        name="data_nascimento"
                    >

                </div>


                <!-- SEXO -->

                <div class="grupo">

                    <label for="sexo">
                        Sexo
                    </label>

                    <select
                        id="sexo"
                        name="sexo"
                    >

                        <option value="">
                            Selecione
                        </option>

                        <option value="Masculino">
                            Masculino
                        </option>

                        <option value="Feminino">
                            Feminino
                        </option>

                        <option value="Outro">
                            Outro
                        </option>

                    </select>

                </div>


                <!-- TELEFONE -->

                <div class="grupo">

                    <label for="telefone">
                        Telefone
                    </label>

                    <input
                        type="text"
                        id="telefone"
                        name="telefone"
                        placeholder="Telefone"
                    >

                </div>


                <!-- E-MAIL -->

                <div class="grupo">

                    <label for="email">
                        E-mail *
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        placeholder="E-mail para login"
                    >

                </div>


                <!-- SENHA -->

                <div class="grupo">

                    <label for="senha">
                        Senha para acesso *
                    </label>

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        minlength="6"
                        required
                        placeholder="Mínimo 6 caracteres"
                    >

                </div>


                <!-- ENDEREÇO -->

                <div class="grupo campo-completo">

                    <label for="endereco">
                        Endereço
                    </label>

                    <input
                        type="text"
                        id="endereco"
                        name="endereco"
                        placeholder="Rua, número, bairro..."
                    >

                </div>


                <!-- CIDADE -->

                <div class="grupo">

                    <label for="cidade">
                        Cidade
                    </label>

                    <input
                        type="text"
                        id="cidade"
                        name="cidade"
                        placeholder="Cidade"
                    >

                </div>


                <!-- ESTADO -->

                <div class="grupo">

                    <label for="estado">
                        Estado
                    </label>

                    <input
                        type="text"
                        id="estado"
                        name="estado"
                        maxlength="2"
                        placeholder="UF"
                    >

                </div>


            </div>


            <!-- BOTÃO -->

            <div class="botao-form">

                <button type="submit">
                    ➕ Cadastrar paciente
                </button>

            </div>

        </form>

    </section>


    <!-- ==========================================
         PACIENTES CADASTRADOS
    =========================================== -->

    <section class="card-form">

        <h2>📋 Pacientes cadastrados</h2>


        <div class="tabela-container">

            <table class="tabela-pacientes">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nome</th>

                        <th>CPF</th>

                        <th>Telefone</th>

                        <th>E-mail</th>

                        <th>Ações</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                if (
                    $resultado &&
                    mysqli_num_rows($resultado) > 0
                ) {

                    while (
                        $paciente =
                        mysqli_fetch_assoc($resultado)
                    ) {

                ?>

                    <tr>

                        <!-- ID -->

                        <td>

                            <?php
                            echo (int)$paciente['id'];
                            ?>

                        </td>


                        <!-- NOME -->

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $paciente['nome'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </td>


                        <!-- CPF -->

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $paciente['cpf'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </td>


                        <!-- TELEFONE -->

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $paciente['telefone'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </td>


                        <!-- E-MAIL -->

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $paciente['email'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </td>


                        <!-- AÇÕES -->

                        <td>

                            <div class="acoes">

                                <a
                                    href="editarPaciente.php?id=<?php echo (int)$paciente['id']; ?>"
                                    class="btn-alterar"
                                >
                                    ✏️ Alterar
                                </a>


                                <a
                                    href="excluirPaciente.php?id=<?php echo (int)$paciente['id']; ?>"
                                    class="btn-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este paciente?');"
                                >
                                    🗑️ Excluir
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td
                            colspan="6"
                            class="mensagem-vazia"
                        >

                            Nenhum paciente cadastrado.

                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>

    </section>


</main>


</body>

</html>2