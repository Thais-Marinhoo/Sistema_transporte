// ESPERA A PÁGINA CARREGAR
window.onload = function(){

    // PEGA ELEMENTOS
    const linhaModelo =
    document.querySelector(".linha-modelo");

    const corpoTabela =
    document.getElementById("corpoTabela");

    const btnAdd =
    document.getElementById("btnAdd");

    // CRIAR LINHA
    function criarLinha(){

        let novaLinha =
        linhaModelo.cloneNode(true);

        // REMOVE CLASSE MODELO
        novaLinha.classList.remove("linha-modelo");

        // MOSTRA LINHA
        novaLinha.style.display = "table-row";

        // LIMPA INPUTS
        novaLinha
        .querySelectorAll("input")
        .forEach(input => {

            input.value = "";

        });

        // ADICIONA
        corpoTabela.appendChild(novaLinha);

    }

    // PRIMEIRA LINHA
    criarLinha();

    // BOTÃO ADICIONAR
    btnAdd.addEventListener("click", function(){

        let quantidade =
        parseInt(
            document.getElementById("quantidadeLinhas").value
        ) || 1;

        for(let i = 0; i < quantidade; i++){

            criarLinha();

        }

    });

    // REMOVER
    document.addEventListener("click", function(e){

        let botao =
        e.target.closest(".btn-remover");

        if(botao){

            botao.closest("tr").remove();

        }

    });

};
window.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");

    form.addEventListener("submit", function () {
        alert("FORMULÁRIO ENVIADO");
    });

});

// LIMPAR URL APÓS O CARREGAMENTO
// ==========================================
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}