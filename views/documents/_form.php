<?php

use app\models\Documents;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use yii\jui\DatePicker;
use kartik\icons\Icon;
Icon::map($this);

/** @var yii\web\View $this */
/** @var app\models\Documents $model */
/** @var app\models\DocumentRows[] $rows */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="documents-form">

    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'enableAjaxValidation' => false,
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'offset' => 'col-sm-offset-0',
                'label' => 'col-sm-4 col-form-label',
                'wrapper' => 'col-sm-12',
            ],
        ],
    ]); ?>

    <div class="row">
        <div class="col-3">
            <?= $form->field($model, 'doc_date', ['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])
                ->widget(DatePicker::class, [
                    'options' => ['class' => 'form-control'],
                ]) ?>
        </div>
        <div class="col-3">
            <?= $form->field($model, 'doc_type', ['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])
                ->dropDownList(Documents::getDocumentsTypes())
            ?>
        </div>
        <div class="col-3">
            <?= $form->field($model, 'doc_state', ['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])
                ->dropDownList(Documents::getDocumentsStates())
            ?>
        </div>
    </div>

    <?php
//    echo $form->field($model, 'counterparty', [
//        'horizontalCssClasses' => [
//            'label' => 'col-sm-2 col-form-label',
//            'wrapper' => 'col-sm-7',
//        ]
//    ])->textInput()
    ?>

    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $rows,
        ]),
        'summary' => false,
        'tableOptions' => [
            'id'=>'rows_table',
            'class'=>'table table-sm',
        ],
        'rowOptions' => function($model, $key, $index, $grid) {
            return ['class' => "document-row document-row-$index"];
        },
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'align-middle text-center'],
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function($model, $key, $index, $column) {
                    return Html::activeHiddenInput($model, "[$index]id") .
                        Html::activeHiddenInput($model, "[$index]updateType", ['class' => 'update-type']) .
                        Html::activeHiddenInput($model, "[$index]good_id");
                },
            ],
            [
                'label' => Yii::t('app/goods', 'Name'),
                'format' => 'raw',
                'value' => function($model, $key, $index, $column) {
                    return "<span id='documentrows-" . $index . "-good_name'>" . (is_null($model->good) ? "" : $model->good->name) . "</span>" . Html::button('...', [
                            'type' => 'button',
                            'class' => 'btn btn-light select-good-btn',
                            'data-row-id' => $index,
                        ]);
                },
                'contentOptions' => [
                    'name' => 'documentrows-good_name',
                    'class' => 'd-flex align-items-center justify-content-between'
                ],
            ],
            [
                'label' => Yii::t('app/docs', 'Count'),
                'format' => 'raw',
                'value' => function($model, $key, $index, $column) use ($form) {
                    return $form->field($model, "[$index]count", [
                        'horizontalCssClasses' => [
                            'field' => 'row',
                        ]
                    ])->textInput(['type' => 'number'])->label(false);
                },
            ],
            [
                'label' => Yii::t('app/docs', 'Price'),
                'format' => 'raw',
                'value' => function($model, $key, $index, $column) use ($form) {
                    return $form->field($model, "[$index]price", [
                        'horizontalCssClasses' => [
                            'field' => 'row'
                        ]
                    ])->textInput()->label(false);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url, $model, $key) {
                        return Html::a(Icon::show('trash'), '#', [
                            'class' => ['delete-button'],
                            'data-target' => "document-row-$key",
                        ]);
                    }
                ],
                'contentOptions' => [
                    'class' => 'align-middle text-center',
                ],
            ]
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/docs', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Add row', ['name' => 'addRow', 'value' => 'true', 'class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
Modal::begin([
    'id' => 'select-good-modal',
    'title' => '<h2>Hello world</h2>',
    'toggleButton' => false,
    'size' => 'modal-lg',
]);

echo $this->render('//goods/choose', [
    'dataProvider' => new ActiveDataProvider([
        'query' => \app\models\Goods::find(),
    ]),
]);

Modal::end();

$this->registerJs("
var modal = bootstrap.Modal.getOrCreateInstance(document.querySelector('#select-good-modal'));
const modalEl = document.getElementById('select-good-modal');
modalEl.addEventListener('hidden.bs.modal', event => {
    let row_index = $('input[name=current_row_id]').val();
    let goods_id = $('input[name=selected_good_id]').val();
    let goods_name = $('input[name=selected_good]').val();
    
    $('input[id=documentrows-'+row_index+'-good_id]').val(goods_id);
    $('span[id=documentrows-'+row_index+'-good_name]').text(goods_name);
});
    
$('document').ready(function(){
    $('.select-good-btn').click(function(){
        $('input[name=current_row_id]').val(this.attributes['data-row-id'].value);
        modal.show();
    });
    
    $('.delete-button').click(function() {
        var row = $(this).closest('.document-row');
        var updateType = row.find('.update-type');
        if (updateType.val() === " . json_encode(\app\models\DocumentRows::UPDATE_TYPE_UPDATE) . ") {
            //marking the row for deletion
            updateType.val(" . json_encode(\app\models\DocumentRows::UPDATE_TYPE_DELETE) . ");
            row.hide();
        } else {
            //if the row is a new row, delete the row
            row.remove();
        }
    });
});
");
?>
