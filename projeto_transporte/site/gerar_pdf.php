<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';
require_once __DIR__ . '/fpdf19/fpdf.php';

// BUSCA DADOS
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

// CRIA PDF
$pdf = new FPDF();
$pdf->AddPage();

/* =========================
   LOGO CENTRALIZADA (TOPO)
========================= */
$logo = __DIR__ . '/imagem.jpeg'; // ajuste extensão se necessário

if (file_exists($logo)) {
    $logoWidth = 25; // tamanho pequeno e moderno
    $pageWidth = 210;
    $x = ($pageWidth - $logoWidth) / 2;

    $pdf->Image($logo, $x, 8, $logoWidth);
}

$pdf->Ln(28); // espaço após logo

/* =========================
   TÍTULO MODERNO
========================= */
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(30, 30, 30);
$pdf->Cell(0, 8, utf8_decode('Alunos por Ponto de Embarque'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(0, 6, utf8_decode('Sistema Rota Certa • Gerado em ' . date('d/m/Y H:i')), 0, 1, 'C');

$pdf->Ln(6);

/* =========================
   CABEÇALHO DA TABELA
========================= */
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(245, 246, 250); // cinza claro moderno
$pdf->SetTextColor(40, 40, 40);

$pdf->Cell(40, 10, utf8_decode('Nº Ponto'), 1, 0, 'C', true);
$pdf->Cell(100, 10, utf8_decode('Nome do Ponto'), 1, 0, 'C', true);
$pdf->Cell(50, 10, utf8_decode('Qtd. Alunos'), 1, 1, 'C', true);

/* =========================
   DADOS
========================= */
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(60, 60, 60);

$fill = false;

if (empty($linhas)) {
    $pdf->Cell(190, 10, utf8_decode('Nenhum ponto cadastrado.'), 1, 1, 'C');
} else {
    foreach ($linhas as $l) {

        // zebra (linhas alternadas)
        $pdf->SetFillColor(250, 250, 250);

        $pdf->Cell(40, 10, $l['numero_ponto'], 1, 0, 'C', $fill);
        $pdf->Cell(100, 10, utf8_decode($l['nome_ponto']), 1, 0, 'L', $fill);
        $pdf->Cell(50, 10, $l['qtd_alunos'], 1, 1, 'C', $fill);

        $fill = !$fill;
    }
}

/* =========================
   TOTAL FINAL
========================= */
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(30, 30, 30);

$pdf->Cell(
    0,
    10,
    utf8_decode("Total geral: {$total_alunos} aluno(s) em " . count($linhas) . " ponto(s)"),
    0,
    1,
    'C'
);

// SAÍDA
$pdf->Output('I', 'alunos_por_ponto.pdf');
exit();
?>