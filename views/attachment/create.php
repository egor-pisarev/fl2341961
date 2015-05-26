<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model egorpisarev\document\models\Attachment */

$this->title = Yii::t('app', 'Create Attachment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attachments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attachment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
