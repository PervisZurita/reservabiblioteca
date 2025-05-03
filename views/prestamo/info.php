<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $mesSeleccionado string */
/* @var $anioSeleccionado string */
/* @var $books array */
/* @var $computadoras array */
/* @var $tiposPrestamo array */
/* @var $bibliotecas array */
// Establecer la localización en español
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');
$this->title = 'Estadísticas';
$this->params['breadcrumbs'][] = $this->title;

$labelsLibros = [];
$dataLibros = [];

foreach ($books as $item) {
    $labelsLibros[] = $item['libro'];
    $dataLibros[] = $item['cantidad'];
}

$labelsComputadoras = [];
$dataComputadoras = [];

foreach ($computadoras as $item) {
    $labelsComputadoras[] = $item['computadora'];
    $dataComputadoras[] = $item['cantidad'];
}

?>
<style>
    .total-cell {
        background-color: #6c757d; /* Fondo oscuro */
        color: white;              /* Texto blanco */
        font-weight: bold;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filtrar por Mes y Año</h3>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(['method' => 'get']); ?>

                <div class="form-group">
                    <label for="mes">Mes:</label>
                    <select name="mes" class="form-control">
                        <?php
                        $meses = [
                            '00' => 'Todos',
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
                        ];
                        foreach ($meses as $mesNumero => $mesNombre) {
                            echo '<option value="' . $mesNumero . '"';
                            if ($mesNumero == $mesSeleccionado) {
                                echo ' selected';
                            }
                            echo '>' . $mesNombre . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="anio">Año:</label>
                    <?= Html::textInput('anio', $anioSeleccionado, ['class' => 'form-control', 'placeholder' => 'Ingrese el año']); ?>
                </div>

                <div class="form-group">
                    <label for="biblioteca_idbiblioteca">Biblioteca:</label>
                    <?= Html::dropDownList(
                        'biblioteca_idbiblioteca',
                        $bibliotecaSeleccionada,
                        \yii\helpers\ArrayHelper::map(\app\models\Biblioteca::find()->all(), 'idbiblioteca', 'Campus'),
                        ['prompt' => 'Seleccione el Campus', 'class' => 'form-control']
                    ) ?>

                </div>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-chart-bar"></i> Generar', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Resultados de Préstamos</h3>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    // Columna para el tipo de préstamo
                    [
                        'attribute' => 'nombre',
                        'label' => 'Tipo de Préstamo',
                    ],
                    // Columna para Estudiantes
                    [
                        'attribute' => 'Estudiante',
                        'label' => 'Estudiantes',
                        'value' => function($model) {
                            return isset($model['Estudiante']) ? $model['Estudiante'] : 0;
                        },
                    ],
                    // Columna para Externos
                    [
                        'attribute' => 'Externo',
                        'label' => 'Externos',
                        'value' => function($model) {
                            return isset($model['Externo']) ? $model['Externo'] : 0;
                        },
                    ],
                    // Columna para Personal Universitario
                    [
                        'attribute' => 'Personal Universitario',
                        'label' => 'Personal Universitario',
                        'value' => function($model) {
                            return isset($model['Personal Universitario']) ? $model['Personal Universitario'] : 0;
                        },
                    ],
                    // Columna para el total
                    [
                        'attribute' => 'total',
                        'label' => 'Total',
                        'value' => function($model) {
                            return isset($model['total']) ? $model['total'] : 0;
                        },
                        'contentOptions' => [
                            'style' => 'background-color: #6c757d; color: white; font-weight: bold;  text-align: center;', // Estilo CSS para el fondo oscuro y texto blanco
                        ],
                    ],
                ],
                'summary' => false, // Puedes desactivar la sección de resumen si no es necesario
                'options' => ['class' => 'table-responsive'], // Asegúrate de que la tabla se vea bien en dispositivos móviles
            ]); ?>
        </div>
    </div>
</div>

</div>


</div>



