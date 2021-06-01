<?php

namespace app\modules\admin\forms;

use app\modules\admin\models\Htm;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Yii;
use yii\base\Model;

class HtmForm extends Model
{
    public $body;
    protected $fileName;
    protected $m;
    protected $htmModel;

    public function __construct($fileName)
    {
        parent::__construct();
        $this->fileName = $fileName;
        $this->body = $this->getCurrentBody();
        $this->m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(Yii::getAlias('@base_folder') . '/components/file_templates'),
        ));
    }

    public function rules()
    {
        return [
            [['body'], 'required'],
            [['body'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'htm_name' => Yii::t('products', 'htm_name'),
            'body' => Yii::t('products', 'htm_body'),
        ];
    }

    public function getCurrentBody()
    {
        $this->htmModel = Htm::findOne(['htm_name' => $this->fileName]);
        return $this->htmModel->body;
    }

    public function updateHtm($product)
    {
        $r = [
            'conf_name' => $product->konfs->conf_name,
            'redaction_num' => \implode('.', \str_split($product->redaction_num)),
            'short_vers' => \call_user_func(function ($konf_version) {
                $array = str_split($konf_version);
                $i = 0;
                $result = '';
                $index_from_delete = null;
                foreach ($array as $k => $v) {
                    if ($v == '.' or $v == '_') {
                        $i++;
                    }
                    if ($i == 3) {
                        $index_from_delete = $k;
                    }
                }
                for ($i = 0; $i < $index_from_delete - 1; $i++) {
                    $result .= $array[$i];
                }
                return $result;
            }, \str_replace('_', '.', $product->konf_version)),
            'content' => $this->body
        ];
        $this->updateFile($this->m->render('htm', $r));
    }

    protected function updateFile($fileContent)
    {
        $file_path = Yii::getAlias('@base_folder' . Yii::$app->params['htm.files.path']) . $this->fileName;
        if (file_exists($file_path)) {
            \unlink($file_path);
            $fp = fopen($file_path, "w");
            if ($fp) {
                if (fwrite($fp, $fileContent)) {
                    $this->htmModel->body = $this->body;
                    $this->htmModel->save();
                }
            }
            fclose($fp);
        }
    }
}
