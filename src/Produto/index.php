<?php
session_start();

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['tipo'], ['Admin', 'Vendedor'])) {
    echo "<script>
        alert('Erro: Você não tem permissão para criar eventos.');
        window.location.href = '../index.php';
      </script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- bloco para a fonte da pagina -->
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <style>
        .font-bangers {
            font-family: 'Bangers', cursive;
        }

        body {
            background-image: url(./img/fundo.png);
            object-fit: cover;
        }
    </style>
    <link rel="shortcut icon" href="../assets/favicon.png" type="image/x-icon">
    <title>ComicZone | Cadastrar Produto</title>
</head>

<body class="bg-[#2e2e2e] h-screen w-screen overflow-hidden">

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <?= $_SESSION['erro'] ?>
            <?php unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <?= $_SESSION['sucesso'] ?>
            <?php unset($_SESSION['sucesso']); ?>
        </div>
    <?php endif; ?>

    <div class="flex justify-center items-center h-screen w-screen relative px-5">

        <div class="relative z-20 flex justify-center items-center w-full max-w-md">
            <form id="formulario-produto" action="cadastrar_produto.php" method="POST" enctype="multipart/form-data"
                class="z-10 flex flex-col gap-4 w-full max-w-lg p-6 bg-gray-600/60 backdrop-blur-md rounded-2xl">
                <h1 class="text-4xl font-bangers text-[#FFD700] text-center text-shadow-2xs">Criando seu <span
                        class="text-[#E60012]">Produto!</span></h1>

                <input type="text" name="titulo" placeholder="Titulo do Produto" required
                    class="w-full px-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300" />

                <textarea name="descricao" placeholder="Descrição do Produto" required
                    class="w-full px-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300 resize-none h-32 min-h-[8rem] max-h-[8rem]"></textarea>

                <div class="w-full">
                    <input type="number" name="preco" step="0.01" placeholder="Valor do Produto" required
                        class="w-full px-4 mb-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300" />

                    <input type="number" name="quantidade" placeholder="Quantidade do Produto" required
                        class="w-full px-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300" />
                </div>

                <label for="capa" class="font-bangers text-2xl text-[#FFD700]">Imagem do Produto</label>
                <div class="flex items-center gap-3">
                    <label for="capa"
                        class="cursor-pointer bg-[#E60012] hover:bg-[#B0000D] text-white hover:text-gray-400 font-bold py-2 px-4 rounded transition duration-300">
                        Escolher Imagem
                    </label>
                    <span id="file-chosen" class="text-white text-sm">Nenhuma imagem selecionada</span>
                </div>
                <input type="file" id="capa" name="imagem" accept="image/*" class="hidden" required />

                <button type="submit"
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded">Cadastrar Produto</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
    <script>
        const fileInput = document.getElementById('capa');
        const fileChosen = document.getElementById('file-chosen');

        fileInput.addEventListener('change', function() {
            fileChosen.textContent = this.files.length > 0 ? this.files[0].name : 'Nenhuma imagem selecionada';
        });
    </script>

</body>

</html>