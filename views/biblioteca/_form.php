<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Biblioteca $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="biblioteca-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Campus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Apertura')->textInput() ?>

    <?= $form->field($model, 'Cierre')->textInput() ?>

    <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Telefono')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
