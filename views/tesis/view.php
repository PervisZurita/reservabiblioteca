<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Tesis $model */

// Título ahora es el tema
$this->title = $model->tema;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tesis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="tesis-view mt-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?= Html::encode($this->title) ?></h5>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-primary',
                ]) ?>
                <?= Html::a('<i class="fas fa-trash-alt"></i> Eliminar', ['delete', 'id' => $model->id], [
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
                    'numero_estanteria',
                    'facultad',
                    'carrera',
                    [
                        'attribute' => 'tema',
                        'format' => 'ntext',
                        'label' => 'Tema de Tesis',
                    ],
                    'autor',
                    'tutor',
                    'anio_publicacion',
                    'codigo',
                ],
            ]) ?>

        </div>
    </div>

</div>
