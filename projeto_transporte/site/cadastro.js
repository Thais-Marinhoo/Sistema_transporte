window.onload = function(){

    const linhaModelo = document.querySelector(".linha-modelo");
    const corpoTabela = document.getElementById("corpoTabela");
    const btnAdd = document.getElementById("btnAdd");

    function criarLinha(){
        let novaLinha = linhaModelo.cloneNode(true);

        novaLinha.classList.remove("linha-modelo");
        novaLinha.style.display = "table-row";

        novaLinha.querySelectorAll("input, select").forEach(campo => {
            campo.removeAttribute("disabled");

            if(campo.tagName === 'INPUT') {
                campo.value = "";

                campo.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.style.border = '';
                        this.style.backgroundColor = '';
                    }
                });
            }
        });

        corpoTabela.appendChild(novaLinha);
    }

    // ❌ NÃO cria linha automaticamente (CORRETO)
    // criarLinha();

    btnAdd.addEventListener("click", function(){
        let quantidade = parseInt(document.getElementById("quantidadeLinhas").value) || 1;

        for(let i = 0; i < quantidade; i++){
            criarLinha();
        }
    });

    document.addEventListener("click", function(e){
        let botao = e.target.closest(".btn-remover");
        if(botao){
            botao.closest("tr").remove();
        }
    });

    const form = document.getElementById('formCA');

    if (form) {
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const erroAntigo = document.getElementById('erro-dinamico-js');
            if (erroAntigo) erroAntigo.remove();

            let temErroCampo = false;
            let temErroEndereco = false;

            let linhasVisiveis = form.querySelectorAll('tr:not(.linha-modelo)');

            for (let linha of linhasVisiveis) {
                let inputNome = linha.querySelector('input[name="nome[]"]');
                let inputEndereco = linha.querySelector('input[name="endereco[]"]');

                [inputNome, inputEndereco].forEach(input => {
                    if (input && input.value.trim() === '') {
                        temErroCampo = true;
                    }
                });

                if (inputEndereco && inputEndereco.value.trim() !== '') {

                    let enderecoFiltrado = inputEndereco.value.trim() + ", Crateús, Ceará, Brasil";
                    let urlApi = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(enderecoFiltrado)}&format=json&limit=1`;

                    try {
                        let resposta = await fetch(urlApi);
                        let dados = await resposta.json();

                        if (dados.length === 0 || !dados[0].lat) {
                            temErroEndereco = true;
                        }

                    } catch (error) {
                        console.error(error);
                    }
                }
            }

            if (temErroCampo || temErroEndereco) {
                alert("Corrija os erros antes de enviar");
            } else {
                form.submit();
            }
        });
    }
};

// limpar URL
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}