<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Prestamo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prestamo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'fecha_solicitud')->textInput() 
    ?>


    <?= $form->field($model, 'cedula_solicitante')
        ->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?php $form->field($model, 'informacionpersonal_d_CIInfPer')
        ->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?php $form->field($model, 'informacionpersonal_CIInfPer')
        ->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?php $form->field($model, 'personaldata_Ci')
        ->textInput(['maxlength' => true, 'readonly' => true]) ?>


    <?= $form->field($model, 'intervalo_solicitado')
        ->label('Horas de Estudio')
        ->textInput(['type' => 'time', 'value' => '01:00:00', 'max' => '08:00:00']) ?>

    <?= $form->field($model, 'tipoprestamo_id')
        ->label('¿Qué buscas?')
        ->dropDownList(
            \yii\helpers\ArrayHelper::map(\app\models\Tipoprestamo::find()->all(), 'id', 'nombre_tipo',),
            ['prompt' => 'Seleccione servicio solicitado', 'disabled' => true]
        ) ?>

    <?= $form->field($model, 'biblioteca_idbiblioteca')
        ->label('Tu Rincón de Lectura')
        ->dropDownList(
            \yii\helpers\ArrayHelper::map(\app\models\Biblioteca::find()->all(), 'idbiblioteca', 'Campus'),
            ['prompt' => 'Seleccione el campus', 'disabled' => true]
        ) ?>
        <?= $form->field($model, 'libro_id')
    ->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\Libro::find()->all(), 'id', function ($model) {
            return $model->codigo_barras . ' - ' . $model->titulo;
        }),
        [
            'prompt' => 'Buscar libro...',
            'disabled' => true,
            'id' => 'libro-search' // Añadimos un ID para el select
        ]
    ) ?>

<?php
// Registrar el JavaScript para convertir el select en un buscador
$this->registerJs('
    $("#libro-search").select2({
        placeholder: "Buscar libro por título o código de barras",
        allowClear: true,
        width: "100%",
        minimumInputLength: 2, // Mínimo de caracteres para iniciar búsqueda
        ajax: {
            url: "' . Url::to(['libro/search']) . '",
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // término de búsqueda
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

    <div class="form-group">
        <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>