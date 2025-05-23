<?php
session_start(); // Adicionado no início para trabalhar com sessões
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
    
    /* Estilos para as mensagens de feedback */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .feedback-message {
      animation: fadeIn 0.3s ease-out;
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 25px;
      border-radius: 8px;
      color: white;
      z-index: 1000;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 350px;
    }
    
    .feedback-success {
      background-color: #48BB78;
    }
    
    .feedback-error {
      background-color: #F56565;
    }
    
    .close-feedback {
      background: none;
      border: none;
      color: white;
      font-weight: bold;
      margin-left: 15px;
      cursor: pointer;
    }
  </style>
  <link rel="shortcut icon" href="../assets/favicon.png" type="image/x-icon">
  <title>ComicZone | Entrar</title>
</head>

<body class="bg-[#2e2e2e] h-screen overflow-hidden">

  <!-- Container para mensagens de feedback dinâmicas -->
  <div id="feedback-container"></div>

  <div class="flex justify-center items-center h-screen relative px-5">

    <img src="img/heroi-esquerdo.png" alt="Herói Esquerdo"
      class="absolute bottom-0 max-h-[90vh] z-10 pointer-events-none left-0" />

    <div class="relative z-20 flex justify-center items-center w-full max-w-md">
      <div
        class="absolute w-[500px] h-[500px] bg-[url('img/fundo-explosao.png')] bg-contain bg-no-repeat bg-center z-0">
      </div>

      <form id="formulario-login" action="login.php" method="POST"
        class="z-10 flex flex-col gap-4 w-full max-w-sm p-6 bg-gray-600/60 backdrop-blur-md rounded-2xl">
        
        <!-- Mensagens de feedback do PHP -->
        <?php if (isset($_SESSION['login_erro'])): ?>
          <div class="bg-red-500 text-white p-3 rounded mb-4 transition-all duration-300">
            <?php echo $_SESSION['login_erro']; ?>
            <?php unset($_SESSION['login_erro']); ?>
          </div>
        <?php endif; ?>

        <h1 class="text-4xl font-bangers text-[#FFD700] text-center text-shadow-2xs">Bem vindo a <span
            class="text-[#E60012]">ComicZone!</span></h1>
        <h1 class="text-4xl font-bangers text-[#FFD700] text-center text-shadow-2xs">ENTRAR</h1>

        <input type="email" name="email" placeholder="Email" required
          class="w-full px-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300" />

        <div class="w-full">
          <input id="passwordInput" type="password" name="senha" placeholder="Senha" required
            class="w-full px-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300" />
          <div class="w-full grid grid-cols-2">
            <label class="flex items-center mt-2 text-sm text-gray-300">
              <input type="checkbox" onclick="togglePassword()" class="mr-2 accent-yellow-400" />
              Mostrar senha
            </label>
            <a href="#" class="w-auto text-right mt-2 text-sm text-gray-300">Esqueceu a senha?</a>
          </div>
        </div>

        <button type="submit"
          class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded">Acessar</button>
        <a href="./register.php"
          class="border-2 border-yellow-400 hover:border-yellow-500 text-white font-bold py-2 px-4 rounded text-center">Criar
          Conta</a>
      </form>

    </div>

    <img src="img/heroi-direito.png" alt="Herói Direito"
      class="absolute bottom-0 max-h-[90vh] z-10 pointer-events-none right-0" />

  </div>

  <script src="script.js"></script>
  <script>
    function togglePassword() {
      const input = document.getElementById('passwordInput');
      input.type = input.type === 'password' ? 'text' : 'password';
    }
    
    // Função para mostrar feedback
    function showFeedback(message, type) {
      const container = document.getElementById('feedback-container');
      const feedback = document.createElement('div');
      feedback.className = `feedback-message feedback-${type}`;
      feedback.innerHTML = `
        ${message}
        <button class="close-feedback" onclick="this.parentElement.remove()">&times;</button>
      `;
      
      container.appendChild(feedback);
      
      // Remove automaticamente após 5 segundos
      setTimeout(() => {
        feedback.style.opacity = '0';
        setTimeout(() => feedback.remove(), 300);
      }, 5000);
    }
    
    // Verificar se há mensagem na URL (para sucesso após cadastro)
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const loginSuccess = urlParams.get('login_success');
      
      if (loginSuccess) {
        showFeedback('Login realizado com sucesso!', 'success');
      }
    });
  </script>
</body>

</html>