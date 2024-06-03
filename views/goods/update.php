<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Goods $model */

$this->title = Yii::t('app', 'Update {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/goods', 'Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app/goods', 'Update');
?>
<div class="goods-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
