<?php

use app\models\Prestamo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\PrestamoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = '📚 Registros de Préstamo';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prestamo-index bg-light p-4 rounded shadow">

    <h1 class="text-primary mb-4"><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        $tipoUsuario = null;
        if (!Yii::$app->user->isGuest) {
            $tipoUsuario = Yii::$app->user->identity->tipo_usuario;
            if ($tipoUsuario === 8 || $tipoUsuario === 21) {
                echo Html::a('<i class="fas fa-plus-circle"></i> Nuevo Préstamo', ['create'], ['class' => 'btn btn-success btn-lg mb-3']);
            }
        }
        ?>
    </p>

    <?php Pjax::begin(); ?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'],
                'maxButtonCount' => 5,
                'prevPageLabel' => '← Anterior',
                'nextPageLabel' => 'Siguiente →',
                'linkOptions' => ['class' => 'page-link'],
                'activePageCssClass' => 'page-item active',
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
            ],
            'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id'],

                [
                    'attribute' => 'fecha_solicitud',
                    'filter' => \yii\jui\DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'fecha_solicitud',
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => ['class' => 'form-control'],
                    ]),
                ],
                [
                    'attribute' => 'tipoprestamo_id',
                    'filter' => \yii\helpers\ArrayHelper::map(\app\models\Tipoprestamo::find()->all(), 'id', 'nombre_tipo'),
                    'value' => fn($model) => $model->tipoprestamo->nombre_tipo ?? '',
                ],
                [
                    'attribute' => 'pc_idpc',
                    'value' => fn($model) => $model->pcIdpc->nombre ?? '',
                ],
                [
                    'attribute' => 'libro_id',
                    'label' => '📖 Título del Libro',
                    'value' => fn($model) => $model->libro->titulo ?? '',
                    'filter' => Html::activeTextInput($searchModel, 'libroTitulo', ['class' => 'form-control']),
                ],
                [
                    'attribute' => 'Cédula Solicitante',
                    'value' => fn($model) => $model->personaldata_Ci
                        ?? $model->informacionpersonal_CIInfPer
                        ?? $model->informacionpersonal_d_CIInfPer,
                ],
                [
                    'attribute' => 'Nombres Solicitante',
                    'value' => function ($model) {
                        if (!empty($model->personaldata_Ci)) {
                            return $model->personaldataCi->getNombre();
                        } elseif (!empty($model->informacionpersonalCIInfPer)) {
                            return $model->informacionpersonalCIInfPer->getNombre();
                        } elseif (!empty($model->informacionpersonalDCIInfPer)) {
                            return $model->informacionpersonalDCIInfPer->getNombre();
                        }
                        return 'No Asignado';
                    },
                ],
                [
                    'attribute' => 'facultad',
                    'label' => '🏫 Facultad / Institución',
                    'value' => function ($model) {
                        if (!empty($model->personaldata_Ci)) {
                            return $model->personaldataCi->Institucion ?? 'Externo Sin Institución';
                        } elseif (!empty($model->informacionpersonalCIInfPer?->factura?->detalleMatricula)) {
                            return $model->informacionpersonalCIInfPer->factura->detalleMatricula->carrera2->getNombreFacultad();
                        } elseif (!empty($model->informacionpersonalDCIInfPer)) {
                            return 'Docente Sin Asignación';
                        }
                        return 'Información no disponible';
                    },
                    'filter' => Html::activeTextInput($searchModel, 'facultad', ['class' => 'form-control']),
                ],
                [
                    'attribute' => 'carrera',
                    'label' => '🎓 Carrera',
                    'value' => function ($model) {
                        if (!empty($model->personaldata_Ci)) {
                            return 'Externo Sin Asignación';
                        } elseif (!empty($model->informacionpersonalCIInfPer?->factura?->detalleMatricula)) {
                            return $model->informacionpersonalCIInfPer->factura->detalleMatricula->getNombCarrera();
                        } elseif (!empty($model->informacionpersonalDCIInfPer)) {
                            return 'Docente Sin Asignación';
                        }
                        return 'Sin Información';
                    },
                    'filter' => Html::activeTextInput($searchModel, 'carrera', ['class' => 'form-control']),
                ],
                [
                    'attribute' => 'nivel',
                    'label' => '📚 Nivel',
                    'value' => function ($model) {
                        if (!empty($model->personaldata_Ci)) {
                            return 'Externo Sin Asignación';
                        } elseif (!empty($model->informacionpersonalCIInfPer?->factura?->detalleMatricula)) {
                            return $model->informacionpersonalCIInfPer->factura->detalleMatricula->nivel ?? 'Estudiante sin Nivel';
                        } elseif (!empty($model->informacionpersonalDCIInfPer)) {
                            return 'Docente Sin Asignación';
                        }
                        return 'Sin Información';
                    },
                    'filter' => Html::activeTextInput($searchModel, 'nivel', ['class' => 'form-control']),
                ],
                [
                    'attribute' => 'tipoSolicitante',
                    'label' => '👤 Tipo de Solicitante',
                    'value' => function ($model) {
                        return match (true) {
                            !empty($model->informacionpersonal_d_CIInfPer) => 'Personal Universitario',
                            !empty($model->personaldata_Ci) => 'Externo',
                            !empty($model->informacionpersonal_CIInfPer) => 'Estudiante',
                            default => 'N/A',
                        };
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'tipoSolicitante', [
                        'Personal Universitario' => 'Personal Universitario',
                        'Externo' => 'Externo',
                        'Estudiante' => 'Estudiante',
                    ], ['class' => 'form-control', 'prompt' => 'Seleccionar']),
                ],
                [
                    'attribute' => 'biblioteca_idbiblioteca',
                    'label' => '🏛 Campus',
                    'value' => fn($model) => $model->bibliotecaIdbiblioteca->Campus ?? '',
                    'filter' => \yii\helpers\ArrayHelper::map(\app\models\Biblioteca::find()->all(), 'idbiblioteca', 'Campus'),
                ],
                [
                    'class' => ActionColumn::class,
                    'urlCreator' => fn($action, Prestamo $model) => Url::toRoute([$action, 'id' => $model->id]),
                    'visible' => $tipoUsuario === 8 || $tipoUsuario === 21,
                ],
            ],
        ]); ?>
    </div>

    <?php Pjax::end(); ?>
</div>
