<div class="page-header">
    <h1>Nowy przelew</h1>
</div>

<?php if (!empty($result)): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-<?php echo $result['success'] ? "success" : "danger" ?>">
                <?php if ($result['success']): ?>
                    <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                <?php else: ?>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php endif; ?>

                <?php echo $result['message'] ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <form method="post" action="transfer.php">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">Nowy przelew</h2>
            </div>
            <div class="panel-body form-horizontal">
                <div class="form-group <?php echo isset($validationErrors['fromAccount']) ? 'has-error' : '' ?>">
                    <label for="fromAccount" class="col-sm-2 control-label">Z rachunku</label>
                    <div class="col-sm-10">
                        <select name="fromAccount" class="form-control" id="fromAccount">
                            <option value="0">Wybierz</option>
                            <?php foreach ($accounts as $account): ?>
                                <option data-currency="<?php echo $account['currency'] ?>" value="<?php echo $account['id'] ?>" <?php echo $request->getHttpParam('fromAccount') == $account['id'] ? 'selected' : '' ?>>
                                    <?php echo StringUtils::formatAccount($account['account_number']) ?> (<?php echo $account['currency'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($validationErrors['fromAccount'])): ?>
                            <span class="help-block"><?php echo $validationErrors['fromAccount'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="recipient" class="col-sm-2 control-label">Odbiorca przelewu</label>
                    <div class="col-sm-10">
                        <select name="recipient" class="form-control" id="recipient">
                            <option value="0">Wybierz</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['user_id'] ?>" <?php echo $request->getHttpParam('recipient') == $user['user_id'] ? 'selected' : '' ?>>
                                    <?php echo $user['first_name'] ?> <?php echo $user['last_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group <?php echo isset($validationErrors['toAccount']) ? 'has-error' : '' ?>">
                    <label for="toAccount" class="col-sm-2 control-label">Na rachunek</label>
                    <div class="col-sm-10">
                        <select name="toAccount" class="form-control" id="toAccount" <?php echo $request->getHttpParam('recipient') == 0 ? 'disabled' : '' ?>>
                            <?php if($request->getHttpParam('recipient') == 0): ?>
                                <option value="0">Wybierz odbiorcę przelewu</option>
                            <?php else: ?>
                                <option value="0">Wybierz</option>
                            <?php endif; ?>

                            <?php foreach ($recipientAccounts as $account): ?>
                                <option value="<?php echo $account['id'] ?>" <?php echo $request->getHttpParam('toAccount') == $account['id'] ? 'selected' : '' ?>>
                                    <?php echo StringUtils::formatAccount($account['account_number']) ?> (<?php echo $account['currency'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($validationErrors['toAccount'])): ?>
                            <span class="help-block"><?php echo $validationErrors['toAccount'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group <?php echo isset($validationErrors['amount']) ? 'has-error' : '' ?>">
                    <label for="amount" class="col-sm-2 control-label">Kwota przelewu</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input name="amount" type="text" class="form-control" id="amount"  value="<?php echo $request->getHttpParam("amount") ?>">
                            <div class="input-group-addon" id="amount-currency">PLN</div>
                        </div>
                        <?php if (isset($validationErrors['amount'])): ?>
                            <span class="help-block"><?php echo $validationErrors['amount'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group <?php echo isset($validationErrors['title']) ? 'has-error' : '' ?>">
                    <label for="title" class="col-sm-2 control-label">Tytuł przelewu</label>
                    <div class="col-sm-10">
                        <textarea name="title" class="form-control" id="title"><?php echo $request->getHttpParam("title") ?></textarea>
                        <?php if (isset($validationErrors['title'])): ?>
                            <span class="help-block"><?php echo $validationErrors['title'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <a href="/dashboard.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Powrót</a>
            </div>
            <div class="col-lg-6">
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-send"></span> Wykonaj przelew</button>
            </div>
        </div>

    </form>
</div>

<script type="text/javascript">

    function fillRecipientAccounts(accounts) {
        var $recipientAccountsSelect = $('#toAccount');
        $recipientAccountsSelect.attr('disabled', accounts.length <= 0);

        if (!$recipientAccountsSelect.is(':disabled')) {
            $recipientAccountsSelect.find('option[value=0]').text("Wybierz");
        } else {
            $recipientAccountsSelect.find('option[value=0]').text("Wybierz odbiorcę przelewu");
        }

        $recipientAccountsSelect.find('option[value!=0]').remove();

        console.log(accounts);

        for (var i in accounts) {
            var account = accounts[i];
            var $option = $('<option/>')
                .val(account.id)
                .text(account.account_number + ' (' + account.currency + ')');

            $recipientAccountsSelect.append($option);
        }
    }

    $('#recipient').change(function() {
        $.ajax({
            url: "user_accounts.php",
            method: 'post',
            data: {user: $(this).val()},
            dataType: 'json',
            success: function(json) {
                fillRecipientAccounts(json);
            }

        });
    });

    $('#fromAccount').change(function() {
        var currency = $('option:selected', this).data('currency');
        $('#amount-currency').text(currency || 'PLN');
    });

    $(function() {
        $('#fromAccount').change();
    });
</script>