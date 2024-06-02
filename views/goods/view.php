<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\icons\Icon;
Icon::map($this);

/** @var yii\web\View $this */
/** @var app\models\Goods $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/goods', 'Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="goods-view">

    <?php if (!Yii::$app->request->isAjax): ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <?php endif;?>

    <p>
        <?php if (!Yii::$app->request->isAjax): ?>
            <?= Html::a(Icon::show('backward'), Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-success']); ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('editClassifiers')): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'vendor',
            //'category_id',
            'category.name',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
