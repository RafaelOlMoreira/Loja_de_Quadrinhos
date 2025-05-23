<?php
session_start();

// Verifica se o usuário tem permissão
if(!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['tipo'], ['Admin', 'Vendedor'])) {
    echo "<script>
        alert('Erro: Você não tem permissão para criar eventos.');
        window.location.href = '../index.php';
      </script>";
  exit;
}

// Verifica se o formulário foi enviado
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// Conexão com o banco de dados
require_once('../conexao.php');

// Diretório para uploads
$uploadDir = '../uploads/produtos/';
if(!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Processa o upload da imagem
$imagemNome = null;
if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $imagemNome = uniqid() . '.' . $extensao;
    $uploadFile = $uploadDir . $imagemNome;
    
    // Verifica se é uma imagem válida
    $check = getimagesize($_FILES['imagem']['tmp_name']);
    if($check === false) {
        $_SESSION['erro'] = "O arquivo não é uma imagem válida.";
        header("Location: index.php");
        exit;
    }
    
    // Move o arquivo para o diretório de uploads
    if(!move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
        $_SESSION['erro'] = "Erro ao fazer upload da imagem.";
        header("Location: index.php");
        exit;
    }
}

// Prepara os dados para inserção
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$preco = floatval($_POST['preco']);
$quantidade = intval($_POST['quantidade']);
$idVendedor = $_SESSION['usuario']['id'];

try {
    // Prepara a query SQL
    $sql = "INSERT INTO produto (Titulo, Descricao, Preco, Quantidade, Imagem, ID_Vendedor) 
            VALUES (:titulo, :descricao, :preco, :quantidade, :imagem, :idVendedor)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':preco', $preco);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':imagem', $imagemNome);
    $stmt->bindParam(':idVendedor', $idVendedor);
    
    // Executa a query
    if($stmt->execute()) {
        $_SESSION['sucesso'] = "Produto cadastrado com sucesso!";
        header("Location: ../index.php");
    } else {
        $_SESSION['erro'] = "Erro ao cadastrar produto. Tente novamente.";
        header("Location: index.php");
    }
} catch(PDOException $e) {
    $_SESSION['erro'] = "Erro no sistema: " . $e->getMessage();
    header("Location: index.php");
}

exit;
?>