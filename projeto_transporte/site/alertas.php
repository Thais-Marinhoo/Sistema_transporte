<?php
// Captura o status atual da URL
$status_atual = $_GET['status'] ?? '';

// 1. Dicionário de todas as mensagens de ERRO do sistema
$mensagens_erro = [
    'endereco'   => 'Não foi possível encontrar as coordenadas para o endereço digitado. Verifique a Ortografia ou se escreveu o nome da forma indicada.',
    'endereco_invalido' => 'O novo endereço digitado na edição não foi localizado em Crateús. Verifique a ortografia.',
    'erro_r'      => 'Não foi possível cadastrar a rota. Verifique se preencheu todos os campos obrigatórios e selecionou pelo menos um ponto.',
    'sem_pontos' => 'Não é possível cadastrar um aluno sem pontos cadastrados no sistema. Cadastre um ponto antes.',
    'erro_pdeletar' => 'Não é possível deletar o último ponto do sistema. Cadastre outro ponto antes.',
    'erro_p'     => 'Não é possível cadastrar o ponto. Verifique os dados e tente novamente.',
    'erro_rdeletada' => 'Não foi possível deletar a rota.'
];

// 2. Dicionário de todas as mensagens de SUCESSO do sistema
$mensagens_sucesso = [
    'sucesso_p'  => 'Ponto cadastrado com sucesso!',
    'sucesso_peditar'  => 'Ponto editado com sucesso!',
    'sucesso_pdeletado' => 'Ponto deletado com sucesso!',
    'sucesso_rdeletada' => 'Rota deletada com sucesso!',
    'sucesso_aluno' => 'Aluno cadastrado com sucesso!',
    'sucesso_edicao' => 'Dados do aluno atualizados e rota recalculada com sucesso!',
    'sucesso_exclusao' => 'Aluno removido do sistema com sucesso.',
    'sucesso_ano' => 'Virada de ano concluída! Alunos promovidos e turmas atualizadas.',
    'sucesso_r' => 'Rota cadastrada com sucesso!'
];

// 3. Renderização Automática do ERRO
if (array_key_exists($status_atual, $mensagens_erro)) {
    echo '
    <div style="display: flex; align-items: center; gap: 12px; background-color: #fff5f5; border-left: 4px solid #e53e3e; border-radius: 6px; padding: 16px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); font-family: system-ui, -apple-system, sans-serif;">
        <span class="material-icons" style="color: #e53e3e; font-size: 24px;">error_outline</span>
        <p style="margin: 0; color: #c53030; font-size: 0.95rem; line-height: 1.5; font-weight: 500;">
            ' . $mensagens_erro[$status_atual] . '
        </p>
    </div>';
}

// 4. Renderização Automática do SUCESSO
if (array_key_exists($status_atual, $mensagens_sucesso)) {
    echo '
    <div style="display: flex; align-items: center; gap: 12px; background-color: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 6px; padding: 16px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); font-family: system-ui, -apple-system, sans-serif;">
        <span class="material-icons" style="color: #16a34a; font-size: 24px;">check_circle_outline</span>
        <p style="margin: 0; color: #14532d; font-size: 0.95rem; line-height: 1.5; font-weight: 500;">
            ' . $mensagens_sucesso[$status_atual] . '
        </p>
    </div>';
}
?>
