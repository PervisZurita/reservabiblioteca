<?php

use app\models\Libro;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Biblioteca;
use app\models\Pais;

/** @var yii\web\View $this */
/** @var app\models\LibroSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Catalogo de Libros');
$this->params['breadcrumbs'][] = $this->title;

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
    .pagination {
        justify-content: center;
        margin-top: 20px;
    }
    .pagination .page-item {
        margin: 0 5px;
    }
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    .pagination .page-item:hover .page-link {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .pagination .page-link {
        color: #007bff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 8px 12px;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        border-color: #ddd;
    }
    .pagination .page-link:focus {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
");

$this->registerJs("
    $(document).on('click', '.download-template', function() {
        window.location.href = $(this).data('url');
    });

    $('#importModal').on('show.bs.modal', function () {
        $('#importFile').val('');
    });

    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#importModal').modal('hide');
                if(response.success) {
                    $.pjax.reload({container: '#pjax-container'});
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error al procesar la importación');
            }
        });
    });
");

?>

<div class="libro-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Libro', ['create'], ['class' => 'btn btn-success btn-create']) ?>
        <?= Html::a('<i class="fas fa-file-import"></i> Importar', ['#'], [
            'class' => 'btn btn-import',
            'data' => ['toggle' => 'modal', 'target' => '#importModal'],
        ]) ?>
        <?= Html::a('<i class="fas fa-file-export"></i> Exportar', ['export'], ['class' => 'btn btn-export']) ?>
    </p>



    <!-- Modal de Importación -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="importForm" action="<?= \yii\helpers\Url::to(['libro/import']) ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Importar Libros</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Instrucciones para la importación -->
                        <div class="import-instructions">
                            <p><strong>Instrucciones:</strong> Sube un archivo CSV con el siguiente orden de columnas:</p>
                            <ul>
                                <li><strong>ubicacion</strong> (Ejemplo: "A1")</li>
                                <li><strong>numero</strong> (Ejemplo: "1234")</li>
                                <li><strong>campus</strong> (Ejemplo: "Campus Central")</li>
                                <li><strong>clasificacion</strong> (Ejemplo: "Ficción")</li>
                                <li><strong>asignatura</strong> (Ejemplo: "Literatura")</li>
                                <li><strong>titulo</strong> (Ejemplo: "Cien Años de Soledad")</li>
                                <li><strong>autor</strong> (Ejemplo: "Gabriel García Márquez")</li>
                                <li><strong>editorial</strong> (Ejemplo: "Editorial XYZ")</li>
                                <li><strong>pais</strong> (Ejemplo: "Colombia")</li>
                                <li><strong>anio de publicacion</strong> (Ejemplo: "1967")</li>
                                <li><strong>codigo de barras</strong> (Ejemplo: "978-1234567890")</li>
                            </ul>
                            <p class="file-format-info">* Verifica que los nombres de las columnas sean exactos.</p>
                            <?= Html::button('Descargar plantilla', [
    'class' => 'btn btn-info download-template',
    'data-url' => \yii\helpers\Url::to(['libro/plantilla']) 
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


    <?php Pjax::begin(['id' => 'pjax-container']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'options' => ['class' => 'pagination justify-content-center'],
            'maxButtonCount' => 5,
            'prevPageLabel' => 'Anterior',
            'nextPageLabel' => 'Siguiente',
            'linkOptions' => ['class' => 'page-link'],
            'activePageCssClass' => 'page-item active',
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'ubicacion',
            'numer',
            [
                'attribute' => 'biblioteca_idbiblioteca',
                'label' => 'Biblioteca',
                'value' => function ($model) {
                    return $model->biblioteca ? $model->biblioteca->Campus : 'No disponible';
                },
                'filter' => ArrayHelper::map(Biblioteca::find()->all(), 'idbiblioteca', 'Campus'),
            ],
            'clasificacion',
            'titulo',
            'autor',
            'editorial',
            [
                'attribute' => 'pais_codigopais', 
                'label' => 'Pais',
                'value' => function ($model) {
                    return $model->pais ? $model->pais->Nombrepais : 'No disponible';
                },
                'filter' => ArrayHelper::map(Pais::find()->all(), 'codigopais', 'Nombrepais'),
            ],
            'anio_publicacion',
            'codigo_barras',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Libro $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
