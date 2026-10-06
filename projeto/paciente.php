<?php

// ==========================================================
// MEDCARE - ÁREA DO PACIENTE
// ==========================================================


// ==========================================================
// PROTEÇÃO
// ==========================================================

include("php/protecao_paciente.php");


// ==========================================================
// CONEXÃO
// ==========================================================

include("php/conexao.php");


// ==========================================================
// DADOS DO USUÁRIO LOGADO
// ==========================================================

$usuario_id = $_SESSION['usuario_id'] ?? null;

$usuario_nome = $_SESSION['usuario_nome'] ?? 'Paciente';


// ==========================================================
// VERIFICAR USUÁRIO
// ==========================================================

if (!$usuario_id) {

    header("Location: login.php");
    exit;
}


// ==========================================================
// BUSCAR DADOS DO PACIENTE
// ==========================================================

$sql = "
    SELECT
        id,
        usuario_id,
        nome,
        cpf,
        rg,
        data_nascimento,
        sexo,
        telefone,
        email,
        endereco,
        cidade,
        estado
    FROM pacientes
    WHERE usuario_id = ?
    LIMIT 1
";


$stmt = mysqli_prepare($conexao, $sql);


if (!$stmt) {

    die(
        "Erro ao preparar consulta: " .
        mysqli_error($conexao)
    );
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $usuario_id
);


mysqli_stmt_execute($stmt);


$resultado = mysqli_stmt_get_result($stmt);


// ==========================================================
// VERIFICAR PACIENTE
// ==========================================================

if (mysqli_num_rows($resultado) === 0) {

    $paciente = null;

} else {

    $paciente = mysqli_fetch_assoc($resultado);
}


mysqli_stmt_close($stmt);


// ==========================================================
// FUNÇÃO PARA EXIBIR TEXTO COM SEGURANÇA
// ==========================================================

