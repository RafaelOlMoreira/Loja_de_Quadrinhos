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
