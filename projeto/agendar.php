
<?php

// =========================================================
// CONEXÃO
// =========================================================

include("php/conexao.php");


// =========================================================
// BUSCAR MÉDICOS
// =========================================================

$sqlMedicos = "
    SELECT
        medicos.id,
        medicos.nome,
        especialidades.nome AS especialidade
    FROM medicos
    LEFT JOIN especialidades
        ON medicos.especialidade_id = especialidades.id
    ORDER BY medicos.nome ASC
";

$resultadoMedicos = mysqli_query($conexao, $sqlMedicos);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agendar Consulta - MedCare</title>


    <!-- GOOGLE FONT -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <style>

        /* =====================================================
           RESET
        ===================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        /* =====================================================
           BODY
        ===================================================== */

        body {
            background: #f4f8fc;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }


        /* =====================================================
           CABEÇALHO
        ===================================================== */

        .header-principal {
            width: 100%;
            background: #ffffff;

            padding: 18px 7%;

            display: flex;
            align-items: center;
            justify-content: space-between;

            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);

            position: sticky;
            top: 0;
            z-index: 1000;
        }


        /* LOGO */

        .logo {
            color: #1976D2;
            font-size: 25px;
            font-weight: 800;
            text-decoration: none;
        }


        /* MENU */

        .menu-principal {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .menu-principal a {
            color: #444;
            text-decoration: none;

            font-size: 14px;
            font-weight: 500;

            transition: 0.3s;
        }

        .menu-principal a:hover {
            color: #1976D2;
        }


        /* BOTÃO ENTRAR */

        .btn-entrar {
            background: #1976D2;
            color: white !important;

            padding: 10px 20px;

            border-radius: 9px;

            font-weight: 600 !important;
        }

        .btn-entrar:hover {
            background: #125ca8 !important;
        }


        /* =====================================================
           CONTAINER
        ===================================================== */

        .container {
            width: 90%;
            max-width: 900px;

            margin: 50px auto;
        }


        /* =====================================================
           TÍTULO
        ===================================================== */

        .titulo {
            text-align: center;
            margin-bottom: 35px;
        }

        .titulo h1 {
            color: #1976D2;

            font-size: 32px;
            font-weight: 700;

            margin-bottom: 8px;
        }

        .titulo p {
            color: #777;
            font-size: 15px;
        }


        /* =====================================================
           CARD
        ===================================================== */

        .card {
            background: #ffffff;

            border-radius: 18px;

            padding: 35px;

            box-shadow:
                0 5px 25px rgba(0, 0, 0, 0.07);
        }

        .card h2 {
            color: #333;

            font-size: 21px;
            font-weight: 600;

            margin-bottom: 25px;
        }


        /* =====================================================
           FORMULÁRIO
        ===================================================== */

        .form-grid {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 20px;
        }


        .grupo {
            display: flex;
            flex-direction: column;
        }


        .grupo label {
            margin-bottom: 8px;

            font-size: 14px;
            font-weight: 600;

            color: #444;
        }


        .grupo input,
        .grupo select,
        .grupo textarea {

            width: 100%;

            padding: 13px 15px;

            border: 1px solid #d8e0e8;

            border-radius: 9px;

            outline: none;

            background: #ffffff;

            color: #333;

            font-family: 'Poppins', sans-serif;

            font-size: 14px;

            transition: 0.2s;
        }


        .grupo input:focus,
        .grupo select:focus,
        .grupo textarea:focus {

            border-color: #1976D2;

            box-shadow:
                0 0 0 3px rgba(25, 118, 210, 0.10);
        }


        .grupo textarea {

            min-height: 110px;

            resize: vertical;
        }


        .grupo-completo {
            grid-column: 1 / -1;
        }


        /* =====================================================
           BOTÃO
        ===================================================== */

        .botao {

            width: 100%;

            margin-top: 25px;

            padding: 14px 20px;

            border: none;

            border-radius: 9px;

            background: #1976D2;

            color: white;

            font-family: 'Poppins', sans-serif;

            font-size: 15px;

            font-weight: 600;

            cursor: pointer;

            transition: 0.3s;
        }


        .botao:hover {
            background: #125ca8;
        }


        /* =====================================================
           LINK VOLTAR
        ===================================================== */

        .voltar {

            display: block;

            width: fit-content;

            margin: 20px auto 0;

            color: #1976D2;

            text-decoration: none;

            font-size: 14px;

            font-weight: 500;
        }


        .voltar:hover {
            text-decoration: underline;
        }


        /* =====================================================
           RESPONSIVO
        ===================================================== */

        @media (max-width: 750px) {

            .header-principal {
                flex-direction: column;
                gap: 15px;

                padding: 18px 5%;
            }


            .menu-principal {
                flex-wrap: wrap;

                justify-content: center;

                gap: 15px;
            }


            .container {
                width: 94%;

                margin: 35px auto;
            }


            .form-grid {
                grid-template-columns: 1fr;
            }


            .grupo-completo {
                grid-column: auto;
            }


            .card {
                padding: 25px;
            }


            .titulo h1 {
                font-size: 27px;
            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     CABEÇALHO
========================================================= -->

<header class="header-principal">

    <a href="index.php" class="logo">
        🏥 MedCare
    </a>


    <nav class="menu-principal">

        <a href="index.php">
            Início
        </a>


        <a href="agendar.php">
            Agendar
        </a>


        <a href="sobre.php">
            Sobre
        </a>


        <a href="contato.php">
            Contato
        </a>


        <a href="login.php" class="btn-entrar">
            Entrar
        </a>

    </nav>

</header>


<!-- =========================================================
     CONTEÚDO
========================================================= -->

<main class="container">


    <div class="titulo">

        <h1>
            📅 Agendar consulta
        </h1>

        <p>
            Preencha seus dados e escolha o melhor horário para sua consulta.
        </p>

    </div>


    <div class="card">

        <h2>
            Dados da consulta
        </h2>


        <form
            action="salvarConsultaPaciente.php"
            method="POST"
        >


            <div class="form-grid">


                <!-- NOME -->

                <div class="grupo">

                    <label for="nome">
                        Nome completo
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Digite seu nome completo"
                        required
                    >

                </div>


                <!-- CPF -->

                <div class="grupo">

                    <label for="cpf">
                        CPF
                    </label>

                    <input
                        type="text"
                        id="cpf"
                        name="cpf"
                        placeholder="Digite seu CPF"
                        required
                    >

                </div>


                <!-- TELEFONE -->

                <div class="grupo">

                    <label for="telefone">
                        Telefone
                    </label>

                    <input
                        type="tel"
                        id="telefone"
                        name="telefone"
                        placeholder="(00) 00000-0000"
                        required
                    >

                </div>


                <!-- MÉDICO -->

                <div class="grupo">

                    <label for="medico_id">
                        Médico
                    </label>

                    <select
                        name="medico_id"
                        id="medico_id"
                        required
                    >

                        <option value="">
                            Selecione um médico
                        </option>


                        <?php

                        if (
                            $resultadoMedicos &&
                            mysqli_num_rows($resultadoMedicos) > 0
                        ) {

                            while (
                                $medico = mysqli_fetch_assoc(
                                    $resultadoMedicos
                                )
                            ) {

                        ?>

                            <option
                                value="<?php echo (int)$medico['id']; ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $medico['nome'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );


                                if (
                                    !empty(
                                        $medico['especialidade']
                                    )
                                ) {

                                    echo " - " .
                                        htmlspecialchars(
                                            $medico['especialidade'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );

                                }

                                ?>

                            </option>

                        <?php

                            }

                        } else {

                        ?>

                            <option value="">
                                Nenhum médico cadastrado
                            </option>

                        <?php

                        }

                        ?>

                    </select>

                </div>


                <!-- DATA -->

                <div class="grupo">

                    <label for="data_consulta">
                        Data
                    </label>

                    <input
                        type="date"
                        id="data_consulta"
                        name="data_consulta"
                        min="<?php echo date('Y-m-d'); ?>"
                        required
                    >

                </div>


                <!-- HORÁRIO -->

                <div class="grupo">

                    <label for="hora_consulta">
                        Horário
                    </label>

                    <input
                        type="time"
                        id="hora_consulta"
                        name="hora_consulta"
                        required
                    >

                </div>


                <!-- OBSERVAÇÃO -->

                <div class="grupo grupo-completo">

                    <label for="observacao">
                        Observação
                    </label>

                    <textarea
                        id="observacao"
                        name="observacao"
                        placeholder="Digite alguma observação, se necessário..."
                    ></textarea>

                </div>


            </div>


            <!-- BOTÃO -->

            <button
                type="submit"
                class="botao"
            >

                📅 Confirmar agendamento

            </button>


        </form>


        <a
            href="index.php"
            class="voltar"
        >
            ← Voltar para o início
        </a>


    </div>

</main>


</body>

</html>
