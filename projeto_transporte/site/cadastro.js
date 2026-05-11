// Quando clicar no botão, adiciona nova linha
document.getElementById("btnAdd").addEventListener("click", function () {

    // Pega a tabela
    let tabela = document.getElementById("tabelaAlunos");

    // Pega a linha modelo
    let linhaModelo = document.querySelector(".linha-modelo");

    // Clona a linha
    let novaLinha = linhaModelo.cloneNode(true);

    // Limpa os campos da nova linha
    novaLinha.querySelectorAll("input").forEach(input => input.value = "");

    // Adiciona na tabela
    tabela.appendChild(novaLinha);

});