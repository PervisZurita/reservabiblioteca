<?php

use app\models\Pc;
use app\models\Biblioteca;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

// Título y ruta
$this->title = 'Lista de Computadores';
$this->params['breadcrumbs'][] = $this->title;

// CSS personalizado
$this->registerCss("
    .table thead th {
        background-color: #000000;
        color: white;
        text-align: center;
    }

    .table tbody td {
        text-align: center;
    }

    .btn-export {
        background-color: #17a2b8;
        color: white;
        margin-right: 5px;
    }

    .btn-import {
        background-color: #28a745;
        color: white;
        margin-right: 5px;
    }

    .import-instructions {
        background-color: #f8f9fa;
        border-left: 4px solid #17a2b8;
        padding: 10px;
        margin-bottom: 15px;
    }

    .file-format-info {
        font-size: 0.9em;
        color: #6c757d;
    }
");

// JavaScript para el modal
$this->registerJs("
    $(document).on('click', '[id^=\"open-modal-button-\"]', function () {
        var url = $(this).data('url');
        $('#pcPrestarModalBody').html('<div class=\"text-center\"><div class=\"spinner-border text-info\" role=\"status\"><span class=\"sr-only\">Cargando...</span></div></div>');
        $.get(url, function(data) {
            $('#pcPrestarModalBody').html(data);
        });
    });

    $('#importModal').on('show.bs.modal', function () {
        $('#importFile').val('');
    });
");
?>

<div class="pc-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $tipoUsuario = Yii::$app->user->isGuest ? null : Yii::$app->user->identity->tipo_usuario;
    if ($tipoUsuario === 8 || $tipoUsuario === 21): ?>
        <p>
            <?= Html::a('Agregar PC <i class="fas fa-plus-circle"></i>', ['create'], [
                'class' => 'btn btn-success btn-create',
                'encode' => false,
            ]) ?>
        </p>
    <?php endif; ?>

    <!-- Modal para prestar PC -->
    <div class="modal fade" id="pcPrestarModal" tabindex="-1" role="dialog" aria-labelledby="pcPrestarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="pcPrestarModalLabel">Prestar Computador</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="pcPrestarModalBody">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <?php Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'],
                'maxButtonCount' => 5,
                'prevPageLabel' => 'Anterior',
                'nextPageLabel' => 'Siguiente',
                'prevPageCssClass' => 'page-item',
                'nextPageCssClass' => 'page-item',
                'linkOptions' => ['class' => 'page-link'],
                'activePageCssClass' => 'page-item active',
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'nombre',
                [
                    'attribute' => 'estado',
                    'value' => function ($model) {
                        $estados = [
                            'D' => 'Disponible',
                            'ND' => 'No Disponible',
                            'F' => 'Fuera de servicio',
                            'EM' => 'En Mantenimiento',
                            'R' => 'Retirada',
                        ];
                        return $estados[$model->estado] ?? $model->estado;
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'estado', [
                        'D' => 'Disponible',
                        'ND' => 'No Disponible',
                        'F' => 'Fuera de servicio',
                        'EM' => 'En Mantenimiento',
                        'R' => 'Retirada',
                    ], ['class' => 'form-control', 'prompt' => 'Todos']),
                ],
                [
                    'attribute' => 'biblioteca_idbiblioteca',
                    'value' => function ($model) {
                        return $model->bibliotecaIdbiblioteca->Campus ?? '-';
                    },
                    'filter' => Html::activeDropDownList(
                        $searchModel,
                        'biblioteca_idbiblioteca',
                        \yii\helpers\ArrayHelper::map(Biblioteca::find()->all(), 'idbiblioteca', 'Campus'),
                        ['class' => 'form-control', 'prompt' => 'Todos']
                    ),
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{prestar}',
                    'buttons' => [
                        'prestar' => function ($url, $model) {
                            return Html::button('<i class="fas fa-plus"></i>', [
                                'class' => 'btn btn-info',
                                'id' => 'open-modal-button-' . $model->idpc,
                                'data-toggle' => 'modal',
                                'data-target' => '#pcPrestarModal',
                                'data-url' => Url::to(['prestamo/prestarpc', 'id' => $model->idpc]),
                            ]);
                        },
                    ],
                ],
                [
                    'class' => ActionColumn::class,
                    'urlCreator' => function ($action, Pc $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'idpc' => $model->idpc, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
                    },
                    'visible' => $tipoUsuario === 8 || $tipoUsuario === 21,
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>
</div>
