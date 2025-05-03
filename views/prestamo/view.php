<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Prestamo $model */

$this->title = $model->id . $model->tipoprestamo_id . $model->biblioteca_idbiblioteca;
$this->params['breadcrumbs'][] = ['label' => 'Prestamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>


<!-- Encabezado -->
<div id="encabezado-impresion">
    <div id="encabezado-izquierda">
        <img src="/img/escudo_ecuador.png" alt="Escudo Ecuador" class="imagen-encabezado">
    </div>
    <div>
        <p>UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES</p>
        <p><?= $model->bibliotecaIdbiblioteca->Campus ?> - Ecuador</p>
    </div>
    <div id="encabezado-derecha">
        <img src="/img/escudo_utelvt.png" alt="Escudo UTELVT" class="imagen-encabezado">
    </div>
</div>
<div class="linea-separadora"></div>
<!-- Final encabezado -->

<div class="prestamo-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="botones-impresion">
        <p>
            <?php
            $tipoUsuario = Yii::$app->user->identity->tipo_usuario;

            if ($tipoUsuario === 8 || $tipoUsuario === 21) :
            ?>
                <a href="<?= \yii\helpers\Url::to(['update', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]) ?>" class="btn btn-primary">Actualizar</a>
                <a href="<?= \yii\helpers\Url::to(['delete', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]) ?>" class="btn btn-danger" data-confirm="¿Estás seguro de eliminar este elemento?" data-method="post">Eliminar</a>
            <?php endif; ?>

            <button class="btn btn-success botones-impresion" onclick="window.print()">Imprimir</button>

        </p>
    </div>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fecha_solicitud',
            'fechaentrega',
            [
                'label' => 'Tiempo Solicitado',
                'value' => function ($model) {
                    $fechaSolicitud = new DateTime($model->fecha_solicitud);
                    $fechaEntrega = new DateTime($model->fechaentrega);
                    $interval = $fechaSolicitud->diff($fechaEntrega);

                    return $interval->format('%h h %i m');
                },
            ],
            [
                'attribute' => 'tipoprestamo_id', // Esto muestra el código del país
                'value' => function ($model) {
                    return $model->tipoprestamo->nombre_tipo; // Accede al nombre del país relacionado
                },
            ],
            [
                'attribute' => 'biblioteca_idbiblioteca', // Esto muestra el código del país
                'value' => function ($model) {
                    return $model->bibliotecaIdbiblioteca->Campus; // Accede al nombre del Campus relacionado
                },
            ],
            [
                'attribute' => 'pc_idpc', // Esto muestra el código
                'label' => 'Dispositivo Solicitado',
                'visible' => !empty($model->pc_idpc),
                'value' => function ($model) {
                    return $model->pc_idpc ? $model->pcIdpc->nombre : ''; // Accede al dato relacionado si no es nulo, de lo contrario, muestra Nada
                },
            ],
            //'pc_biblioteca_idbiblioteca',
            //'libro_codigo_barras',
            //'libro_biblioteca_idbiblioteca',
            //MAS DATOS DEL LIBRO
            [
                'attribute' => 'libro_id', // Esto muestra el código
                'label' => 'Titulo Solicitado',
                'visible' => !empty($model->libro_id),
                'value' => function ($model) {
                    return $model->libro ? $model->libro->codigo_barras . ' - ' . $model->libro->titulo : ''; // Accede al dato relacionado si no es nulo, de lo contrario, muestra Nada
                },
            ],
            
            //ESTUDIANTE
            //'informacionpersonal_CIInfPer',
            [
                'attribute' => 'informacionpersonal_CIInfPer',
                'visible' => !empty($model->informacionpersonal_CIInfPer),
            ],
            [
                'attribute' => 'informacionpersonal_CIInfPer',
                'label' => 'Apellidos',
                'visible' => !empty($model->informacionpersonal_CIInfPer),
                'value' => function ($model) {
                    if ($model->informacionpersonalCIInfPer !== null) {
                        return $model->informacionpersonalCIInfPer->ApellInfPer . ' ' . $model->informacionpersonalCIInfPer->ApellMatInfPer;
                    } else {
                        return 'No disponible'; // O cualquier otro mensaje adecuado
                    }
                },
            ],
            [
                'attribute' => 'informacionpersonal_CIInfPer',
                'label' => 'Nombres',
                'visible' => !empty($model->informacionpersonal_CIInfPer),
                'value' => function ($model) {
                    if ($model->informacionpersonalCIInfPer !== null) {
                        return $model->informacionpersonalCIInfPer->NombInfPer;
                    } else {
                        return 'No disponible'; // O cualquier otro mensaje adecuado
                    }
                },
            ],
            //PERSONAL UNIVERSITARIO
            //'informacionpersona_d_CIInfPer',
            [
                'attribute' => 'informacionpersonal_d_CIInfPer',
                'visible' => !empty($model->informacionpersonal_d_CIInfPer),
            ],
            [
                'attribute' => 'informacionpersonal_d_CIInfPer',
                'label' => 'Apellidos',
                'visible' => !empty($model->informacionpersonal_d_CIInfPer),
                'value' => function ($model) {
                    if ($model->informacionpersonalDCIInfPer !== null) {
                        return $model->informacionpersonalDCIInfPer->ApellInfPer . ' ' . $model->informacionpersonalDCIInfPer->ApellMatInfPer;
                    } else {
                        return 'No disponible'; // O cualquier otro mensaje adecuado
                    }
                },
            ],
            [
                'attribute' => 'informacionpersonal_d_CIInfPer',
                'label' => 'Nombres',
                'visible' => !empty($model->informacionpersonal_d_CIInfPer),
                'value' => function ($model) {
                    if ($model->informacionpersonalDCIInfPer !== null) {
                        return $model->informacionpersonalDCIInfPer->NombInfPer;
                    } else {
                        return 'No disponible'; // O cualquier otro mensaje adecuado
                    }
                },
            ],

            //SOLICITANTE EXTERNO
            //'personaldata_Ci',
            [
                'attribute' => 'personaldata_Ci',
                'visible' => !empty($model->personaldata_Ci),
            ],
            [
                'attribute' => 'personaldata_Ci',
                'label' => 'Nombres',
                'visible' => !empty($model->personaldata_Ci),
                'value' => function ($model) {
                    return $model->personaldataCi->Nombres;
                },
            ],
            [
                'attribute' => 'personaldata_Ci',
                'label' => 'Apellidos',
                'visible' => !empty($model->personaldata_Ci),
                'value' => function ($model) {
                    return $model->personaldataCi->Apellidos;
                },
            ],
            [
                'attribute' => 'personaldata_Ci',
                'label' => 'Institución',
                'visible' => !empty($model->personaldata_Ci),
                'value' => function ($model) {
                    return $model->personaldataCi->Institucion;
                },
            ],
            [
                'attribute' => 'personaldata_Ci',
                'label' => 'Email',
                'visible' => !empty($model->personaldata_Ci),
                'value' => function ($model) {
                    return $model->personaldataCi->Email;
                },
            ],
        ],
    ]) ?>

</div>





<style>
    #encabezado-impresion {
        display: none;
        /* Oculta el encabezado en la vista normal */
    }

    @media print {
        #encabezado-impresion {
            top: 0;
            left: 0;
            right: 0;
            padding: 10px;
            text-align: center;
            font-family: "Times New Roman", serif;
            text-transform: uppercase;
            font-size: 26px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }

        #encabezado-izquierda img,
        #encabezado-derecha img {
            width: 100px;
            display: inline;
        }

        .linea-separadora {
            border-top: 2px solid green;
            /* Cambia el grosor y el color de la línea según tus preferencias */
            margin-top: 20px;
            /* Espacio superior */
            margin-bottom: 20px;
            /* Espacio inferior */
        }

        .botones-impresion {
            display: none;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 24px;
        }

        h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .detalle-titulo {
            font-weight: bold;
            font-size: 40px;
            margin-bottom: 10px;
        }

        .detalle-dato {
            font-size: 18px;
            margin-bottom: 15px;
        }


    }
</style>