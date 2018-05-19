<div class="page-header">
    <h1>Panel sterowania</h1>
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="panel panel-default user-data">
            <div class="panel-heading">
                <h2 class="panel-title">Twoje dane</h2>
            </div>
            <div class="panel-body">
                <div class="media">
                    <div class="media-left">
                        <?php if(isset($user['security_image'])): ?>
                            <img src="<?php echo $user['security_image'] ?>" class="img-thumbnail media-object" />
                        <?php endif; ?>
                    </div>
                    <div class="media-body">
                        <table class="table table-condensed user-data-table">
                            <tr>
                                <td>Nazwa użytkownika</td>
                                <td><strong><?php echo $user['name'] ?></strong></td>
                            </tr>
                            <tr>
                                <td>Imię</td>
                                <td><?php echo $userData['first_name'] ?></td>
                            </tr>
                            <tr>
                                <td>Nazwisko</td>
                                <td><?php echo $userData['last_name'] ?></td>
                            </tr>
                            <tr>
                                <td>Adres</td>
                                <td><?php echo $userData['address'] ?></td>
                            </tr>
                            <tr>
                                <td>Kraj</td>
                                <td><?php echo $countries[$userData['country']] ?></td>
                            </tr>
                            <tr>
                                <td>Telefon</td>
                                <td><?php echo $userData['phone'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <a href="/user_data.php" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-edit"></span> Edytuj</a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="panel panel-default user-accounts">
            <div class="panel-heading">
                <h2 class="panel-title">Twoje konta</h2>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Numer</th>
                        <th class="text-center">Dostępne środki</th>
                        <th class="text-center">Waluta</th>
                        <th></th>
                    </tr>

                    <?php foreach($accounts as $ord => $account): ?>
                        <tr>
                            <td class="text-center"><?php echo $ord + 1 ?></td>
                            <td class="text-center"><?php echo StringUtils::formatAccount($account['account_number']) ?></td>
                            <td class="text-center"><?php echo StringUtils::formatAmount($account['balance']) ?></td>
                            <td class="text-center"><?php echo $account['currency'] ?></td>
                            <td>
                                <a href="/history.php?account=<?php echo $account['id'] ?>" class="btn-sm btn-default" data-toggle="tooltip" title="Historia"><span class="glyphicon glyphicon-hourglass"></span></a>
                                &nbsp;
                                <a href="/transfer.php?fromAccount=<?php echo $account['id'] ?>" class="btn-sm btn-default" data-toggle="tooltip" title="Nowy przelew"><span class="glyphicon glyphicon-send"></span></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>