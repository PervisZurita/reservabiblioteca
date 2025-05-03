<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Personaldata $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="personaldata-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Ci')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Apellidos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nombres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FechaNacimiento')->textInput() ?>

    <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Genero')->dropDownList([ 'M' => 'Masculino', 'F' => 'Femenino', ], ['prompt' => 'Seleccione su género']) ?>

    <?= $form->field($model, 'Institucion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nivel')->dropDownList($nivel, ['prompt' => 'Seleccione su nivel académico']) ?>

    <?php // $form->field($model, 'Facultad')->dropDownList($facultades, ['prompt' => 'Seleccione su facultad']) ?>

    <?php // $form->field($model, 'Ciclo')->dropDownList($ciclo, ['prompt' => 'Seleccione su ciclo']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
