<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Modal;

/** @var yii\web\View $this */
/** @var app\models\Prestamo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
Modal::begin([
    'title' => 'Solicitud de Préstamo de Libro',
    'id' => 'modal-prestamo-libro',
    'size' => 'modal-lg',
]);
?>

<?php $form = ActiveForm::begin(['id' => 'form-prestamo-libro']); ?>
<div class="prestamo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cedula_solicitante')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?= $form->field($model, 'informacionpersonal_d_CIInfPer')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?= $form->field($model, 'informacionpersonal_CIInfPer')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?= $form->field($model, 'personaldata_Ci')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'tipoprestamo_id')->label('¿Qué buscas?')->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\Tipoprestamo::find()->all(), 'id', 'nombre_tipo'),
        ['prompt' => 'Seleccione servicio solicitado', 'disabled' => true]
    ) ?>

    <?= $form->field($model, 'intervalo_solicitado')->label('Horas de Inmersión Literaria')->textInput([
        'type' => 'time',
        'value' => '01:00:00',
        'max' => '08:00:00'
    ]) ?>

    <?= $form->field($model, 'biblioteca_idbiblioteca')->label('Tu Rincón de Lectura')->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\Biblioteca::find()->all(), 'idbiblioteca', 'Campus'),
        ['prompt' => 'Seleccione su ubicación']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php ActiveForm::end(); ?>

<?php
Modal::end();

// Script para select2
$this->registerJs('
    $("#libro-search").select2({
        placeholder: "Buscar libro por título o código de barras",
        allowClear: true,
        width: "100%",
        minimumInputLength: 2,
        ajax: {
            url: "' . \yii\helpers\Url::to(['libro/search']) . '",
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.items
                };
            },
            cache: true
        }
    });
');
?>
