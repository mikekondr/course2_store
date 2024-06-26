<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Documents $model */
/** @var \app\models\DocumentRows[] $rows title */

$this->title = Yii::t('app', 'Update {name}', ['name' => $model->doc_name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/docs', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documents-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'rows' => $rows,
    ]) ?>

</div>
