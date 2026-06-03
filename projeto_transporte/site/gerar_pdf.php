<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
include '../conexao.php';
 
// Busca pontos com a quantidade de alunos em cada um
$sql = "
    SELECT 
        p.numero_ponto,
        p.nome_ponto,
        COUNT(a.id_aluno) AS qtd_alunos
    FROM ponto p
    LEFT JOIN aluno a ON a.id_ponto = p.id_ponto
    GROUP BY p.id_ponto, p.numero_ponto, p.nome_ponto
    ORDER BY p.numero_ponto ASC
";
$resultado = mysqli_query($conexao, $sql);
 
$total_alunos = 0;
$linhas = [];
while ($linha = mysqli_fetch_assoc($resultado)) {
    $total_alunos += $linha['qtd_alunos'];
    $linhas[] = $linha;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alunos por Ponto - Rota Certa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #fff;
            color: #222;
        }
 
        .cabecalho {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0b2c5f;
            padding-bottom: 15px;
        }
 
        .cabecalho h1 {
            color: #0b2c5f;
            font-size: 28px;
            margin: 0 0 5px 0;
        }
 
        .cabecalho p {
            color: #555;
            font-size: 14px;
            margin: 0;
        }
 
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
 
        thead tr {
            background: #0b2c5f;
            color: #fff;
        }
 
        thead th {
            padding: 13px 15px;
            text-align: left;
            font-size: 14px;
        }
 
        tbody tr:nth-child(even) {
            background: #f4f6f9;
        }
 
        tbody tr:hover {
            background: #e8f1ff;
        }
 
        tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
 
        .td-qtd {
            text-align: center;
            font-weight: bold;
            color: #0b2c5f;
        }
 
        .rodape {
            margin-top: 25px;
            text-align: right;
            font-size: 13px;
            color: #555;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
 
        .btn-imprimir {
            display: inline-block;
            margin-bottom: 25px;
            padding: 12px 28px;
            background: #ffc107;
            color: #0b1f3a;
            font-weight: bold;
            font-size: 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
 
        /* Esconde o botão ao imprimir */
        @media print {
            .btn-imprimir {
                display: none;
            }
 
            body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
 
    <button class="btn-imprimir" onclick="window.print()">
        🖨️ Imprimir / Salvar PDF
    </button>
 
    <div class="cabecalho">
        <h1>Alunos por Ponto de Embarque</h1>
        <p>Sistema Rota Certa &nbsp;|&nbsp; Gerado em: <?= date('d/m/Y \à\s H:i') ?></p>
    </div>
 
    <table>
        <thead>
            <tr>
                <th>Nº Ponto</th>
                <th>Nome do Ponto</th>
                <th style="text-align:center;">Qtd. Alunos</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($linhas)): ?>
                <tr>
                    <td colspan="3" style="text-align:center; color:#888;">
                        Nenhum ponto cadastrado.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($linhas as $linha): ?>
                <tr>
                    <td><?= htmlspecialchars($linha['numero_ponto']) ?></td>
                    <td><?= htmlspecialchars($linha['nome_ponto']) ?></td>
                    <td class="td-qtd"><?= $linha['qtd_alunos'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
 
    <div class="rodape">
        Total geral: <strong><?= $total_alunos ?> aluno(s)</strong> distribuídos em <strong><?= count($linhas) ?> ponto(s)</strong>
    </div>
 
</body>
</html>