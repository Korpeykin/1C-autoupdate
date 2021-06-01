<?php

namespace app\modules\admin\models;

use Exception;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Yii;
use yii\base\Security;

class Htm extends \app\modules\main\models\Htm
{
    protected $m;

    public function __construct()
    {
        parent::__construct();
        $this->m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(Yii::getAlias('@base_folder') . '/components/file_templates'),
        ));
        $security = new Security();
        $this->htm_name = $security->generateRandomString(12) . '.htm';
    }

    public function makeHtmFile($data)
    {
        try {
            $content = $this->generateContent($data);
            // throw new \Exception('test');
            return $this->makeFile($content);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    protected function makeFile($fileContent)
    {
        $file_path = Yii::getAlias('@base_folder' . Yii::$app->params['htm.files.path']) . $this->htm_name;
        if (!file_exists($file_path)) {
            $fp = fopen($file_path, "w");
            if ($fp) {
                if (fwrite($fp, $fileContent)) {
                    // $this->save();
                    fclose($fp);
                    return $this->htm_name;
                }
            }
        }
        return false;
    }

    protected function generateContent($data)
    {
        $d = [
            'konf_version' => \str_replace('_', '.', $data['konf_version'])
        ];
        $new_content = $this->m->render('htm_content', $d);
        $old_content = $this->getPreviosContent($data);
        $this->body = $new_content . "\n" . $old_content;
        $r = [
            'conf_name' => $data['conf_name'],
            'redaction_num' => \implode('.', \str_split($data['redaction_num'])),
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
            }, \str_replace('_', '.', $data['konf_version'])),
            'content' => $this->body
        ];
        return $this->m->render('htm', $r);
    }


    protected function getPreviosContent($data)
    {
        $render_data = Products::find()
            ->with(['html'])
            ->where(['konf_id' => $data['konf_id']])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        if (!empty($render_data->html->body)) {
            return $render_data->html->body;
        } else {
            return '';
        }
    }
}
