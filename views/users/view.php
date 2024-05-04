<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\icons\Icon;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/users','Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
Icon::map($this);
?>
<div class="users-view">

    <?php if (!Yii::$app->request->isAjax): ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <?php endif; ?>

    <p>
        <?php if (!Yii::$app->request->isAjax): ?>
            <?= Html::a(Icon::show('backward'), Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-success']); ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app','Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app','Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'fullname',
            [
                'attribute' => 'role',
                'value' => function ($model) {
                    return Yii::t('app', $model->role);
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
