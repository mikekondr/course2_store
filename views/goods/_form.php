<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\icons\Icon;
Icon::map($this);
/** @var yii\web\View $this */
/** @var app\models\Goods $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="goods-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(\app\controllers\CategoriesController::get_categories()); ?>

    <div class="form-group">
        <?= Html::a(Icon::show('backward'), Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-success']); ?>
        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
