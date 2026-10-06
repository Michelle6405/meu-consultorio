<?php

include("php/protecao_admin.php");
include("php/conexao.php");


// =========================================================
// FUNÇÃO PARA CONTAR REGISTROS
// =========================================================

function contarRegistrosRelatorio($conexao, $tabela)
{
    $tabelasPermitidas = [
        "pacientes",
        "medicos",
        "especialidades",
        "consultas"
    ];

    if (!in_array($tabela, $tabelasPermitidas)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM $tabela";

    $resultado = mysqli_query($conexao, $sql);

    if (!$resultado) {
        return 0;
    }

    $dados = mysqli_fetch_assoc($resultado);

    return (int)$dados['total'];
}


// =========================================================
// TOTAIS
// =========================================================

$totalPacientes =
    contarRegistrosRelatorio(
        $conexao,
        "pacientes"
    );

$totalMedicos =
    contarRegistrosRelatorio(
        $conexao,
        "medicos"
    );

$totalEspecialidades =
    contarRegistrosRelatorio(
        $conexao,
        "especialidades"
    );

$totalConsultas =
    contarRegistrosRelatorio(
        $conexao,
        "consultas"
    );


// =========================================================
// STATUS DAS CONSULTAS
// =========================================================

$agendadas = 0;
$realizadas = 0;
$canceladas = 0;

$sqlStatus = "
    SELECT status, COUNT(*) AS total
    FROM consultas
    GROUP BY status
";

$resultadoStatus = mysqli_query(
    $conexao,
    $sqlStatus
);

if ($resultadoStatus) {

    while ($linha = mysqli_fetch_assoc($resultadoStatus)) {

        $status = $linha['status'];
        $total = (int)$linha['total'];

        if ($status === 'agendada') {
            $agendadas = $total;
        }

        elseif ($status === 'realizada') {
            $realizadas = $total;
        }

        elseif ($status === 'cancelada') {
            $canceladas = $total;
        }
    }
}


// =========================================================
// BUSCAR CONSULTAS
// =========================================================

$consultas = [];

$sqlConsultas = "
    SELECT
        consultas.id,
        consultas.data_consulta,
        consultas.hora_consulta,
        consultas.observacao,
        consultas.status,
        pacientes.nome AS paciente,
        medicos.nome AS medico
    FROM consultas
    INNER JOIN pacientes
        ON consultas.paciente_id = pacientes.id
    INNER JOIN medicos
        ON consultas.medico_id = medicos.id
    ORDER BY
        consultas.data_consulta DESC,
        consultas.hora_consulta DESC
";

$resultadoConsultas = mysqli_query(
    $conexao,
    $sqlConsultas
);

if ($resultadoConsultas) {

    while ($consulta = mysqli_fetch_assoc($resultadoConsultas)) {
        $consultas[] = $consulta;
    }

}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Relatórios - MedCare</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>


<?php include("php/menu_admin.php"); ?>


<main class="relatorios-container">


    <!-- =====================================================
         TÍTULO
    ====================================================== -->

    <div class="titulo-relatorios">

        <h2>
            📊 Relatórios
        </h2>

        <p>
            Consulte os principais dados do sistema MedCare.
        </p>

    </div>


    <!-- =====================================================
         CARDS
    ====================================================== -->

    <div class="relatorios-cards">


        <div class="relatorio-card">

            <div class="relatorio-icone">
                👥
            </div>

            <div>

                <span>
                    Pacientes
                </span>

                <strong>
                    <?php echo $totalPacientes; ?>
                </strong>

            </div>

        </div>


        <div class="relatorio-card">

            <div class="relatorio-icone">
                👨‍⚕️
            </div>

            <div>

                <span>
                    Médicos
                </span>

                <strong>
                    <?php echo $totalMedicos; ?>
                </strong>

            </div>

        </div>


        <div class="relatorio-card">

            <div class="relatorio-icone">
                🩺
            </div>

            <div>

                <span>
                    Especialidades
                </span>

                <strong>
                    <?php echo $totalEspecialidades; ?>
                </strong>

            </div>

        </div>


        <div class="relatorio-card">

            <div class="relatorio-icone">
                📅
            </div>

            <div>

                <span>
                    Consultas
                </span>

                <strong>
                    <?php echo $totalConsultas; ?>
                </strong>

            </div>

        </div>


    </div>


    <!-- =====================================================
         STATUS
    ====================================================== -->

    <div class="status-cards">


        <div class="status-card agendada">

            <span>
                🕐
            </span>

            <div>

                <p>
                    Agendadas
                </p>

                <strong>
                    <?php echo $agendadas; ?>
                </strong>

            </div>

        </div>


        <div class="status-card realizada">

            <span>
                ✅
            </span>

            <div>

                <p>
                    Realizadas
                </p>

                <strong>
                    <?php echo $realizadas; ?>
                </strong>

            </div>

        </div>


        <div class="status-card cancelada">

            <span>
                ❌
            </span>

            <div>

                <p>
                    Canceladas
                </p>

                <strong>
                    <?php echo $canceladas; ?>
                </strong>

            </div>

        </div>


    </div>


    <!-- =====================================================
         GRÁFICO
    ====================================================== -->

    <div class="relatorio-grafico">

        <h3>
            Situação das Consultas
        </h3>

        <div class="grafico-relatorio">

            <canvas id="graficoRelatorio"></canvas>

        </div>

    </div>


    <!-- =====================================================
         TABELA
    ====================================================== -->

    <div class="relatorio-tabela">

        <div class="titulo-tabela">

            <h3>
                📋 Histórico de Consultas
            </h3>

            <span>
                <?php echo count($consultas); ?> consulta(s)
            </span>

        </div>


        <?php if (count($consultas) > 0): ?>

            <div class="tabela-scroll">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Data
                            </th>

                            <th>
                                Horário
                            </th>

                            <th>
                                Paciente
                            </th>

                            <th>
                                Médico
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Observação
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($consultas as $consulta): ?>

                        <?php

                        $data = date(
                            "d/m/Y",
                            strtotime($consulta['data_consulta'])
                        );

                        $hora = date(
                            "H:i",
                            strtotime($consulta['hora_consulta'])
                        );

                        $status =
                            $consulta['status']
                            ?? 'agendada';

                        ?>

                        <tr>

                            <td>
                                <?php echo $data; ?>
                            </td>

                            <td>
                                <?php echo $hora; ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $consulta['paciente'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $consulta['medico'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>

                                <?php if ($status === 'agendada'): ?>

                                    <span class="status-agendada">
                                        Agendada
                                    </span>

                                <?php elseif ($status === 'realizada'): ?>

                                    <span class="status-realizada">
                                        Realizada
                                    </span>

                                <?php elseif ($status === 'cancelada'): ?>

                                    <span class="status-cancelada">
                                        Cancelada
                                    </span>

                                <?php else: ?>

                                    <span class="status-outro">
                                        <?php
                                        echo htmlspecialchars(
                                            $status,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php

                                if (
                                    !empty(
                                        $consulta['observacao']
                                    )
                                ) {

                                    echo htmlspecialchars(
                                        $consulta['observacao'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );

                                } else {

                                    echo "—";

                                }

                                ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="sem-consultas">

                <div>
                    📋
                </div>

                <h3>
                    Nenhuma consulta encontrada
                </h3>

                <p>
                    Ainda não existem consultas cadastradas.
                </p>

                <a
                    href="consultas.php"
                    class="botao"
                >
                    ➕ Cadastrar Consulta
                </a>

            </div>

        <?php endif; ?>

    </div>


</main>


<script>

const graficoRelatorio =
    document.getElementById('graficoRelatorio');

new Chart(
    graficoRelatorio,
    {
        type: 'doughnut',

        data: {

            labels: [
                'Agendadas',
                'Realizadas',
                'Canceladas'
            ],

            datasets: [
                {
                    data: [
                        <?php echo $agendadas; ?>,
                        <?php echo $realizadas; ?>,
                        <?php echo $canceladas; ?>
                    ]
                }
            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    position: 'bottom'
                }

            }

        }

    }
);

</script>


<style>

/* =========================================================
   RELATÓRIOS
========================================================= */

.relatorios-container {
    width: 94%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 25px 0 40px;
}


/* =========================================================
   TÍTULO
========================================================= */

.titulo-relatorios {
    text-align: center;
    margin-bottom: 25px;
}

.titulo-relatorios h2 {
    color: #1976D2;
    font-size: 28px;
    margin-bottom: 7px;
}

.titulo-relatorios p {
    color: #666;
    font-size: 15px;
}


/* =========================================================
   CARDS PRINCIPAIS
========================================================= */

.relatorios-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 18px;
}

.relatorio-card {
    background: white;
    padding: 18px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, .08);

    display: flex;
    align-items: center;
    gap: 14px;
}

.relatorio-icone {
    font-size: 30px;
}

.relatorio-card span {
    display: block;
    color: #666;
    font-size: 13px;
    margin-bottom: 4px;
}

.relatorio-card strong {
    color: #1976D2;
    font-size: 24px;
}


/* =========================================================
   STATUS
========================================================= */

.status-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.status-card {
    background: white;
    padding: 17px 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, .08);

    display: flex;
    align-items: center;
    gap: 15px;
}

.status-card > span {
    font-size: 27px;
}

.status-card p {
    color: #666;
    font-size: 13px;
    margin-bottom: 4px;
}

.status-card strong {
    font-size: 23px;
}

.status-card.agendada strong {
    color: #1976D2;
}

.status-card.realizada strong {
    color: #2e7d32;
}

.status-card.cancelada strong {
    color: #dc3545;
}


/* =========================================================
   GRÁFICO
========================================================= */

.relatorio-grafico {
    background: white;
    width: 100%;
    max-width: 760px;
    height: 350px;

    margin: 0 auto 25px;

    padding: 18px;

    border-radius: 12px;

    box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
}

.relatorio-grafico h3 {
    text-align: center;
    font-size: 17px;
    margin-bottom: 10px;
}

.grafico-relatorio {
    position: relative;
    width: 100%;
    height: 290px;
}

.grafico-relatorio canvas {
    width: 100% !important;
    height: 100% !important;
}


/* =========================================================
   TABELA
========================================================= */

.relatorio-tabela {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
}

.titulo-tabela {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.titulo-tabela h3 {
    color: #333;
    font-size: 18px;
}

.titulo-tabela span {
    color: #666;
    font-size: 13px;
}

.tabela-scroll {
    width: 100%;
    overflow-x: auto;
}

.tabela-scroll table {
    margin-top: 15px;
    min-width: 850px;
}


/* =========================================================
   STATUS DA TABELA
========================================================= */

.status-agendada,
.status-realizada,
.status-cancelada,
.status-outro {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    white-space: nowrap;
}

.status-agendada {
    background: #e3f2fd;
    color: #1976D2;
}

.status-realizada {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-cancelada {
    background: #ffebee;
    color: #c62828;
}

.status-outro {
    background: #f5f5f5;
    color: #555;
}


/* =========================================================
   SEM CONSULTAS
========================================================= */

.sem-consultas {
    text-align: center;
    padding: 50px 20px;
}

.sem-consultas > div {
    font-size: 50px;
    margin-bottom: 12px;
}

.sem-consultas h3 {
    margin-bottom: 8px;
}

.sem-consultas p {
    color: #777;
    margin-bottom: 20px;
}


/* =========================================================
   RESPONSIVO
========================================================= */

@media (max-width: 1100px) {

    .relatorios-cards {
        grid-template-columns: repeat(2, 1fr);
    }

}


@media (max-width: 700px) {

    .relatorios-container {
        width: 92%;
    }

    .relatorios-cards {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .relatorio-card {
        padding: 14px;
    }

    .relatorio-icone {
        font-size: 24px;
    }

    .relatorio-card strong {
        font-size: 21px;
    }

    .status-cards {
        grid-template-columns: 1fr;
    }

    .relatorio-grafico {
        height: 320px;
    }

    .grafico-relatorio {
        height: 260px;
    }

    .relatorio-tabela {
        padding: 15px;
    }

}


@media (max-width: 450px) {

    .relatorios-cards {
        grid-template-columns: 1fr;
    }

    .titulo-relatorios h2 {
        font-size: 24px;
    }

    .titulo-tabela {
        flex-direction: column;
        align-items: flex-start;
    }

    .relatorio-grafico {
        height: 300px;
        padding: 12px;
    }

    .grafico-relatorio {
        height: 245px;
    }

}

</style>


</body>

</html>