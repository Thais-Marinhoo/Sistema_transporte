// ESPERA A PÁGINA CARREGAR
window.onload = function(){

    // PEGA ELEMENTOS
    const linhaModelo = document.querySelector(".linha-modelo");
    const corpoTabela = document.getElementById("corpoTabela");
    const btnAdd = document.getElementById("btnAdd");

    // CRIAR LINHA
    function criarLinha(){
        let novaLinha = linhaModelo.cloneNode(true);

        // REMOVE CLASSE MODELO E HABILITA CAMPOS
        novaLinha.classList.remove("linha-modelo");
        novaLinha.style.display = "table-row";

        novaLinha.querySelectorAll("input, select").forEach(campo => {
            campo.removeAttribute("disabled");
            
            if(campo.tagName === 'INPUT') {
                campo.value = "";
                
                // Remove o destaque vermelho instantaneamente assim que começa a digitar
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

    // PRIMEIRA LINHA
    criarLinha();

    // BOTÃO ADICIONAR
    btnAdd.addEventListener("click", function(){
        let quantidade = parseInt(document.getElementById("quantidadeLinhas").value) || 1;
        for(let i = 0; i < quantidade; i++){
            criarLinha();
        }
    });

    // REMOVER
    document.addEventListener("click", function(e){
        let botao = e.target.closest(".btn-remover");
        if(botao){
            botao.closest("tr").remove();
        }
    });

    // =========================================================================
    // VALIDAÇÃO AVANÇADA DO FORMULÁRIO (CAMPOS + API GEOLOCALIZAÇÃO)
    // =========================================================================
    const form = document.getElementById('formCA');

    if (form) {
        // Usamos async/await para conseguir esperar a resposta da API antes de enviar
        form.addEventListener('submit', async function(event) {
            
            // 1. Sempre paramos o envio no início para rodar os testes assíncronos
            event.preventDefault(); 
            
            // Remove caixinhas de erros antigas do topo da tela
            const erroAntigo = document.getElementById('erro-dinamico-js');
            if (erroAntigo) erroAntigo.remove();

            let temErroCampo = false;
            let temErroEndereco = false;
            
            // Pega apenas as linhas que a coordenadora adicionou
            let linhasVisiveis = form.querySelectorAll('tr:not(.linha-modelo)');
            
            // Usamos um laço tradicional for...of para permitir o uso de await dentro dele
            for (let linha of linhasVisiveis) {
                let inputNome = linha.querySelector('input[name="nome[]"]');
                let inputEndereco = linha.querySelector('input[name="endereco[]"]');

                // Validação de Campos Vazios
                [inputNome, inputEndereco].forEach(input => {
                    if (input && input.value.trim() === '') {
                        temErroCampo = true;
                        input.style.border = '2px solid #e53e3e'; 
                        input.style.backgroundColor = '#fff5f5'; 
                    }
                });

                // Se o campo de endereço não estiver vazio, vamos testar na API em tempo real
                if (inputEndereco && inputEndereco.value.trim() !== '') {
                    let enderecoDigitado = inputEndereco.value.trim();
                    let enderecoFiltrado = enderecoDigitado + ", Crateús, Ceará, Brasil";
                    
                    let urlApi = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(enderecoFiltrado)}&format=json&limit=1`;

                    try {
                        // Faz a busca na API de forma silenciosa, sem recarregar nada
                        let resposta = await fetch(urlApi, {
                            headers: { 'User-Agent': 'SistemaTransporteCrateus/1.0 (heitor.almeida2@aluno.ce.gov.br)' }
                        });
                        let dados = await resposta.json();

                        // CORREÇÃO AQUI: Como a API retorna uma lista, checamos se ela está vazia 
                        // ou se o primeiro item da lista [0] não possui a latitude.
                        if (dados.length === 0 || !dados[0] || !dados[0].lat) {
                            temErroEndereco = true;
                            inputEndereco.style.border = '2px solid #e53e3e';
                            inputEndereco.style.backgroundColor = '#fff5f5';
                        }
                    } catch (error) {
                        console.error("Erro ao conectar na API:", error);
                    }

                }
            }

            // 2. EXIBIÇÃO DE MENSAGENS BASEADO NOS ERROS ENCONTRADOS
            if (temErroCampo || temErroEndereco) {
                let mensagemTexto = 'Por favor, preencha todos os campos do aluno. Não deixe linhas em branco.';
                
                if (temErroEndereco && !temErroCampo) {
                    mensagemTexto = 'Um ou mais endereços digitados não foram localizados em Crateús. Verifique a ortografia.';
                } else if (temErroCampo && temErroEndereco) {
                    mensagemTexto = 'Verifique os campos: há informações em branco e endereços inválidos destacados.';
                }

                // Cria a caixinha visual idêntica ao seu alertas.php
                const caixaErro = document.createElement('div');
                caixaErro.id = 'erro-dinamico-js';
                caixaErro.style.cssText = "display: flex; align-items: center; gap: 12px; background-color: #fff5f5; border-left: 4px solid #e53e3e; border-radius: 6px; padding: 16px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); font-family: system-ui, -apple-system, sans-serif;";
                
                caixaErro.innerHTML = `
                    <span class="material-icons" style="color: #e53e3e; font-size: 24px;">error_outline</span>
                    <p style="margin: 0; color: #c53030; font-size: 0.95rem; line-height: 1.5; font-weight: 500;">
                        ${mensagemTexto}
                    </p>
                `;

                form.parentNode.insertBefore(caixaErro, form);
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
            } else {
                // Se passou em todos os testes de campos e de endereços, envia de fato para o PHP!
                form.submit();
            }
        });
    }
};

// LIMPAR URL APÓS O CARREGAMENTO
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}