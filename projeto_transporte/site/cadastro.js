window.onload = function(){

    const linhaModelo = document.querySelector(".linha-modelo");
    const corpoTabela = document.getElementById("corpoTabela");
    const btnAdd      = document.getElementById("btnAdd");

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

            let temErroCampo     = false;
            let temErroDuplicado = false;
            let temErroEndereco  = false;

            let linhasAtivas = corpoTabela.querySelectorAll('tr:not(.linha-modelo)');

            let nomesNoFormulario = [];
            let nomesBanco = (typeof NOMES_CADASTRADOS !== 'undefined')
                ? NOMES_CADASTRADOS.map(n => n.trim().toLowerCase())
                : [];

            const API_KEY     = "172ff5e777874a13b995e244562a96a5";
            const TIPOS_VALIDOS = ['street', 'amenity', 'building', 'suburb', 'district'];

            // Mostra botão de loading enquanto valida endereços
            const btnSalvar = form.querySelector('.btn-salvar');
            const textoOriginal = btnSalvar ? btnSalvar.textContent : '';
            if (btnSalvar) {
                btnSalvar.disabled = true;
                btnSalvar.textContent = 'Verificando...';
            }

            for (let linha of linhasAtivas) {
                let inputNome     = linha.querySelector('input[name="nome[]"]');
                let inputEndereco = linha.querySelector('input[name="endereco[]"]');

                // ── Campos vazios ──────────────────────────────────────
                [inputNome, inputEndereco].forEach(function(input) {
                    if (input && input.value.trim() === '') {
                        temErroCampo = true;
                        input.style.border = "2px solid red";
                        input.style.backgroundColor = "#ffe6e6";
                    }
                });

                // ── Nomes duplicados ───────────────────────────────────
                if (inputNome && inputNome.value.trim() !== '') {
                    let nomeAtual = inputNome.value.trim().toLowerCase();

                    if (nomesNoFormulario.includes(nomeAtual) || nomesBanco.includes(nomeAtual)) {
                        temErroDuplicado = true;
                        inputNome.style.border = "2px solid #e67e00";
                        inputNome.style.backgroundColor = "#fff3cd";
                    } else {
                        nomesNoFormulario.push(nomeAtual);
                    }
                }

                // ── Validação de endereço via Geoapify ─────────────────
                if (inputEndereco && inputEndereco.value.trim() !== '') {
                    let enderecoFiltrado = inputEndereco.value.trim() + ", Crateús, Ceará, Brasil";
                    let url = "https://api.geoapify.com/v1/geocode/search"
                            + "?text="  + encodeURIComponent(enderecoFiltrado)
                            + "&bias=proximity:-40.6617,-4.9782"
                            + "&limit=1"
                            + "&apiKey=" + API_KEY;

                    try {
                        let resposta = await fetch(url);
                        let dados    = await resposta.json();

                        let achou = false;
                        if (dados.features && dados.features[0]) {
                            let confidence = dados.features[0].properties?.rank?.confidence ?? 0;
                            let tipo       = dados.features[0].properties?.result_type ?? '';
                            if (confidence >= 0.4 && TIPOS_VALIDOS.includes(tipo)) {
                                achou = true;
                            }
                        }

                        if (!achou) {
                            temErroEndereco = true;
                            inputEndereco.style.border = "2px solid red";
                            inputEndereco.style.backgroundColor = "#ffe6e6";
                        }

                    } catch (err) {
                        console.error("Erro ao consultar Geoapify:", err);
                        temErroEndereco = true;
                        inputEndereco.style.border = "2px solid red";
                        inputEndereco.style.backgroundColor = "#ffe6e6";
                    }
                }
            }

            // Reabilita o botão
            if (btnSalvar) {
                btnSalvar.disabled = false;
                btnSalvar.textContent = textoOriginal;
            }

            // ── Monta mensagem de erro ─────────────────────────────────
            let erros = [];
            if (temErroCampo)     erros.push('campos em branco (em vermelho)');
            if (temErroDuplicado) erros.push('nomes repetidos (em laranja)');
            if (temErroEndereco)  erros.push('endereços não encontrados em Crateús (em vermelho)');

            if (erros.length > 0) {
                let mensagemErro = 'Corrija os itens destacados antes de salvar: ' + erros.join(', ') + '.';

                const caixaErro = document.createElement('div');
                caixaErro.id = 'erro-dinamico-js';
                caixaErro.style.cssText =
                    "display:flex; align-items:center; gap:12px;" +
                    "background-color:#fff5f5; border-left:4px solid #e53e3e;" +
                    "border-radius:6px; padding:16px; margin:15px 0;" +
                    "box-shadow:0 2px 8px rgba(0,0,0,0.05);";

                caixaErro.innerHTML =
                    '<span class="material-icons" style="color:#e53e3e; font-size:24px;">error_outline</span>' +
                    '<p style="margin:0; color:#c53030; font-size:0.95rem; line-height:1.5; font-weight:500;">' +
                    mensagemErro + '</p>';

                form.parentNode.insertBefore(caixaErro, form);
                window.scrollTo({ top: 0, behavior: 'smooth' });

            } else {
                form.submit();
            }
        });
    }
};

// Limpa o ?status= da URL após exibir o alerta do PHP
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}