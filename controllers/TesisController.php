<?php

namespace app\controllers;
use Yii;  
use yii\web\UploadedFile;
use yii\helpers\Json;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\models\Tesis;
use app\models\TesisSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TesisController implements the CRUD actions for Tesis model.
 */
class TesisController extends Controller
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
     * Lists all Tesis models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TesisSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tesis model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tesis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Tesis();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tesis model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tesis model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tesis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Tesis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tesis::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    // Exportar a CSV
    public function actionExport()
    {
        $tesis = \app\models\Tesis::find()->all();
    
        $filename = 'tesis_export.csv';
        $fp = fopen('php://output', 'w');
    
        // Encabezado
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0'); // No caché para forzar la descarga
    
        // Columnas
        fputcsv($fp, [
            'numero de estanteria', 'facultad', 'carrera', 'tema', 'autor', 
            'tutor', 'anio de publicacion', 'codigo'
        ]);
    
        // Datos de las tesis
        foreach ($tesis as $item) {
            fputcsv($fp, [
                $item->numero_estanteria,
                $item->facultad,
                $item->carrera,
                $item->tema,
                $item->autor,
                $item->tutor,
                $item->anio_publicacion,
                $item->codigo,
            ]);
        }
    
        fclose($fp);
        exit; // Asegúrate de que la salida se detenga después de enviar el archivo
    }
    public function actionImport()
{
    if (Yii::$app->request->isPost && isset($_FILES['importFile'])) {
        $file = $_FILES['importFile']['tmp_name'];

        // Abrir el archivo CSV
        if (($handle = fopen($file, 'r')) !== false) {
            $first = true; // Omite la primera línea (cabecera)
            $count = 0; // Para contar cuántas filas se insertan

            // Leer las filas del archivo CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($first) {
                    $first = false;
                    continue;
                }

                // Crear una nueva tesis
                $tesis = new \app\models\Tesis();
                $tesis->numero_estanteria = $data[0] ?? null;
                $tesis->facultad = $data[1] ?? null;
                $tesis->carrera = $data[2] ?? null;
                $tesis->tema = $data[3] ?? null;
                $tesis->autor = $data[4] ?? null;
                $tesis->tutor = $data[5] ?? null;
                $tesis->anio_publicacion = $data[6] ?? null;
                $tesis->codigo = $data[7] ?? null;

                // Guardar la tesis en la base de datos
                if ($tesis->save()) {
                    $count++;
                } else {
                    Yii::error("Error al guardar tesis en fila $count: " . json_encode($tesis->errors));
                }
            }

            fclose($handle);

            Yii::$app->session->setFlash('success', "$count tesis importadas correctamente.");
        } else {
            Yii::$app->session->setFlash('error', "No se pudo abrir el archivo.");
        }
    } else {
        Yii::$app->session->setFlash('error', "No se recibió ningún archivo.");
    }

    return $this->redirect(['index']); // o la vista que desees mostrar luego de la importación
}


     



// Descargar plantilla CSV
public function actionPlantilla()
{
    $csvContent = "numero_estanteria,facultad,carrera,tema,autor,tutor,anio_publicacion,codigo\n";
    $csvContent .= "12A,Ingeniería,Sistemas Computacionales,Inteligencia Artificial,Juan Pérez,Dra. María López,2023,TSC-2023-001\n";
    $csvContent .= "15B,Ciencias,Física,Electromagnetismo,Laura Gómez,Dr. Luis García,2022,FIS-2022-002\n";

    Yii::$app->response->sendContentAsFile($csvContent, 'plantilla_tesis.csv', [
        'mimeType' => 'text/csv',
        'forceDownload' => true,
    ]);
}

    
}
