const busca = document.getElementById("buscarAluno");
const tabela = document.getElementById("tabelaLista");
const linhas = tabela.getElementsByTagName("tr");

busca.addEventListener("keyup", function () {

    let texto = busca.value.toLowerCase();

    for(let i = 1; i < linhas.length; i++){

        let linha = linhas[i];

        let conteudo = linha.textContent.toLowerCase();

        if(conteudo.includes(texto)){

            linha.style.display = "";

        }else{

            linha.style.display = "none";

        }

    }

});

