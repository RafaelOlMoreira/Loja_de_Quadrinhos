document.getElementById('loginForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const login = e.target[0].value;
  const senha = e.target[1].value;
  //fiz simplezinho agora pq nao sei puxar do banco de dados pra fazer a autentificação :(
  if (login && senha) {
    alert(`Bem-vindo, ${login}!`);
  } else {
    alert('Preencha todos os campos.');
  }
});

// Mostrar mensagem de sucesso se existir na sessão
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const loginSuccess = urlParams.get('login_success');
    
    if (loginSuccess) {
        showFeedback('Login realizado com sucesso!', 'success');
    }
    
    // Fechar mensagem quando clicar no X
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('close-feedback')) {
            e.target.parentElement.remove();
        }
    });
});

function showFeedback(message, type) {
    const feedback = document.createElement('div');
    feedback.className = `feedback-message feedback-${type}`;
    feedback.innerHTML = `
        ${message}
        <button class="close-feedback">&times;</button>
    `;
    
    document.body.appendChild(feedback);
    
    // Remove automaticamente após 5 segundos
    setTimeout(() => {
        feedback.style.opacity = '0';
        setTimeout(() => feedback.remove(), 300);
    }, 5000);
}