<?php

use app\models\Informacionpersonal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\InformacionpersonalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Lista de Estudiantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="informacionpersonal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?php //     <?= Html::a('Agregar Estudiante <i class="fas fa-user-plus"></i>', ['create'], ['class' => 'btn btn-success my-3']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <div class='table-responsive'>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'], // Agrega una clase CSS personalizada al contenedor de paginación
                'maxButtonCount' => 5, // Controla el número de botones de página que se muestran
                'prevPageLabel' => 'Anterior',
                'nextPageLabel' => 'Siguiente',
                'prevPageCssClass' => 'page-item', // Clase CSS para el botón "Anterior"
                'nextPageCssClass' => 'page-item', // Clase CSS para el botón "Siguiente"
                'linkOptions' => ['class' => 'page-link'], // Agrega una clase CSS personalizada a los enlaces de página
                'activePageCssClass' => 'page-item active', // Clase CSS para la página activa
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'], // Estilo de los botones deshabilitados

            ],
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'CIInfPer',
                'ApellInfPer',
                'ApellMatInfPer',
                'NombInfPer',
                //'codigo_dactilar',
             //   [
               //     'class' => ActionColumn::className(),
                //    'urlCreator' => function ($action, Informacionpersonal $model, $key, $index, $column) {
                //        return Url::toRoute([$action, 'CIInfPer' => $model->CIInfPer]);
               //     }
               // ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>

    </div>
</div>