<?php include_once "../utils/Score.php" ?>

<div class="panel panel-info score-panel">
    <div class="panel-heading">
        Twoje punkty
    </div>
    <div class="panel-body">
        <div class="<?php if (Score::getInstance()->justScored()): ?>animated bounceIn <?php endif; ?>">
            <?php echo Score::getInstance()->getPoints() ?>
        </div>
    </div>
</div>