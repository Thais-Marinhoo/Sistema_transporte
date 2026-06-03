// ==========================================
// BUSCA EM PONTOS
// ==========================================
document.getElementById('buscaPonto').addEventListener('keyup', function () {
    let filtro = this.value.toLowerCase();
    let linhas = document.querySelectorAll('#tabelaPontos tr');
    for (let i = 1; i < linhas.length; i++) {
        linhas[i].style.display = linhas[i].textContent.toLowerCase().includes(filtro) ? '' : 'none';
    }
});

// ==========================================
// BUSCA EM ROTAS
// ==========================================
document.getElementById('buscaRota').addEventListener('keyup', function () {
    let filtro = this.value.toLowerCase();
    let linhas = document.querySelectorAll('#tabelaRotas tr');
    for (let i = 1; i < linhas.length; i++) {
        linhas[i].style.display = linhas[i].textContent.toLowerCase().includes(filtro) ? '' : 'none';
    }
});

// ==========================================
// MOSTRAR / ESCONDER MOTORISTA SECUNDÁRIO
// ==========================================
document.getElementById('turnoManha').addEventListener('change', function () {
    document.getElementById('motoristaSecundario').style.display =
        this.value === 'sim' ? 'block' : 'none';
});

// MODAL EDITAR PONTO
document.querySelectorAll(".btnEditarPonto").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
        const botao = e.target.closest(".btnEditarPonto"); // ← pega o botão mesmo clicando no ícone

        document.getElementById("editPontoId").value       = botao.dataset.id;
        document.getElementById("editNumeroPonto").value   = botao.dataset.numero;
        document.getElementById("editNomePonto").value     = botao.dataset.nome;
        document.getElementById("editEnderecoPonto").value = botao.dataset.endereco;

        document.getElementById("modalEditarPonto").style.display = "flex";
    });
});

// MODAL EDITAR ROTA
document.querySelectorAll(".btnEditarRota").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
        const botao = e.target.closest(".btnEditarRota"); // ← mesma correção

        document.getElementById("editRotaId").value         = botao.dataset.id;
        document.getElementById("editNomeRota").value       = botao.dataset.rota;
        document.getElementById("editMotoristaRota").value  = botao.dataset.motorista;
        document.getElementById("editMotorista2Rota").value = botao.dataset.motorista2;
        document.getElementById("editStatusRota").value     = botao.dataset.status;
        document.getElementById("editStatus2Rota").value    = botao.dataset.status2;

        document.getElementById("modalEditarRota").style.display = "flex";
    });
});

// Fechar clicando fora do modal
document.getElementById("modalEditarRota").addEventListener("click", function (e) {
    if (e.target === this) this.style.display = "none";
});

// ==========================================
// LIMPAR URL APÓS CARREGAMENTO
// ==========================================
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}