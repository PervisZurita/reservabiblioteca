<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'Auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Status')->dropDownList([
                        '0' => 'Inactivo',
                        '1' => 'Activo',
                    ], ['prompt' => 'Seleccione el estado', 'class' => 'form-control']) ?>

    <?= $form->field($model, 'tipo_usuario')->dropDownList([
                        '1' => 'Externo',
                        '13' => 'Estudiante',
                        '18' => 'Docente',
                        '21' => 'Personal',
                        '7' => 'Gerente',
                        '8' => 'Admin'
                    ], ['prompt' => 'Seleccione el tipo de usuario', 'class' => 'form-control']) ?>

    <?php // $form->field($model, 'Created_at')->textInput() ?>

    <?php // $form->field($model, 'Updated_at')->textInput() ?>

    <?php // $form->field($model, 'Temporalpassword')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'Tempralpasswordtime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
