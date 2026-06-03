window.onload = function () {

    const linhaModelo = document.querySelector(".linha-modelo");
    const corpoTabela = document.getElementById("corpoTabela");
    const btnAdd      = document.getElementById("btnAdd");

    // ==========================================
    // CRIAR LINHA (clona o modelo e habilita os campos)
    // ==========================================
    function criarLinha() {
        let novaLinha = linhaModelo.cloneNode(true);

        novaLinha.classList.remove("linha-modelo");
        novaLinha.style.display = "table-row";

        // Remove o disabled de todos os campos clonados
        novaLinha.querySelectorAll("input, select").forEach(function (campo) {
            campo.removeAttribute("disabled");

            // Limpa o valor dos inputs de texto
            if (campo.tagName === 'INPUT') {
                campo.value = "";
            }

            // Remove destaque vermelho assim que começa a digitar
            campo.addEventListener('input', function () {
                if (this.value.trim() !== '') {
                    this.style.border = '';
                    this.style.backgroundColor = '';
                }
            });
        });

        corpoTabela.appendChild(novaLinha);
    }

    // Começa com uma linha pronta
    criarLinha();

    // Botão adicionar mais linhas
    btnAdd.addEventListener("click", function () {
        let quantidade = parseInt(document.getElementById("quantidadeLinhas").value) || 1;
        for (let i = 0; i < quantidade; i++) {
            criarLinha();
        }
    });

    // Botão remover linha (protege para não remover a última)
    document.addEventListener("click", function (e) {
        let botao = e.target.closest(".btn-remover");
        if (botao) {
            let linhasReais = corpoTabela.querySelectorAll('tr:not(.linha-modelo)');
            if (linhasReais.length <= 1) {
                alert("É necessário ter pelo menos uma linha.");
                return;
            }
            botao.closest("tr").remove();
        }
    });

    // ==========================================
    // VALIDAÇÃO ANTES DE ENVIAR
    // ==========================================
    const form = document.getElementById('formCA');

    if (form) {
        form.addEventListener('submit', async function (event) {
            // Segura o envio para validar primeiro
            event.preventDefault();

            // Remove mensagem de erro anterior se existir
            const erroAntigo = document.getElementById('erro-dinamico-js');
            if (erroAntigo) erroAntigo.remove();

            // Remove todos os destaques vermelhos anteriores
            form.querySelectorAll('input').forEach(function (input) {
                input.style.border = '';
                input.style.backgroundColor = '';
            });

            let temErroCampo    = false;
            let temErroEndereco = false;

            // Pega só as linhas reais (não o modelo escondido)
            let linhasVisiveis = corpoTabela.querySelectorAll('tr:not(.linha-modelo)');

            for (let linha of linhasVisiveis) {
                let inputNome     = linha.querySelector('input[name="nome[]"]');
                let inputEndereco = linha.querySelector('input[name="endereco[]"]');

                // Destaca campos de texto vazios
                [inputNome, inputEndereco].forEach(function (input) {
                    if (input && input.value.trim() === '') {
                        temErroCampo = true;
                        input.style.border          = '2px solid #e53e3e';
                        input.style.backgroundColor = '#fff5f5';
                    }
                });

                // Verifica endereço na API do Nominatim (só se preenchido)
                if (inputEndereco && inputEndereco.value.trim() !== '') {
                    let enderecoFiltrado = inputEndereco.value.trim() + ", Crateús, Ceará, Brasil";
                    let urlApi = "https://nominatim.openstreetmap.org/search?q=" +
                                 encodeURIComponent(enderecoFiltrado) + "&format=json&limit=1";

                    try {
                        let resposta = await fetch(urlApi, {
                            headers: {
                                'User-Agent': 'SistemaTransporteCrateus/1.0 (heitor.almeida2@aluno.ce.gov.br)'
                            }
                        });
                        let dados = await resposta.json();

                        // Se a API não encontrou o endereço, destaca de vermelho
                        if (dados.length === 0 || !dados[0] || !dados[0].lat) {
                            temErroEndereco = true;
                            inputEndereco.style.border          = '2px solid #e53e3e';
                            inputEndereco.style.backgroundColor = '#fff5f5';
                        }

                    } catch (erro) {
                        // Se a API falhar (sem internet, timeout etc.), deixa o PHP decidir
                        console.error("Erro ao consultar API de endereço:", erro);
                    }
                }
            }

            // Se encontrou algum erro, mostra a mensagem e NÃO envia
            if (temErroCampo || temErroEndereco) {

                let mensagem = 'Por favor, preencha todos os campos. Não deixe linhas em branco.';

                if (temErroEndereco && !temErroCampo) {
                    mensagem = 'Um ou mais endereços não foram localizados em Crateús. Verifique a ortografia.';
                } else if (temErroCampo && temErroEndereco) {
                    mensagem = 'Verifique os campos destacados: há informações em branco e endereços inválidos.';
                }

                // Cria a caixa de erro no mesmo estilo do alertas.php
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
                    mensagem + '</p>';

                // Insere a mensagem acima do formulário
                form.parentNode.insertBefore(caixaErro, form);
                window.scrollTo({ top: 0, behavior: 'smooth' });

            } else {
                // Tudo certo — envia para o cadastroback.php
                form.submit();
            }
        });
    }

};

// Limpa o ?status= da URL após carregar a página (evita que a mensagem reapareça ao recarregar)
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}