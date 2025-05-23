<?php
session_start();
require_once './conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION['usuario']['id'])) {
        echo "<script>
                alert('Usuário não autenticado.');
                window.location.href = 'index.php';
              </script>";
        exit;
    }

    $id_usuario = $_SESSION['usuario']['id'];
    $email = trim($_POST['email']);
    $assunto = trim($_POST['assunto']);
    $mensagem = trim($_POST['mensagem']);
    $status = 'aberto';

    try {
        $sql = "INSERT INTO ticket (ID_Usuario, Email, Assunto, Mensagem, Status)
                VALUES (:id_usuario, :email, :assunto, :mensagem, :status)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':assunto', $assunto);
        $stmt->bindParam(':mensagem', $mensagem);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Ticket enviado com sucesso!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Erro ao enviar o ticket.');
                    window.location.href = 'index.php';
                  </script>";
        }
    } catch (PDOException $e) {
        error_log("Erro ao inserir ticket: " . $e->getMessage());
        echo "<script>
                alert('Erro no sistema. Por favor, tente novamente mais tarde.');
                window.location.href = 'index.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Método de requisição inválido.');
            window.location.href = 'index.php';
          </script>";
}
?>
