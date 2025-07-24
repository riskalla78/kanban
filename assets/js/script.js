//O código dessa página é responsável pela interatividade do sistema
$(".task-card").draggable({
  revert: "invalid",
  cursor: "move",
  zIndex: 1000,
});

//Torna os cards arrastáveis
$(".tasks-container").droppable({
  accept: ".task-card",
  hoverClass: "hovered",
  drop: function (event, ui) {
    const taskId = ui.draggable.data("task-id");
    const newStatus = $(this).data("status");

    $.post(
      "includes/task_actions.php",
      {
        update_status: 1,
        tarefa_id: taskId,
        novo_status: newStatus,
      },
      function () {
        location.reload();
      },
      "json"
    ).fail(function () {
      alert("Erro ao atualizar status.");
    });
  },
});

// Visualizar tarefa
$(".btn-view").click(function () {
  const taskId = $(this).data("task-id");

  $.getJSON("includes/get_task.php", { tarefa_id: taskId })
    .done(function (response) {
      if (response.success) {
        const task = response.task;

        $("#modal-task-title").text(task.titulo);
        $("#modal-task-description").text(task.descricao || "Sem descrição");
        $("#modal-task-creator").text(task.nome_criador);
        $("#modal-task-created").text(
          new Date(task.data_criacao).toLocaleString()
        );

        const statusMap = {
          A_FAZER: "A Fazer",
          FAZENDO: "Fazendo",
          REVISANDO: "Revisando",
          CONCLUIDO: "Concluído",
        };
        $("#modal-task-status").text(statusMap[task.status] || task.status);

        if (task.status === "CONCLUIDO" && task.data_conclusao) {
          const start = new Date(task.data_criacao);
          const end = new Date(task.data_conclusao);
          const diff = Math.floor((end - start) / 1000);

          const days = Math.floor(diff / (3600 * 24));
          const hours = Math.floor((diff % (3600 * 24)) / 3600);
          const minutes = Math.floor((diff % 3600) / 60);

          let timeStr = "";
          if (days > 0) timeStr += days + " dias ";
          if (hours > 0) timeStr += hours + " horas ";
          if (minutes > 0) timeStr += minutes + " minutos";

          $("#modal-task-total-time").text(timeStr || "Menos de 1 minuto");
        } else {
          $("#modal-task-total-time").text("Tarefa ainda não concluída");
        }

        let priorityClass = "";
        switch (task.prioridade) {
          case "ALTA":
            priorityClass = "priority-HIGH";
            break;
          case "MEDIA":
            priorityClass = "priority-MEDIUM";
            break;
          case "BAIXA":
            priorityClass = "priority-LOW";
            break;
        }

        $("#modal-task-priority")
          .attr("class", "task-priority " + priorityClass)
          .text(
            task.prioridade.charAt(0).toUpperCase() +
              task.prioridade.slice(1).toLowerCase()
          );

        $("#task-modal").dialog({
          modal: true,
          width: 500,
          title: "Detalhes da Tarefa",
        });
      } else {
        alert(response.message);
      }
    })
    .fail(function () {
      alert("Erro ao carregar os dados da tarefa.");
    });
});

// Excluir tarefa
$(".btn-delete").click(function () {
  if (!confirm("Deseja realmente excluir esta tarefa?")) return;

  const taskId = $(this).data("task-id");

  $.post(
    "includes/task_actions.php",
    {
      delete_task: 1,
      tarefa_id: taskId,
    },
    function (response) {
      if (response.success) {
        location.reload();
      } else {
        alert("Erro ao excluir tarefa: " + response.message);
      }
    },
    "json"
  ).fail(function () {
    alert("Erro na requisição de exclusão.");
  });
});

// Editar tarefa
$(".btn-edit").click(function () {
  const taskId = $(this).data("task-id");

  $.getJSON("includes/get_task.php", { tarefa_id: taskId }).done(function (
    response
  ) {
    if (response.success) {
      const task = response.task;
      $("#edit-task-id").val(task.tarefa_id);
      $("#edit-titulo").val(task.titulo);
      $("#edit-descricao").val(task.descricao);
      $("#edit-prioridade").val(task.prioridade);

      $("#edit-task-modal").dialog({
        modal: true,
        title: "Editar Tarefa",
        width: 500,
        buttons: {
          Salvar: function () {
            $.post(
              "includes/task_actions.php",
              $("#edit-task-form").serialize() + "&edit_task=1",
              function () {
                location.reload();
              }
            ).fail(function () {
              alert("Erro ao atualizar tarefa.");
            });
          },
          Cancelar: function () {
            $(this).dialog("close");
          },
        },
      });
    } else {
      alert("Tarefa não encontrada.");
    }
  });
});
