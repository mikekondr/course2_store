<?php

use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\MyHelpers;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app/users','Users');
$this->params['breadcrumbs'][] = $this->title;
\app\assets\MyModalViewAsset::register($this);
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app','Add new'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => array_merge([
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'fullname',
            [
                'attribute' => 'role',
                'value' => function($model){
                    return Yii::t('app', $model->role);
                }
            ],
        ], MyHelpers::getCreatedUpdatedGridCols(), [
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Users $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'contentOptions' => [
                    'style' => 'width: 80px',
                    'nowrap' => true,
                ]
            ],
        ]),
        'rowOptions' => function ($model, $key, $index, $column) {
            return [
                'data-id' => $model->id,
                'onclick' => 'show_modal(' . $model->id . ', "'. Html::encode($model->fullname) .'", "' . Url::toRoute(["users/view-modal"]) .'")',
                'style' => 'cursor:pointer',
            ];
        },
    ]); ?>

    <?php Pjax::end(); ?>

</div>
