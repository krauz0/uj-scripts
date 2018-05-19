<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Hack The Bank</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/site.css">
    <link rel="stylesheet" href="css/animate.css">

    <!-- jQuery -->
    <script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/index.php">Hack The Bank</a>
            </div>
            <div id="navbar">
                <ul class="nav navbar-nav">
                    <li><a href="/index.php">Start</a></li>
                    <li><a href="/user_data.php">Edycja danych</a></li>
                    <li><a href="/transfer.php">Wykonaj przelew</a></li>
                    <li class="pull-right"><a href="/logout.php">Wyloguj</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php include "score_table.html.php" ?>

    <div class="container">
        <div class="starter-template">
            <?php echo $content ?>
        </div>
    </div>

    <script src="js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>