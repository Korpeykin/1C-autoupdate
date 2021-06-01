<?php

namespace app\modules\main\components\helpers;

use Yii;

class NamesHelper
{
    public static function findImageNames($text)    // функция вычленяющая из тела статьи(строки) все названия фотографий
    {
        if (is_string($text)) {
            $img_html = '<img src="';
            $alt_html = 'alt="';
            $text_html = stristr($text, $img_html);
            $names = [];
            $i = 0;
            if ($text_html === false) {
                return null;
            } else {
                while ($text_html != false) {  // $stristr = строка начиная с первого <img
                    $text_html = substr($text_html, strlen($img_html));         //отрезаю этот тег имг
                    $stristr = $text_html;
                    $stristr = stristr($stristr, '"', true);
                    $a = strlen(Yii::$app->params['posts.photos']);
                    $stristr = substr($stristr, $a);
                    $names[$i]['img_src'] =  $stristr;                          // формирование имени файла
                    
                    $stristr = $text_html;
                    $stristr = stristr($stristr, $alt_html);
                    $stristr = substr($stristr, strlen($alt_html));
                    //$stristr = $alt_html;
                    $stristr = $stristr = stristr($stristr, '"', true);
                    $names[$i]['alt'] =  $stristr;                              // формирование описания картинки (alt)
                    
                    unset($stristr);
                    $text_html = stristr($text_html, $img_html);
                    $i++;
                }
                return $names;
            }
        } else {
            return false;
        }
    }
}
