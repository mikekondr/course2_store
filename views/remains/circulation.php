<?php

use app\models\Remains;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Goods circulation');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remains-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'good_id',
            [
                'attribute' => 'good_id',
                'label' => Yii::t('app/goods', 'Good'),
                'value' => function ($model) {
                    return $model->good->name;
                }
            ],
            //'consignment_id',
            [
                'attribute' => 'consignment_id',
                'label' => Yii::t('app/goods', 'Consignment'),
                'value' => function ($model) {
                    return is_null($model->consignment) ? '' : $model->consignment->name;
                },
            ],
            'count',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
