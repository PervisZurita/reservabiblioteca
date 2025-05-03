<?php

use app\models\Tesis;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Catálogo de tesis');
$this->params['breadcrumbs'][] = $this->title;

// Estilo CSS para el encabezado de la tabla y botones
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

    .btn-create {
        margin-right: 5px;
    }

    /* Estilos para el modal */
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

    .template-download {
        margin-top: 10px;
    }
");

// Registrar JS para el modal
$this->registerJs("
    $(document).on('click', '.download-template', function() {
        window.location.href = $(this).data('url');
    });
    
    $('#importModal').on('show.bs.modal', function () {
        $('#importFile').val('');
    });
");

?>

<div class="tesis-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Crear Tesis'), ['create'], ['class' => 'btn btn-success btn-create']) ?>
        <?= Html::a(Yii::t('app', '<i class="fas fa-file-import"></i> Importar'), ['#'], [
            'class' => 'btn btn-import',
            'data' => [
                'toggle' => 'modal',
                'target' => '#importModal',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', '<i class="fas fa-file-export"></i> Exportar'), ['export'], ['class' => 'btn btn-export']) ?>
    </p>
    
    <!-- Modal de Importación -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="importForm" action="<?= \yii\helpers\Url::to(['tesis/import']) ?>" method="post" enctype="multipart/form-data">
                    <?= Yii::$app->request->csrfToken ? Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) : '' ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Importar Tesis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Instrucciones para la importación -->
                        <div class="import-instructions">
                            <p><strong>Instrucciones:</strong> Sube un archivo CSV con el siguiente orden de columnas:</p>
                            <ul>
                                <li><strong>numero_estanteria</strong> (Ejemplo: "12A")</li>
                                <li><strong>facultad</strong> (Ejemplo: "Ingeniería")</li>
                                <li><strong>carrera</strong> (Ejemplo: "Sistemas Computacionales")</li>
                                <li><strong>tema</strong> (Ejemplo: "Inteligencia Artificial")</li>
                                <li><strong>autor</strong> (Ejemplo: "Juan Pérez")</li>
                                <li><strong>tutor</strong> (Ejemplo: "Dra. María López")</li>
                                <li><strong>anio_publicacion</strong> (Ejemplo: "2023")</li>
                                <li><strong>codigo</strong> (Ejemplo: "TSC-2023-001")</li>
                            </ul>
                            <p class="file-format-info">* Verifica que los nombres de las columnas sean exactos.</p>
                            <?= Html::button('Descargar plantilla', [
                                'class' => 'btn btn-info download-template',
                                'data-url' => \yii\helpers\Url::to(['tesis/plantilla'])
                            ]) ?>
                        </div>

                        <!-- Formulario para subir archivo CSV -->
                        <div class="form-group">
                            <label for="importFile">Seleccionar archivo CSV</label>
                            <input type="file" name="importFile" id="importFile" class="form-control" required accept=".csv">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Importar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'numero_estanteria',
            'facultad',
            'carrera',
            'tema:ntext',
            'autor',
            'tutor',
            'anio_publicacion',
            'codigo',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Tesis $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'contentOptions' => ['style' => 'width: 120px;'],
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
