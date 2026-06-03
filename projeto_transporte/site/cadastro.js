window.onload = function () {

    const linhaModelo = document.querySelector(".linha-modelo");
    const corpoTabela = document.getElementById("corpoTabela");
    const btnAdd = document.getElementById("btnAdd");
    const form = document.getElementById("formCA");

    function criarLinha() {
        let nova = linhaModelo.cloneNode(true);

        nova.classList.remove("linha-modelo");
        nova.style.display = "table-row";

        nova.querySelectorAll("input, select").forEach(el => {
            el.removeAttribute("disabled");
            if (el.tagName === "INPUT") el.value = "";
        });

        corpoTabela.appendChild(nova);
    }

    criarLinha();

    btnAdd.onclick = function () {
        let qtd = parseInt(document.getElementById("quantidadeLinhas").value) || 1;
        for (let i = 0; i < qtd; i++) {
            criarLinha();
        }
    };

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-remover")) {
            e.target.closest("tr").remove();
        }
    });

    // 🔥 SUBMIT COM VALIDAÇÃO SIMPLES (SEM TRAVAR ENVIO)
    form.addEventListener("submit", function (e) {

        let linhas = document.querySelectorAll("tbody tr:not(.linha-modelo)");
        let erro = false;

        linhas.forEach(linha => {
            linha.querySelectorAll("input").forEach(input => {
                if (input.value.trim() === "") {
                    erro = true;
                    input.style.border = "2px solid red";
                }
            });
        });

        if (erro) {
            e.preventDefault();
            alert("Preencha todos os campos antes de salvar.");
        }

        // se não tiver erro → deixa enviar normal
    });

};