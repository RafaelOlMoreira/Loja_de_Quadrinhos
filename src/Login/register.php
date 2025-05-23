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
  <title>ComicZone | Criar Conta</title>
  <link rel="shortcut icon" href="../assets/favicon.png" type="image/x-icon">
</head>

<body class="bg-[#2e2e2e] h-screen overflow-hidden">

  <div class="flex justify-center items-center h-screen relative px-5">

    <img src="img/heroi-esquerdo.png" alt="Herói Esquerdo"
      class="absolute bottom-0 max-h-[90vh] z-10 pointer-events-none left-0" />

    <div class="relative z-20 flex justify-center items-center w-full max-w-md">
      <div
        class="absolute w-[500px] h-[500px] bg-[url('img/fundo-explosao.png')] bg-contain bg-no-repeat bg-center z-0">
      </div>

      <form id="formulario-registro" action="registro.php" method="POST"
        class="z-10 flex flex-col gap-4 w-full max-w-sm p-6 bg-gray-600/60 backdrop-blur-md rounded-2xl">
        <h1 class="text-4xl font-bangers text-[#FFD700] text-center text-shadow-2xs">Bem vindo a <span class="text-[#E60012]">ComicZone!</span></h1>
        <h1 class="text-4xl font-bangers text-[#FFD700] text-center text-shadow-2xs">Criar Conta</h1>
        <input type="text" name="nome" placeholder="Nome e Sobrenome" required
          class="w-full px-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300" />
        <input type="email" name="email" placeholder="Email" required
          class="w-full px-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300" />
        <div class="w-full">

          <input id="senha" type="password" name="senha" placeholder="Senha" required
            class="w-full px-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300" />

          <input id="confirmarSenha" type="password" name="confirmarSenha" placeholder="Confirmar Senha" required
            class="w-full my-4 px-4 py-3 rounded-lg bg-[#2D2D2D] text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-300" />

          <div class="w-full grid grid-cols-2">
            <label class="flex items-center gap-2 text-white text-sm">
              <input type="checkbox" id="mostrarSenhas" class="accent-[#FFD700]" />
              Mostrar senhas
            </label>
          </div>
        </div>
        <button type="submit"
          class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded">Criar Conta</button>
        <a href="./logar.php"
          class="border-2 border-yellow-400 hover:border-yellow-500 text-white font-bold py-2 px-4 rounded text-center">Entrar</a>
      </form>
    </div>

    <img src="img/heroi-direito.png" alt="Herói Direito"
      class="absolute bottom-0 max-h-[90vh] z-10 pointer-events-none right-0" />

  </div>

  <script src="script.js"></script>
  <script>
    const checkbox = document.getElementById("mostrarSenhas");
    const senhaInput = document.getElementById("senha");
    const confirmarInput = document.getElementById("confirmarSenha");

    checkbox.addEventListener("change", () => {
      const tipo = checkbox.checked ? "text" : "password";
      senhaInput.type = tipo;
      confirmarInput.type = tipo;
    });
  </script>
</body>

</html>