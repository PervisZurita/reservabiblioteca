<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\InformacionpersonalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="informacionpersonal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'CIInfPer') ?>

    <?= $form->field($model, 'ApellInfPer') ?>

    <?= $form->field($model, 'ApellMatInfPer') ?>

    <?= $form->field($model, 'NombInfPer') ?>

    <?= $form->field($model, 'codigo_dactilar') ?>

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Restablecer', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
