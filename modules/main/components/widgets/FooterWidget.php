<?php

namespace  app\modules\main\components\widgets;

use app\modules\admin\models\FooterCol1;
use app\modules\admin\models\FooterCol2;
use app\modules\main\forms\FooterMessagesForm;
use yii\base\Widget;
use Yii;

class FooterWidget extends Widget
{
    public $template;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $items = FooterCol1::find()->asArray()->all();
        $items_col_2 = FooterCol2::find()->orderBy('sub_title')->all();
        $translation = [];                                                  // переменная для хранения свойства класса p_types, тк в цикле при первой интерации он не пустой, а потом почему-то пустой
        $messages = new FooterMessagesForm();
        foreach ($items_col_2 as $name => $item) {
            if (!empty($item->p_types)) {
                $translation = $item->p_types;                              // собсна запись переводов для хранения
            }
            if (isset($translation[$item->sub_title])) {                            // на случай, если человек закоментил сущность в конфиге футера...чтобы оно не валилось в ошибку
                $items_col_2[$name]->sub_title = $translation[$item->sub_title];
            } else {
                unset($items_col_2[$name]);                                         // и не выводило эту сущность
            }
        }

        if ($messages->load(Yii::$app->request->post())) {
            if (!$messages->validate()) {
                $result = [];
                // The code below comes from ActiveForm::validate(). We do not need to validate the model
                // again, as it was already validated by save(). Just collect the messages.
                foreach ($messages->getErrors() as $attribute => $errors) {
                    $result[] = $errors;
                }

                $errors = \yii\helpers\Json::encode($result);
                $script=<<<JS
                        var data=$errors;
                        var msg='';
                        for(error in data){
                            msg+=data[error]+"\\n";
                        }
                        alert(msg);
JS;
                $this->getView()->registerJs($script, \yii\web\View::POS_READY);
            } else {
                if ($messages->processMessage()) {                                              // валидация с охранение данных в БД
                    Yii::$app->session->setFlash('success', 'Ваше сообщение отправленно!');
                    $messages = new FooterMessagesForm();
                } else {
                    Yii::$app->session->setFlash('danger', 'Ваше сообщение не отправленно! Сообшения можно отправлять раз в '.(Yii::$app->params['time.footer.message']/60).' минут!');
                    $messages = new FooterMessagesForm();
                }
            }
        }
        
        return $this->renderFile(__DIR__.'/tpl/footer_tpl.php', [
            'items' => $items,
            'items_col_2' => $items_col_2,
            'messages' => $messages,
        ]);
    }
}
