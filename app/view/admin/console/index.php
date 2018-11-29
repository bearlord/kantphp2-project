<?php

use Kant\Kant;
use Kant\Bootstrap\ActiveForm;
use Kant\Bootstrap\Html;
use Kant\Captcha\Captcha;
?>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
		<div class="row">
			<div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-full-bg">
				<div class="row w-100">
					<div class="col-lg-4 mx-auto">
						<div class="auth-form-dark text-left p-5">
							<h2><?= Kant::t("app", "Login") ?></h2>
							<h4 class="font-weight-light"><?= Kant::t("app", "Hello! let's get started"); ?></h4>

							<?php
							$form = ActiveForm::begin([
										'options' => ['class' => 'form-horizontal'],
							]);
							?>

							<?=
							$form->field($model, 'username', [
								'template' => "{label}\n{input}<i class=\"mdi mdi-account\"></i>\n<div>{error}</div>",
								'options' => [
									'class' => 'form-group',
									'style' => 'height: 80px'
								]
							])->textInput(['placeholder' => Kant::t('app', 'Please enter username')])
							?>

							<?=
							$form->field($model, 'password', [
								'template' => "{label}\n{input}<i class=\"mdi mdi-eye\"></i>\n<div>{error}</div>",
								'options' => [
									'class' => 'form-group',
									'style' => 'height: 80px'
								]
							])->passwordInput(['placeholder' => Kant::t('app', 'Please enter password')])
							?>

							<div class="form-group">
								<?=
								$form->field($model, 'verifyCode', [
									'options' => [
										'class' => 'form-group',
										'style' => 'height: 100px'
									]
								])->widget(Captcha::className(), [
									'captchaAction' => '/common/service/captcha',
									'template' => '<div class="row" style="position:relative"><div class="col-sm-12">{input}</div><div class="col-sm-3" style="position:absolute;right:0;top:-10px;text-align:right">{image}</div></div>',
									'imageOptions' => ['alt' => '点击换图', 'title' => '点击换图', 'style' => 'cursor:pointer; height:40px;'],
									'options' => [
										'class' => 'form-control',
										'placeholder' => Kant::t('app', 'Please enter captcha')
									]
								])
								?>
							</div>

							<div class="mt-5">
<?= Html::submitButton('登录', ['class' => 'btn btn-block btn-warning btn-lg font-weight-medium', 'name' => 'submit-button']) ?>
							</div>
<?php ActiveForm::end(); ?>
						</div>
					</div>
				</div>
			</div>
			<!-- content-wrapper ends -->
		</div>
		<!-- row ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->