<?php

use Kant\Kant;
use Kant\Bootstrap\Html;
use Kant\Widget\ActiveForm;
use Kant\Helper\Url;
?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-md-12 grid-margin">
			<div class="card">
                <div class="card-body">
					<nav aria-label="breadcrumb" role="navigation">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#"><?= Kant::t('app', 'Home') ?></a></li>
							<li class="breadcrumb-item"><a href="#"><?= Kant::t('app', 'Articles') ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?= Kant::t('app', 'Add Article') ?></li>
						</ol>
					</nav>

					<div class="row">
						<div class="col-md-9 d-flex align-items-stretch grid-margin">
							<div class="row flex-grow">
								<div class="col-12 stretch-card">
									<div class="card">
										<div class="card-body">
											<?php
											$form = ActiveForm::begin([
														'method' => 'post',
														'options' => [
															'autocomplete' => 'off',
														],
														'fieldConfig' => [
															'options' => [
																'class' => 'form-group row'
															],
															'template' => "{label}\n<div class=\"col-md-10 col-sm-10\">{input}</div><div class=\"offset-md-2 offset-sm-2 col-md-10 col-sm-10\">{error}</div>",
															'labelOptions' => [
																'class' => 'col-md-2 col-sm-2 col-form-label'
															],
														]
											]);
											?>

											<?=
											$form->field($articleModel, 'art_title')->textInput([
												'class' => 'form-control',
											]);
											?>

											<?=
											$form->field($articleModel, 'art_keywords')->textInput([
												'class' => 'form-control',
											]);
											?>

											<?=
											$form->field($articleModel, 'art_description')->widget(\Kant\Tinymce\TinymceWidget::class, [
												'clientOptions' => [
													'height' => '200px',
													'elfinder' => Url::to('/elfinder/manager', ['getfile' => 'tinymce'])
												]
											]);
											?>

											<?=
											$form->field($articleModel, 'art_content')->widget(\Kant\Tinymce\TinymceWidget::class, [
												'clientOptions' => [
													'height' => '600px',
													'elfinder' => Url::to('/elfinder/manager', ['getfile' => 'tinymce'])
												]
											]);
											?>

											<?=
											$form->field($articleModel, 'art_thumb')->widget(\Kant\Elfinder\InputFile::className(), [
												'language' => 'zh-CN',
												'controller' => 'elfinder',
												'filter' => 'image',
												'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
												'options' => ['class' => 'form-control'],
												'buttonOptions' => ['class' => 'btn btn-default'],
												'buttonName' => '选择图片',
												'multiple' => false
											]);
											?>

											<?= Html::submitButton('提交', ['class' => 'btn btn-success mr-2', 'name' => 'submit-button']) ?>
											<?php ActiveForm::end(); ?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 grid-margin stretch-card">
							<div class="card">
								<div class="card-body">
									<?=
									$form->field($articleModel, 'art_state', [
										'template' => "{label}\n{input}\n{error}",
										'labelOptions' => [
											'class' => 'col-form-label'
										],
									])->dropDownList($articleModel->articleStates);
									?>

									<?=
									$form->field($articleModel, 'is_link', [
										'template' => "{label}\n<div class='icheck' style='width:100%'>{input}</div>\n{error}",
										'labelOptions' => [
											'class' => 'col-form-label'
										],
									])->radioList([
										'1' => '是',
										'0' => '否',
											], [
										'lable' => 'aaa'
									]);
									?>

									<?=
									$form->field($articleModel, 'art_url', [
										'template' => "{label}\n{input}\n{error}",
										'labelOptions' => [
											'class' => 'col-form-label'
										],
									])->textInput([
										'class' => 'form-control',
									]);
									?>

									<?=
									$form->field($articleModel, 'allow_comment', [
										'template' => "{label}\n<div class='icheck' style='width:100%'>{input}</div>\n{error}",
										'labelOptions' => [
											'class' => 'col-form-label'
										],
									])->radioList([
										'1' => '是',
										'0' => '否',
									]);
									?>

									<?=
									$form->field($articleModel, 'copy_from', [
										'template' => "{label}\n{input}\n{error}",
										'labelOptions' => [
											'class' => 'col-form-label'
										],
									])->textInput([
										'class' => 'form-control',
									]);
									?>

								</div>
							</div>
						</div>
					</div>
                </div>
			</div>
		</div>
	</div>


</div>