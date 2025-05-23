<?php
session_start();
include("../conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  try {
    $sql = "SELECT * FROM usuario WHERE Email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

      if (password_verify($senha, $usuario['Senha'])) {
        $_SESSION['usuario'] = [
          'id' => $usuario['ID_Usuario'],
          'nome' => $usuario['Nome'],
          'email' => $usuario['Email'],
          'tipo' => $usuario['Tipo'] // Adicione esta linha
        ];

        // Adiciona mensagem de sucesso na sessão
        $_SESSION['login_sucesso'] = "Login realizado com sucesso!";
        header("Location: ../index.php");
        exit;
      } else {
        $_SESSION['login_erro'] = "Senha incorreta! Por favor, tente novamente.";
        header("Location: logar.php");
        exit;
      }
    } else {
      $_SESSION['login_erro'] = "Usuário não encontrado. Verifique seu e-mail.";
      header("Location: logar.php");
      exit;
    }
  } catch (PDOException $e) {
    $_SESSION['login_erro'] = "Erro no sistema: Por favor, tente mais tarde.";
    header("Location: logar.php");
    exit;
  }
}
