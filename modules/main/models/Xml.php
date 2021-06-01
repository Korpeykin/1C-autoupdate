<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "xml".
 *
 * @property string $xml_name
 * @property string|null $body
 */
class Xml extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xmls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['xml_name'], 'required'],
            [['body'], 'string'],
            [['xml_name'], 'string', 'max' => 16],
            [['xml_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'xml_name' => Yii::t('products', 'xml_name'),
            'body' => Yii::t('products', 'xml_body'),
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::class, ['xml' => 'xml_name']);
    }
}
