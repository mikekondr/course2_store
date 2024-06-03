<?php

use app\models\Documents;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\MyHelpers;
/** @var yii\web\View $this */
/** @var app\models\DocumentsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app/docs', 'Documents');
$this->params['breadcrumbs'][] = $this->title;
\app\assets\MyModalViewAsset::register($this);
?>
<div class="documents-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Add new'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $column) {
            return [
                'data-id' => $model->id,
                'onclick' => "window.location.href = '" . Url::toRoute(["documents/update", "id" => $model->id]) . "'",
                'style' => 'cursor:pointer',
            ];
        },
        'columns' => ArrayHelper::merge([
            'id',
            [
                'attribute' => 'doc_date',
                'value' => 'date',
            ],
            [
                'attribute' => 'doc_type',
                'value' => function($model) {
                    return Yii::t('app/docs', Documents::DOCTYPE_NAMES[$model->doc_type]);
                }
            ],
            [
                'attribute' => 'doc_state',
                'value' => function($model) {
                    return Yii::t('app/docs', Documents::DOCSTATE_NAMES[$model->doc_state]);
                }
            ],
            //'counterparty',
            [
                'attribute' => 'author_id',
                'value' => 'author.fullname',
            ],
        ], MyHelpers::getCreatedUpdatedGridCols(), [
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, Documents $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'contentOptions' => [
                    'style' => 'width: 80px',
                    'nowrap' => true,
                ],
            ],
        ]),
    ]); ?>

    <?php Pjax::end(); ?>

</div>
