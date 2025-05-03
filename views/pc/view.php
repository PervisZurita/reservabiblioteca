<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Pc $model */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'PCs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="pc-view mt-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'idpc' => $model->idpc, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca], [
                    'class' => 'btn btn-sm btn-outline-primary',
                ]) ?>
                <?= Html::a('<i class="fas fa-trash-alt"></i> Eliminar', ['delete', 'idpc' => $model->idpc, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca], [
                    'class' => 'btn btn-sm btn-outline-danger',
                    'data' => [
                        'confirm' => '¿Estás seguro de eliminar este elemento?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-bordered table-striped table-hover'],
                'attributes' => [
                    'nombre',
                    'estado',
                    [
                        'attribute' => 'biblioteca_idbiblioteca',
                        'label' => 'Campus',
                        'value' => function ($model) {
                            return $model->bibliotecaIdbiblioteca->Campus;
                        },
                    ],
                ],
            ]) ?>

        </div>
    </div>

</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
