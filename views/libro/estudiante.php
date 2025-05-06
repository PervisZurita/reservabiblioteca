<?php

use app\models\Libro;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Pais;
use yii\bootstrap4\Modal;

/** @var yii\web\View $this */
/** @var app\models\LibroSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Catálogo de Libros');
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

    $('#prestamo-modal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.data('remote');
        $('#modalContent').load(url);
    });
");
?>

<div class="libro-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $tipoUsuario = null;
    if (!Yii::$app->user->isGuest) {
        $tipoUsuario = Yii::$app->user->identity->tipo_usuario;
    }
    ?>

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
            'ubicacion',
            'titulo',
            'autor',
            'editorial',
            [
                'attribute' => 'pais_codigopais',
                'label' => 'País',
                'value' => function ($model) {
                    return $model->pais ? $model->pais->Nombrepais : 'No disponible';
                },
                'filter' => ArrayHelper::map(Pais::find()->all(), 'codigopais', 'Nombrepais'),
            ],
            'anio_publicacion',
            'codigo_barras',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{prestar}',
                'buttons' => [
                    'prestar' => function ($url, $model) {
                        $url = Url::to(['libro/prestarlib', 'id' => $model->id]);
                        return Html::button('<i class="fas fa-plus"></i>', [
                            'class' => 'btn btn-success',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal-prestamo-libro',
                            'data-remote' => $url,
                            'title' => 'Prestasr Libro',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php
Modal::begin([
    'id' => 'modal-prestamo-libro',
    'title' => '<h4>Prestar Libro</h4>',
    'size' => Modal::SIZE_LARGE,
]);
echo '<div id="modalContent"></div>';
Modal::end();
?>
