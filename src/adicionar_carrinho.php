<?php
session_start();

if(!isset($_SESSION['usuario'])) {
    $_SESSION['redirect_to'] = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: login.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id) {
    // Inicializa o carrinho se não existir
    if(!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }
    
    // Adiciona o produto ao carrinho
    if(isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade']++;
    } else {
        $_SESSION['carrinho'][$id] = [
            'quantidade' => 1,
            'adicionado_em' => time()
        ];
    }
    
    $_SESSION['sucesso'] = "Produto adicionado ao carrinho!";
}

header("Location: ".($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
?>