document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const nome = document.getElementById("nome").value.trim();
    const descricao = document.getElementById("descricao").value.trim();
    const data = document.getElementById("data").value;
    const hora = document.getElementById("hora").value;
    const local = document.getElementById("local").value.trim();
    const categoria = document.getElementById("categoria").value;

    if (!nome || !descricao || !data || !hora || !local || !categoria) {
      alert("⚠️ Por favor, preencha todos os campos obrigatórios!");
      return;
    }

    alert("✅ Evento publicado com sucesso!");
    form.reset(); 
  });
});