<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Préstamos por Tipo y Fecha</h3>
        </div>
        <div class="card-body">
        <?= GridView::widget([
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels' => $prestamosPorTipoYFecha,
    ]),
    'columns' => array_merge(
        [
            ['attribute' => 'nombre', 'label' => 'Tipo de Préstamo'],
        ],
        array_map(function($fecha) {
            // Obtener el día de la semana en español
            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');
            $diaSemana = strftime('%A', strtotime($fecha)); // 'lunes', 'martes', etc.
            $inicialDia = strtoupper($diaSemana[0]); // Tomar la inicial del día y convertirla a mayúscula

            return [
                'attribute' => $fecha,
                'label' => $inicialDia . "<br><span style='writing-mode: vertical-rl; transform: rotate(180deg);'>" . date('d-m-Y', strtotime($fecha)) . "</span>",
                'encodeLabel' => false,  // Permitir HTML en el label
                'format' => 'raw',
                'contentOptions' => function ($model, $key, $index, $column) use ($fecha) {
                    return ['style' => 'text-align: center;'];
                },
            ];
        }, $fechas),
        [
            [
                'attribute' => 'total',
                'label' => 'Total por Tipo',
                'contentOptions' => [
                            'style' => 'background-color: #6c757d; color: white; font-weight: bold;  text-align: center;', // Estilo CSS para el fondo oscuro y texto blanco
                        ],
            ],
        ]
    ),
]); ?>

</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Estadísticas de Préstamos por Tipo y Fecha</h3>
    </div>
    <div class="card-body">
        <canvas id="prestamosChart" style="width: 100%; height: 400px;"></canvas>
    </div>
