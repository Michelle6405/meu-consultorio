
<?php

// =========================================================
// PROTEÇÃO DO ADMINISTRADOR
// =========================================================

include("php/protecao_admin.php");

// =========================================================
// CONEXÃO COM O BANCO
// =========================================================

include("php/conexao.php");

// =========================================================
// CADASTRAR ESPECIALIDADE
// =========================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");

    if ($nome === "") {

        $mensagem = "Digite o nome da especialidade.";

    } else {

        // Verificar se já existe
        $sqlVerificar = "
            SELECT id
            FROM especialidades
            WHERE nome = ?
        ";

        $stmtVerificar = $conexao->prepare($sqlVerificar);
        $stmtVerificar->bind_param("s", $nome);
        $stmtVerificar->execute();
        $resultadoVerificar = $stmtVerificar->get_result();

        if ($resultadoVerificar->num_rows > 0) {

            $mensagem = "Essa especialidade já está cadastrada.";

        } else {

            // Cadastrar especialidade
            $sqlCadastrar = "
                INSERT INTO especialidades (nome)
                VALUES (?)
            ";

            $stmtCadastrar = $conexao->prepare($sqlCadastrar);
            $stmtCadastrar->bind_param("s", $nome);

            if ($stmtCadastrar->execute()) {

                header("Location: especialidades.php?sucesso=1");
                exit;

            } else {

                $mensagem = "Erro ao cadastrar especialidade.";
            }

            $stmtCadastrar->close();
        }

        $stmtVerificar->close();
    }
}

// =========================================================
// MENSAGEM DE SUCESSO
// =========================================================

if (isset($_GET["sucesso"])) {
    $mensagemSucesso = "Especialidade cadastrada com sucesso!";
}

// =========================================================
// BUSCAR ESPECIALIDADES
// =========================================================

$sql = "
    SELECT id, nome
    FROM especialidades
    ORDER BY id DESC
";

$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Erro ao buscar especialidades: " . $conexao->error);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Especialidades - MedCare</title>

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- CSS PRINCIPAL -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<?php include("php/menu_admin.php"); ?>


<!-- =========================================================
     CONTEÚDO PRINCIPAL
========================================================= -->

<main class="pagina-especialidades">

    <section class="especialidades-container">

        <!-- =====================================================
             TÍTULO
        ====================================================== -->

        <div class="especialidades-titulo">

            <span class="icone-titulo">🩺</span>

            <div>

                <h1>Especialidades</h1>

                <p>
                    Cadastre e gerencie as especialidades médicas
                    disponíveis no MedCare.
                </p>

            </div>

        </div>


        <!-- =====================================================
             MENSAGENS
        ====================================================== -->

        <?php if (isset($mensagemSucesso)): ?>

            <div class="mensagem sucesso">
                ✅ <?= htmlspecialchars($mensagemSucesso) ?>
            </div>

        <?php endif; ?>


        <?php if (isset($mensagem)): ?>

            <div class="mensagem erro">
                ⚠️ <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>


        <!-- =====================================================
             FORMULÁRIO
        ====================================================== -->

        <div class="especialidade-card">

            <div class="card-titulo">

                <div class="icone-card">
                    ➕
                </div>

                <div>

                    <h2>Nova especialidade</h2>

                    <p>
                        Adicione uma nova especialidade médica.
                    </p>

                </div>

            </div>


            <form
                method="POST"
                action=""
                class="form-especialidade"
            >

                <div class="campo-especialidade">

                    <label for="nome">
                        Nome da especialidade
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Ex.: Cardiologia"
                        maxlength="100"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="botao-cadastrar-especialidade"
                >
                    Cadastrar especialidade
                </button>

            </form>

        </div>


        <!-- =====================================================
             LISTA DE ESPECIALIDADES
        ====================================================== -->

        <div class="especialidades-lista">

            <div class="lista-cabecalho">

                <div>

                    <h2>Especialidades cadastradas</h2>

                    <p>
                        Confira todas as especialidades disponíveis.
                    </p>

                </div>

                <div class="contador-especialidades">

                    <?= $resultado->num_rows ?>

                    especialidade(s)

                </div>

            </div>


            <?php if ($resultado->num_rows > 0): ?>

                <div class="lista-cards">

                    <?php while ($especialidade = $resultado->fetch_assoc()): ?>

                        <div class="especialidade-item">

                            <div class="especialidade-info">

                                <div class="icone-especialidade">
                                    🩺
                                </div>

                                <div>

                                    <h3>
                                        <?= htmlspecialchars($especialidade["nome"]) ?>
                                    </h3>

                                    <span>
                                        ID: <?= (int) $especialidade["id"] ?>
                                    </span>

                                </div>

                            </div>

                            <div class="acoes-especialidade">

                                <a
                                    href="editarEspecialidade.php?id=<?= (int) $especialidade["id"] ?>"
                                    class="botao-editar"
                                >
                                    ✏️ Editar
                                </a>

                                <a
                                    href="excluirEspecialidade.php?id=<?= (int) $especialidade["id"] ?>"
                                    class="botao-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir esta especialidade?');"
                                >
                                    🗑️ Excluir
                                </a>

                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <div class="lista-vazia">

                    <div class="icone-vazio">
                        🩺
                    </div>

                    <h3>Nenhuma especialidade cadastrada</h3>

                    <p>
                        Cadastre a primeira especialidade usando o formulário acima.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