function mostrar($valor)
{
    if ($valor === null || $valor === '') {
        return 'Não informado';
    }

    return htmlspecialchars(
        $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}


// ==========================================================
// FORMATAR DATA DE NASCIMENTO
// ==========================================================

$data_nascimento_formatada = 'Não informado';


if (
    $paciente &&
    !empty($paciente['data_nascimento']) &&
    $paciente['data_nascimento'] !== '0000-00-00'
) {

    $data = DateTime::createFromFormat(
        'Y-m-d',
        $paciente['data_nascimento']
    );


    if ($data) {

        $data_nascimento_formatada =
            $data->format('d/m/Y');
    }
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meu Perfil - MedCare</title>


    <!-- ==================================================
         GOOGLE FONTS
    ================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- ==================================================
         CSS PRINCIPAL
    ================================================== -->

    <link
        rel="stylesheet"
        href="css/style.css"
    >


    <!-- ==================================================
         CSS DA ÁREA DO PACIENTE
    ================================================== -->

    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: 'Poppins', sans-serif;

            background: #f4f8fc;

            color: #333;
        }


        /* ==================================================
           HEADER
        ================================================== */

        .header-paciente {

            background: #ffffff;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 16px 45px;

            box-shadow:
                0 2px 12px rgba(0, 0, 0, 0.08);

            position: sticky;

            top: 0;

            z-index: 1000;
        }


        .logo-paciente {

            display: flex;

            align-items: center;

            gap: 10px;

            text-decoration: none;

            color: #1976d2;

            font-size: 24px;

            font-weight: 800;
        }


        .logo-paciente span {

            font-size: 27px;
        }


        .menu-paciente {

            display: flex;

            align-items: center;

            gap: 8px;

            flex-wrap: wrap;
        }


        .menu-paciente a {

            text-decoration: none;

            color: #444;

            font-size: 14px;

            font-weight: 500;

            padding: 9px 13px;

            border-radius: 8px;

            transition: 0.2s;
        }


        .menu-paciente a:hover {

            background: #eef6ff;

            color: #1976d2;
        }


        .menu-paciente .ativo {

            background: #1976d2;

            color: white;
        }


        .menu-paciente .sair {

            background: #fce8e8;

            color: #d32f2f;
        }


        .menu-paciente .sair:hover {

            background: #d32f2f;

            color: white;
        }


        /* ==================================================
           CONTAINER
        ================================================== */

        .container-paciente {

            width: 92%;

            max-width: 1200px;

            margin: 35px auto 60px;
        }


        /* ==================================================
           BOAS-VINDAS
        ================================================== */

        .boas-vindas {

            background:
                linear-gradient(
                    135deg,
                    #1976d2,
                    #42a5f5
                );

            color: white;

            border-radius: 18px;

            padding: 35px;

            margin-bottom: 25px;

            box-shadow:
                0 8px 25px
                rgba(25, 118, 210, 0.20);
        }


        .boas-vindas h1 {

            margin: 0 0 8px;

            font-size: 28px;
        }


        .boas-vindas p {

            margin: 0;

            font-size: 15px;

            opacity: 0.95;
        }


        /* ==================================================
           CARDS
        ================================================== */

        .cards-acoes {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;

            margin-bottom: 25px;
        }


        .card-acao {

            background: white;

            border-radius: 15px;

            padding: 25px;

            text-decoration: none;

            color: #333;

            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.06);

            border: 1px solid #edf1f5;

            transition: 0.25s;

            display: flex;

            align-items: center;

            gap: 18px;
        }


        .card-acao:hover {

            transform: translateY(-3px);

            box-shadow:
                0 8px 22px
                rgba(0, 0, 0, 0.10);

            border-color: #1976d2;
        }


        .icone-acao {

            width: 58px;

            height: 58px;

            border-radius: 13px;

            background: #eaf4ff;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 27px;

            flex-shrink: 0;
        }


        .card-acao h3 {

            margin: 0 0 5px;

            color: #1976d2;

            font-size: 17px;
        }


        .card-acao p {

            margin: 0;

            color: #777;

            font-size: 13px;
        }


        /* ==================================================
           CARD
        ================================================== */

        .card {

            background: white;

            border-radius: 15px;

            padding: 28px;

            margin-bottom: 25px;

            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.06);
        }


        .card-titulo {

            display: flex;

            align-items: center;

            gap: 10px;

            margin-bottom: 22px;
        }


        .card-titulo h2 {

            margin: 0;

            font-size: 21px;

            color: #222;
        }


        .linha-dados {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 18px;
        }


        .dado {

            background: #f8fafc;

            border-radius: 10px;

            padding: 14px 16px;

            border: 1px solid #edf1f5;
        }


        .dado label {

            display: block;

            font-size: 12px;

            color: #777;

            margin-bottom: 4px;

            font-weight: 500;
        }


        .dado strong {

            display: block;

            font-size: 14px;

            color: #333;

            word-break: break-word;
        }


        /* ==================================================
           AVISO
        ================================================== */

        .aviso {

            background: #fff8e1;

            border: 1px solid #ffe082;

            color: #795548;

            padding: 20px;

            border-radius: 12px;

            line-height: 1.6;
        }


        .aviso strong {

            display: block;

            margin-bottom: 5px;
        }


        /* ==================================================
           RODAPÉ
        ================================================== */

        .footer-paciente {

            text-align: center;

            color: #888;

            font-size: 13px;

            padding: 20px;
        }


        /* ==================================================
           RESPONSIVIDADE
        ================================================== */

        @media (max-width: 850px) {

            .header-paciente {

                flex-direction: column;

                gap: 15px;

                padding: 16px 20px;
            }


            .menu-paciente {

                justify-content: center;
            }


            .cards-acoes {

                grid-template-columns: 1fr;
            }


            .linha-dados {

                grid-template-columns: 1fr;
            }
        }


        @media (max-width: 500px) {

            .container-paciente {

                width: 94%;

                margin-top: 20px;
            }


            .boas-vindas {

                padding: 25px;
            }


            .boas-vindas h1 {

                font-size: 23px;
            }


            .card {

                padding: 20px;
            }


            .menu-paciente a {

                font-size: 12px;

                padding: 8px 9px;
            }
        }

    </style>

</head>


<body>


<!-- =====================================================
     CABEÇALHO
===================================================== -->

