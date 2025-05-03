<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Biblioteca;
use app\models\Pais;
use app\models\Asignatura;

/** @var yii\web\View $this */
/** @var app\models\Libro $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="libro-form container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0"><?= Yii::t('app', 'Registro de Libro') ?></h3>
        </div>

        <div class="card-body p-4">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                    'labelOptions' => ['class' => 'control-label'],
                ],
            ]); ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <?= $form->field($model, 'ubicacion')->textInput([
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Ingrese la ubicación'
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'numer')->textInput([
                        'required' => true,
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Ingrese el número'
                    ]) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <?= $form->field($model, 'biblioteca_idbiblioteca')->dropDownList(
                        ArrayHelper::map(Biblioteca::find()->all(), 'idbiblioteca', 'Campus'),
                        [
                            'prompt' => 'Seleccione una campus',
                            'class' => 'form-control form-control-lg',
                            'required' => true
                        ]
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'clasificacion')->textInput([
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Ingrese la clasificación'
                    ]) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <?= $form->field($model, 'asignatura_id')->dropDownList(
                        ArrayHelper::map(Asignatura::find()->all(), 'id', 'Nombre'),
                        [
                            'prompt' => 'Seleccione una asignatura',
                            'class' => 'form-control form-control-lg',
                            'required' => true
                        ]
                    ) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <?= $form->field($model, 'titulo')->textInput([
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Ingrese el título del libro'
                    ]) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <?= $form->field($model, 'autor')->textInput([
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Ingrese el autor'
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'editorial')->textInput([
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Ingrese la editorial'
                    ]) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <?= $form->field($model, 'pais_codigopais')->dropDownList(
                        ArrayHelper::map(Pais::find()->all(), 'codigopais', 'Nombrepais'),
                        [
                            'prompt' => 'Seleccione un país',
                            'class' => 'form-control form-control-lg',
                            'required' => true
                        ]
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'anio_publicacion')->textInput([
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Ingrese el año de publicación'
                    ]) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <?= $form->field($model, 'codigo_barras')->textInput([
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Ingrese el código de barras'
                    ]) ?>
                </div>
            </div>

            <div class="form-group text-center mt-4">
                <?= Html::submitButton(Yii::t('app', 'Guardar'), [
                    'class' => 'btn btn-primary btn-lg px-5 py-2',
                    'style' => 'font-weight: bold;'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem;
        font-size: 1.5rem;
    }

    .form-control-lg {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control-lg:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .control-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #495057;
    }

    .help-block {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .has-error .form-control {
        border-color: #dc3545;
    }

    .has-error .help-block {
        color: #dc3545;
    }
</style>
