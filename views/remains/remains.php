<?php

use app\models\Remains;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = isset($title) ? $title : Yii::t('app/goods', 'Remains');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remains-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'good_id',
                'label' => Yii::t('app/goods', 'Good'),
                'value' => function ($model) {
                    return $model['name'];
                }
            ],
            [
                'attribute' => 'consignment_id',
                'label' => Yii::t('app/goods', 'Consignment'),
                'value' => function ($model) {
                    return is_null($model['cons_date']) ? '' : date('d.m.Y', $model['cons_date']) . ' (' . $model['price'] . ')';
                },
            ],
            [
                'label' => Yii::t('app/goods', 'Expire'),
                'value' => function ($model) {
                    if (is_null($model['expiry']))
                        return '-';
                    else {
                        $dt = new DateTime(date('Y-m-d', $model['cons_date']));
                        $dt->add(new DateInterval('P' . $model['expiry'] . 'D'));

                        return $dt->format('d.m.Y');
                    }
                }
            ],
            'count',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
