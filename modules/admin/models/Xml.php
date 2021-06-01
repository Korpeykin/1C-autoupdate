<?php

namespace app\modules\admin\models;

use Exception;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Yii;
use yii\base\Security;

class Xml extends \app\modules\main\models\Xml
{
    protected $xml_file_name;

    public function __construct()
    {
        parent::__construct();
        $security = new Security();
        $this->xml_file_name = $security->generateRandomString(12) . '.xml';
        $this->xml_name = $security->generateRandomString(12) . '.zip';
    }

    public function makeXmlFile($data)
    {
        try {
            // $data['konf_version'] = str_replace('_', '.', $data['konf_version']);
            $render_data = Products::find()
                ->with(['konfs', 'xmls'])
                ->select(['id', 'created_at', 'konf_id', 'zip', 'konf_version', 'xml'])
                ->where(['konf_id' => $data['konf_id']])
                ->orderBy(['id' => SORT_DESC])
                ->asArray()->all();
            $m = new Mustache_Engine(array(
                'loader' => new Mustache_Loader_FilesystemLoader(Yii::getAlias('@base_folder') . '/components/file_templates'),
            ));
            $r = [
                'date' => \date("d.m.Y\TH:i:s"),
                'xml' => $this->generateMustacheRenderData($data, $render_data)
            ];
            $fileContent = $m->render('xml', $r);
            $this->makeFile($fileContent, $data['targets']);
            return $this->xml_name;
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    protected function generateMustacheRenderData($data, $render_data)
    {
        $this_xml_data = [
            'conf_name' => $data['conf_name'],
            'provider_name' => $data['provider_name'],
            'update_file_path' => $data['provider_name'] . '/' . $data['conf_name'] . '/' . $data['konf_version'] . '/' . $data['file_name'],
            'update_file_size' => \call_user_func(function ($data) {
                try {
                    return \filesize(Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $data['file_name']);
                } catch (Exception $e) {
                    throw new Exception($e);
                }
            }, $data),
            'konf_version' => \str_replace('_', '.', $data['konf_version']),
            'konf_version_from' => \call_user_func(function ($data) {
                $res = [];
                foreach ($data['targets'] as $target) {
                    \array_push($res, ['target' => \str_replace('_', '.', $target)]);
                }
                return $res;
            }, $data)
        ];
        $res = [];
        \array_push($res, $this_xml_data);
        foreach ($render_data as $product) {
            $push = [
                'conf_name' => $product['konfs']['conf_name'],
                'provider_name' => $product['konfs']['provider_name'],
                'update_file_path' => $product['konfs']['provider_name'] . '/' . $product['konfs']['conf_name'] . '/' . $product['konf_version'] . '/' . $product['zip'],
                'update_file_size' => \call_user_func(function ($product) {
                    try {
                        return \filesize(Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $product['zip']);
                    } catch (Exception $e) {
                        throw new Exception($e);
                    }
                }, $product),
                'konf_version' => \str_replace('_', '.', $product['konf_version']),
                'konf_version_from' => \call_user_func(function ($product) {
                    $res = [];
                    $targets = \json_decode($product['xmls']['targets']);
                    foreach ($targets as $target) {
                        \array_push($res, ['target' => \str_replace('_', '.', $target)]);
                    }
                    return $res;
                }, $product)
            ];
            \array_push($res, $push);
        }
        return $res;
    }

    protected function makeFile($fileContent, $targets)
    {
        try {
            $xml_file_path = Yii::getAlias('@base_folder' . Yii::$app->params['xml.files.path']) . $this->xml_file_name;
            $zip_file_path = Yii::getAlias('@base_folder' . Yii::$app->params['xml.files.path']) . $this->xml_name;
            $fp = fopen($xml_file_path, "w");
            if ($fp) {
                if (fwrite($fp, $fileContent)) {
                    $this->body = $fileContent;
                    $this->targets = \json_encode($targets);
                }
                fclose($fp);
            }
            $zip = new \ZipArchive;
            if ($zip->open($zip_file_path, \ZipArchive::CREATE) === true) {
                $zip->addFile($xml_file_path, 'v8cscdsc.xml');
                $zip->close();
            }
            \unlink($xml_file_path);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
