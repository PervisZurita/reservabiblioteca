<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Libros Más Solicitados';
$this->params['breadcrumbs'][] = $this->title;
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="libro">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Generador Estadístico</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['method' => 'get']); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= Html::dropDownList('mes', $mesSeleccionado, [
                                    '01' => 'Enero',
                                    '02' => 'Febrero',
                                    '03' => 'Marzo',
                                    '04' => 'Abril',
                                    '05' => 'Mayo',
                                    '06' => 'Junio',
                                    '07' => 'Julio',
                                    '08' => 'Agosto',
                                    '09' => 'Septiembre',
                                    '10' => 'Octubre',
                                    '11' => 'Noviembre',
                                    '12' => 'Diciembre',
                                ], ['prompt' => 'Selecciona el Mes', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= Html::textInput('anio', $anioSeleccionado, ['class' => 'form-control', 'placeholder' => 'Ingrese el año']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= Html::dropDownList(
                                    'bibliotecaId',
                                    $bibliotecaSeleccionada,
                                    \yii\helpers\ArrayHelper::map(\app\models\Biblioteca::find()->all(), 'idbiblioteca', 'Campus'),
                                    ['prompt' => 'Seleccione el Campus', 'class' => 'form-control']
                                ) ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?= Html::dropDownList(
                                    'asignaturaId',
                                    $asignaturaSeleccionada,
                                    \yii\helpers\ArrayHelper::map(\app\models\Asignatura::find()->all(), 'id', 'Nombre'),
                                    ['prompt' => 'Seleccione la asignatura', 'class' => 'form-control']
                                ) ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('<i class="fas fa-chart-pie"></i> Generar', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class=col-md-6>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informe de Solicitudes de Libros</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Título del Libro</th>
                                <th>Total de Solicitudes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($librosMasSolicitados as $libro) : ?>
                                <tr>
                                    <td><?= $libro['titulo'] ?></td>
                                    <td><?= $libro['total'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top 10 Libros Más Solicitados</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficaLibrosMasSolicitados" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>


</div>

<?php
$this->registerJs("
    var ctx = document.getElementById('graficaLibrosMasSolicitados').getContext('2d');
    var colores = [
    'rgba(255, 99, 132, 0.5)',   // Rojo
    'rgba(54, 162, 235, 0.5)',   // Azul
    'rgba(255, 206, 86, 0.5)',   // Amarillo
    'rgba(75, 192, 192, 0.5)',   // Verde agua
    'rgba(153, 102, 255, 0.5)',  // Púrpura
    'rgba(255, 159, 64, 0.5)',   // Naranja
    'rgba(231, 76, 60, 0.5)',    // Granate
    'rgba(46, 204, 113, 0.5)',   // Verde esmeralda
    'rgba(52, 152, 219, 0.5)',   // Azul claro
    'rgba(241, 196, 15, 0.5)'    // Amarillo oscuro
    ];
    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: " . json_encode($chartData['labels']) . ",
            datasets: [{
                label: 'Libros más solicitados',
                data: " . json_encode($chartData['data']) . ",
                backgroundColor: colores, // Asignar los colores al conjunto de datos
                borderColor: colores,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
");
?>