</main>


<!-- =========================================================
     ESTILOS DA PÁGINA
========================================================= -->

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f4f8fc;
        color: #263238;
    }


    /* =========================================================
       CONTAINER
    ========================================================= */

    .pagina-especialidades {
        width: 100%;
        min-height: calc(100vh - 80px);
        padding: 45px 20px;
    }

    .especialidades-container {
        width: 94%;
        max-width: 1200px;
        margin: 0 auto;
    }


    /* =========================================================
       TÍTULO
    ========================================================= */

    .especialidades-titulo {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 30px;
    }

    .icone-titulo {
        width: 62px;
        height: 62px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #1976d2;
        color: white;
        border-radius: 16px;
        font-size: 30px;
        box-shadow: 0 8px 20px rgba(25, 118, 210, 0.18);
    }

    .especialidades-titulo h1 {
        margin: 0;
        color: #123b5d;
        font-size: 30px;
        font-weight: 800;
    }

    .especialidades-titulo p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 15px;
    }


    /* =========================================================
       MENSAGENS
    ========================================================= */

    .mensagem {
        width: 100%;
        padding: 15px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .mensagem.sucesso {
        background: #e8f7ee;
        color: #18794e;
        border: 1px solid #b7e4c7;
    }

    .mensagem.erro {
        background: #fff0f0;
        color: #c62828;
        border: 1px solid #f2b8b8;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .especialidade-card {
        background: white;
        border-radius: 18px;
        padding: 28px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(30, 60, 90, 0.08);
        border: 1px solid #e6edf4;
    }

    .card-titulo {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .icone-card {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf4ff;
        color: #1976d2;
        border-radius: 12px;
        font-size: 21px;
    }

    .card-titulo h2 {
        margin: 0;
        color: #173f5f;
        font-size: 20px;
    }

    .card-titulo p {
        margin: 4px 0 0;
        color: #7b8794;
        font-size: 13px;
    }


    /* =========================================================
       FORMULÁRIO
    ========================================================= */

    .form-especialidade {
        display: flex;
        align-items: flex-end;
        gap: 15px;
    }

    .campo-especialidade {
        flex: 1;
    }

    .campo-especialidade label {
        display: block;
        margin-bottom: 8px;
        color: #34495e;
        font-size: 14px;
        font-weight: 600;
    }

    .campo-especialidade input {
        width: 100%;
        height: 48px;
        padding: 0 15px;
        border: 1px solid #d7e0e8;
        border-radius: 10px;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        transition: 0.2s;
    }

    .campo-especialidade input:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.10);
    }

    .botao-cadastrar-especialidade {
        height: 48px;
        padding: 0 25px;
        border: none;
        border-radius: 10px;
        background: #1976d2;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        white-space: nowrap;
    }

    .botao-cadastrar-especialidade:hover {
        background: #125ca8;
        transform: translateY(-1px);
    }


    /* =========================================================
       LISTA
    ========================================================= */

    .especialidades-lista {
        background: white;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 5px 20px rgba(30, 60, 90, 0.08);
        border: 1px solid #e6edf4;
    }

    .lista-cabecalho {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .lista-cabecalho h2 {
        margin: 0;
        color: #173f5f;
        font-size: 20px;
    }

    .lista-cabecalho p {
        margin: 5px 0 0;
        color: #7b8794;
        font-size: 13px;
    }

    .contador-especialidades {
        padding: 8px 14px;
        background: #eaf4ff;
        color: #1976d2;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }


    /* =========================================================
       CARDS DAS ESPECIALIDADES
    ========================================================= */

    .lista-cards {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .especialidade-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 18px 20px;
        border: 1px solid #e4ebf2;
        border-radius: 13px;
        transition: 0.2s;
    }

    .especialidade-item:hover {
        border-color: #b9d6f2;
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.07);
    }

    .especialidade-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .icone-especialidade {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f7ff;
        border-radius: 11px;
        font-size: 20px;
    }

    .especialidade-info h3 {
        margin: 0;
        color: #263238;
        font-size: 15px;
        font-weight: 600;
    }

    .especialidade-info span {
        display: block;
        margin-top: 3px;
        color: #8996a3;
        font-size: 12px;
    }


    /* =========================================================
       AÇÕES
    ========================================================= */

    .acoes-especialidade {
        display: flex;
        gap: 8px;
    }

    .acoes-especialidade a {
        text-decoration: none;
        padding: 9px 13px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        transition: 0.2s;
    }

    .botao-editar {
        background: #eef6ff;
        color: #1976d2;
    }

    .botao-editar:hover {
        background: #dceeff;
    }

    .botao-excluir {
        background: #fff0f0;
        color: #d32f2f;
    }

    .botao-excluir:hover {
        background: #ffe0e0;
    }


    /* =========================================================
       LISTA VAZIA
    ========================================================= */

    .lista-vazia {
        text-align: center;
        padding: 45px 20px;
        border: 2px dashed #dce5ed;
        border-radius: 12px;
    }

    .icone-vazio {
        font-size: 42px;
        margin-bottom: 10px;
    }

    .lista-vazia h3 {
        margin: 0;
        color: #455a64;
        font-size: 17px;
    }

    .lista-vazia p {
        margin: 7px 0 0;
        color: #8996a3;
        font-size: 13px;
    }


    /* =========================================================
       RESPONSIVIDADE
    ========================================================= */

    @media (max-width: 900px) {

        .especialidades-container {
            width: 94%;
        }

        .form-especialidade {
            flex-direction: column;
            align-items: stretch;
        }

        .botao-cadastrar-especialidade {
            width: 100%;
        }

        .especialidade-card {
            padding: 20px;
        }

    }


    @media (max-width: 600px) {

        .pagina-especialidades {
            padding: 30px 10px;
        }

        .especialidades-titulo {
            align-items: flex-start;
        }

        .especialidades-titulo h1 {
            font-size: 25px;
        }

        .especialidades-titulo p {
            font-size: 14px;
        }

        .lista-cabecalho {
            flex-direction: column;
            align-items: flex-start;
        }

        .especialidade-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .acoes-especialidade {
            width: 100%;
        }

        .acoes-especialidade a {
            flex: 1;
            text-align: center;
        }

        .card-titulo {
            align-items: flex-start;
        }

    }

</style>


</body>
</html>