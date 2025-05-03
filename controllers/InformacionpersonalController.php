<?php

namespace app\controllers;

use app\models\Informacionpersonal;
use app\models\InformacionpersonalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InformacionpersonalController implements the CRUD actions for Informacionpersonal model.
 */
class InformacionpersonalController extends Controller
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
     * Lists all Informacionpersonal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InformacionpersonalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Informacionpersonal model.
     * @param string $CIInfPer Cédula
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($CIInfPer)
    {
        return $this->render('view', [
            'model' => $this->findModel($CIInfPer),
        ]);
    }

    /**
     * Creates a new Informacionpersonal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Informacionpersonal();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'CIInfPer' => $model->CIInfPer]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Informacionpersonal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $CIInfPer Cédula
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($CIInfPer)
    {
        $model = $this->findModel($CIInfPer);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'CIInfPer' => $model->CIInfPer]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Informacionpersonal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $CIInfPer Cédula
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($CIInfPer)
    {
        $this->findModel($CIInfPer)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Informacionpersonal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $CIInfPer Cédula
     * @return Informacionpersonal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($CIInfPer)
    {
        if (($model = Informacionpersonal::findOne(['CIInfPer' => $CIInfPer])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
