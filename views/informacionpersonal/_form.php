<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Informacionpersonal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="informacionpersonal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'CIInfPer')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'ApellInfPer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ApellMatInfPer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NombInfPer')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'codigo_dactilar')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
