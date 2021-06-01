<?php

namespace app\modules\main\forms;

use app\modules\main\models\FooterMessages;
use Yii;
use yii\base\Model;
use app\modules\user\models\User;

class FooterMessagesForm extends Model
{
    public $email;
    public $name;
    public $text;
    //public $pre_url;

    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['name'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['name', 'email'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
        ];
    }

    public function processMessage()
    {
        if ($this->validateMessageTime()) {
            $db_messages = new FooterMessages();
            $db_messages->name = $this->name;
            $db_messages->email = $this->email;
            $db_messages->text = $this->text;
            $db_messages->created_at = strtotime("now");
            $db_messages->user_ip = ip2long(Yii::$app->request->userIP);
            if ($db_messages->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;           // если не прошло валидацию - возвращаем фалсе
        }
    }

    protected function validateMessageTime()
    {
        $last_message = FooterMessages::find()
        ->where(['user_ip' => ip2long(Yii::$app->request->userIP)])
        ->orderBy(['created_at' => SORT_DESC])
        ->one();
        if (isset($last_message->created_at)) {
            if ((strtotime("now") - $last_message->created_at) > Yii::$app->params['time.footer.message']) {    // если (сейча - время_создания) больше времени в конфиге - сохранять новое сообщение (для конкретнго айпишника)
                return true;
            } else {
                return false;       // если прошло слишком мало времени с прошлого обращения - не дадим делать новое
            }
        } else {
            return true;        //если объект пустой, тоесть для этого айпишника вообще нет записей - можно записывать сообщение
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'name' => 'Ваше имя',
            'text' => 'Ваше сообщение',
        ];
    }
}
