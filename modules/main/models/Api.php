<?php

namespace app\modules\main\models;

use Yii;
use yii\web\NotFoundHttpException;

class Api
{
    protected $queryData;
    protected $konf;
    protected $product;

    public function __construct()
    {
        $queryData = \explode('/', $_SERVER['REQUEST_URI']);
        $this->queryData = \array_slice($queryData, 2);
        if (\count($this->queryData) !== 5) {
            throw new NotFoundHttpException('Bad api request');
        }
    }

    public function giveOpenData()
    {
        $data = [
            'konf_name' => $this->queryData[1],
            'redactionNum' => $this->queryData[2],
            'version' => $this->queryData[3],
            'file' => $this->queryData[4]
        ];
        $konf = Konfs::findOne(['conf_name' => $data['konf_name']]);
        if (empty($konf)) {
            throw new NotFoundHttpException('Bad api request');
        }
        $product = $konf->getOpenApiProducts($data)->one();
        $file_array = \explode('.', $data['file']);
        $file_extension = \array_pop($file_array);
        if (!\in_array($file_extension, ['txt', 'htm', 'zip'])) {
            throw new NotFoundHttpException('Bad api request');
        }
        if ($file_extension == 'zip') {
            $file_extension = 'xml';
        }

        if (\file_exists(Yii::getAlias('@base_folder' . Yii::$app->params[$file_extension . '.files.path']) . $product->{$file_extension})) {
            return [
                'res_name' => $file_extension,
                'data' => Yii::getAlias('@base_folder' . Yii::$app->params[$file_extension . '.files.path']) . $product->{$file_extension}
            ];
        }
        throw new NotFoundHttpException('Bad api request');
    }

    public function giveCloseData()
    {
        $data = [
            'provider_name' => $this->queryData[1],
            'konf_name' => $this->queryData[2],
            'konf_version' => $this->queryData[3],
            'file' => $this->queryData[4]
        ];
        $file_array = \explode('.', $data['file']);
        $file_extension = \array_pop($file_array);
        if ($file_extension !== 'zip') {
            throw new NotFoundHttpException('Bad api request');
        }
        $konf = Konfs::findOne(['conf_name' => $data['konf_name'], 'provider_name' => $data['provider_name']]);
        if (empty($konf)) {
            throw new NotFoundHttpException('Bad api request');
        }
        $product = $konf->getCloseApiProducts($data)->one();
        if (empty($product)) {
            throw new NotFoundHttpException('Bad api request');
        }
        if (\file_exists(Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $product->zip)) {
            return Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $product->zip;
        }
        throw new NotFoundHttpException('Bad api request');
    }
}
