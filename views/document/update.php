<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model egorpisarev\document\models\Document */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Document',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="document-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<h2><?=Yii::t('app','Uploaded Attachments');?></h2>
<?php Pjax::begin(); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'filename',
        'size',
        'created_at:datetime',
        'updated_at:datetime',
        ['class' => 'yii\grid\ActionColumn',
            'buttons' => [
            'delete' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/attachment/delete','id'=>$model->id], [
                    'title' => Yii::t('yii', 'Delete'),
                    //'data-pjax'=>'w0',
                ]);
            }
        ]],
    ],
]); ?>
<?php Pjax::end(); ?>
<h2><?=Yii::t('app','Upload New Attachments');?></h2>
<?php if(!$model->isNewRecord): ?>
    <?= \kato\DropZone::widget([
        'uploadUrl'=> Yii::$app->urlManager->createUrl(['/document/upload','id'=> $model->id])
    ]);?>
<?php endif; ?>

