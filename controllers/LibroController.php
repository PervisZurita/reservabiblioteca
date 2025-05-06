<?php

namespace app\controllers;
use yii;
use app\models\Libro;
use app\models\LibroSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\data\ActiveDataProvider;



/**
 * LibroController implements the CRUD actions for Libro model.
 */
class LibroController extends Controller
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
     * Lists all Libro models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LibroSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Libro model.
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
     * Creates a new Libro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Libro();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Libro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $biblioteca_idbiblioteca)
    {
        $model = $this->findModel($id, $biblioteca_idbiblioteca);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Libro model.
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

    
public function actionEstudiante()
{
    $searchModel = new LibroSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('estudiante', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}



    /**
     * Finds the Libro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return Libro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $biblioteca_idbiblioteca)
    {
        if (($model = Libro::findOne(['id' => $id, 'biblioteca_idbiblioteca' => $biblioteca_idbiblioteca])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    // Exportar a CSV
public function actionExport()
{
    $libros = \app\models\Libro::find()->all();

    $filename = 'libros_export.csv';
    $fp = fopen('php://output', 'w');

    // Encabezado
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=' . $filename);

    // Columnas
    fputcsv($fp, [
        'ubicacion', 'numero', 'campus', 'clasificacion', 'asignatura',
        'titulo', 'autor', 'editorial', 'pais', 'anio de publicacion', 'codigo de barras'
    ]);

    foreach ($libros as $libro) {
        fputcsv($fp, [
            $libro->ubicacion,
            $libro->numer,
            $libro->biblioteca_idbiblioteca,
            $libro->clasificacion,
            $libro->asignatura_id,
            $libro->titulo,
            $libro->autor,
            $libro->editorial,
            $libro->pais_codigopais,
            $libro->anio_publicacion,
            $libro->codigo_barras
        ]);
    }

    fclose($fp);
    exit;
}
// Importar desde CSV
public function actionImport()
{
    if (Yii::$app->request->isPost && isset($_FILES['importFile'])) {
        $file = $_FILES['importFile']['tmp_name'];

        if (($handle = fopen($file, 'r')) !== false) {
            $first = true;
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($first) {
                    $first = false; // omitir cabecera
                    continue;
                }

                $libro = new \app\models\Libro();
                $libro->ubicacion = $data[0];
                $libro->numer = $data[1];
                $libro->biblioteca_idbiblioteca = $data[2];
                $libro->clasificacion = $data[3];
                $libro->asignatura_id = $data[4];
                $libro->titulo = $data[5];
                $libro->autor = $data[6];
                $libro->editorial = $data[7];
                $libro->pais_codigopais = $data[8];
                $libro->anio_publicacion = $data[9];
                $libro->codigo_barras = $data[10];
                $libro->save();
            }
            fclose($handle);
        }

        return $this->asJson(['success' => true, 'message' => 'Importación completada correctamente']);
    }

    return $this->asJson(['success' => false, 'message' => 'Archivo no válido']);
}
public function actionPlantilla()
{
    $csvContent = "ubicacion,numero,campus,clasificacion,asignatura,titulo,autor,editorial,pais,anio_de_publicacion,codigo_de_barras\n";
    $csvContent .= "A1,101,Campus Central,Historia,Matemáticas,Historia Universal,Juan Pérez,Editorial ABC,México,2020,1234567890123\n";
    $csvContent .= "B2,102,Campus Norte,Literatura,Lengua Española,Don Quijote de la Mancha,Miguel de Cervantes,Editorial XYZ,España,1605,9876543210987\n";
    $csvContent .= "C3,103,Campus Sur,Ciencias,Física,Fundamentos de Física,Isaac Newton,Editorial MNO,Reino Unido,1687,1231231231231\n";

    Yii::$app->response->sendContentAsFile($csvContent, 'plantilla_libros.csv', [
        'mimeType' => 'text/csv',
        'forceDownload' => true,
    ]);
}


}