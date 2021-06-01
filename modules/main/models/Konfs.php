<?php

namespace app\modules\main\models;

use app\modules\admin\models\Targets;
use Yii;

/**
 * This is the model class for table "konfs".
 *
 * @property int $id
 * @property string|null $provider_name
 * @property string|null $conf_name
 */
class Konfs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'konfs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['provider_name', 'conf_name'], 'required'],
            [['provider_name', 'conf_name'], 'string', 'max' => 16],
            [['provider_name', 'conf_name'], 'string', 'min' => 3],
            [['provider_name', 'conf_name'], 'match', 'pattern' => '/^[A-Za-zА-Яа-я0-9_\-.]+$/u'],
            // ['provider_name', 'unique', 'targetClass' => self::class, 'message' => 'PROVIDER_EXISTS'],
            // ['conf_name', 'unique', 'targetClass' => self::class, 'message' => Yii::t('app', 'KONF_EXISTS')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'provider_name' => Yii::t('app', 'Provider Name'),
            'conf_name' => Yii::t('app', 'Conf Name'),
        ];
    }

    public function getProducts()
    {
        return $this->hasMany(Products::class, ['konf_id' => 'id']);
    }

    public function getTargets()
    {
        return $this->hasMany(Targets::class, ['konf_id' => 'id']);
    }

    public function getOpenApiProducts($data)
    {
        return $this->hasMany(Products::class, ['konf_id' => 'id'])
            ->where(['redaction_num' => $data['redactionNum'], 'platform_version' => $data['version']])
            ->orderBy(['id' => SORT_DESC]);
    }

    public function getCloseApiProducts($data)
    {
        return $this->hasMany(Products::class, ['konf_id' => 'id'])
            ->where(['konf_version' => $data['konf_version']])
            ->andWhere(['zip' => $data['file']]);
    }
}
