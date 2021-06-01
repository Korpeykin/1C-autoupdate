<?php

namespace app\modules\admin\controllers;

use app\modules\admin\forms\HtmForm;
use app\modules\admin\forms\ProductsForm;
use app\modules\admin\models\Konfs;
use app\modules\admin\models\Products;
use app\modules\admin\models\searchModels\KonfsSearch;
use app\modules\admin\models\searchModels\ProductsSearch;
use app\modules\admin\models\Targets;
use Yii;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UpdatesController implements the CRUD actions for Konfs model.
 */
class UpdatesController extends Controller
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

    public function actionIndex()
    {
        $model = new Konfs();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model = new Konfs();
        }
        $searchModel = new KonfsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionViewProducts($id)
    {
        $model = new ProductsForm($id, ProductsForm::SCENARIO_ADMIN_CREATE);

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->newProduct();

            $model = new ProductsForm($id, ProductsForm::SCENARIO_ADMIN_CREATE);
        }
        $searchModel = new ProductsSearch($id);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('products', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $model = Products::find()->with(['konfs', 'html', 'txts', 'xmls'])->where(['id' => $id])->one();
        $htmModel = new HtmForm($model->htm);
        if ($htmModel->load(Yii::$app->request->post()) && $htmModel->validate()) {
            $htmModel->updateHtm($model);
            $htmModel = new HtmForm($model->htm);
        }
        return $this->render('view', [
            'model' => $model,
            'htmModel' => $htmModel
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Konfs::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateFile($fileName, $id)
    {
        $formModel = new ProductsForm(0, ProductsForm::SCENARIO_ADMIN_UPDATE);
        if ($formModel->load(Yii::$app->request->post())) {
            try {
                $formModel->file = UploadedFile::getInstance($formModel, 'file');
                $formModel->updateProductFile($fileName);
                Yii::$app->getSession()->setFlash('success', Yii::t('products', 'file_update_success'));
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('products', 'file_update_error'));
            }
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->renderAjax('_update_file', [
            'model' => $formModel,
            'fileName' => $fileName
        ]);
    }

    public function actionDeleteKonfs($id)
    {
        $konf = $this->findKonfsModel($id);
        foreach ($konf->products as $product) {
            $files = [
                'zip' => $product->zip,
                'txt' => $product->txt,
                'xml' => $product->xml,
                'htm' => $product->htm
            ];
            foreach ($files as $param => $fileName) {
                if ($fileName) {
                    $paramName = $param . '.files.path';
                    $filePath = Yii::getAlias('@base_folder' . Yii::$app->params[$paramName]) . $fileName;
                    if (\file_exists($filePath)) {
                        \unlink($filePath);
                    }
                }
            }
        }
        $konf->delete();
        return $this->redirect(['index']);
    }

    public function actionDeleteProduct($id)
    {
        $product = Products::findOne($id);
        if ($product) {
            $konfId = $product->konf_id;
            $target = Targets::findOne(['target' => $product->konf_version]);
            $files = [
                'zip' => $product->zip,
                'txt' => $product->txt,
                'xml' => $product->xml,
                'htm' => $product->htm
            ];
            foreach ($files as $param => $fileName) {
                if ($fileName) {
                    $paramName = $param . '.files.path';
                    $filePath = Yii::getAlias('@base_folder' . Yii::$app->params[$paramName]) . $fileName;
                    if (\file_exists($filePath)) {
                        \unlink($filePath);
                    }
                }
            }
            $product->delete();
            $target->delete();
            return $this->redirect(['view-products', 'id' => $konfId]);
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionDownloadZip($fileName)
    {
        if (\file_exists(Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $fileName)) {
            return Yii::$app->response->sendFile(Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $fileName);
        } else {
            return null;
        }
    }

    /**
     * Finds the Konfs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Konfs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findKonfsModel($id)
    {
        $model = Konfs::findOne($id);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
