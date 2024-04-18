<?php

use app\models\Categories;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app/categories','Categories');
$this->params['breadcrumbs'][] = $this->title;
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
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Categories $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'visibleButtons' => [
                    'update' => Yii::$app->user->can('editClassifiers'),
                    'delete' => Yii::$app->user->can('editClassifiers'),
                ]
            ],
        ],
        'rowOptions' => function ($model, $key, $index, $column) {
            return [
                'data-id' => $model->id,
                'onclick' => 'location.href="'. Url::toRoute(['/categories/view', 'id' => $model->id]) .'"',
                'style' => 'cursor:pointer',
            ];
        },
    ]); ?>

    <?php Pjax::end(); ?>

</div>
