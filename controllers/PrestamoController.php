<?php

namespace app\controllers;

use Yii;
use app\models\Libro;
use app\models\Pc;
use app\models\Biblioteca;
use app\models\Prestamo;
use app\models\DetalleMatricula;
use app\models\Factura;
use app\models\Carrera;
use app\models\PrestamoSearch;
use app\models\Tipoprestamo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\JsExpression;
use yii\db\Expression;
use yii\data\ArrayDataProvider; // Asegúrate de incluir esta línea

/**
 * PrestamoController implements the CRUD actions for Prestamo model.
 */
class PrestamoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Prestamo models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PrestamoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
    /*
        // Ordenar por fecha de solicitud
        $dataProvider->query->joinWith([
            'informacionpersonalCIInfPer.factura.detalleMatricula.carrera',
        ])
        ->orderBy(['fecha_solicitud' => SORT_DESC]);
        
        */
   

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Prestamo model.
     * @param int $id ID
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $biblioteca_idbiblioteca)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $biblioteca_idbiblioteca),
        ]);
    }

    /**
     * Creates a new Prestamo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Prestamo();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                list($horas, $minutos) = explode(':', $model->intervalo_solicitado);

            
                
            $fechaSolicitud = new \DateTime($model->fecha_solicitud);
	    $model->fecha_solicitud = Yii::$app->formatter->asDatetime($fechaSolicitud , 'yyyy-MM-dd HH:mm:ss');
            $intervalo = new \DateInterval('PT' . $horas . 'H' . $minutos . 'M'); // PT horas minutos
            $fechaEntrega = $fechaSolicitud->add($intervalo);
            $model->fechaentrega = Yii::$app->formatter->asDatetime($fechaEntrega, 'yyyy-MM-dd HH:mm:ss');
 	     
if ($model->tipoprestamo_id === 'COMP') {
                    $model->pc_biblioteca_idbiblioteca = $model->biblioteca_idbiblioteca;
                } elseif ($model->tipoprestamo_id === 'LIB') {
                    $model->libro_biblioteca_idbiblioteca = $model->biblioteca_idbiblioteca;
                }

                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Prestamo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $biblioteca_idbiblioteca)
    {
        $model = $this->findModel($id, $biblioteca_idbiblioteca);

        // Verificar si se envió un formulario
        if ($model->load(\Yii::$app->request->post())) {
            // Asignar valores y calcular la fecha de entrega
            list($horas, $minutos) = explode(':', $model->intervalo_solicitado);

            $fechaSolicitud = new \DateTime($model->fecha_solicitud);
            $intervalo = new \DateInterval('PT' . $horas . 'H' . $minutos . 'M'); // PT horas minutos
            $fechaEntrega = $fechaSolicitud->add($intervalo);
            $model->fechaentrega = Yii::$app->formatter->asDatetime($fechaEntrega, 'yyyy-MM-dd HH:mm:ss');

            // Verificar si el modelo se guarda con éxito
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Prestamo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $biblioteca_idbiblioteca)
    {
        $this->findModel($id, $biblioteca_idbiblioteca)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Prestamo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return Prestamo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $biblioteca_idbiblioteca)
    {
        if (($model = Prestamo::findOne(['id' => $id, 'biblioteca_idbiblioteca' => $biblioteca_idbiblioteca])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPrestarlibro($id)
    {

        // Cargar el modelo de libro basado en el $id recibido        
        $libro = Libro::findOne(['id' => $id]);

        // Verificar si el libro existe
        if (!$libro) {
            throw new NotFoundHttpException('El libro no se encontró.');
        }

        $model = new Prestamo();

        if (!Yii::$app->user->isGuest) {
            $userData = Yii::$app->user->identity;

            // Verifica qué relación no es nula y asigna la cédula correspondiente
            if ($userData->personaldata !== null) {
                $model->cedula_solicitante = $userData->personaldata->Ci;
                $model->personaldata_Ci = $model->cedula_solicitante;
            } elseif ($userData->informacionpersonal !== null) {
                $model->cedula_solicitante = $userData->informacionpersonal->CIInfPer;
                $model->informacionpersonal_CIInfPer = $model->cedula_solicitante;
            } elseif ($userData->informacionpersonalD !== null) {
                $model->cedula_solicitante = $userData->informacionpersonalD->CIInfPer;
                $model->informacionpersonal_d_CIInfPer = $model->cedula_solicitante;;
            }
        }

        $model->tipoprestamo_id = 'LIB';

        $model->biblioteca_idbiblioteca = $libro->biblioteca_idbiblioteca; // Campus donde se encuentra el usuario... mejorar (Y)
        $model->libro_id = $id;
        $model->libro_biblioteca_idbiblioteca = $libro->biblioteca_idbiblioteca; //Campus donde se encuentra el libro

        // Verificar si se envió un formulario
        if ($model->load(\Yii::$app->request->post())) {
            // Asignar valores y calcular la fecha de entrega
            list($horas, $minutos) = explode(':', $model->intervalo_solicitado);

          
            $fechaSolicitud = new \DateTime($model->fecha_solicitud);
	    $model->fecha_solicitud = Yii::$app->formatter->asDatetime($fechaSolicitud , 'yyyy-MM-dd HH:mm:ss');
            $intervalo = new \DateInterval('PT' . $horas . 'H' . $minutos . 'M'); // PT horas minutos
            $fechaEntrega = $fechaSolicitud->add($intervalo);
            $model->fechaentrega = Yii::$app->formatter->asDatetime($fechaEntrega, 'yyyy-MM-dd HH:mm:ss');
 	     
            // Verificar si el modelo se guarda con éxito
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
            }
        }

        return $this->renderAjax('prestarlib', [
            'model' => $model,
        ]);
    }


    public function actionPrestarpc($id)
    {
        $pc = Pc::findOne(['idpc' => $id]);

        // Verificar si el computador existe
        if (!$pc) {
            throw new NotFoundHttpException('El computador no se encontró.');
        }

        $model = new Prestamo();

        if (!Yii::$app->user->isGuest) {
            $userData = Yii::$app->user->identity;

            // Verifica qué relación no es nula y asigna la cédula correspondiente
            if ($userData->personaldata !== null) {
                $model->cedula_solicitante = $userData->personaldata->Ci;
                $model->personaldata_Ci = $model->cedula_solicitante;
            } elseif ($userData->informacionpersonal !== null) {
                $model->cedula_solicitante = $userData->informacionpersonal->CIInfPer;
                $model->informacionpersonal_CIInfPer = $model->cedula_solicitante;
            } elseif ($userData->informacionpersonalD !== null) {
                $model->cedula_solicitante = $userData->informacionpersonalD->CIInfPer;
                $model->informacionpersonal_d_CIInfPer = $model->cedula_solicitante;;
            }
        }

        $model->intervalo_solicitado = '01:00:00';
        $model->tipoprestamo_id = 'COMP';
        $model->biblioteca_idbiblioteca = $pc->biblioteca_idbiblioteca; // Campus donde se encuentra el usuario... mejorar (Y)
        $model->pc_idpc = $id;
        $model->pc_biblioteca_idbiblioteca = $pc->biblioteca_idbiblioteca; //Campus donde se encuentra el computador

        // Verificar si se envió un formulario
        if ($model->load(\Yii::$app->request->post())) {
            // Asignar valores y calcular la fecha de entrega
            list($horas, $minutos) = explode(':', $model->intervalo_solicitado);

            
            $fechaSolicitud = new \DateTime($model->fecha_solicitud);
	    $model->fecha_solicitud = Yii::$app->formatter->asDatetime($fechaSolicitud , 'yyyy-MM-dd HH:mm:ss');
            $intervalo = new \DateInterval('PT' . $horas . 'H' . $minutos . 'M'); // PT horas minutos
            $fechaEntrega = $fechaSolicitud->add($intervalo);
            $model->fechaentrega = Yii::$app->formatter->asDatetime($fechaEntrega, 'yyyy-MM-dd HH:mm:ss');
 	     
            // Verificar si el modelo se guarda con éxito
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
            }
        }

        return $this->renderAjax('prestarcomp', [
            'model' => $model,
        ]);
    }


    public function actionPrestarespacio()
    {

        $model = new Prestamo();

        if (!Yii::$app->user->isGuest) {
            $userData = Yii::$app->user->identity;

            // Verifica qué relación no es nula y asigna la cédula correspondiente
            if ($userData->personaldata !== null) {
                $model->cedula_solicitante = $userData->personaldata->Ci;
                $model->personaldata_Ci = $model->cedula_solicitante;
            } elseif ($userData->informacionpersonal !== null) {
                $model->cedula_solicitante = $userData->informacionpersonal->CIInfPer;
                $model->informacionpersonal_CIInfPer = $model->cedula_solicitante;
            } elseif ($userData->informacionpersonalD !== null) {
                $model->cedula_solicitante = $userData->informacionpersonalD->CIInfPer;
                $model->informacionpersonal_d_CIInfPer = $model->cedula_solicitante;;
            }
        }
        // Asignar valores al modelo de Prestamo
        $model->tipoprestamo_id = 'ESP';

        //$biblioteca = \Yii::$app->controller->findBibliotecaById(['idbiblioteca' => $model->biblioteca_idbiblioteca]);
        // Verificar si se envió un formulario
        if ($model->load(\Yii::$app->request->post())) {
            // Asignar valores y calcular la fecha de entrega
            list($horas, $minutos) = explode(':', $model->intervalo_solicitado);

            $fechaSolicitud = new \DateTime($model->fecha_solicitud);
	    $model->fecha_solicitud = Yii::$app->formatter->asDatetime($fechaSolicitud , 'yyyy-MM-dd HH:mm:ss');
            $intervalo = new \DateInterval('PT' . $horas . 'H' . $minutos . 'M'); // PT horas minutos
            $fechaEntrega = $fechaSolicitud->add($intervalo);
            $model->fechaentrega = Yii::$app->formatter->asDatetime($fechaEntrega, 'yyyy-MM-dd HH:mm:ss');
 	     

            // Verificar si el modelo se guarda con éxito
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
            }
        }

        return $this->renderAjax('prestaresp', [
            'model' => $model,
        ]);
    }


    public function actionInfo()
    {
        $mesSeleccionado = Yii::$app->request->get('mes', date('m'));
        $anioSeleccionado = Yii::$app->request->get('anio', date('Y'));
        $bibliotecaSeleccionada = Yii::$app->request->get('biblioteca_idbiblioteca', null);

        // Obtén las bibliotecas disponibles
        $bibliotecas = Biblioteca::find()->all();

        // Obtener los datos de libro limitando a 10 elementos
        $queryLibro = new \yii\db\Query();
        $queryLibro->select(['MONTH(fecha_solicitud) AS mes', 'id', 'COUNT(*) AS cantidad'])
            ->from('prestamo')
            ->where(['MONTH(fecha_solicitud)' => $mesSeleccionado, 'YEAR(fecha_solicitud)' => $anioSeleccionado])
            ->andFilterWhere(['biblioteca_idbiblioteca' => $bibliotecaSeleccionada]) // Cambio aquí
            ->groupBy(['mes', 'lid'])
            ->orderBy(['mes' => SORT_ASC, 'cantidad' => SORT_DESC])
            ->distinct()
            ->limit(10); // Limitar a 10 elementos

        $dataLibro = $queryLibro->all();

        $books = [];

        foreach ($dataLibro as $row) {
            $mes = $row['mes'];
            $libroId = $row['id'];
            $cantidad = $row['cantidad'];

            // Obtén información del libro, incluyendo el nombre
            $libro = Libro::findOne($libroId);

            if ($libro && $libro->titulo !== null && $libro->titulo !== 'No disponible') {
                $nombreLibro = $libro->titulo;
                $books[] = [
                    'mes' => $mes,
                    'libro' => $nombreLibro,
                    'cantidad' => $cantidad,
                ];
            }
        }

        // Obtener los datos de computadora limitando a 10 elementos
        $queryPc = new \yii\db\Query();
        $queryPc->select(['MONTH(fecha_solicitud) AS mes', 'pc_idpc', 'COUNT(*) AS cantidad'])
            ->from('prestamo')
            ->where(['MONTH(fecha_solicitud)' => $mesSeleccionado, 'YEAR(fecha_solicitud)' => $anioSeleccionado])
            ->andFilterWhere(['biblioteca_idbiblioteca' => $bibliotecaSeleccionada]) // Cambio aquí
            ->groupBy(['mes', 'pc_idpc'])
            ->orderBy(['mes' => SORT_ASC, 'cantidad' => SORT_DESC])
            ->distinct()
            ->limit(10); // Limitar a 10 elementos

        $dataPc = $queryPc->all();

        $computadoras = [];

        foreach ($dataPc as $row) {
            $mes = $row['mes'];
            $pcId = $row['pc_idpc'];
            $cantidad = $row['cantidad'];

            // Obtén información de la computadora, incluyendo el nombre
            $computadora = Pc::findOne($pcId);

            if ($computadora && $computadora->idpc !== null && $computadora->idpc !== 'No disponible') {
                $nombreComputadora = $computadora->nombre;
                $computadoras[] = [
                    'mes' => $mes,
                    'computadora' => $nombreComputadora,
                    'cantidad' => $cantidad,
                ];
            }
        }

        // Obtener los tipos de préstamo
$tiposPrestamo = Tipoprestamo::find()->all();

// Definir un arreglo para almacenar los resultados
$tablaResultados = [];

// Inicializar acumuladores para los totales
$totalesPorSolicitante = [];

// Obtener los datos de los préstamos (tipos de solicitante y cantidad)
$query = new \yii\db\Query();
$query->select([
    'tipo_solicitante' => new \yii\db\Expression('
        CASE
            WHEN informacionpersonal_d_CIInfPer IS NOT NULL THEN "Personal Universitario"
            WHEN personaldata_Ci IS NOT NULL THEN "Externo"
            WHEN informacionpersonal_CIInfPer IS NOT NULL THEN "Estudiante"
            ELSE "N/A"
        END
    '),
    'tipo_prestamo' => 'prestamo.tipoprestamo_id', // Asegúrate de que este campo exista en tu tabla `prestamo`
    'cantidad' => 'COUNT(*)'
])
->from('prestamo')
->leftJoin('informacionpersonal', 'prestamo.informacionpersonal_CIInfPer = informacionpersonal.CIInfPer')
->leftJoin('personaldata', 'prestamo.personaldata_Ci = personaldata.Ci')
->where(['YEAR(fecha_solicitud)' => $anioSeleccionado]);
// Filtrar por mes solo si el mes seleccionado no es 0
if ($mesSeleccionado != 0) {
    $query->andWhere(['MONTH(fecha_solicitud)' => $mesSeleccionado]);
}

// Filtro adicional para la biblioteca si está seleccionado
$query->andFilterWhere(['biblioteca_idbiblioteca' => $bibliotecaSeleccionada]);

// Agrupación por tipo de solicitante y tipo de préstamo
$query->groupBy(['tipo_solicitante', 'tipo_prestamo']);


$data = $query->all();

// Inicializar la estructura de datos para la tabla de resultados
foreach ($tiposPrestamo as $tipo) {
    // Inicializar la fila para este tipo de préstamo
    $fila = ['nombre' => $tipo->nombre_tipo, 'total' => 0];

    // Procesar los datos obtenidos de la consulta
    foreach ($data as $row) {
        // Verificar si el tipo de préstamo coincide con el nombre del tipo
        if ($row['tipo_prestamo'] == $tipo->id) {
            // Asignar la cantidad a la fila correspondiente
            $fila[$row['tipo_solicitante']] = $row['cantidad'];
            $fila['total'] += $row['cantidad']; // Total por tipo de préstamo

            // Acumular la cantidad por tipo de solicitante
            if (!isset($totalesPorSolicitante[$row['tipo_solicitante']])) {
                $totalesPorSolicitante[$row['tipo_solicitante']] = 0;
            }
            $totalesPorSolicitante[$row['tipo_solicitante']] += $row['cantidad'];
        }
    }

    // Agregar la fila a la tabla de resultados
    $tablaResultados[] = $fila;
}

// Agregar la fila de totales al final
$totalFila = ['nombre' => 'Total', 'total' => 0];
foreach ($totalesPorSolicitante as $tipoSolicitante => $totalCantidad) {
    $totalFila[$tipoSolicitante] = $totalCantidad; // Asignar total por tipo de solicitante
    $totalFila['total'] += $totalCantidad; // Total general
}

// Agregar la fila de totales a la tabla de resultados
$tablaResultados[] = $totalFila;

// Crear el DataProvider para la vista
$dataProvider = new ArrayDataProvider([
    'allModels' => $tablaResultados,
    'pagination' => false, // Desactivar paginación si es necesario
]);

// Generar todas las fechas del mes seleccionado
$fechas = [];
$totalesPorDia = [];
if ($mesSeleccionado != 0){
    $numeroDiasMes = cal_days_in_month(CAL_GREGORIAN, $mesSeleccionado, $anioSeleccionado);

    for ($dia = 1; $dia <= $numeroDiasMes; $dia++) {
    $fecha = sprintf('%04d-%02d-%02d', $anioSeleccionado, $mesSeleccionado, $dia);
    $fechas[] = $fecha;
    $totalesPorDia[$fecha] = 0;  // Inicializar totales por día
    }
}
else 
{
// Generar todas las fechas del año seleccionado, pero solo por mes
for ($mes = 1; $mes <= 12; $mes++) {
    // Generar fecha con solo año y mes
    $fecha = sprintf('%04d-%02d', $anioSeleccionado, $mes);
    $fechas[] = $fecha;
    $totalesPorDia[$fecha] = 0;  // Inicializar totales por mes
}
}
// Procesar los préstamos por tipo y fecha
$prestamosPorTipoYFecha = [];
$totalGeneral = 0;

// Obtener la lista de tipos de préstamos
$tiposPrestamo = Tipoprestamo::find()->all();

foreach ($tiposPrestamo as $tipo) {
    // Inicializar la fila para este tipo de préstamo
    $fila = ['nombre' => $tipo->nombre_tipo, 'total' => 0];
    
    // Recorrer todas las fechas del mes
    foreach ($fechas as $fecha) {
        // Aquí debes asegurarte de obtener la cantidad de préstamos correcta para este tipo y fecha
        // Por ejemplo, si tienes una tabla 'prestamos' con las columnas 'tipo_prestamo_id' y 'fecha'
        // podrías hacer algo así para contar los préstamos por tipo y fecha:
        if ($mesSeleccionado != 0){
        $cantidadPrestamos = Prestamo::find()
            ->where(['tipoprestamo_id' => $tipo->id])
            ->andWhere(['DATE(fecha_solicitud)' => $fecha])
            ->count();
        }else{
            $cantidadPrestamos = Prestamo::find()
            ->where(['tipoprestamo_id' => $tipo->id])
            ->andWhere(['like', 'DATE_FORMAT(fecha_solicitud, "%Y-%m")', $fecha])  // Filtra por año y mes
            ->count();
        }
           
        // Guardar la cantidad de préstamos en la fila
        $fila[$fecha] = $cantidadPrestamos;

        // Sumar al total de este tipo
        $fila['total'] += $cantidadPrestamos;

        // Sumar al total por día
        $totalesPorDia[$fecha] += $cantidadPrestamos;

        // Sumar al total general
        $totalGeneral += $cantidadPrestamos;
    }
    
    // Agregar la fila al array principal
    $prestamosPorTipoYFecha[] = $fila;
}

// Añadir los totales por día
$totales = ['nombre' => 'Total por Día'];
foreach ($fechas as $fecha) {
    // Añadir los totales por día a la fila de totales
    $totales[$fecha] = $totalesPorDia[$fecha];
}
$totales['total'] = $totalGeneral; // Añadir el total global

// Añadir la fila de totales al final
$prestamosPorTipoYFecha[] = $totales;

// Datos agrupados por carrera y fecha
$fechas = [];
$totalesPorFecha = []; // Para almacenar los totales por fecha
$totalGeneral = 0; // Variable para el total general

if ($mesSeleccionado != 0){
    $numeroDiasMes = cal_days_in_month(CAL_GREGORIAN, $mesSeleccionado, $anioSeleccionado);

    for ($dia = 1; $dia <= $numeroDiasMes; $dia++) {
    $fecha = sprintf('%04d-%02d-%02d', $anioSeleccionado, $mesSeleccionado, $dia);
    $fechas[] = $fecha;
    $totalesPorDia[$fecha] = 0;  // Inicializar totales por día
    }
}
else 
{
// Generar todas las fechas del año seleccionado, pero solo por mes
for ($mes = 1; $mes <= 12; $mes++) {
    // Generar fecha con solo año y mes
    $fecha = sprintf('%04d-%02d', $anioSeleccionado, $mes);
    $fechas[] = $fecha;
    $totalesPorDia[$fecha] = 0;  // Inicializar totales por mes
}
}


// Obtener las carreras
$carreras = Carrera::find()
    ->select(['idCarr', 'NombCarr'])
    ->distinct()
    ->where(['like', 'NombCarr', '2020'])
    ->andWhere(['StatusCarr' => 1])
    ->all();

$prestamosPorCarreraYFecha = [];

    $totalesPorFecha[$fecha] = 0;


foreach ($carreras as $carrera) {
    $nombreCarrera = $carrera->NombCarr;

    foreach ($tiposPrestamo as $tipo) {
        $fila = ['nombre' => $tipo->nombre_tipo, 'carrera' => $nombreCarrera, 'total' => 0];
                      
        foreach ($fechas as $fecha) {
     // Inicializar la clave en $totalesPorFecha si no existe
     if (!isset($totalesPorFecha[$fecha])) {
        $totalesPorFecha[$fecha] = 0;
    }
            if ($mesSeleccionado != 0){
           
            $cantidadPrestamos = (new \yii\db\Query())
                ->select(['COUNT(DISTINCT prestamo.informacionpersonal_CIInfPer)'])
                ->from('prestamo')
                ->leftJoin('informacionpersonal', 'prestamo.informacionpersonal_CIInfPer = informacionpersonal.CIInfPer')
                ->leftJoin('factura', 'informacionpersonal.CIInfPer = factura.cedula')
                ->leftJoin('detalle_matricula', 'factura.id = detalle_matricula.idfactura')
                ->leftJoin('carrera AS carrera2', 'detalle_matricula.idcarr = carrera2.idCarr')
                ->where([
                    'tipoprestamo_id' => $tipo->id,
                    'carrera2.NombCarr' => $nombreCarrera,
                    'DATE(fecha_solicitud)' => $fecha,
                ])
                ->orderBy(['factura.idper' => SORT_DESC, 'detalle_matricula.idmatricula' => SORT_DESC, 'carrera2.idCarr' => SORT_DESC])
                ->scalar();

               
                }else{
                    
            $cantidadPrestamos = (new \yii\db\Query())
                ->select(['COUNT(DISTINCT prestamo.informacionpersonal_CIInfPer)'])
                ->from('prestamo')
                ->leftJoin('informacionpersonal', 'prestamo.informacionpersonal_CIInfPer = informacionpersonal.CIInfPer')
                ->leftJoin('factura', 'informacionpersonal.CIInfPer = factura.cedula')
                ->leftJoin('detalle_matricula', 'factura.id = detalle_matricula.idfactura')
                ->leftJoin('carrera AS carrera2', 'detalle_matricula.idcarr = carrera2.idCarr')
                ->where([
                        'tipoprestamo_id' => $tipo->id,
                    'carrera2.NombCarr' => $nombreCarrera,
                ])
                ->andWhere(['like', 'DATE_FORMAT(fecha_solicitud, "%Y-%m")', $fecha])  // Filtra por año y mes
                ->orderBy(['factura.idper' => SORT_DESC, 'detalle_matricula.idmatricula' => SORT_DESC, 'carrera2.idCarr' => SORT_DESC])
                ->scalar();

            }
            // Acumula el total para esta fecha y carrera
            $fila[$fecha] = $cantidadPrestamos;
            $fila['total'] += $cantidadPrestamos;
                        
            $totalesPorFecha[$fecha] += $cantidadPrestamos; // Acumula el total por fecha
        }

        $prestamosPorCarreraYFecha[] = $fila;
        $totalGeneral += $fila['total']; // Acumula el total general
    }
}

// Ordenar los préstamos por carrera y total de mayor a menor
usort($prestamosPorCarreraYFecha, function($a, $b) {
    return $b['total'] <=> $a['total']; // Orden descendente
});

// Crear la fila del total general usando los totales por fecha
$filaTotal = ['nombre' => 'Total General', 'carrera' => '', 'total' => $totalGeneral];
foreach ($fechas as $fecha) {
    $filaTotal[$fecha] = $totalesPorFecha[$fecha];
}
$prestamosPorCarreraYFecha[] = $filaTotal;


        return $this->render('info', [
            'prestamosPorCarreraYFecha' => $prestamosPorCarreraYFecha,
            'mesSeleccionado' => $mesSeleccionado,
            'anioSeleccionado' => $anioSeleccionado,
            'bibliotecas' => $bibliotecas,
            'bibliotecaSeleccionada' => $bibliotecaSeleccionada,
            'books' => $books,
            'computadoras' => $computadoras,
           // 'tiposSolicitante' => $tiposSolicitante,
            'prestamosPorTipoYFecha' => $prestamosPorTipoYFecha,  // Asegúrate de que el arreglo de datos esté bien nombrado
            'fechas' => $fechas,  // Pasar la variable de fechas a la vista 
            'totalesPorDia' => $totalesPorDia,
         
            'totalesPorFecha' => $totalesPorFecha, // Asegúrate de incluir esto
            'totalGeneral' => $totalGeneral, // Asegúrate de pasar la variable
            'tablaResultados' => $tablaResultados,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionEstadisticalibro()
    {

        $mesSeleccionado = Yii::$app->request->get('mes', date('m'));
        $anioSeleccionado = Yii::$app->request->get('anio', date('Y'));
        $bibliotecaSeleccionada = Yii::$app->request->get('bibliotecaId', null);
        $asignaturaSeleccionada =  Yii::$app->request->get('asignaturaId', null);

        $query = new \yii\db\Query();
        
        $query->select(['libro.titulo', 'COUNT(*) as total'])
            ->from('prestamo')
            ->where(['MONTH(fecha_solicitud)' => $mesSeleccionado, 'YEAR(fecha_solicitud)' => $anioSeleccionado])
            ->join('INNER JOIN', 'libro', 'libro.id = prestamo.libro_id AND libro.biblioteca_idbiblioteca = prestamo.libro_biblioteca_idbiblioteca')
            ->groupBy('libro.titulo')
            ->orderBy('total DESC')
            ->limit(10);

        if ($asignaturaSeleccionada !== null) {
            $query->andWhere(['libro.asignatura_id' => $asignaturaSeleccionada]);
        }

        if ($bibliotecaSeleccionada !== null) {
            $query->andWhere(['prestamo.biblioteca_idbiblioteca' => $bibliotecaSeleccionada]); // Cambio aquí
        }

        $librosMasSolicitados = $query->all();

        $labels = [];
        $data = [];
        foreach ($librosMasSolicitados as $libro) {
            $labels[] = $libro['titulo'];
            $data[] = $libro['total'];
        }

        // Datos para el gráfico
        $chartData = [
            'labels' => $labels,
            'data' => $data,
        ];

        return $this->render('estadisticalibro', [
            'librosMasSolicitados' =>$librosMasSolicitados,
            'chartData' => $chartData, // Pasar los datos del gráfico a la vista
            'mesSeleccionado' => $mesSeleccionado,
            'anioSeleccionado' => $anioSeleccionado,
            'bibliotecaSeleccionada' => $bibliotecaSeleccionada,
            'asignaturaSeleccionada' => $asignaturaSeleccionada,
        ]);
    }
}
