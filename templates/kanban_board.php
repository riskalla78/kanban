<?php
// Verifica se a variável $tarefas_por_status existe e é um array válido
// Caso contrário, exibe mensagem de erro e encerra a execução
if (!isset($tarefas_por_status) || !is_array($tarefas_por_status)): ?>
    <p>Erro: tarefas não carregadas corretamente.</p>
    <?php return; ?>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/functions.php';
?>

<?php if (isLoggedIn()): ?>
    <header>
        <div class="header-content">
            <div class="logo">Kanban Board</div>
            <div class="user-info">
                <div class="user-badge">
                    <?= htmlspecialchars($_SESSION['nome']) ?> (<?= isManager() ? 'Gerente' : 'Usuário' ?>)
                </div>
                <a href="?logout" class="btn btn-danger">Sair</a>
            </div>
        </div>
    </header>

    <div class="container">
        <?php if (isManager()): // Se for gerente, mostra alerta especial ?>
            <div class="system-alert">
                <i class="fas fa-crown"></i> Você está logado como Gerente e pode gerenciar todas as tarefas
            </div>
        <?php endif; ?>

        <div class="kanban-board">
            <!-- Loop através dos 4 status possíveis do Kanban -->
            <?php foreach (['A_FAZER' => 'A Fazer', 'FAZENDO' => 'Fazendo', 'REVISANDO' => 'Revisando', 'CONCLUIDO' => 'Concluído'] as $status_key => $status_label): ?>
                <div class="kanban-column column-<?= strtolower($status_key) ?>">
                    <div class="column-header">
                        <span><?= $status_label ?></span>
                        <!-- Mostra a quantidade de tarefas nesse status -->
                        <span class="task-count"><?= count($tarefas_por_status[$status_key]) ?></span>
                    </div>
                    <div class="tasks-container" data-status="<?= $status_key ?>">
                        <?php if (empty($tarefas_por_status[$status_key])): // Se não houver tarefas ?>
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Nenhuma tarefa</p>
                            </div>
                        <?php else: // Se houver tarefas, loop através delas ?>
                            <?php foreach ($tarefas_por_status[$status_key] as $tarefa): ?>
                                <div class="task-card prioridade-<?= $tarefa['prioridade'] ?>"
                                    data-task-id="<?= $tarefa['tarefa_id'] ?>">
                                    <div class="task-header">
                                        <div class="task-title"><?= htmlspecialchars($tarefa['titulo']) ?></div>
                                        <!-- Exibe a prioridade com classes CSS diferentes -->
                                        <div class="task-priority priority-<?=
                                            $tarefa['prioridade'] === 'ALTA' ? 'HIGH' :
                                            ($tarefa['prioridade'] === 'MEDIA' ? 'MEDIUM' : 'LOW')
                                            ?>">
                                            <?= $tarefa['prioridade'] === 'ALTA' ? 'Alta' :
                                                ($tarefa['prioridade'] === 'MEDIA' ? 'Média' : 'Baixa') ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($tarefa['descricao'])): // Se houver descrição ?>
                                        <div class="task-description">
                                            <!-- Limita a descrição a 100 caracteres -->
                                            <?= htmlspecialchars(mb_strimwidth($tarefa['descricao'], 0, 100, "...")) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="task-meta">
                                        <!-- Formata a data de criação -->
                                        <div><?= date('d/m/Y', strtotime($tarefa['data_criacao'])) ?></div>
                                        <div>Criado por: <?= htmlspecialchars($tarefa['nome_criador']) ?></div>
                                    </div>

                                    <?php if ($status_key === 'CONCLUIDO' && $tarefa['data_conclusao']): ?>
                                        <?php
                                        // Calcula o tempo entre criação e conclusão
                                        $inicio = new DateTime($tarefa['data_criacao']);
                                        $fim = new DateTime($tarefa['data_conclusao']);
                                        $intervalo = $inicio->diff($fim);
                                        ?>
                                        <div class="task-time">
                                            <i class="fas fa-clock"></i>
                                            Tempo total:
                                            <?= $intervalo->d > 0 ? $intervalo->d . 'd ' : '' ?>
                                            <?= $intervalo->h > 0 ? $intervalo->h . 'h ' : '' ?>
                                            <?= $intervalo->i > 0 ? $intervalo->i . 'm' : '<1m' ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="task-actions">
                                        <!-- Botões de ação para cada tarefa -->
                                        <button class="btn-icon btn-view" data-task-id="<?= $tarefa['tarefa_id'] ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-icon btn-edit" data-task-id="<?= $tarefa['tarefa_id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <?php if (isManager() || $tarefa['usuario_id'] == $_SESSION['usuario_id']): ?>
                                            <!-- Botão de deletar só aparece para gerentes ou dono da tarefa -->
                                            <button class="btn-icon btn-delete" data-task-id="<?= $tarefa['tarefa_id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Formulário para adicionar novas tarefas -->
        <div class="add-task-form">
            <h3 class="form-title">Adicionar Nova Tarefa</h3>
            <form method="POST" action="/kanban/includes/task_actions.php">
                <input type="hidden" name="add_task" value="1">
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Prioridade</label>
                    <select name="prioridade" class="form-control" required>
                        <option value="BAIXA">Baixa</option>
                        <option value="MEDIA" selected>Média</option>
                        <option value="ALTA">Alta</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Adicionar Tarefa</button>
            </form>
        </div>
    </div>

    <!-- Modal para visualização detalhada (inicialmente oculto) -->
    <div id="task-modal" style="display:none;">
        <div class="task-card" style="border: none; cursor: default;">
            <div class="task-header">
                <h3 class="task-title" id="modal-task-title"></h3>
                <div class="task-priority" id="modal-task-priority"></div>
            </div>
            <div class="task-description" id="modal-task-description"></div>
            <div class="task-meta">
                <div>Criado por: <span id="modal-task-creator"></span></div>
                <div>Criado em: <span id="modal-task-created"></span></div>
            </div>
            <div class="time-info">
                <div>Status atual: <span id="modal-task-status"></span></div>
                <div>Tempo total: <span id="modal-task-total-time"></span></div>
            </div>
        </div>
    </div>

    <!-- Modal para edição de tarefas (inicialmente oculto) -->
    <div id="edit-task-modal" style="display:none;">
        <form id="edit-task-form">
            <input type="hidden" name="tarefa_id" id="edit-task-id">
            <div class="form-group">
                <label>Título</label>
                <input type="text" name="titulo" id="edit-titulo" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea name="descricao" id="edit-descricao" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Prioridade</label>
                <select name="prioridade" id="edit-prioridade" class="form-control" required>
                    <option value="BAIXA">Baixa</option>
                    <option value="MEDIA">Média</option>
                    <option value="ALTA">Alta</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Inclui o JavaScript que controla a interação -->
    <script src="assets/js/script.js"></script>

<?php endif; // Fim da verificação de login ?>