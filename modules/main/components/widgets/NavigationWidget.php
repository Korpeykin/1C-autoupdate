<?php

namespace  app\modules\main\components\widgets;

use app\modules\admin\models\Navigation;
use yii\base\Widget;

/*
за отображение менб отвечает поле access:
0 - видно всем и всегда
1 - доволнительно видно "гостям" сайта
2 - видно залогиненым (не гостям)
 */

class NavigationWidget extends Widget
{
    public $template;

    public function init()
    {
        parent::init();
        /* if ($this->items === null) {
            $this->items = 'Enter navigation parametrs for widget!';
        } */
    }

    public function run()
    {
        $items = Navigation::find()->orderBy(['subparagraph' => SORT_ASC])->all();

        $this->template = $this->getCommentHtml($items);
        return $this->template;
    }

    protected function getCommentHtml($items)
    {
        $str = '';
        //debug($this->data);
        $i = count($items);                  //для вывода конечных хтмл тегов
        //debug($i);
        $str .= $this->catToTemplate($items, $i);   //перебираем всю менюху, формируем хтмл и отдаем виджету
      
        //debug($str);
        return $str;
    }

    protected function catToTemplate(&$items, $i)
    {
        ob_start();
        include __DIR__ . '/tpl/menu_tpl.php';
        return ob_get_clean();
    }
}
