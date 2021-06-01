<?php

namespace app\modules\admin\forms;

use app\modules\admin\models\Htm;
use app\modules\admin\models\Products;
use app\modules\admin\models\Targets;
use app\modules\admin\models\Txt;
use app\modules\admin\models\Xml;
use yii\base\Model;
use Yii;
use yii\base\Security;
use yii\helpers\ArrayHelper;

/**
 * Signup form
 */
class ProductsForm extends Model
{
    const SCENARIO_ADMIN_CREATE = 'adminCreate';
    const SCENARIO_ADMIN_UPDATE = 'adminUpdate';

    public $konf_version;
    private $konf_id;
    private $security;
    public $redaction_num;
    public $platform_version;
    public $file;
    public $targets;

    public function __construct($konf_id = 0, $scenario)
    {
        parent::__construct();
        $this->scenario = $scenario;
        if ($this->scenario === self::SCENARIO_ADMIN_CREATE) {
            $this->konf_id = $konf_id;
            $this->security = new Security();
        }
    }

    public function rules()
    {
        return [
            [['konf_version', 'redaction_num', 'platform_version'], 'required', 'on' => self::SCENARIO_ADMIN_CREATE],
            [['file'], 'required'],
            ['konf_version', 'match', 'pattern' => '/^\d(?:_\d+)+$/u', 'message' => Yii::t('products', 'KONF_PATTERN')],
            ['konf_version', 'unique', 'targetClass' => Products::class, 'message' => Yii::t('products', 'KONF_EXISTS')],
            [['redaction_num', 'platform_version'], 'integer'],
            ['targets', 'safe'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'zip'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_CREATE] = ['konf_version', 'redaction_num', 'platform_version', 'file', 'targets'];
        $scenarios[self::SCENARIO_ADMIN_UPDATE] = ['file'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'konf_version' => Yii::t('app', 'KONF_VERSION'),
            'redaction_num' => Yii::t('app', 'REDACT_NUM'),
            'platform_version' => Yii::t('app', 'PLATF_VERS'),
            'file' => Yii::t('products', 'file'),
            'targets' => Yii::t('products', 'targets'),
        ];
    }

    protected function upload()
    {
        if ($this->validate()) {
            try {
                $file_name = $this->security->generateRandomString(12);
                $this->file->saveAs(Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $file_name . '.' . $this->file->extension);
                return $file_name . '.' . $this->file->extension;
            } catch (\Exception $e) {
                throw new \Exception($e);
            }
        } else {
            throw new \Exception('File validation failed');
        }
    }

    public function updateProductFile($file_name)
    {
        if ($this->validate()) {
            try {
                if (\file_exists(Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $file_name)) {
                    \unlink(Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $file_name);
                }
                $this->file->saveAs(Yii::getAlias('@base_folder' . Yii::$app->params['zip.files.path']) . $file_name);
            } catch (\Exception $e) {
                throw new \Exception($e);
            }
        } else {
            throw new \Exception('File validation failed');
        }
    }

    public function newProduct()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $product = new Products();
                $xmlProvider = new Xml();
                $txtProvider = new Txt();
                $htmProvider = new Htm();
                $targetsProvider = new Targets();

                $product->konf_version = $this->konf_version;
                $product->redaction_num = $this->redaction_num;
                $product->platform_version = $this->platform_version;
                $product->konf_id = $this->konf_id;

                $product->zip = $this->upload();

                $product->txt = $txtProvider->makeTxtFile($this->konf_version, \array_reverse($this->targets));

                $konf = $product->konfs;

                $product->xml = $xmlProvider->makeXmlFile([
                    'konf_id' => $product->konf_id,
                    'konf_version' => $product->konf_version,
                    'file_name' => $product->zip,
                    'conf_name' => $konf->conf_name,
                    'provider_name' => $konf->provider_name,
                    'targets' => $this->targets,
                ]);

                $product->htm = $htmProvider->makeHtmFile([
                    'konf_version' => $product->konf_version,
                    'conf_name' => $konf->conf_name,
                    'konf_id' => $product->konf_id,
                    'redaction_num' => $product->redaction_num,
                ]);

                $targetsProvider->konf_id = $this->konf_id;
                $targetsProvider->target = $this->konf_version;

                $product->save();
                $targetsProvider->save();
                $xmlProvider->save();
                $txtProvider->save();
                $htmProvider->save();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                $this->deleteBadFiles([
                    'zip' => $product->zip,
                    'txt' => $product->txt,
                    'xml' => $product->xml,
                    'htm' => $product->htm
                ]);
                return false;
            }
        }
    }

    protected function deleteBadFiles($fileNames)
    {
        foreach ($fileNames as $param => $name) {
            if ($name) {
                $paramName = $param . '.files.path';
                $filePath = Yii::getAlias('@base_folder' . Yii::$app->params[$paramName]) . $name;
                if (\file_exists($filePath)) {
                    \unlink($filePath);
                }
            }
        }
    }

    public function getTargetsDropdown()
    {
        $data = ArrayHelper::map(Targets::find()->where(['konf_id' => $this->konf_id])->orderBy(['id' => SORT_DESC])->all(), 'target', 'target');
        return $data;
    }
}
