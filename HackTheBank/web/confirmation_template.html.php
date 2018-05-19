<div class="page-header">
    <h1>
        <?php if ($transaction['is_own']): ?>
            Potwierdzenie wykonania przelewu
        <?php else: ?>
            Potwierdzenie otrzymania przelewu
        <?php endif; ?>
    </h1>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title">&nbsp;</h2>
            </div>
            <div class="panel-body">

                <div class="dropdown-header">
                    <h1>
                        Kwota: <?php echo StringUtils::formatAmount($transaction['amount']) ?> <?php echo $transaction['currency'] ?>
                        <br>
                        <small class="">Tytułem: <em><?php echo $transaction['title'] ?></em></small>
                        <small class="pull-right">Data: <?php echo $transaction['date'] ?></small>
                    </h1>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">Nadawca</h2>
            </div>
            <div class="panel-body">
                <table class="table user-data-table">
                    <tr>
                        <td>Imię</td>
                        <td><?php echo $transaction['sender_first_name'] ?></td>
                    </tr>

                    <tr>
                        <td>Nazwisko</td>
                        <td><?php echo $transaction['sender_last_name'] ?></td>
                    </tr>

                    <tr>
                        <td>Rachunek</td>
                        <td><?php echo StringUtils::formatAccount($transaction['sender_account_number']) ?></td>
                    </tr>

                    <tr>
                        <td>Adres</td>
                        <td><?php echo $transaction['sender_address'] ?></td>
                    </tr>

                    <tr>
                        <td>Kraj</td>
                        <td><?php echo $countries[$transaction['sender_country']] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Odbiorca</h2>
            </div>
            <div class="panel-body">
                <table class="table user-data-table">
                    <tr>
                        <td>Imię</td>
                        <td><?php echo $transaction['recipient_first_name'] ?></td>
                    </tr>

                    <tr>
                        <td>Nazwisko</td>
                        <td><?php echo $transaction['recipient_last_name'] ?></td>
                    </tr>

                    <tr>
                        <td>Rachunek</td>
                        <td><?php echo StringUtils::formatAccount($transaction['recipient_account_number']) ?></td>
                    </tr>

                    <tr>
                        <td>Adres</td>
                        <td><?php echo $transaction['recipient_address'] ?></td>
                    </tr>

                    <tr>
                        <td>Kraj</td>
                        <td><?php echo $countries[$transaction['recipient_country']] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
    </div>
</div>

<a href="/history.php?account=<?php echo $transaction['account_id'] ?>" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Powrót</a>
