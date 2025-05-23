<?php
// Conexão com o banco
$host = 'localhost';
$usuario = 'root';
$senha = ''; // coloque sua senha aqui se houver
$banco = 'comiczone'; // nome do seu banco de dados

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica conexão
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

// Recebendo os dados
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirmarSenha = $_POST['confirmarSenha'];

// Verifica se as senhas coincidem
if ($senha !== $confirmarSenha) {
  echo "<script>alert('As senhas não coincidem!'); window.history.back();</script>";
  exit;
}

// Verifica se o email já está cadastrado
$sqlVerifica = "SELECT * FROM usuario WHERE Email = ?";
$stmt = $conn->prepare($sqlVerifica);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo "<script>alert('Email já cadastrado!'); window.history.back();</script>";
  exit;
}

// Criptografa a senha
$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

// Insere no banco
$sql = "INSERT INTO usuario (Nome, Email, Senha, Tipo) VALUES (?, ?, ?, 'usuario')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senhaCriptografada);

if ($stmt->execute()) {
  echo "<script>alert('Conta criada com sucesso!'); window.location.href='logar.php';</script>";
} else {
  echo "Erro ao registrar: " . $stmt->error;
}

$conn->close();
?>