</div>
</div>
</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Préstamos por Carrera y Fecha (COMPUTADORAS)</h3>
            </div>
            <div class="card-body">
                <?php
                // Filtrar los préstamos para incluir solo aquellos que son de tipo 'Computadora' y con total > 0
                $prestamosFiltrados = array_filter($prestamosPorCarreraYFecha, function($prestamo) {
                    return $prestamo['nombre'] === 'Computadora' && $prestamo['total'] > 0;
                });

                // Calcular el total general y los totales por fecha en base a los datos filtrados
                $totalesPorFechaFiltrados = array_fill_keys($fechas, 0);
                $totalGeneralFiltrado = 0;

                foreach ($prestamosFiltrados as $prestamo) {
                    $totalGeneralFiltrado += $prestamo['total']; // Acumula el total general
                    foreach ($fechas as $fecha) {
                        $totalesPorFechaFiltrados[$fecha] += $prestamo[$fecha]; // Acumula los totales por fecha
                    }
                }

                // Renderizar el GridView con los datos filtrados
                echo GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $prestamosFiltrados,
                    ]),
                    'showFooter' => true,
                    'columns' => array_merge(
                        [
                            ['attribute' => 'carrera', 'label' => 'Carrera', 'footer' => 'Total General'],
                        ],
                        array_map(function($fecha) use ($totalesPorFechaFiltrados) {
                            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');
                            $diaSemana = strftime('%A', strtotime($fecha));
                            $inicialDia = strtoupper($diaSemana[0]);

                            return [
                                'attribute' => $fecha,
                                'label' => $inicialDia . "<br><span style='writing-mode: vertical-rl; transform: rotate(180deg);'>" . date('d-m-Y', strtotime($fecha)) . "</span>",
                                'encodeLabel' => false,
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'footer' => $totalesPorFechaFiltrados[$fecha], // Total filtrado para cada fecha
                                'footerOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                            ];
                        }, $fechas),
                        [
                            [
                                'attribute' => 'total',
                                'label' => 'Total por Tipo',
                                'footer' => $totalGeneralFiltrado, // Total general filtrado
                                'footerOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                                'contentOptions' => [
                            'style' => 'background-color: #6c757d; color: white; font-weight: bold;  text-align: center;', // Estilo CSS para el fondo oscuro y texto blanco
                                ],
                            ],
                        ]
                    ),
                ]);
                ?>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Préstamos por Carrera y Fecha (ESPACIO FISICO)</h3>
            </div>
            <div class="card-body">
                <?php
                // Filtrar los préstamos para incluir solo aquellos que son de tipo 'Espacio Físico' y con total > 0
                $prestamosFiltrados = array_filter($prestamosPorCarreraYFecha, function($prestamo) {
                    return $prestamo['nombre'] === 'Espacio Físico' && $prestamo['total'] > 0;
                });

                // Calcular el total general y los totales por fecha en base a los datos filtrados
                $totalesPorFechaFiltrados = array_fill_keys($fechas, 0);
                $totalGeneralFiltrado = 0;

                foreach ($prestamosFiltrados as $prestamo) {
                    $totalGeneralFiltrado += $prestamo['total']; // Acumula el total general
                    foreach ($fechas as $fecha) {
                        $totalesPorFechaFiltrados[$fecha] += $prestamo[$fecha]; // Acumula los totales por fecha
                    }
                }

                // Renderizar el GridView con los datos filtrados
                echo GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $prestamosFiltrados,
                    ]),
                    'showFooter' => true,
                    'columns' => array_merge(
                        [
                            ['attribute' => 'carrera', 'label' => 'Carrera', 'footer' => 'Total General'],
                        ],
                        array_map(function($fecha) use ($totalesPorFechaFiltrados) {
                            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');
                            $diaSemana = strftime('%A', strtotime($fecha));
                            $inicialDia = strtoupper($diaSemana[0]);

                            return [
                                'attribute' => $fecha,
                                'label' => $inicialDia . "<br><span style='writing-mode: vertical-rl; transform: rotate(180deg);'>" . date('d-m-Y', strtotime($fecha)) . "</span>",
                                'encodeLabel' => false,
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'footer' => $totalesPorFechaFiltrados[$fecha], // Total filtrado para cada fecha
                                'footerOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                            ];
                        }, $fechas),
                        [
                            [
                                'attribute' => 'total',
                                'label' => 'Total por Tipo',
                                'contentOptions' => [
                            'style' => 'background-color: #6c757d; color: white; font-weight: bold;  text-align: center;', // Estilo CSS para el fondo oscuro y texto blanco
                        ],
                                'footer' => $totalGeneralFiltrado, // Total general filtrado
                                'footerOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                            ],
                        ]
                    ),
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Préstamos por Carrera y Fecha (LIBRO)</h3>
            </div>
            <div class="card-body">
                <?php
                // Filtrar los préstamos para incluir solo aquellos que son de tipo 'Espacio Físico' y con total > 0
                $prestamosFiltrados = array_filter($prestamosPorCarreraYFecha, function($prestamo) {
                    return $prestamo['nombre'] === 'Libro' && $prestamo['total'] > 0;
                });

                // Calcular el total general y los totales por fecha en base a los datos filtrados
                $totalesPorFechaFiltrados = array_fill_keys($fechas, 0);
                $totalGeneralFiltrado = 0;

                foreach ($prestamosFiltrados as $prestamo) {
                    $totalGeneralFiltrado += $prestamo['total']; // Acumula el total general
                    foreach ($fechas as $fecha) {
                        $totalesPorFechaFiltrados[$fecha] += $prestamo[$fecha]; // Acumula los totales por fecha
                    }
                }

                // Renderizar el GridView con los datos filtrados
                echo GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $prestamosFiltrados,
                    ]),
                    'showFooter' => true,
                    'columns' => array_merge(
                        [
                            ['attribute' => 'carrera', 'label' => 'Carrera', 'footer' => 'Total General'],
                        ],
                        array_map(function($fecha) use ($totalesPorFechaFiltrados) {
                            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');
                            $diaSemana = strftime('%A', strtotime($fecha));
                            $inicialDia = strtoupper($diaSemana[0]);

                            return [
                                'attribute' => $fecha,
                                'label' => $inicialDia . "<br><span style='writing-mode: vertical-rl; transform: rotate(180deg);'>" . date('d-m-Y', strtotime($fecha)) . "</span>",
                                'encodeLabel' => false,
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'footer' => $totalesPorFechaFiltrados[$fecha], // Total filtrado para cada fecha
                                'footerOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                            ];
                        }, $fechas),
                        [
                            [
                                'attribute' => 'total',
                                'label' => 'Total por Tipo',
                                'contentOptions' => [
                            'style' => 'background-color: #6c757d; color: white; font-weight: bold;  text-align: center;', // Estilo CSS para el fondo oscuro y texto blanco
                        ],
                               
                                'footer' => $totalGeneralFiltrado, // Total general filtrado
                                'footerOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                            ],
                        ]
                    ),
                ]);
                ?>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Libros Más Solicitados</h3>
            </div>
            <div class="card-body">
                <canvas id="chartLibros" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Computadoras Más Solicitadas</h3>
            </div>
            <div class="card-body">
                <canvas id="chartComputadoras" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<?php
