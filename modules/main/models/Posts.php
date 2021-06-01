<?php

namespace app\modules\main\models;

use Yii;
use yii\helpers\Url;
use app\modules\user\models\User;
use app\modules\admin\models\Countries;
use app\modules\main\components\helpers\NamesHelper;
use app\modules\user\behaviors\FlowAccessChechBehavior;
use app\modules\user\behaviors\TranslitBehavior;
use app\modules\user\constants\Consts;
use app\modules\user\models\PostsLikes;
use app\modules\user\models\PostsViews;
use yii\behaviors\TimestampBehavior;
use raoul2000\workflow\validation\WorkflowValidator;
use raoul2000\workflow\validation\WorkflowScenario;
use raoul2000\workflow\events\WorkflowEvent;

/**
 * This is the model class for table "{{%posts}}".
 *
 * @property int $id
 * @property int $country_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $upload_at
 * @property int $author_id
 * @property int|null $show_it
 * @property int|null $enable_likes
 * @property int|null $enable_dislikes
 * @property int|null $enable_coments
 * @property string $main_theme
 * @property string $sub_theme
 * @property string|null $body
 *
 * @property User $author
 * @property Countries $country
 */
class Posts extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class,                                 // занесение временной метки создания/изменения пользователя

            [
                'class' => \raoul2000\workflow\base\SimpleWorkflowBehavior::className(),
                'statusAttribute' => Consts::STATUS_ATTR,                         // model attribute to store status
                'source' => 'PostWorkflowSource',                           // workflow source component name
                'defaultWorkflowId' => Consts::FLOW_NAME,
                'propagateErrorsToModel' => true,
            ],

            [
                'class' => FlowAccessChechBehavior::className(),
            ],

            [
                'class' => TranslitBehavior::className(),
            ],
        ];
    }

    public function init()
    {
        $this->on(
            WorkflowEvent::beforeEnterStatus(Consts::FLOW_USER_PROCESS),
            function ($event) {
                //debug($event);
                $newPath = Yii::getAlias('@webroot' . Yii::$app->params['posts.photos']) . Yii::$app->user->identity->username . '/' . $this->id;
                if (!file_exists($newPath)) {
                    mkdir($newPath, 0777, true);
                }
            }
        );

        $this->on(
            WorkflowEvent::beforeEnterStatus(Consts::FLOW_ADMIN_PROCESS),
            function ($event) {
                //debug($event);
                $newPath = Yii::getAlias('@webroot' . Yii::$app->params['posts.photos']) . $this->author->username . '/' . $this->id;
                if (!file_exists($newPath)) {
                    mkdir($newPath, 0777, true);
                }
            }
        );

        $this->on(
            WorkflowEvent::afterEnterStatus(Consts::FLOW_USER_DENIED),
            function ($event) {
                $this->upload_at = null;
                $this->update();
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        //debug($this->statusAttribute);
        return [
            [[$this->statusAttribute], WorkflowValidator::className()],
            [['country_id', 'main_theme', 'sub_theme', 'body', 'author_id'], 'required', 'on' => WorkflowScenario::leaveStatus(Consts::FLOW_USER_PROCESS)],
            [['country_id', 'main_theme', 'sub_theme', 'body', 'author_id'], 'required', 'on' => WorkflowScenario::leaveStatus(Consts::FLOW_ADMIN_PROCESS)],
            [['country_id', 'main_theme', 'sub_theme', 'body', 'author_id', 'upload_at'], 'required', 'on' => WorkflowScenario::enterStatus(Consts::FLOW_ADMIN_PUBLISHED)],
            [['show_it'], 'validateDel', 'on' => WorkflowScenario::enterStatus(Consts::FLOW_ADMIN_PUBLISHED)],
            [['show_it'], 'validateDel', 'on' => WorkflowScenario::enterStatus(Consts::FLOW_USER_DENIED)],
            [['country_id', 'author_id'], 'integer'],
            [['enable_likes', 'enable_dislikes', 'enable_coments', 'show_it'], 'boolean'],
            [['body'], 'string'],
            [['main_theme', 'sub_theme'], 'string', 'max' => 255],
            //[['main_theme', 'sub_theme'], 'match', 'pattern' => '^[\w\d\sа-яА-ЯёЁ.,;:&()*%#-]$'],
            [['main_theme', 'sub_theme'], 'filter', 'filter' => 'trim'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    public function validateDel()
    {
        if ($this->show_it != true) {
            $this->addError('show_it', 'Статья должна быть помечена, как "Не удалено"');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'country_id' => 'Страна',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
            'upload_at' => 'Время публикации',
            'author_id' => Yii::t('app', 'Author ID'),
            'show_it' => 'Пометка "Не удалено"',
            'main_theme' => 'Основная тема',
            'sub_theme' => 'Описание',
            'body' => 'Содержание (текст)',
            'enable_likes' => 'Активировать лайки',
            'enable_dislikes' => 'Активировать дизлайки',
            'enable_coments' => 'Активировать комментарии',
            Consts::STATUS_ATTR => 'Статус',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['id' => 'country_id']);
    }

    public function getLikes()
    {
        return $this->hasMany(PostsLikes::className(), ['post_id' => 'id']);
    }

    public function getViews()
    {
        return $this->hasMany(PostsViews::className(), ['post_id' => 'id']);
    }

    public function saveIt()
    {
        $this->author_id = Yii::$app->user->identity->id;
        //$model->upload_at = strtotime($model->upload_at);
        if ($this->save()) {
            return true;
        } else {
            return false;
        }
    }
}