<header class="header-paciente">

    <a
        href="paciente.php"
        class="logo-paciente"
    >

        <span>🏥</span>

        MedCare

    </a>


    <nav class="menu-paciente">

        <a href="index.php">
            🏠 Início
        </a>


        <a
            href="paciente.php"
            class="ativo"
        >
            👤 Meu perfil
        </a>


        <a href="agendar.php">
            📅 Agendar consulta
        </a>


        <a href="minhas_consultas.php">
            📋 Minhas consultas
        </a>


        <a href="sobre.php">
            ℹ️ Sobre
        </a>


        <a href="contato.php">
            📞 Contato
        </a>


        <a
            href="php/logout.php"
            class="sair"
        >
            🚪 Sair
        </a>

    </nav>

</header>


<!-- =====================================================
     CONTEÚDO
===================================================== -->

<main class="container-paciente">


    <!-- =================================================
         BOAS-VINDAS
    ================================================== -->

    <section class="boas-vindas">

        <h1>
            Olá, <?= mostrar($usuario_nome) ?>! 👋
        </h1>

        <p>
            Bem-vindo à sua área do paciente no MedCare.
            Aqui você pode consultar seus dados e acessar
            suas consultas.
        </p>

    </section>


    <!-- =================================================
         AÇÕES
    ================================================== -->

    <section class="cards-acoes">


        <a
            href="agendar.php"
            class="card-acao"
        >

            <div class="icone-acao">
                📅
            </div>

            <div>

                <h3>
                    Agendar consulta
                </h3>

                <p>
                    Escolha uma especialidade,
                    médico, data e horário.
                </p>

            </div>

        </a>


        <a
            href="minhas_consultas.php"
            class="card-acao"
        >

            <div class="icone-acao">
                📋
            </div>

            <div>

                <h3>
                    Minhas consultas
                </h3>

                <p>
                    Consulte suas consultas
                    agendadas e realizadas.
                </p>

            </div>

        </a>


    </section>


    <!-- =================================================
         DADOS DO PACIENTE
    ================================================== -->

    <section class="card">

        <div class="card-titulo">

            <span style="font-size: 24px;">
                👤
            </span>

            <h2>
                Meus dados
            </h2>

        </div>


        <?php if ($paciente): ?>

            <div class="linha-dados">


                <div class="dado">

                    <label>
                        Nome completo
                    </label>

                    <strong>
                        <?= mostrar($paciente['nome']) ?>
                    </strong>

                </div>


                <div class="dado">

                    <label>
                        CPF
                    </label>

                    <strong>
                        <?= mostrar($paciente['cpf']) ?>
                    </strong>

                </div>


                <div class="dado">

                    <label>
                        RG
                    </label>

                    <strong>
                        <?= mostrar($paciente['rg']) ?>
                    </strong>

                </div>


                <div class="dado">

                    <label>
                        Data de nascimento
                    </label>

                    <strong>
                        <?= $data_nascimento_formatada ?>
                    </strong>

                </div>


                <div class="dado">

                    <label>
                        Sexo
                    </label>

                    <strong>
                        <?= mostrar($paciente['sexo']) ?>
                    </strong>

                </div>


                <div class="dado">

                    <label>
                        Telefone
                    </label>

                    <strong>
                        <?= mostrar($paciente['telefone']) ?>
                    </strong>

                </div>


                <div class="dado">

                    <label>
                        E-mail
                    </label>

                    <strong>
                        <?= mostrar($paciente['email']) ?>
                    </strong>

                </div>


                <div class="dado">

                    <label>
                        Endereço
                    </label>

                    <strong>
                        <?= mostrar($paciente['endereco']) ?>
                    </strong>

                </div>


                <div class="dado">

                    <label>
                        Cidade
                    </label>

                    <strong>
                        <?= mostrar($paciente['cidade']) ?>
                    </strong>

                </div>


                <div class="dado">

                    <label>
                        Estado
                    </label>

                    <strong>
                        <?= mostrar($paciente['estado']) ?>
                    </strong>

                </div>


            </div>


        <?php else: ?>


            <div class="aviso">

                <strong>
                    ⚠️ Cadastro de paciente não encontrado
                </strong>

                Sua conta está conectada, mas não encontramos
                um cadastro de paciente vinculado ao seu usuário.

                Entre em contato com o administrador para
                verificar o vínculo da sua conta.

            </div>


        <?php endif; ?>

    </section>

</main>


<!-- =====================================================
     RODAPÉ
===================================================== -->

<footer class="footer-paciente">

    © <?= date('Y') ?> MedCare -
    Sistema de Gerenciamento de Consultas

</footer>


</body>

</html>