// Código JavaScript para crear los gráficos con Chart.js
$this->registerJs("
    var ctxLibros = document.getElementById('chartLibros').getContext('2d');
    var coloresCalidos = [
    'rgba(231, 76, 60, 0.5)',    // Granate
    'rgba(217, 30, 24, 0.5)',    // Rojo oscuro
    'rgba(192, 57, 43, 0.5)',    // Rojo intenso
    'rgba(231, 111, 81, 0.5)',   // Rojo salmón
    'rgba(203, 67, 53, 0.5)',    // Rojo pálido
    'rgba(242, 120, 75, 0.5)',   // Naranja intenso
    'rgba(245, 171, 53, 0.5)',   // Naranja vivo
    'rgba(211, 84, 0, 0.5)',     // Naranja oscuro
    'rgba(241, 196, 15, 0.5)',   // Amarillo oscuro
    'rgba(244, 208, 63, 0.5)'    // Amarillo intenso
    ];
    var chartLibros = new Chart(ctxLibros, {
        type: 'bar',
        data: {
            labels: " . json_encode($labelsLibros) . ",
            datasets: [{
                label: 'Libros Más Solicitados',
                data: " . json_encode($dataLibros) . ",
                backgroundColor: coloresCalidos,
                borderColor: coloresCalidos,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctxComputadoras = document.getElementById('chartComputadoras').getContext('2d');
    var coloresFrios = [
    'rgba(52, 152, 219, 0.5)',   // Azul claro
    'rgba(44, 130, 201, 0.5)',   // Azul medio
    'rgba(38, 97, 156, 0.5)',    // Azul oscuro
    'rgba(90, 200, 250, 0.5)',   // Azul agua
    'rgba(142, 196, 221, 0.5)',  // Azul pálido
    'rgba(115, 185, 190, 0.5)',  // Azul verdoso
    'rgba(79, 193, 233, 0.5)',   // Azul celeste
    'rgba(93, 173, 226, 0.5)',   // Azul pastel
    'rgba(72, 126, 176, 0.5)',   // Azul acero
    'rgba(68, 108, 179, 0.5)'    // Azul intenso
    ];
    var chartComputadoras = new Chart(ctxComputadoras, {
        type: 'line',
        data: {
            labels: " . json_encode($labelsComputadoras) . ",
            datasets: [{
                label: 'Computadoras Más Solicitadas',
                data: " . json_encode($dataComputadoras) . ",
                backgroundColor: coloresFrios,
                borderColor: coloresFrios,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
");

$labels = json_encode(array_map(function($fecha) {
    return date('d-m-Y', strtotime($fecha));
}, $fechas));

$datasets = [];

foreach ($prestamosPorTipoYFecha as $tipo) {
    $data = [];
    foreach ($fechas as $fecha) {
        $data[] = $tipo[$fecha] ?? 0; // Suponiendo que cada $tipo tiene los valores correspondientes a las fechas
    }
    $datasets[] = [
        'label' => $tipo['nombre'],
        'data' => $data,
        'borderColor' => '#' . substr(md5(rand()), 0, 6), // Color aleatorio para cada tipo
        'fill' => false,
    ];
}
?>

<?php $this->registerJs("
    var ctx = document.getElementById('prestamosChart').getContext('2d');
    var prestamosChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: $labels,
            datasets: " . json_encode($datasets) . "
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Fechas'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Cantidad de Préstamos'
                    }
                }
            }
        }
    });
"); 
?>