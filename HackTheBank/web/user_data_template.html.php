<div class="page-header">
    <h1>Edycja użytkownika</h1>
</div>

<?php if (!empty($result)): ?>
    <div clasc="row">
        <div class="col-lg-12">
            <div class="alert alert-<?php echo $result['success'] ? "success" : "danger" ?>">
                <?php echo $result['message'] ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Dane osobowe</h2>
                </div>
                <div class="panel-body form-horizontal">
                    <div class="form-group <?php echo isset($validationErrors['firstName']) ? 'has-error' : '' ?>">
                        <label for="firstName" class="col-sm-2 control-label">Imię</label>
                        <div class="col-sm-10">
                            <input type="text" name="firstName" class="form-control" id="firstName" value="<?php echo $request->getHttpParam("firstName", $userData['first_name']) ?>">
                            <?php if (isset($validationErrors['firstName'])): ?>
                                <span class="help-block"><?php echo $validationErrors['firstName'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group <?php echo isset($validationErrors['lastName']) ? 'has-error' : '' ?>">
                        <label for="lastName" class="col-sm-2 control-label">Nazwisko</label>
                        <div class="col-sm-10">
                            <input type="text" name="lastName" class="form-control" id="lastName" value="<?php echo $request->getHttpParam("lastName", $userData['last_name']) ?>">
                            <?php if (isset($validationErrors['lastName'])): ?>
                                <span class="help-block"><?php echo $validationErrors['lastName'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group <?php echo isset($validationErrors['address']) ? 'has-error' : '' ?>">
                        <label for="address" class="col-sm-2 control-label">Adres</label>
                        <div class="col-sm-10">
                            <input type="text" name="address" class="form-control" id="address" value="<?php echo $request->getHttpParam("address", $userData['address']) ?>">
                            <?php if (isset($validationErrors['address'])): ?>
                                <span class="help-block"><?php echo $validationErrors['address'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group <?php echo isset($validationErrors['phone']) ? 'has-error' : '' ?>">
                        <label for="phone" class="col-sm-2 control-label">Telefon</label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" class="form-control" id="phone" value="<?php echo $request->getHttpParam("phone", $userData['phone']) ?>">
                            <?php if (isset($validationErrors['phone'])): ?>
                                <span class="help-block"><?php echo $validationErrors['phone'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group <?php echo isset($validationErrors['country']) ? 'has-error' : '' ?>">
                        <label for="country" class="col-sm-2 control-label">Kraj</label>
                        <div class="col-sm-10">
                            <select name="country" class="form-control" id="country">
                                <?php foreach ($countries as $code => $desc): ?>
                                    <option <?php echo $code == $request->getHttpParam("country", $userData['country']) ? 'selected' : '' ?> value="<?php echo $code ?>">
                                        <?php echo $desc ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($validationErrors['country'])): ?>
                                <span class="help-block"><?php echo $validationErrors['country'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Obraz bezpieczeństwa</h2>
                </div>
                <div class="panel-body form-horizontal">
                    <div class="media">
                        <div class="media-left">
                            <?php if(isset($user['security_image'])): ?>
                                <img src="<?php echo $user['security_image'] ?>" class="img-thumbnail media-object" />
                            <?php endif; ?>
                        </div>
                        <div class="media-body">
                            <div class="form-group <?php echo isset($validationErrors['securityImage']) ? 'has-error' : '' ?>">
                                <label for="securityImage" class="col-sm-2 control-label">Obraz</label>
                                <div class="col-sm-10">
                                    <input type="file" name="securityImage" id="securityImage"">
                                    <?php if (isset($validationErrors['securityImage'])): ?>
                                        <span class="help-block"><?php echo $validationErrors['securityImage'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <a href="/dashboard.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Powrót</a>
        </div>
        <div class="col-lg-6">
            <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-save"></span> Zapisz</button>
        </div>
    </div>
</form>