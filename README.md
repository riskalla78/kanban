# 🗂️ Kanban Board

Um sistema Kanban simples e funcional desenvolvido com **PHP**, **MariaDB**, **jQuery** e **CSS responsivo**. Permite que usuários criem, arrastem, editem e excluam tarefas, com controle de usuários e permissões para gerentes. Tarefas com prioridade maior vão para o topo.


https://github.com/user-attachments/assets/876dba78-3a58-416d-a6b1-3164cc20a2ce


## 🧾 Apresentação do Desenvolvimento

Para atingir o resultado proposto de um sistema Kanban funcional, segui os seguintes passos:

1. **Planejamento da estrutura do sistema:**

   - Separei os arquivos em pastas organizadas: `assets/`, `includes/`, `templates/`, e `config/`.
   - Desta forma separei o CSS e javascript em assets, a lógica do backend em inclues, a parte visual em templates, a conexão com o banco de dados em config e o roteamento em index, assim promovendo encapsulamento e divisão de tarefas, boas práticas de programação.

2. **Criação da base de dados e lógica de autenticação:**

   - Desenvolvi scripts SQL para criação das tabelas de usuários e tarefas.
   - Implementei sistema de login com diferenciação entre usuários e gerentes.
   - Sessões foram usadas para controle de autenticação.

3. **Implementação do front-end com drag & drop:**

   - Usei `jQuery UI` para permitir arrastar e soltar tarefas entre colunas.

4. **Funcionalidades dinâmicas com AJAX:**

   - As ações de visualizar, editar, excluir e mover tarefas foram feitas com requisições AJAX para evitar recarregamentos desnecessários.

5. **Responsividade:**

   - Adicionei media queries no CSS para garantir boa visualização e usabilidade em dispositivos móveis.

6. **Melhorias:**

   - Corrigi erros encontrados durante os testes (ex: redirecionamentos, falhas de sessão, prioridade de ordenação).
   - Implementei diferenciação visual por prioridade (Alta, Média, Baixa) e cálculo de tempo de conclusão.

7. **Finalização:**
   - Escrevi este `README.md` para documentar o projeto, orientando instalação, estrutura, funcionamento e decisões técnicas adotadas.

Esse processo garantiu um sistema completo, limpo e funcional, adequado a equipes que queiram organizar tarefas de forma visual e intuitiva.

## 🚀 Funcionalidades

- Login e autenticação de usuários
- Interface responsiva com 4 colunas: A Fazer, Fazendo, Revisando, Concluído
- Drag & drop de tarefas entre colunas (jQuery UI)
- Adicionar, visualizar, editar e excluir tarefas
- Cálculo automático de tempo de execução de tarefas concluídas
- Diferenciação de permissões (usuário comum vs gerente)
- Destaque de prioridades (Alta, Média, Baixa)
- Modal para visualizar e editar tarefas

## 🛠️ Tecnologias Utilizadas

- **PHP 8+**
- **MariaDB**
- **HTML5 + CSS3**
- **jQuery + jQuery UI**
- **Font Awesome**
- **AJAX para interações assíncronas**

## ⚙️ Instalação

1. Clone o repositório:

   ```bash
   git clone https://github.com/seu-usuario/kanban.git
   ```

2. Importe o banco de dados:

   - O arquivo kanban_db no repositório

3. Inicie o servidor local com XAMPP:
   ```
   http://localhost/kanban
   ```

## 👤 Usuário Administrador (exemplo)

- **Usuário:** `admin@kanban.com`
- **Senha:** `admin123`
