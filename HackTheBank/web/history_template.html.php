<div class="page-header">
    <h1>Historia transakcji</h1>
</div>

<div class="panel panel-default user-accounts">
    <div class="panel-heading">
        <h2 class="panel-title">Historia transakcji dla rachunku <strong><?php echo $account['account_number'] ?></strong></h2>
    </div>
    <div class="panel-body">
        <?php if (!empty($transactions)): ?>
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nadawca</th>
                    <th class="text-center">Odbiorca</th>
                    <th class="text-center">Kwota</th>
                    <th class="text-center">Waluta</th>
                    <th class="text-center">Data transakcji</th>
                    <th></th>
                </tr>

                <?php foreach($transactions as $ord => $transaction): ?>
                    <tr>
                        <td class="text-center"><?php echo $ord + 1 ?></td>
                        <td>
                            <?php echo $transaction['sender'] ?>
                            <mark><small><em>(<?php echo StringUtils::formatAccount($transaction['sender_account_number']) ?>)</em></small></mark>
                        </td>
                        <td>
                            <?php echo $transaction['recipient'] ?>
                            <mark><small><em>(<?php echo StringUtils::formatAccount($transaction['recipient_account_number']) ?>)</em></small></mark>
                        </td>
                        <td class="text-center"><?php echo StringUtils::formatAmount($transaction['amount']) ?></td>
                        <td class="text-center"><?php echo $account['currency'] ?></td>
                        <td class="text-center"><?php echo $transaction['date'] ?></td>
                        <td>
                            <a href="/confirmation.php?transaction=<?php echo $transaction['id'] ?>" class="btn-sm btn-default" data-toggle="tooltip" title="Potwierdzenie przelewu">
                                <span class="glyphicon glyphicon-eye-open"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                Nie odnaleziono transakcji dla rachunku <strong><?php echo $account['account_number'] ?></strong>
            </div>
        <?php endif; ?>

    </div>
</div>
<a href="/dashboard.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Powrót</a>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
