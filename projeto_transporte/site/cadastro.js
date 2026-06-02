
// ESPERA A PÁGINA CARREGAR
window.onload = function(){

    // PEGA ELEMENTOS
    const linhaModelo =
    document.querySelector(".linha-modelo");

    const corpoTabela =
    document.getElementById("corpoTabela");

    const btnAdd =
    document.getElementById("btnAdd");

    const form =
    document.querySelector("form");

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

    // REMOVER LINHA
    document.addEventListener("click", function(e){

        let botao =
        e.target.closest(".btn-remover");

        if(botao){

            let linhas =
            corpoTabela.querySelectorAll("tr:not(.linha-modelo)");

            // NÃO DEIXA APAGAR TODAS
            if(linhas.length <= 1){

                alert("Precisa existir pelo menos uma linha.");

                return;

            }

            botao.closest("tr").remove();

        }

    });

    // VALIDAR FORMULÁRIO
    form.addEventListener("submit", function(e){

        let linhas =
        corpoTabela.querySelectorAll("tr:not(.linha-modelo)");

        let possuiAluno = false;

        linhas.forEach(function(linha){

            let nome =
            linha.querySelector('input[name="nome[]"]')
            .value
            .trim();

            let endereco =
            linha.querySelector('input[name="endereco[]"]')
            .value
            .trim();

            if(nome !== "" && endereco !== ""){

                possuiAluno = true;

            }

        });

        // BLOQUEIA ENVIO
        if(!possuiAluno){

            e.preventDefault();

            alert("Cadastre pelo menos um aluno antes de salvar.");

        }

    });

};