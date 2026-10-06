<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contato - MedCare</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

</head>

<body>


<!-- =====================================================
     CABEÇALHO
===================================================== -->

<header class="header-principal">

    <div class="logo">

        <span class="logo-icone">🏥</span>

        <span>MedCare</span>

    </div>


    <nav class="menu-principal">

        <a href="index.php">
            Início
        </a>

        <a href="sobre.php">
            Sobre
        </a>

        <a href="contato.php" class="ativo">
            Contato
        </a>

        <a href="login.php" class="btn-entrar">
            🔐 Entrar
        </a>

    </nav>

</header>


<!-- =====================================================
     CONTEÚDO
===================================================== -->

<main class="contato">

    <div class="pagina-titulo">

        <h1>Entre em Contato</h1>

        <p>
            Entre em contato conosco para tirar dúvidas
            ou obter mais informações.
        </p>

    </div>


    <!-- =================================================
         FORMULÁRIO
    ================================================== -->

    <section class="formulario">

        <form action="#" method="POST">


            <div class="form-grupo">

                <label for="nome">
                    Nome
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Digite seu nome"
                    required
                >

            </div>


            <div class="form-grupo">

                <label for="email">
                    E-mail
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Digite seu e-mail"
                    required
                >

            </div>


            <div class="form-grupo">

                <label for="assunto">
                    Assunto
                </label>

                <input
                    type="text"
                    id="assunto"
                    name="assunto"
                    placeholder="Digite o assunto"
                    required
                >

            </div>


            <div class="form-grupo">

                <label for="mensagem">
                    Mensagem
                </label>

                <textarea
                    id="mensagem"
                    name="mensagem"
                    placeholder="Digite sua mensagem"
                    rows="6"
                    required
                ></textarea>

            </div>


            <button type="submit" class="btn">
                ✉️ Enviar Mensagem
            </button>

        </form>

    </section>


    <!-- =================================================
         INFORMAÇÕES
    ================================================== -->

    <section class="caixa">

        <h2>Informações do Consultório</h2>

        <p>
            📍 Endereço: Rua Exemplo, nº 100 - Centro
        </p>

        <p>
            📞 Telefone: (38) 99999-9999
        </p>

        <p>
            📧 E-mail: contato@medcare.com
        </p>

        <p>
            🕒 Horário: Segunda a Sexta - 08:00 às 18:00
        </p>

    </section>

</main>


<!-- =====================================================
     RODAPÉ
===================================================== -->

<footer>

    <strong>
        MedCare - Sistema para Consultório Médico
    </strong>

    <p>
        📍 Japonvar - MG
    </p>

    <p>
        ✉ contato@medcare.com
    </p>

    <p>
        © 2026 MedCare
    </p>

</footer>


</body>
</html>