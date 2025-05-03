<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PersonaldataSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="personaldata-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'Ci') ?>

    <?= $form->field($model, 'Apellidos') ?>

    <?= $form->field($model, 'Nombres') ?>

    <?= $form->field($model, 'FechaNacimiento') ?>

    <?= $form->field($model, 'Email') ?>

    <?php // echo $form->field($model, 'Genero') ?>

    <?php // echo $form->field($model, 'Institucion') ?>

    <?php // echo $form->field($model, 'Nivel') ?>

    <?php // echo $form->field($model, 'Facultad') ?>

    <?php // echo $form->field($model, 'Ciclo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
