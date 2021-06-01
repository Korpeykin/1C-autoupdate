<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "targets".
 *
 * @property int $id
 * @property int $product_id
 * @property string $target
 *
 * @property Products $product
 */
class Targets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'targets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['konf_id', 'target'], 'required'],
            [['konf_id'], 'integer'],
            [['target'], 'string', 'max' => 255],
            ['target', 'match', 'pattern' => '/^\d(?:_\d)+$/u', 'message' => Yii::t('products', 'KONF_PATTERN')],
            [['konf_id'], 'exist', 'skipOnError' => true, 'targetClass' => Konfs::class, 'targetAttribute' => ['konf_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('products', 'ID'),
            'konf_id' => Yii::t('products', 'setKonf'),
            'target' => Yii::t('products', 'Target_id'),
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKonfs()
    {
        return $this->hasOne(Konfs::class, ['id' => 'konf_id']);
    }
}
