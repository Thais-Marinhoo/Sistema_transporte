// ESPERA A PÁGINA CARREGAR
window.onload = function(){

    // PEGA ELEMENTOS
    const linhaModelo = document.querySelector(".linha-modelo");
    const corpoTabela = document.getElementById("corpoTabela");
    const btnAdd = document.getElementById("btnAdd");

    // CRIAR LINHA
    function criarLinha(){
        let novaLinha = linhaModelo.cloneNode(true);

        // REMOVE CLASSE MODELO
        novaLinha.classList.remove("linha-modelo");

        // MOSTRA LINHA
        novaLinha.style.display = "table-row";

        // LIMPA INPUTS E ADICIONA O ESCUTADOR DE DIGITAÇÃO
        novaLinha.querySelectorAll("input").forEach(input => {
            input.value = "";
            
            // FUNÇÃO: Remove o vermelho assim que a pessoa começa a digitar
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.style.border = '';
                    this.style.backgroundColor = '';
                }
            });
        });

        // ADICIONA
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
    // VALIDAÇÃO DO FORMULÁRIO COM MENSAGEM DINÂMICA
    // =========================================================================
    const form = document.getElementById('formCA');

    if (form) {
        form.addEventListener('submit', function(event) {
            let temErro = false;
            
            // PEGA APENAS OS INPUTS DAS LINHAS VISÍVEIS
            let linhasVisiveis = form.querySelectorAll('tr:not(.linha-modelo)');
            
            linhasVisiveis.forEach(linha => {
                let inputs = line = linha.querySelectorAll('input');
                
                inputs.forEach(input => {
                    if (input.value.trim() === '') {
                        event.preventDefault(); // Trava o envio (mantém os dados na tela)
                        temErro = true;
                        
                        // Destaca o input vazio
                        input.style.border = '2px solid #e53e3e'; 
                        input.style.backgroundColor = '#fff5f5'; 
                    }
                });
            });
            
            // Se houver erro, cria a caixinha do alertas.php via JavaScript
            if (temErro) {
                // Remove mensagens de erro antigas para não acumular na tela
                const erroAntigo = document.getElementById('erro-dinamico-js');
                if (erroAntigo) erroAntigo.remove();

                // Cria o HTML idêntico ao do seu alertas.php
                const caixaErro = document.createElement('div');
                caixaErro.id = 'erro-dinamico-js';
                caixaErro.style.cssText = "display: flex; align-items: center; gap: 12px; background-color: #fff5f5; border-left: 4px solid #e53e3e; border-radius: 6px; padding: 16px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); font-family: system-ui, -apple-system, sans-serif;";
                
                caixaErro.innerHTML = `
                    <span class="material-icons" style="color: #e53e3e; font-size: 24px;">error_outline</span>
                    <p style="margin: 0; color: #c53030; font-size: 0.95rem; line-height: 1.5; font-weight: 500;">
                        Por favor, preencha todos os campos do aluno. Não deixe linhas em branco.
                    </p>
                `;

                // Insere a caixinha de erro logo antes do formulário (no topo da tela)
                form.parentNode.insertBefore(caixaErro, form);

                // Sobe a tela suavemente para ela ler o erro e ver os campos vermelhos
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    }
};

// LIMPAR URL APÓS O CARREGAMENTO
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}
