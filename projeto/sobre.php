```php
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sobre - MedCare</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* ==========================================
           PÁGINA SOBRE
        ========================================== */

        .sobre-hero {
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            color: white;
            border-radius: 22px;
            padding: 55px 45px;
            margin-bottom: 35px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(25, 118, 210, 0.18);
        }

        .sobre-hero::before {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            right: -70px;
            top: -80px;
        }

        .sobre-hero::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            right: 100px;
            bottom: -80px;
        }

        .sobre-hero-conteudo {
            position: relative;
            z-index: 2;
            max-width: 750px;
        }

        .sobre-hero h1 {
            margin: 0 0 12px;
            font-size: 38px;
            font-weight: 800;
        }

        .sobre-hero p {
            margin: 0;
            font-size: 16px;
            line-height: 1.7;
            opacity: 0.95;
        }

        .sobre-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 25px;
        }

        .sobre-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #e8eef5;
            transition: 0.3s;
        }

        .sobre-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.09);
        }

        .sobre-icone {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            background: #e8f2fc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 18px;
        }

        .sobre-card h2 {
            margin: 0 0 15px;
            color: #1976D2;
            font-size: 21px;
            font-weight: 700;
        }

        .sobre-card p {
            margin: 0 0 12px;
            color: #606b75;
            line-height: 1.7;
            font-size: 14px;
        }

        .sobre-card p:last-child {
            margin-bottom: 0;
        }

        .lista-funcionalidades {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }

        .funcionalidade {
            background: #f7faff;
            border: 1px solid #e5edf6;
            border-radius: 10px;
            padding: 13px 15px;
            color: #46515c;
            font-size: 13px;
            line-height: 1.4;
        }

        .funcionalidade span {
            color: #1976D2;
            font-weight: 700;
            margin-right: 5px;
        }

        .tecnologias {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 20px;
        }

        .tecnologia {
            background: #f7faff;
            border: 1px solid #e5edf6;
            border-radius: 10px;
            padding: 15px 10px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #46515c;
        }

        .tecnologia .tec-icone {
            display: block;
            font-size: 24px;
            margin-bottom: 7px;
        }

        .objetivo {
            background: white;
            border-radius: 18px;
            padding: 32px;
            margin-top: 25px;
            border-left: 5px solid #1976D2;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .objetivo h2 {
            margin: 0 0 12px;
            color: #1976D2;
            font-size: 21px;
        }

        .objetivo p {
            margin: 0;
            color: #606b75;
            line-height: 1.8;
            font-size: 14px;
        }

        /* ==========================================
           RESPONSIVIDADE
        ========================================== */

        @media (max-width: 800px) {

            .sobre-grid {
                grid-template-columns: 1fr;
            }

            .lista-funcionalidades {
                grid-template-columns: 1fr;
            }

            .tecnologias {
                grid-template-columns: repeat(2, 1fr);
            }

            .sobre-hero {
                padding: 40px 25px;
            }

            .sobre-hero h1 {
                font-size: 30px;
            }
        }

        @media (max-width: 500px) {

            .tecnologias {
                grid-template-columns: 1fr;
            }

            .sobre-card,
            .objetivo {
                padding: 24px;
            }
        }
    </style>
</head>

<body>

    <!-- ==========================================
         CABEÇALHO
    ========================================== -->

    <header class="header-principal">

        <div class="logo">
            <span class="logo-icone">🏥</span>
            <span>MedCare</span>
        </div>

        <nav class="menu-principal">

            <a href="index.php">Início</a>

            <a href="agendar.php">Agendar</a>

            <a href="sobre.php" class="ativo">Sobre</a>

            <a href="contato.php">Contato</a>

            <a href="login.php" class="btn-entrar">
                🔐 Entrar
            </a>

        </nav>

    </header>


    <!-- ==========================================
         CONTEÚDO
    ========================================== -->

    <main class="pagina">

        <!-- HERO -->

        <section class="sobre-hero">

            <div class="sobre-hero-conteudo">

                <h1>Sobre o MedCare</h1>

                <p>
                    Uma solução desenvolvida para tornar o gerenciamento
                    de consultórios médicos mais simples, organizado
                    e eficiente.
                </p>

            </div>

        </section>


        <!-- CARDS PRINCIPAIS -->

        <div class="sobre-grid">

            <!-- SOBRE O SISTEMA -->

            <section class="sobre-card">

                <div class="sobre-icone">
                    🏥
                </div>

                <h2>Sobre o Sistema</h2>

                <p>
                    O <strong>MedCare</strong> é um sistema desenvolvido
                    para auxiliar no gerenciamento de um consultório médico.
                </p>

                <p>
                    A plataforma permite organizar informações de pacientes,
                    médicos, especialidades e consultas em um único ambiente.
                </p>

                <p>
                    Dessa forma, o sistema ajuda a tornar as atividades
                    do consultório mais práticas e organizadas.
                </p>

            </section>


            <!-- FUNCIONALIDADES -->

            <section class="sobre-card">

                <div class="sobre-icone">
                    ⚙️
                </div>

                <h2>Funcionalidades</h2>

                <p>
                    O MedCare reúne recursos para facilitar o controle
                    das principais informações do consultório.
                </p>

                <div class="lista-funcionalidades">

                    <div class="funcionalidade">
                        <span>✓</span>
                        Cadastro de pacientes
                    </div>

                    <div class="funcionalidade">
                        <span>✓</span>
                        Cadastro de médicos
                    </div>

                    <div class="funcionalidade">
                        <span>✓</span>
                        Especialidades médicas
                    </div>

                    <div class="funcionalidade">
                        <span>✓</span>
                        Agendamento de consultas
                    </div>

                    <div class="funcionalidade">
                        <span>✓</span>
                        Organização da agenda
                    </div>

                    <div class="funcionalidade">
                        <span>✓</span>
                        Controle de consultas
                    </div>

                    <div class="funcionalidade">
                        <span>✓</span>
                        Relatórios
                    </div>

                    <div class="funcionalidade">
                        <span>✓</span>
                        Gerenciamento de informações
                    </div>

                </div>

            </section>

        </div>


        <!-- TECNOLOGIAS -->

        <section class="sobre-card">

            <div class="sobre-icone">
                💻
            </div>

            <h2>Tecnologias Utilizadas</h2>

            <p>
                O sistema foi desenvolvido utilizando tecnologias
                voltadas para aplicações web e gerenciamento de banco de dados.
            </p>

            <div class="tecnologias">

                <div class="tecnologia">
                    <span class="tec-icone">🌐</span>
                    HTML5
                </div>

                <div class="tecnologia">
                    <span class="tec-icone">🎨</span>
                    CSS3
                </div>

                <div class="tecnologia">
                    <span class="tec-icone">⚙️</span>
                    PHP
                </div>

                <div class="tecnologia">
                    <span class="tec-icone">🗄️</span>
                    MySQL
                </div>

                <div class="tecnologia">
                    <span class="tec-icone">🖥️</span>
                    XAMPP
                </div>

                <div class="tecnologia">
                    <span class="tec-icone">📝</span>
                    VS Code
                </div>

            </div>

        </section>


        <!-- OBJETIVO -->

        <section class="objetivo">

            <h2>🎯 Objetivo do MedCare</h2>

            <p>
                O principal objetivo do <strong>MedCare</strong> é oferecer
                uma solução simples e prática para auxiliar na organização
                de um consultório médico. O sistema busca facilitar o
                gerenciamento das informações, melhorar a organização dos
                dados e contribuir para um atendimento mais eficiente.
            </p>

        </section>

    </main>


    <!-- ==========================================
         RODAPÉ
    ========================================== -->

    <footer>

        <strong>
            MedCare - Sistema de Gerenciamento de Consultório Médico
        </strong>

        <p>
            © 2026 MedCare
        </p>

    </footer>

</body>

</html>
```
