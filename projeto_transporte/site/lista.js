const busca = document.getElementById("buscarAluno");
const filtroSerie = document.getElementById("filtroSerie");
const filtroCurso = document.getElementById("filtroCurso");
const tabela = document.getElementById("tabelaLista");
const linhas = tabela ? tabela.getElementsByTagName("tr") : [];

function filtrarTabela() {
    let texto = busca ? busca.value.toLowerCase().trim() : "";
    let serie = filtroSerie ? filtroSerie.value.toLowerCase().trim() : "";
    let curso = filtroCurso ? filtroCurso.value.toLowerCase().trim() : "";

    for (let i = 1; i < linhas.length; i++) {
        let linha = linhas[i];
        if (!linha.cells || linha.cells.length < 5) continue;

        let conteudoGeral = linha.textContent.toLowerCase();
        
        // Conforme a nova ordem: Nome=0, Série=1, Curso=2, Endereço=3, Ponto=4
        let colunaSerie = linha.cells[1].textContent.toLowerCase().trim();
        let colunaCurso = linha.cells[2].textContent.toLowerCase().trim();

        // Tratamento para casar value="Informatica" com o texto "Informática"
        if(curso === "informatica") curso = "informática";
        if(curso === "administracao") curso = "administração";

        let buscaOk = conteudoGeral.includes(texto);
        let serieOk = serie === "" || colunaSerie.includes(serie);
        let cursoOk = curso === "" || colunaCurso.includes(curso);

        linha.style.display = (buscaOk && serieOk && cursoOk) ? "" : "none";
    }
}



if (busca) busca.addEventListener("keyup", filtrarTabela);
if (filtroSerie) filtroSerie.addEventListener("change", filtrarTabela);
if (filtroCurso) filtroCurso.addEventListener("change", filtrarTabela);

// MODAIS
const modalVisualizar = document.getElementById("modalVisualizar");
const fecharVisualizar = document.getElementById("fecharVisualizar");

document.querySelectorAll(".btnVisualizar").forEach(botao => {
    botao.addEventListener("click", function() {
        if(document.getElementById("viewNome")) document.getElementById("viewNome").value = this.dataset.nome || "";
        if(document.getElementById("viewSerie")) document.getElementById("viewSerie").value = this.dataset.serie || "";
        if(document.getElementById("viewCurso")) document.getElementById("viewCurso").value = this.dataset.curso || "";
        if(document.getElementById("viewEndereco")) document.getElementById("viewEndereco").value = this.dataset.endereco || "";
        if (modalVisualizar) modalVisualizar.style.display = "flex";
    });
});

if (fecharVisualizar && modalVisualizar) fecharVisualizar.onclick = function() { modalVisualizar.style.display = "none"; };

const modalEditar = document.getElementById("modalEditar");
const fecharEditar = document.getElementById("fecharEditar");

document.querySelectorAll(".btnEditar").forEach(botao => {
    botao.addEventListener("click", function() {
        if(document.getElementById("editId")) document.getElementById("editId").value = this.dataset.id || "";
        if(document.getElementById("editNome")) document.getElementById("editNome").value = this.dataset.nome || "";
        if(document.getElementById("editSerie")) document.getElementById("editSerie").value = this.dataset.serie || "";
        if(document.getElementById("editCurso")) document.getElementById("editCurso").value = this.dataset.curso || "";
        if(document.getElementById("editEndereco")) document.getElementById("editEndereco").value = this.dataset.endereco || "";
        if (modalEditar) modalEditar.style.display = "flex";
    });
});

if (fecharEditar && modalEditar) fecharEditar.onclick = function() { modalEditar.style.display = "none"; };

if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}