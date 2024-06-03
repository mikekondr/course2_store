<?php

use app\models\Categories;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\MyHelpers;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app/categories','Categories');
$this->params['breadcrumbs'][] = $this->title;
\app\assets\MyModalViewAsset::register($this);
?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('editClassifiers')): ?>
    <p>
        <?= Html::a(Yii::t('app','Add new'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => array_merge([
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
        ], MyHelpers::getCreatedUpdatedGridCols(), [
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Categories $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'visibleButtons' => [
                    'update' => Yii::$app->user->can('editClassifiers'),
                    'delete' => Yii::$app->user->can('editClassifiers'),
                ],
                'contentOptions' => [
                    'style' => 'width: 80px;',
                    'nowrap' => true,
                ]
            ],
        ]),
        'rowOptions' => function ($model, $key, $index, $column) {
            return [
                'data-id' => $model->id,
                'onclick' => 'show_modal(' . $model->id . ', "'. Html::encode($model->name) .'", "' . Url::toRoute(["categories/view-modal"]) .'")',
                'style' => 'cursor:pointer',
            ];
        },
    ]); ?>

    <?php Pjax::end(); ?>

</div>
