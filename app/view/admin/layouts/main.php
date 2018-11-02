<?php
    use app\modules\admin\assets\AppAsset;
    AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
    <head>
        <title>layout</title>
        <?php $this->head() ?>
    </head>
    <?php $this->beginBody() ?>
    <body>
         <?php echo $content; ?>
		
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage(true) ?>
