<?php

// ==========================================
// SEGURANÇA E CONEXÃO
// ==========================================

include("php/protecao_admin.php");
include("php/conexao.php");


// ==========================================
// BUSCAR ESPECIALIDADES
// ==========================================

$sqlEspecialidades = "
    SELECT
        id,
        nome
    FROM especialidades
    ORDER BY nome ASC
";

$resultadoEspecialidades = mysqli_query(
    $conexao,
    $sqlEspecialidades
);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Médicos - MedCare</title>

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

        .tabela-medicos {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
        }

        .tabela-medicos thead {
            background: #1976D2;
            color: white;
        }

        .tabela-medicos th {
            padding: 14px 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .tabela-medicos td {
            padding: 13px 12px;
            border-bottom: 1px solid #edf0f4;
            font-size: 13px;
            vertical-align: middle;
        }

        .tabela-medicos tbody tr {
            transition: 0.2s;
        }

        .tabela-medicos tbody tr:hover {
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
           MENSAGEM VAZIA
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
    ========================================== -->

    <section class="titulo-pagina">

        <h1>👨‍⚕️ Médicos</h1>

        <p>
            Cadastre e gerencie os médicos do MedCare.
        </p>

    </section>


    <!-- ==========================================
         CADASTRO DE MÉDICO
    ========================================== -->

    <section class="card-form">

        <h2>➕ Cadastrar médico</h2>

        <form
            action="salvarMedico.php"
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
                        placeholder="Nome completo do médico"
                    >

                </div>


                <!-- CRM -->

                <div class="grupo">

                    <label for="crm">
                        CRM *
                    </label>

                    <input
                        type="text"
                        id="crm"
                        name="crm"
                        required
                        placeholder="Número do CRM"
                    >

                </div>


                <!-- ESPECIALIDADE -->

                <div class="grupo">

                    <label for="especialidade_id">
                        Especialidade *
                    </label>

                    <select
                        id="especialidade_id"
                        name="especialidade_id"
                        required
                    >

                        <option value="">
                            Selecione uma especialidade
                        </option>


                        <?php

                        if (
                            $resultadoEspecialidades &&
                            mysqli_num_rows($resultadoEspecialidades) > 0
                        ) {

                            while (
                                $especialidade =
                                mysqli_fetch_assoc(
                                    $resultadoEspecialidades
                                )
                            ) {

                        ?>

                            <option
                                value="<?php echo (int)$especialidade['id']; ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $especialidade['nome'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                ?>

                            </option>

                        <?php

                            }

                        } else {

                        ?>

                            <option value="">
                                Nenhuma especialidade cadastrada
                            </option>

                        <?php

                        }

                        ?>

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
                        placeholder="Telefone do médico"
                    >

                </div>


                <!-- E-MAIL -->

                <div class="grupo">

                    <label for="email">
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="E-mail do médico"
                    >

                </div>


            </div>


            <!-- BOTÃO -->

            <div class="botao-form">

                <button type="submit">
                    ➕ Cadastrar médico
                </button>

            </div>

        </form>

    </section>


    <!-- ==========================================
         MÉDICOS CADASTRADOS
    ========================================== -->

    <section class="card-form">

        <h2>📋 Médicos cadastrados</h2>


        <div class="tabela-container">

            <table class="tabela-medicos">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nome</th>

                        <th>CRM</th>

                        <th>Especialidade</th>

                        <th>Telefone</th>

                        <th>E-mail</th>

                        <th>Ações</th>

                    </tr>

                </thead>


                <tbody>


                <?php

                // ==========================================
                // BUSCAR MÉDICOS
                // ==========================================

                $sqlMedicos = "
                    SELECT
                        medicos.id,
                        medicos.nome,
                        medicos.crm,
                        medicos.telefone,
                        medicos.email,
                        especialidades.nome AS especialidade
                    FROM medicos
                    LEFT JOIN especialidades
                        ON medicos.especialidade_id =
                           especialidades.id
                    ORDER BY medicos.nome ASC
                ";

                $resultadoMedicos = mysqli_query(
                    $conexao,
                    $sqlMedicos
                );


                if (
                    $resultadoMedicos &&
                    mysqli_num_rows($resultadoMedicos) > 0
                ) {

                    while (
                        $medico =
                        mysqli_fetch_assoc(
                            $resultadoMedicos
                        )
                    ) {

                ?>

                    <tr>


                        <!-- ID -->

                        <td>

                            <?php
                            echo (int)$medico['id'];
                            ?>

                        </td>


                        <!-- NOME -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $medico['nome'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- CRM -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $medico['crm'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- ESPECIALIDADE -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $medico['especialidade']
                                ?? 'Não definida',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- TELEFONE -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $medico['telefone'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- E-MAIL -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $medico['email'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- AÇÕES -->

                        <td>

                            <div class="acoes">

                                <a
                                    href="editarMedico.php?id=<?php echo (int)$medico['id']; ?>"
                                    class="btn-alterar"
                                >
                                    ✏️ Alterar
                                </a>


                                <a
                                    href="excluirMedico.php?id=<?php echo (int)$medico['id']; ?>"
                                    class="btn-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este médico?');"
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
                            colspan="7"
                            class="mensagem-vazia"
                        >

                            Nenhum médico cadastrado.

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

</html>