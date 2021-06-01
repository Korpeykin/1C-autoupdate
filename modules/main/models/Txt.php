<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "xml".
 *
 * @property string $xml_name
 * @property string|null $body
 */
class Txt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'txts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['txt_name'], 'required'],
            [['body'], 'string'],
            [['txt_name'], 'string', 'max' => 16],
            [['txt_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'txt_name' => Yii::t('products', 'txt_name'),
            'body' => Yii::t('products', 'txt_body'),
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::class, ['txt' => 'txt_name']);
    }
}
