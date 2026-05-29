<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

if(!isset($_POST['nome'])){
    die("Nenhum dado recebido!");
}

$nomes = $_POST['nome'];
$series = $_POST['serie'];
$cursos = $_POST['curso'];
$endereco = $_POST['endereco'];

// ... (Seu código existente recebe as variáveis do formulário, ex: $endereco = $_POST['endereco'];)

// 1. Prepara o endereço garantindo que a busca foque em Crateús
$endereco_filtrado = $endereco . ", Crateús, Ceará, Brasil";

// 2. Transforma o texto em um formato que a URL da internet entenda (troca espaços por %20, etc)
$url_api = "https://openstreetmap.org" . urlencode($endereco_filtrado) . "&format=json&limit=1";

// 3. Configura a identificação obrigatória que a API gratuita exige para não bloquear o seu site
$opcoes_requisicao = [
    'http' => [
        'header' => "User-Agent: ProjetoEscolarTransporte/1.0 (heitor.almeida2@aluno.ce.gov.br)\r\n"
    ]
];
$contexto = stream_context_create($opcoes_requisicao);

// 4. Faz o disparo para a API do OpenStreetMap e recebe a resposta em texto
$resposta_texto = file_get_contents($url_api, false, $contexto);

// 5. Converte o texto da resposta em uma lista legível pelo PHP (Array)
$resultado_dados = json_decode($resposta_texto, true);

// 6. Verifica se a API encontrou o local com sucesso
if (!empty($resultado_dados) && isset($resultado_dados[0]['lat'])) {
    
    // Sucesso! Aqui estão as duas variáveis numéricas prontinhas que você precisava
    $latitude = (float) $resultado_dados[0]['lat'];
    $longitude = (float) $resultado_dados[0]['lon'];
    
    // ... (A partir daqui você coloca o seu código de INSERT no banco de dados)
    // Exemplo: usar $latitude e $longitude na sua query do banco.

} else {
    // Caso o endereço digitado seja inválido ou inexistente em Crateús
    echo "Erro: Não conseguimos localizar as coordenadas para o endereço digitado. Verifique a ortografia.";
    exit; // Interrompe para não salvar dados vazios no banco
}


for($i = 0; $i < count($nomes); $i++){

    $nome = trim($nomes[$i]);
    $serie = trim($series[$i]);
    $curso = trim($cursos[$i]);
    $endereco = trim($enderecos[$i]);

    // IGNORA LINHAS VAZIAS
    if(
        $nome == "" ||
        $serie == "" ||
        $curso == "" ||
        $endereco == ""
    ){
        continue;
    }

    // ESCAPA DADOS
    $nomeEsc = mysqli_real_escape_string($conexao, $nome);

    // VERIFICA SE O ALUNO JÁ EXISTE
    $sqlVerifica = "
        SELECT id_aluno
        FROM aluno
        WHERE nome = '$nomeEsc'
    ";

    $resultado = mysqli_query($conexao, $sqlVerifica);

    if($resultado && mysqli_num_rows($resultado) > 0){
        continue;
    }

    // INSERT
    $sql = "
        INSERT INTO aluno
        (
            nome,
            endereco,
            serie,
            curso,
            id_ponto
        )

        VALUES
        (
            '".mysqli_real_escape_string($conexao, $nome)."',

            '".mysqli_real_escape_string($conexao, $endereco)."',

            '".mysqli_real_escape_string($conexao, $serie)."',

            '".mysqli_real_escape_string($conexao, $curso)."',

            NULL
        )
    ";

    $resultadoInsert = mysqli_query($conexao, $sql);

    // MOSTRA ERRO SE DER PROBLEMA
    if(!$resultadoInsert){

        die("Erro no banco: " . mysqli_error($conexao));

    }

}

// REDIRECIONA
header("Location: lista.alunos.php");
exit();

?>
