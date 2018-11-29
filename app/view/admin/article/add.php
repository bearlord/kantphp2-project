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
															'template' => "{label}\n<div class=\"col-md-9 col-sm-9\">{input}</div><div class=\"offset-md-3 offset-sm-3 col-md-9 col-sm-9\">{error}</div>",
															'labelOptions' => ['class' => 'col-md-3 col-sm-3 col-form-label'],
														]
											]);
											?>

											<?=
											$form->field($articleModel, 'art_title', [
												'errorOptions' => [
													'class' => 'help-block'
												]
											])->textInput([
												'class' => 'form-control',
											]);
											?>

											<?=
											$form->field($articleModel, 'art_content', [
												'errorOptions' => [
													'class' => 'help-block'
												]
											])->widget(\Kant\Tinymce\TinymceWidget::class, [
												'clientOptions' => [
													'height' => '600px',
													'elfinder' => Url::to('/elfinder/manager', ['getfile' => 'tinymce'])
												]
											]);
											?>

											<?=
											$form->field($articleModel, 'art_thumb', [
												'errorOptions' => [
													'class' => 'help-block'
												]
											])->widget(\Kant\Elfinder\InputFile::className(), [
												'language' => 'zh-CN',
												'controller' => 'elfinder',
												'filter' => 'image',
												'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
												'options' => ['class' => 'form-control'],
												'buttonOptions' => ['class' => 'btn btn-default'],
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
									<h4 class="card-title">Basic form</h4>
									<p class="card-description">
										Basic form elements
									</p>
									<form class="forms-sample">
										<div class="form-group">
											<label for="exampleInputName1">Name</label>
											<input type="text" class="form-control" id="exampleInputName1" placeholder="Name">
										</div>
										<div class="form-group">
											<label for="exampleInputEmail3">Email address</label>
											<input type="email" class="form-control" id="exampleInputEmail3" placeholder="Email">
										</div>
										<div class="form-group">
											<label for="exampleInputPassword4">Password</label>
											<input type="password" class="form-control" id="exampleInputPassword4" placeholder="Password">
										</div>
										<div class="form-group">
											<label>File upload</label>
											<input type="file" name="img[]" class="file-upload-default">
											<div class="input-group col-xs-12">
												<input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
												<div class="input-group-append">
													<button class="file-upload-browse btn btn-info" type="button">Upload</button>                          
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="exampleInputCity1">City</label>
											<input type="text" class="form-control" id="exampleInputCity1" placeholder="Location">
										</div>
										<div class="form-group">
											<label for="exampleTextarea1">Textarea</label>
											<textarea class="form-control" id="exampleTextarea1" rows="2"></textarea>
										</div>
										<button type="submit" class="btn btn-success mr-2">Submit</button>
										<button class="btn btn-light">Cancel</button>
									</form>
								</div>
							</div>
						</div>
					</div>

                </div>
			</div>

		</div>



	</div>


</div>