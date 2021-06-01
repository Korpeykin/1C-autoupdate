<?php

namespace app\modules\admin\controllers;

use app\modules\admin\forms\LicensesForm;
use Yii;
use app\modules\admin\models\Licenses;
use app\modules\admin\models\searchModels\LicensesSearch;
use PharIo\Manifest\License;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LicensesController implements the CRUD actions for Licenses model.
 */
class LicensesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Licenses models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LicensesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Licenses model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Licenses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LicensesForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->addNewLicense();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Licenses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model_db = $this->findModel($id);
        $model_db->scenario = Licenses::SCENARIO_ADMIN_UPDATE;
        if ($model_db->load(Yii::$app->request->post()) && $model_db->save()) {
            Yii::$app->mailer->compose('@app/modules/admin/mails/emailConfirm', ['license' => $model_db, 'user' => $model_db->user, 'pass' => $model_db->newPassword])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($model_db->user->email)
                ->setSubject('Изменение лицензии ' . Yii::$app->name)
                ->send();
            return $this->redirect(['view', 'id' => $model_db->id]);
        }

        return $this->render('update', [
            'model' => $model_db,
        ]);
    }

    /**
     * Deletes an existing Licenses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Licenses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Licenses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Licenses::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
