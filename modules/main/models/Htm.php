<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "htm".
 *
 * @property string $htm_name
 * @property string|null $body
 */
class Htm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'html';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['htm_name'], 'required'],
            [['body'], 'string'],
            [['htm_name'], 'string', 'max' => 16],
            [['htm_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'htm_name' => Yii::t('products', 'htm_name'),
            'body' => Yii::t('products', 'htm_body'),
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::class, ['htm' => 'htm_name']);
    }
}
