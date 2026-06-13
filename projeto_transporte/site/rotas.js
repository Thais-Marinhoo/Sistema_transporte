// ==========================================
// BUSCA EM PONTOS (seguro)
// ==========================================
const buscaPonto = document.getElementById('buscaPonto');

if (buscaPonto) {
    buscaPonto.addEventListener('keyup', function () {
        let filtro = this.value.toLowerCase();
        let linhas = document.querySelectorAll('#tabelaPontos tr');

        for (let i = 1; i < linhas.length; i++) {
            linhas[i].style.display =
                linhas[i].textContent.toLowerCase().includes(filtro) ? '' : 'none';
        }
    });
}


// ==========================================
// BUSCA EM ROTAS (seguro)
// ==========================================
const buscaRota = document.getElementById('buscaRota');

if (buscaRota) {
    buscaRota.addEventListener('keyup', function () {
        let filtro = this.value.toLowerCase();
        let linhas = document.querySelectorAll('#tabelaRotas tr');

        for (let i = 1; i < linhas.length; i++) {
            linhas[i].style.display =
                linhas[i].textContent.toLowerCase().includes(filtro) ? '' : 'none';
        }
    });
}


// ==========================================
// MOSTRAR / ESCONDER MOTORISTA SECUNDÁRIO
// ==========================================
const turnoManha = document.getElementById('turnoManha');

if (turnoManha) {
    turnoManha.addEventListener('change', function () {
        const box = document.getElementById('motoristaSecundario');
        if (box) {
            box.style.display = this.value === 'sim' ? 'block' : 'none';
        }
    });
}


// ==========================================
// MODAL EDITAR PONTO
// ==========================================
const modalPonto = document.getElementById("modalEditarPonto");
const fecharPonto = document.getElementById("fecharEditarPonto");

document.querySelectorAll(".btnEditarPonto").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
        const botao = e.currentTarget;

        document.getElementById("editPontoId").value       = botao.dataset.id;
        document.getElementById("editNumeroPonto").value   = botao.dataset.numero;
        document.getElementById("editNomePonto").value     = botao.dataset.nome;
        document.getElementById("editEnderecoPonto").value = botao.dataset.endereco;

        if (modalPonto) modalPonto.style.display = "flex";
    });
});

if (fecharPonto) {
    fecharPonto.addEventListener("click", function () {
        if (modalPonto) modalPonto.style.display = "none";
    });
}

if (modalPonto) {
    modalPonto.addEventListener("click", function (e) {
        if (e.target === this) {
            this.style.display = "none";
        }
    });
}


// ==========================================
// MODAL EDITAR ROTA
// ==========================================
const modalRota = document.getElementById("modalEditarRota");
const fecharRota = document.getElementById("fecharEditarRota");

document.querySelectorAll(".btnEditarRota").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
        const botao = e.currentTarget;

        document.getElementById("editRotaId").value         = botao.dataset.id;
        document.getElementById("editNomeRota").value       = botao.dataset.rota;
        document.getElementById("editMotoristaRota").value  = botao.dataset.motorista;
        document.getElementById("editMotorista2Rota").value = botao.dataset.motorista2;
        document.getElementById("editStatusRota").value     = botao.dataset.status;
        document.getElementById("editStatus2Rota").value    = botao.dataset.status2;

        if (modalRota) modalRota.style.display = "flex";
    });
});

if (fecharRota) {
    fecharRota.addEventListener("click", function () {
        if (modalRota) modalRota.style.display = "none";
    });
}

if (modalRota) {
    modalRota.addEventListener("click", function (e) {
        if (e.target === this) {
            this.style.display = "none";
        }
    });
}


// ==========================================
// LIMPAR URL APÓS STATUS
// ==========================================
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}