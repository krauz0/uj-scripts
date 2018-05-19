<?php include "score_table.html.php" ?>
<div class="panel panel-primary login-panel">
    <div class="panel-heading">
        <h2 class="panel-title">Zaloguj się</h2>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-4">
                <p>
                    <strong>Obraz bezpieczeństwa</strong>
                </p>
                <p>
                    <img id="security-image" class="img-thumbnail" src="security_image.php" />
                </p>
            </div>
            <div class="col-lg-8">
                <?php if (isset($loginError)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $loginError ?>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="form-group">
                        <label for="login">Nazwa użytkownika</label>
                        <input name="login" type="text" class="form-control" id="login" value="<?php echo isset($login) ? $login : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło

                        </label>
                        <input name="password" type="password" class="form-control" id="password">
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Zaloguj</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function updateSecurityImage() {
        $('#security-image').attr('src', "security_image.php?username=" + $('[name=login]').val());
    }

    $('[name=login]').blur(function() {
        updateSecurityImage();
    });

    $(function() {
        setTimeout(updateSecurityImage, 1000);
    });
</script>
