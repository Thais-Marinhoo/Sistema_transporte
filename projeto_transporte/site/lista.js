const busca =
document.getElementById("buscarAluno");

const filtroSerie =
document.getElementById("filtroSerie");

const filtroCurso =
document.getElementById("filtroCurso");

const tabela =
document.getElementById("tabelaLista");

const linhas =
tabela.getElementsByTagName("tr");

/* FILTROS */

function filtrarTabela(){

    let texto =
    busca.value.toLowerCase();

    let serie =
    filtroSerie.value.toLowerCase();

    let curso =
    filtroCurso.value.toLowerCase();

    for(let i = 1; i < linhas.length; i++){

        let linha = linhas[i];

        let conteudo =
        linha.textContent.toLowerCase();

        let colunaSerie =
        linha.cells[1].textContent.toLowerCase();

        let colunaCurso =
        linha.cells[2].textContent.toLowerCase();

        let buscaOk =
        conteudo.includes(texto);

        let serieOk =
        serie == "" ||
        colunaSerie.includes(serie);

        let cursoOk =
        curso == "" ||
        colunaCurso.includes(curso);

        linha.style.display =
        buscaOk && serieOk && cursoOk
        ? ""
        : "none";

    }

}

busca.addEventListener(
    "keyup",
    filtrarTabela
);

filtroSerie.addEventListener(
    "change",
    filtrarTabela
);

filtroCurso.addEventListener(
    "change",
    filtrarTabela
);

/* MODAL VISUALIZAR */

const modalVisualizar =
document.getElementById("modalVisualizar");

const fecharVisualizar =
document.getElementById("fecharVisualizar");

document
.querySelectorAll(".btnVisualizar")

.forEach(botao => {

    botao.addEventListener("click", function(){

        document.getElementById("viewNome")
        .value = this.dataset.nome;

        document.getElementById("viewSerie")
        .value = this.dataset.serie;

        document.getElementById("viewCurso")
        .value = this.dataset.curso;

        document.getElementById("viewEndereco")
        .value = this.dataset.endereco;

        modalVisualizar.style.display = "flex";

    });

});

fecharVisualizar.onclick = function(){

    modalVisualizar.style.display = "none";

};

/* MODAL EDITAR */

const modalEditar =
document.getElementById("modalEditar");

const fecharEditar =
document.getElementById("fecharEditar");

document
.querySelectorAll(".btnEditar")

.forEach(botao => {

    botao.addEventListener("click", function(){

        document.getElementById("editId")
        .value = this.dataset.id;

        document.getElementById("editNome")
        .value = this.dataset.nome;

        document.getElementById("editSerie")
        .value = this.dataset.serie;

        document.getElementById("editCurso")
        .value = this.dataset.curso;

        document.getElementById("editEndereco")
        .value = this.dataset.endereco;

        modalEditar.style.display = "flex";

    });

});

fecharEditar.onclick = function(){

    modalEditar.style.display = "none";

};