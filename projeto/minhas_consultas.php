<?php

// ==========================================
// PROTEÇÃO DO PACIENTE
// ==========================================

include("php/protecao_paciente.php");
include("php/conexao.php");


// ==========================================
// USUÁRIO LOGADO
// ==========================================

$usuario_id = $_SESSION['usuario_id'];


// ==========================================
// BUSCAR CONSULTAS DO PACIENTE
// ==========================================

$sql = "
    SELECT
        consultas.id,
        consultas.data_consulta,
        consultas.hora_consulta,
        consultas.observacao,
        consultas.status,

        medicos.nome AS medico_nome,

        especialidades.nome AS especialidade

    FROM consultas

    INNER JOIN pacientes
        ON consultas.paciente_id = pacientes.id

    INNER JOIN medicos
        ON consultas.medico_id = medicos.id

    LEFT JOIN especialidades
        ON medicos.especialidade_id = especialidades.id

    WHERE pacientes.usuario_id = ?

    ORDER BY
        consultas.data_consulta DESC,
        consultas.hora_consulta DESC
";


$stmt = mysqli_prepare(
    $conexao,
    $sql
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $usuario_id
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Minhas Consultas - MedCare</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f4f8fb;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .cabecalho {
            margin-bottom: 25px;
        }

        .cabecalho h1 {
            margin-bottom: 5px;
            color: #222;
        }

        .cabecalho p {
            color: #666;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f4f8fb;
            font-weight: 600;
        }

        tr:hover {
            background: #fafcff;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-agendada {
            background: #fff3cd;
            color: #856404;
        }

        .status-realizada {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-cancelada {
            background: #f8d7da;
            color: #842029;
        }

        .sem-consultas {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .botao {
            display: inline-block;
            margin-top: 20px;
            background: #0d6efd;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .botao:hover {
            background: #0b5ed7;
        }

        .voltar {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #0d6efd;
            font-weight: 600;
        }

        .observacao {
            max-width: 250px;
            color: #666;
            font-size: 13px;
        }

        @media (max-width: 700px) {

            th,
            td {
                padding: 10px;
                font-size: 13px;
            }

        }

    </style>

</head>

<body>

    <main class="container">

        <section class="cabecalho">

            <h1>📋 Minhas consultas</h1>

            <p>
                Aqui você pode acompanhar suas consultas agendadas.
            </p>

        </section>


        <section class="card">

            <?php if (mysqli_num_rows($resultado) > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Data</th>

                            <th>Horário</th>

                            <th>Médico</th>

                            <th>Especialidade</th>

                            <th>Observação</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($consulta = mysqli_fetch_assoc($resultado)): ?>

                            <?php

                            // ==========================================
                            // FORMATAR DATA
                            // ==========================================

                            $dataFormatada = date(
                                'd/m/Y',
                                strtotime($consulta['data_consulta'])
                            );


                            // ==========================================
                            // FORMATAR HORÁRIO
                            // ==========================================

                            $horaFormatada = date(
                                'H:i',
                                strtotime($consulta['hora_consulta'])
                            );


                            // ==========================================
                            // STATUS
                            // ==========================================

                            $status = strtolower(
                                $consulta['status']
                            );

                            ?>

                            <tr>

                                <td>
                                    <?= $dataFormatada ?>
                                </td>

                                <td>
                                    <?= $horaFormatada ?>
                                </td>

                                <td>
                                    Dr(a).
                                    <?= htmlspecialchars(
                                        $consulta['medico_nome']
                                    ) ?>
                                </td>

                                <td>

                                    <?php if (!empty($consulta['especialidade'])): ?>

                                        <?= htmlspecialchars(
                                            $consulta['especialidade']
                                        ) ?>

                                    <?php else: ?>

                                        Não informado

                                    <?php endif; ?>

                                </td>

                                <td class="observacao">

                                    <?php if (!empty($consulta['observacao'])): ?>

                                        <?= htmlspecialchars(
                                            $consulta['observacao']
                                        ) ?>

                                    <?php else: ?>

                                        Nenhuma

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if ($status === 'agendada'): ?>

                                        <span class="status status-agendada">
                                            📅 Agendada
                                        </span>

                                    <?php elseif ($status === 'realizada'): ?>

                                        <span class="status status-realizada">
                                            ✅ Realizada
                                        </span>

                                    <?php elseif ($status === 'cancelada'): ?>

                                        <span class="status status-cancelada">
                                            ❌ Cancelada
                                        </span>

                                    <?php else: ?>

                                        <span class="status">
                                            <?= htmlspecialchars(
                                                $consulta['status']
                                            ) ?>
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="sem-consultas">

                    <h2>📅 Nenhuma consulta encontrada</h2>

                    <p>
                        Você ainda não possui consultas cadastradas.
                    </p>

                    <a
                        href="agendar.php"
                        class="botao"
                    >
                        📅 Agendar consulta
                    </a>

                </div>

            <?php endif; ?>

        </section>


        <a
            href="paciente.php"
            class="voltar"
        >
            ← Voltar para minha área
        </a>

    </main>

</body>

</html>

<?php

mysqli_stmt_close($stmt);

mysqli_close($conexao);

?>