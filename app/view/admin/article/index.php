<?php

use Kant\Kant;
use Kant\Bootstrap\Html;
use Kant\Widget\ActiveForm;
use Kant\Data\ActiveDataProvider;
use Kant\Grid\GridView;
use Kant\Widget\Pjax;
use Kant\Helper\Url;
use app\models\Article;

$query = Article::find();

$dataProvider = new ActiveDataProvider([
	'query' => $query,
	'pagination' => [
		'pageSize' => 2
	],
		]);
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
						<div class="col-md-12">
							<?php Pjax::begin(); ?>
							<?=
							GridView::widget([
								'layout' => "{items}\n<div class=\"row\"><div class=\"col-sm-6\">{summary}</div><div class=\"col-sm-6\">{pager}</div></div>",
								'tableOptions' => [
									'class' => 'table table-striped table-bordered table-hover'
								],
								'pager' => [
									'pageCssClass' => 'page-item',
									'linkOptions' => [
										'class' => 'page-link'
									]
								],
								'dataProvider' => $dataProvider, 'columns' => [
									[
										'class' => 'Kant\Grid\CheckboxColumn',
										'name' => 'ids',
										'cssClass' => 'ids',
										'checkboxOptions' => function ($model, $key, $index, $column) {
											return [
												'value' => $model['id']
											];
										}
									],
									[
										'class' => 'Kant\Grid\SerialColumn',
									],
									'art_title',
									[
										'class' => 'Kant\Grid\DataColumn',
										'attribute' => 'art_state',
										'value' => function ($model) {
											return $model->getPrettyArticleState($model['art_state']);
										},
									],
									[
										'class' => 'Kant\Grid\ActionColumn',
										'header' => '操作',
										'template' => '{edit}',
										'buttons' => [
											'edit' => function ($url, $model, $key) {
												$url = Url::to('admin/article/edit', ['id' => $model['id']]);
												return Html::a('编辑', $url, [
															'class' => 'btn btn-sm btn-primary'
												]);
											}
										],
									],
								]
							]);
							?>
							<?php Pjax::end() ?>
						</div>
					</div>
                </div>
			</div>
		</div>
	</div>


</div>