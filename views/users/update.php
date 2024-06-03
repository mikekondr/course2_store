<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = Yii::t('app','Update {name}', ['name' => $model->username]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/users','Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app','Update');
?>
<div class="users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
