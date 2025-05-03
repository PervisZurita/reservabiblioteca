<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Libro $model */

// Usamos el título del libro como encabezado principal
$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Libros'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="libro-view mt-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?= Html::encode($this->title) ?></h5>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca], [
                    'class' => 'btn btn-sm btn-outline-primary',
                ]) ?>
                <?= Html::a('<i class="fas fa-trash-alt"></i> Eliminar', ['delete', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca], [
                    'class' => 'btn btn-sm btn-outline-danger',
                    'data' => [
                        'confirm' => Yii::t('app', '¿Estás seguro de eliminar este elemento?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-bordered table-hover'],
                'attributes' => [
                    'id',
                    'ubicacion',
                    'numer',
                    'biblioteca_idbiblioteca',
                    'clasificacion',
                    'asignatura_id',
                    'titulo',
                    'autor',
                    'editorial',
                    'pais_codigopais',
                    'anio_publicacion',
                    'codigo_barras',
                ],
            ]) ?>

        </div>
    </div>

</div>
