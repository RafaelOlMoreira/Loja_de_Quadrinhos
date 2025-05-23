<?php
session_start();

// Verifica se há mensagem de sucesso para mostrar
if (isset($_SESSION['login_sucesso'])) {
    $mensagem_sucesso = $_SESSION['login_sucesso'];
    unset($_SESSION['login_sucesso']);
}
?>

<?php
session_start();
require_once('conexao.php'); // Ajuste o caminho conforme sua estrutura

try {
    // Consulta para obter os produtos
    $sql = "SELECT p.*, u.Nome AS Vendedor 
            FROM produto p
            JOIN usuario u ON p.ID_Vendedor = u.ID_Usuario
            ORDER BY p.ID_Produto DESC
            LIMIT 8"; // Limite de 8 produtos ou ajuste conforme necessário

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $produtos = [];
    $erro = "Erro ao carregar produtos: " . $e->getMessage();
}
?>

<?php
require_once('conexao.php');

try {
    // Consulta para obter os próximos eventos (ordenados por data mais próxima)
    $sql = "SELECT * FROM evento 
            WHERE Data >= NOW() 
            ORDER BY Data ASC 
            LIMIT 3"; // Limita a 3 eventos ou ajuste conforme necessário

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $eventos = [];
    $erro = "Erro ao carregar eventos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/favicon.png" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- bloco para a fonte da pagina -->
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <style>
        .font-bangers {
            font-family: 'Bangers', cursive;
        }
    </style>

    <title>ComicZone</title>
</head>

<body class="h-0 bg-[#242424]">

    <!-- Navbar -->
    <nav class="bg-[#1E1E1E] fixed w-full top-0 z-20">
        <div class="max-w-screen-xl mx-auto p-4 flex items-center justify-between">
            <!-- Logo e titulo -->
            <a href="#" class="flex items-center gap-3">
                <span class="text-3xl font-bangers text-[#FFD700]">ComicZone</span>
            </a>

            <!-- Itens menu -->
            <ul class="hidden md:flex gap-6 text-2xl font-bangers">
                <li><a href="#inicio" class="text-white hover:text-[#FF3131]">Início</a></li>
                <li><a href="#loja" class="text-white hover:text-[#FF3131]">Loja</a></li>
                <li><a href="#eventos" class="text-white hover:text-[#FF3131]">Eventos</a></li>
                <li><a href="#contato" class="text-white hover:text-[#FF3131]">Contato</a></li>
            </ul>

            <!-- Botão de Login/Logout -->
            <div class="flex items-center gap-3 md:gap-0">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <!-- Usuário logado - Mostrar nome e botão Sair -->
                    <div class="flex items-center gap-3">
                        <span class="hidden md:block text-white font-medium text-sm">
                            Olá, <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>
                        </span>
                        <a href="../src/Login/logout.php"
                            class="bg-[#FF3131] text-white font-medium text-sm px-4 py-2 rounded hover:bg-[#cc1e1e] transition-colors">
                            Sair
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Usuário não logado - Mostrar botão Login -->
                    <a href="../src/Login/logar.php"
                        class="bg-[#FF3131] text-white font-medium text-sm px-4 py-2 rounded hover:bg-[#cc1e1e] transition-colors">
                        Fazer Login
                    </a>
                <?php endif; ?>

                <!-- Botão menu hamburguer -->
                <button data-collapse-toggle="navbar-sticky" type="button"
                    class="md:hidden p-2 text-gray-400 hover:bg-gray-700 rounded focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Banner Section -->
    <section class="mt-[20px]">
        <img src="assets/banner.png" alt="Banner Hero" class="w-full h-auto object-cover">
    </section>

    <!-- Inicio Section -->
    <section class="" id="inicio">
        <h2 class="text-8xl font-bangers text-[#FFD700] pt-6 text-center">Quem Somos?</h2>
        <p class="text-white p-9 text-4xl font-bangers m-9 text-center">Na <span
                class="text-[#FFD700]">ComicZone</span>, somos apaixonados por quadrinhos e cultura pop. Aqui você
            encontra uma curadoria incrível de
            HQs clássicas, mangás, edições raras e lançamentos exclusivos. Mais do que uma loja, somos um ponto de
            encontro para fãs de todas as idades que compartilham a mesma paixão por heróis, vilões e grandes aventuras.
        </p>
        <h2 class="text-8xl font-bangers text-[#FFD700] pt-12 text-center">O que você vai encontrar aqui?</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
            <!-- Coluna 1 ocupada com todos os cards -->
            <div class="lg:col-span-1 lg:space-y-6">

                <!-- Card 1 -->
                <div
                    class="flex items-center text-base text-black font-bangers bg-yellow-400 border-[4px] border-black shadow-[6px_6px_0_0_rgba(0,0,0,1)] rounded-xl p-3 transition-transform hover:scale-105 mb-6">
                    <span class="flex-1 ms-3 whitespace-nowrap text-2xl">
                        <span class="text-4xl">📚</span> HQs e Mangás
                    </span>
                    <span class="mx-10 text-center justify-center hidden xl:block">
                        Explore lançamentos, clássicos e edições raras do universo das HQs e mangás!
                    </span>
                </div>

                <!-- Card 2 -->
                <div
                    class="flex items-center text-base text-black font-bangers bg-pink-400 border-[4px] border-black shadow-[6px_6px_0_0_rgba(0,0,0,1)] rounded-xl p-3 transition-transform hover:scale-105 mb-6">
                    <span class="flex-1 ms-3 whitespace-nowrap text-2xl">
                        <span class="text-4xl">🎁</span> Produtos Geek
                    </span>
                    <span class="mx-10 text-center justify-center hidden xl:block">
                        Camisetas, pôsteres, canecas, action figures
                    </span>
                </div>

                <!-- Card 3 -->
                <div
                    class="flex items-center text-base text-black font-bangers bg-green-300 border-[4px] border-black shadow-[6px_6px_0_0_rgba(0,0,0,1)] rounded-xl p-3 transition-transform hover:scale-105 mb-6">
                    <span class="flex-1 ms-3 whitespace-nowrap text-2xl">
                        <span class="text-4xl">🎨</span> Quadrinhos Autorais
                    </span>
                    <span class="mx-10 text-center justify-center hidden xl:block">
                        Espaço para artistas independentes
                    </span>
                </div>

                <!-- Card 4 -->
                <div
                    class="flex items-center text-base text-black font-bangers bg-blue-300 border-[4px] border-black shadow-[6px_6px_0_0_rgba(0,0,0,1)] rounded-xl p-3 transition-transform hover:scale-105 mb-6">
                    <span class="flex-1 ms-3 whitespace-nowrap text-2xl">
                        <span class="text-4xl">📦</span> Assinatura Mensal
                    </span>
                    <span class="mx-10 text-center justify-center hidden xl:block">
                        Receba HQs surpresa todo mês
                    </span>
                </div>

                <!-- Card 5 -->
                <div
                    class="flex items-center text-base text-black font-bangers bg-red-400 border-[4px] border-black shadow-[6px_6px_0_0_rgba(0,0,0,1)] rounded-xl p-3 transition-transform hover:scale-105 mb-6">
                    <span class="flex-1 ms-3 whitespace-nowrap text-2xl">
                        <span class="text-4xl">🧑‍🎨</span> Eventos e Workshops
                    </span>
                    <span class="mx-10 text-center justify-center hidden xl:block">
                        Criação de personagens, desenho, cosplay
                    </span>
                </div>

            </div>
            <div class="grid grid-cols-2">
                <div>
                    <img src="./assets/card1.png"
                        class="w-auto h-full border-[4px] border-black shadow-[6px_6px_0_0_rgba(0,0,0,1)] rounded-xl p-3 transition-transform hover:scale-105">
                </div>
                <div>
                    <img src="./assets/card2.png"
                        class="w-auto h-full border-[4px] border-black shadow-[6px_6px_0_0_rgba(0,0,0,1)] rounded-xl p-3 transition-transform hover:scale-105">
                </div>
            </div>
        </div>

    </section>

    <!-- Section da loja -->
    <section class="h-auto" id="loja">
        <h2 class="text-9xl font-bangers text-[#FFD700] pt-6 text-center">Conheça nossa loja!</h2>
        <p class="text-white p-32 text-4xl font-bangers pt-6 pb-6 text-center">
            Mergulhe em um universo de aventuras, heróis, magia e histórias incríveis!
            Aqui você encontra HQs, mangás, produtos geek e tudo o que um verdadeiro fã ama.
            Explore, colecione e viva cada página como se fosse sua!
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7 place-items-center 2xl:mx-64
                    xl:mx-48 lg:mx-32 md:mx-24 mt-4 mb-24">

            <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="w-full max-w-sm border-[4px] border-black shadow-[6px_6px_0_0_rgba(0,0,0,1)] rounded-xl p-3 transition-transform hover:scale-105">
                        <div class="p-4">
                            <img class="w-full h-48 object-contain mb-4"
                                src="uploads/produtos/<?= $produto['Imagem'] ?>"
                                alt="<?= htmlspecialchars($produto['Titulo']) ?>"
                                onerror="this.src='./assets/placeholder.png'" />

                            <h5 class="text-xl font-semibold tracking-tight text-white">
                                <?= htmlspecialchars($produto['Titulo']) ?>
                            </h5>
                            <p class="text-gray-400 text-sm mt-2 line-clamp-2">
                                <?= htmlspecialchars($produto['Descricao']) ?>
                            </p>

                            <div class="flex items-center justify-between mt-4">
                                <span class="text-2xl font-bold text-white">
                                    R$ <?= number_format($produto['Preco'], 2, ',', '.') ?>
                                </span>
                                <a href="adicionar_carrinho.php?id=<?= $produto['ID_Produto'] ?>"
                                    class="text-white bg-[#E60012] hover:bg-[#B0000D] font-medium rounded-lg text-sm px-4 py-2 text-center">
                                    Comprar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-10">
                    <p class="text-xl text-gray-600">Nenhum produto disponível no momento</p>
                </div>
            <?php endif; ?>

        </div>

        <?php if (isset($_SESSION['usuario']) && ($_SESSION['usuario']['tipo'] == 'Admin' || $_SESSION['usuario']['tipo'] == 'Vendedor')): ?>
            <div class="col-start-2 justify-center text-center pb-24">
                <p class="text-5xl font-bangers text-[#FFD700] text-center pb-4">Anuncie seus produtos aqui!</p>
                <a href="./Produto/index.php"
                    class="w-full text-center bg-[#E60012] hover:bg-[#B0000D] text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Criar Produto
                </a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Section eventos -->
    <section class="h-auto" id="eventos">
        <h2 class="text-8xl font-bangers text-[#FFD700] text-center">Veja os Eventos da comunidade!</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 place-items-center m-12 gap-7">
            <?php if (!empty($eventos)): ?>
                <?php foreach ($eventos as $evento):
                    // Formata a data para exibição
                    $dataEvento = new DateTime($evento['Data']);
                    $dataFormatada = $dataEvento->format('d-m-Y');

                    // Define os labels das categorias
                    $categoriaLabel = match ($evento['Categoria']) {
                        'cosplay' => 'COSPLAY',
                        'assinaturas' => 'AUTÓGRAFOS',
                        'estreia' => 'ESTREIA',
                        'palestra' => 'PALESTRA',
                        'feira' => 'FEIRA',
                        default => 'EVENTO'
                    };
                ?>

                    <!-- Card de Evento Dinâmico -->
                    <div class="h-full event-card bg-[#242424] rounded-xl overflow-hidden shadow-lg shadow-black/30 transition-transform hover:scale-105">
                        <div class="relative h-48 overflow-hidden">
                            <img src="uploads/eventos/<?= $evento['Imagem'] ?>"
                                alt="<?= htmlspecialchars($evento['Nome']) ?>"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                onerror="this.src='./assets/banner.png'">
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent h-16"></div>
                            <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                EM BREVE
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-2">
                                <span class="bg-[#FFF200] text-black text-xs font-semibold px-2.5 py-0.5 rounded">
                                    <?= $categoriaLabel ?>
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-[#E60012] mb-2">
                                <?= htmlspecialchars($evento['Nome']) ?>
                            </h3>
                            <p class="text-white mb-4">
                                <?= htmlspecialchars($evento['Descricao']) ?>
                            </p>
                            <div class="flex items-center text-gray-500 mb-4">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path fill="currentColor" d="M6 1a1 1 0 0 0-2 0h2ZM4 4a1 1 0 0 0 2 0H4Zm7-3a1 1 0 1 0-2 0h2ZM9 4a1 1 0 1 0 2 0H9Zm7-3a1 1 0 1 0-2 0h2Zm-2 3a1 1 0 1 0 2 0h-2ZM1 6a1 1 0 0 0 0 2V6Zm18 2a1 1 0 1 0 0-2v2ZM5 11v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 11v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 15v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 15v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 11v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM5 15v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM2 4h16V2H2v2Zm16 0h2a2 2 0 0 0-2-2v2Zm0 0v14h2V4h-2Zm0 14v2a2 2 0 0 0 2-2h-2Zm0 0H2v2h16v-2ZM2 18H0a2 2 0 0 0 2 2v-2Zm0 0V4H0v14h2ZM2 4V2a2 2 0 0 0-2 2h2Zm2-3v3h2V1H4Zm5 0v3h2V1H9Zm5 0v3h2V1h-2ZM1 8h18V6H1v2Zm3 3v.01h2V11H4Zm1 1.01h.01v-2H5v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H5v2h.01v-2ZM9 11v.01h2V11H9Zm1 1.01h.01v-2H10v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM9 15v.01h2V15H9Zm1 1.01h.01v-2H10v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM14 15v.01h2V15h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM14 11v.01h2V11h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM4 15v.01h2V15H4Zm1 1.01h.01v-2H5v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H5v2h.01v-2Z" />
                                </svg>
                                <span class="ml-1"><?= $dataFormatada ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 ml-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <span><?= htmlspecialchars($evento['Local_Evento']) ?></span>
                            </div>
                            <a href="ingressos.php?evento=<?= $evento['ID_Evento'] ?>"
                                class="block w-full bg-[#E60012] hover:bg-[#B0000D] text-white font-medium py-2 px-4 rounded-lg transition-colors text-center">
                                Garantir Ingresso
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mensagem quando não há eventos -->
                <div class="col-span-full text-center py-10">
                    <p class="text-xl text-gray-400">Nenhum evento programado no momento</p>
                </div>
            <?php endif; ?>

            <!-- Botão para criar evento (sempre visível) -->
            <div class="row-start-2 col-start-2 justify-center text-center">
                <p class="text-5xl font-bangers text-[#FFD700] text-center pb-4">Organize seu evento agora!</p>
                <a href="./Evento/criar_evento.php"
                    class="w-full text-center bg-[#E60012] hover:bg-[#B0000D] text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Organizar Evento
                </a>
            </div>
        </div>
    </section>

    <!-- Section Contato -->
    <section class="bg-[#242424]" id="contato">
        <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
            <h2 class="text-8xl font-bangers text-[#FFD700] text-center">Fale com a Gente!</h2>
            <p class="font-bangers text-center text-white text-4xl mb-16 mt-8">Tem alguma
                dúvida, sugestão ou quer bater um papo sobre quadrinhos? Estamos sempre prontos para te ouvir!
                Preencha o formulário abaixo ou fale diretamente pelo nosso WhatsApp ou redes sociais</p>
            <form action="./processar_ticket.php" method="POST" class="space-y-8">
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Seu
                        E-mail</label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-3 rounded-lg bg-[#1f1f1f] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300"
                        placeholder="exemplo@email.com" required>
                </div>
                <div>
                    <label for="subject"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Assunto</label>
                    <input type="text" id="subject" name="assunto"
                        class="w-full px-4 py-3 rounded-lg bg-[#1f1f1f] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300"
                        placeholder="Informe-nos como podemos ajudá-lo(a)" required>
                </div>
                <div class="sm:col-span-2">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Sua
                        Mensagem</label>
                    <textarea id="message" rows="6" name="mensagem" required
                        class="w-full px-4 py-3 rounded-lg bg-[#1f1f1f] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300 resize-none"
                        placeholder="Escreva sua mensagem detalhada"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-[#FFD700] hover:bg-yellow-500 text-black font-bold py-3 rounded-lg transition duration-300">Enviar
                    Mensagem</button>
            </form>
        </div>
    </section>



    <footer
        class="bottom-0 left-0 z-20 w-full p-4 border-t border-yellow-500 shadow-sm md:flex md:items-center md:justify-center md:p-6 bg-[#242424]">
        <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2026 <a href="#"
                class="hover:underline">ComicZone™</a>. Todos os direitos reservados.
        </span>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alertaLogin = document.getElementById('alerta-login');
            const usuarioLogado = localStorage.getItem('usuarioLogado');

            if (usuarioLogado === 'true') {
                alertaLogin.style.display = 'none';
            } else {
                alertaLogin.style.display = 'block';
            }
        });
    </script>

    <script>
        document.getElementById('botao-logout').addEventListener('click', () => {
            localStorage.removeItem('usuarioLogado');
            window.location.href = './Login/logar.php'; // Redireciona para a página de login
        });
    </script>

    <script>
        // Mostra alerta de sucesso se existir
        <?php if (!empty($mensagem_sucesso)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                // Cria o elemento do alerta
                const alerta = document.createElement('div');
                alerta.className =
                    'fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center z-50 animate-fade-in';
                alerta.innerHTML = `
        <span>✔ <?php echo $mensagem_sucesso; ?></span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-xl">×</button>
    `;

                // Adiciona ao corpo
                document.body.appendChild(alerta);

                // Remove automaticamente após 5 segundos
                setTimeout(() => {
                    alerta.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                    setTimeout(() => alerta.remove(), 300);
                }, 5000);
            });
        <?php endif; ?>
    </script>

</body>

</html>