<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Security;

class Txt extends \app\modules\main\models\Txt
{
    public function __construct()
    {
        parent::__construct();
        $security = new Security();
        $this->txt_name = $security->generateRandomString(12) . '.txt';
    }

    public function makeTxtFile($konf_version, $targets)
    {
        try {
            $file = Yii::getAlias('@base_folder' . Yii::$app->params['txt.files.path']) . $this->txt_name;
            $new_version = str_replace('_', '.', $konf_version);

            $from_versions = 'FromVersions=;';
            if (!empty($targets)) {
                foreach ($targets as $vers) {
                    $from_versions .= str_replace('_', '.', $vers) . ';';
                }
            }

            $date = date("d.m.Y");
            $fileContent = <<< EOT
Version=$new_version
$from_versions
UpdateDate=$date
EOT;
            if (!file_exists($file)) {
                $fp = fopen($file, "w");
                if ($fp) {
                    if (fwrite($fp, $fileContent)) {
                        $this->body = $fileContent;
                        // $this->save();
                    }
                    fclose($fp);
                    return $this->txt_name;
                }
            }
        } catch (\Exception $e) {
            // return $this->txt_name;
            throw new \Exception($e);
        }
    }
}
