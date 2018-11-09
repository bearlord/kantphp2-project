<?php

use app\modules\admin\assets\ConsoleAsset;

ConsoleAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $this->title ?></title>
	<?php $this->head() ?>
    </head>
<?php $this->beginBody() ?>
    <body>
        <?= $content; ?>
		
<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage(true) ?>
