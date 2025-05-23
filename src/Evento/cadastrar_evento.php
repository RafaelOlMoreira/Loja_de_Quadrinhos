<?php
session_start();

// Verifica permissão
if(!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['tipo'], ['Admin', 'Organizador'])) {
    $_SESSION['erro'] = "Acesso negado. Você não tem permissão para criar eventos.";
    header("Location: ../../login.php");
    exit;
}

// Conexão com o banco
require_once('../conexao.php');

// Configurações de upload
$uploadDir = '../../uploads/eventos/';
if(!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Processa o upload da imagem
$imagemNome = null;
if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    // Verifica tamanho máximo (2MB)
    if($_FILES['imagem']['size'] > 2097152) {
        $_SESSION['erro'] = "A imagem deve ter no máximo 2MB.";
        header("Location: criar_evento.php");
        exit;
    }

    // Verifica tipo de arquivo
    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
    
    if(!in_array($extensao, $extensoesPermitidas)) {
        $_SESSION['erro'] = "Formato de imagem inválido. Use JPG ou PNG.";
        header("Location: criar_evento.php");
        exit;
    }

    // Gera nome único para o arquivo
    $imagemNome = uniqid('evento_') . '.' . $extensao;
    $uploadFile = $uploadDir . $imagemNome;
    
    // Verifica se é uma imagem válida
    if(!getimagesize($_FILES['imagem']['tmp_name'])) {
        $_SESSION['erro'] = "O arquivo não é uma imagem válida.";
        header("Location: criar_evento.php");
        exit;
    }
    
    // Move o arquivo
    if(!move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
        $_SESSION['erro'] = "Erro ao salvar a imagem. Tente novamente.";
        header("Location: criar_evento.php");
        exit;
    }
} else {
    $_SESSION['erro'] = "É necessário enviar uma imagem para o evento.";
    header("Location: criar_evento.php");
    exit;
}

// Combina data e hora
$dataHora = $_POST['data'] . ' ' . $_POST['hora'] . ':00';

try {
    // Prepara e executa a query
    $sql = "INSERT INTO evento (ID_Organizador, Nome, Descricao, Data, Local_Evento, Categoria, Imagem) 
            VALUES (:organizador, :nome, :descricao, :data, :local, :categoria, :imagem)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':organizador' => $_SESSION['usuario']['id'],
        ':nome' => htmlspecialchars($_POST['nome']),
        ':descricao' => htmlspecialchars($_POST['descricao']),
        ':data' => $dataHora,
        ':local' => htmlspecialchars($_POST['local']),
        ':categoria' => $_POST['categoria'],
        ':imagem' => $imagemNome
    ]);

    $_SESSION['sucesso'] = "Evento criado com sucesso!";
    header("Location: ../index.php");
    exit;

} catch(PDOException $e) {
    // Remove a imagem se houve erro no banco
    if($imagemNome && file_exists($uploadDir . $imagemNome)) {
        unlink($uploadDir . $imagemNome);
    }
    
    $_SESSION['erro'] = "Erro ao criar evento: " . $e->getMessage();
    header("Location: criar_evento.php");
    exit;
}
?>