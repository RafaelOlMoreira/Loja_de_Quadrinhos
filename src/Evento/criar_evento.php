<?php
session_start();

// Verifica se o usuário tem permissão
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['tipo'], ['Admin', 'Organizador'])) {
  echo "<script>
        alert('Erro: Você não tem permissão para criar eventos.');
        window.location.href = '../index.php';
      </script>";
  exit;
}

require_once('../conexao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ComicZone | Criar Evento</title>
  <link rel="shortcut icon" href="../assets/favicon.png" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Noto+Sans+JP:wght@700&display=swap"
    rel="stylesheet" />
  <style>
    body {
      font-family: 'Noto Sans JP', sans-serif;
      background: url('fundo.png') no-repeat center center fixed;
      background-size: cover;
      padding-top: 80px;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: -1;
    }

    .font-bangers {
      font-family: 'Bangers', cursive;
    }

    /* Adicione no seu <style> existente */
    .animate-fade-in {
      animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body class="min-h-screen">

  <?php if (isset($_SESSION['erro'])): ?>
    <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fadeIn">
      <?= $_SESSION['erro'] ?>
      <?php unset($_SESSION['erro']); ?>
    </div>
  <?php endif; ?>

  <!-- NAVBAR -->
  <nav class="bg-[#1E1E1E] fixed w-full top-0 z-20 shadow-md">
    <div class="max-w-screen-xl mx-auto p-4 flex items-center justify-between">
      <a href="#" class="flex items-center gap-3">
        <img src="#" class="h-8" alt="">
        <span class="text-3xl font-bangers text-[#FFD700]">ComicZone</span>
      </a>
      <ul class="hidden md:flex gap-6 text-2xl font-bangers">
        <li><a href="../index.html#inicio" class="text-white hover:text-[#FF3131]">Início</a></li>
        <li><a href="../index.html#loja" class="text-white hover:text-[#FF3131]">Loja</a></li>
        <li><a href="../index.html#eventos" class="text-white hover:text-[#FF3131]">Eventos</a></li>
        <li><a href="../index.html#contato" class="text-white hover:text-[#FF3131]">Contato</a></li>
      </ul>
      <div class="flex items-center gap-3 md:gap-0">
        <a href="./Login/logar.php"
          class="bg-[#FF3131] text-white font-medium text-sm px-4 py-2 rounded hover:bg-[#cc1e1e]">
          Fazer Login
        </a>
        <button data-collapse-toggle="navbar-sticky" type="button"
          class="md:hidden p-2 text-gray-400 hover:bg-gray-700 rounded focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </nav>

  <!-- CONTEÚDO PRINCIPAL -->
  <main class="flex items-center justify-center p-6">
    <div class="w-full max-w-2xl bg-white border-4 border-black p-8 shadow-[4px_4px_0px_black]">
      <h1 class="text-3xl mb-6 text-center text-black">📖 Criar Evento</h1>
      <div
        class="border-2 border-black rounded-[50px_50px_50px_0] p-4 bg-white text-sm text-gray-700 shadow-[3px_3px_0px_black] mb-6">
        Preencha os dados abaixo como se estivesse escrevendo um novo capítulo de sua HQ favorita!
      </div>

      <form action="cadastrar_evento.php" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div>
          <label for="nome" class="block font-bold mb-2">Nome do Evento</label>
          <input type="text" id="nome" name="nome" required
            class="w-full border-2 border-black bg-white p-3 rounded-lg font-bold focus:ring-2 focus:ring-[#FFD700]">
        </div>

        <div>
          <label for="descricao" class="block font-bold mb-2">Descrição</label>
          <textarea id="descricao" name="descricao" rows="4" required
            class="w-full border-2 border-black bg-white p-3 rounded-lg font-bold focus:ring-2 focus:ring-[#FFD700] resize-none"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="data" class="block font-bold mb-2">Data</label>
            <input type="date" id="data" name="data" required
              class="w-full border-2 border-black bg-white p-3 rounded-lg font-bold focus:ring-2 focus:ring-[#FFD700]">
          </div>
          <div>
            <label for="hora" class="block font-bold mb-2">Hora</label>
            <input type="time" id="hora" name="hora" required
              class="w-full border-2 border-black bg-white p-3 rounded-lg font-bold focus:ring-2 focus:ring-[#FFD700]">
          </div>
        </div>

        <div>
          <label for="local" class="block font-bold mb-2">Local do Evento</label>
          <input type="text" id="local" name="local" required
            class="w-full border-2 border-black bg-white p-3 rounded-lg font-bold focus:ring-2 focus:ring-[#FFD700]">
        </div>

        <div>
          <label for="categoria" class="block font-bold mb-2">Categoria</label>
          <select id="categoria" name="categoria" required
            class="w-full border-2 border-black bg-white p-3 rounded-lg font-bold focus:ring-2 focus:ring-[#FFD700]">
            <option value="">Selecione uma categoria</option>
            <option value="cosplay">Concurso de Cosplay</option>
            <option value="assinaturas">Sessão de Autógrafos</option>
            <option value="estreia">Estreia de Mangá</option>
            <option value="palestra">Palestra com Autor</option>
          </select>
        </div>

        <div>
          <label for="imagem" class="block font-bold mb-2">Imagem do Evento</label>
          <input type="file" id="imagem" name="imagem" accept="image/*" required
            class="w-full border-2 border-black bg-white p-3 rounded-lg font-bold focus:ring-2 focus:ring-[#FFD700]">
          <p class="text-sm text-gray-500 mt-1">Formatos aceitos: JPG, PNG (Máx. 2MB)</p>
        </div>

        <div class="text-center mt-8">
          <button type="submit"
            class="bg-[#E60012] hover:bg-[#B0000D] text-white font-bold py-3 px-8 rounded-lg uppercase tracking-wider transition-colors duration-300">
            Publicar Evento
          </button>
        </div>
      </form>
    </div>
  </main>

  <!-- RODAPÉ -->
  <footer
    class="bottom-0 left-0 z-20 w-full p-4 border-t border-yellow-500 shadow-sm md:flex md:items-center md:justify-center md:p-6 bg-[#242424]">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2026 <a href="#"
        class="hover:underline">ComicZone™</a>. Todos os direitos reservados.
    </span>
  </footer>

  <script>
    // Remove a mensagem de erro após 5 segundos
    setTimeout(() => {
      const mensagem = document.querySelector('.animate-fadeIn');
      if (mensagem) {
        mensagem.style.opacity = '0';
        setTimeout(() => mensagem.remove(), 300);
      }
    }, 5000);
  </script>

</body>

</html>