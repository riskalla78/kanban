<?php if (!isLoggedIn()): ?>
    <div class="container">
        <div class="auth-container">
            <h2 class="auth-title">Sistema Kanban</h2>

            <?php if (isset($login_error)): ?>
                <div class="error-message"><?= $login_error ?></div>
            <?php endif; ?>

            <?php if (isset($register_error)): ?>
                <div class="error-message"><?= $register_error ?></div>
            <?php endif; ?>

            <?php if (isset($success_msg)): ?>
                <div class="success-message"><?= $success_msg ?></div>
            <?php endif; ?>

            <div id="login-form">
                <h3 class="form-title">Login</h3>
                <form method="POST">
                    <input type="hidden" name="login" value="1">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%">Entrar</button>
                </form>
                <div class="auth-switch">
                    Não tem uma conta? <a href="#" id="show-register">Registre-se</a>
                </div>
            </div>

            <div id="register-form" style="display:none;">
                <h3 class="form-title">Criar Conta</h3>
                <form method="POST">
                    <input type="hidden" name="register" value="1">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%">Registrar</button>
                </form>
                <div class="auth-switch">
                    Já tem uma conta? <a href="#" id="show-login">Faça login</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#show-register').click(function (e) {
                e.preventDefault();
                $('#login-form').hide();
                $('#register-form').show();
            });

            $('#show-login').click(function (e) {
                e.preventDefault();
                $('#register-form').hide();
                $('#login-form').show();
            });
        });
    </script>
<?php endif; ?>