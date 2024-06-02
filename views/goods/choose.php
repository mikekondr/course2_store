<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<div class="goods-index">

    <?= Html::hiddenInput('current_row_id') ?>
    <?= Html::hiddenInput('selected_good_id') ?>
    <?= Html::hiddenInput('selected_good') ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-sm table-striped table-bordered table-hover'],
        'layout' => "{items}",
        'columns' => [
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
        ],
        'rowOptions' => function ($model, $key, $index, $column) {
            return [
                //'data-id' => $model->id,
                'onclick' => "choose($model->id, '$model->name')",
                //'class' => 'select-goods-item',
                'style' => 'cursor:pointer',
            ];
        },
    ]); ?>
</div>

<?php
$this->registerJs("
function choose(goods_id, goods_name){
    $('input[name=selected_good_id]').val(goods_id);
    $('input[name=selected_good]').val(goods_name);
    bootstrap.Modal.getOrCreateInstance(document.querySelector('#select-good-modal')).hide();
}
", $this::POS_HEAD);
?>