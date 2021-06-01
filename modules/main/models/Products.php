<?php

namespace app\modules\main\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int $konf_id
 * @property string|null $konf_version
 * @property int|null $redaction_num
 * @property int|null $platform_version
 * @property string|null $txt
 * @property string|null $htm
 * @property string|null $xml
 * @property string|null $zip
 *
 * @property Konfs $konf
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['konf_id'], 'required'],
            [['konf_id', 'redaction_num', 'platform_version'], 'integer'],
            [['konf_version', 'txt', 'xml', 'zip'], 'string', 'max' => 16],
            ['htm', 'string'],
            [['konf_id'], 'exist', 'skipOnError' => true, 'targetClass' => Konfs::class, 'targetAttribute' => ['konf_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'konf_id' => Yii::t('app', 'Konf ID'),
            'konf_version' => Yii::t('app', 'KONF_VERSION'),
            'redaction_num' => Yii::t('app', 'REDACT_NUM'),
            'platform_version' => Yii::t('app', 'PLATF_VERS'),
            'txt' => Yii::t('app', 'Txt'),
            'htm' => Yii::t('app', 'Htm'),
            'xml' => Yii::t('app', 'Xml'),
            'zip' => Yii::t('app', 'Zip'),
        ];
    }

    /**
     * Gets query for [[Konf]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKonfs()
    {
        return $this->hasOne(Konfs::class, ['id' => 'konf_id']);
    }

    public function getXmls()
    {
        return $this->hasOne(Xml::class, ['xml_name' => 'xml']);
    }

    public function getTxts()
    {
        return $this->hasOne(Txt::class, ['txt_name' => 'txt']);
    }

    public function getHtml()
    {
        return $this->hasOne(Htm::class, ['htm_name' => 'htm']);
    }
}
