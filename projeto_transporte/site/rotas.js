// Busca em Pontos
document.getElementById('buscaPonto').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let linhas = document.querySelectorAll('#tabelaPontos tr');
    for(let i = 1; i < linhas.length; i++) {
        let texto = linhas[i].textContent.toLowerCase();
        linhas[i].style.display = texto.includes(filtro) ? '' : 'none';
    }
});

// Busca em Rotas
document.getElementById('buscaRota').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let linhas = document.querySelectorAll('#tabelaRotas tr');
    for(let i = 1; i < linhas.length; i++) {
        let texto = linhas[i].textContent.toLowerCase();
        linhas[i].style.display = texto.includes(filtro) ? '' : 'none';
    }
});

// Mostrar/esconder motorista secundário
const turnoManha = document.getElementById('turnoManha');
const motoristaSecundario = document.getElementById('motoristaSecundario');

turnoManha.addEventListener('change', function(){
    if(this.value === 'sim'){
        motoristaSecundario.style.display = 'block';
    } else {
        motoristaSecundario.style.display = 'none';
    }
});

// LIMPAR URL APÓS O CARREGAMENTO
// ==========================================
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}