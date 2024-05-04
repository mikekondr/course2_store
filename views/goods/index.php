<?php

use app\models\Goods;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\MyHelpers;

/** @var yii\web\View $this */
/** @var app\models\GoodsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app/goods', 'Goods');
$this->params['breadcrumbs'][] = $this->title;
\app\assets\MyModalViewAsset::register($this);
?>
<div class="goods-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('editClassifiers')): ?>
            <?= Html::a(Yii::t('app/goods', 'Create Goods'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-sm table-striped table-bordered table-hover'],
        'columns' => array_merge([
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width: 75px;'],
            ],
            'name',
            'vendor',
            [
                'attribute' => 'category_name',
                'value' => 'category.name',
            ],
        ], MyHelpers::getCreatedUpdatedGridCols(), [
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Goods $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'contentOptions' => [
                    'style' => 'width: 80px',
                    'nowrap' => true,
                ],
            ],
        ]),
        'rowOptions' => function ($model, $key, $index, $column) {
            return [
                'data-id' => $model->id,
                'onclick' => 'show_modal(' . $model->id . ', "'. Html::encode($model->name) .'", "' . Url::toRoute(["goods/view-modal"]) .'")',
                'style' => 'cursor:pointer',
            ];
        },
    ]); ?>

    <?php Pjax::end(); ?>

</div>
