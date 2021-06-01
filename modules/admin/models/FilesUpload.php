<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\base\Security;
use yii\helpers\Url;
use Yii;

class FilesUpload extends Model
{
    const SCENARIO_NEW = 'new';           //сценарий когда новая запись (фото обязательно)
    const SCENARIO_UPDATE = 'update';           //когда изменяем - не обязательно прикреплять новое фото, можно оставить старое

    public $images;
    public $files;
    public $new_one = false;


    public function rules()
    {
        return [
            //[['images'], 'file', 'extensions' => 'png, jpg, jpeg, gif, svg', 'maxSize' => 1024 * 1024 * 30, 'on' => ['new', 'update']],
            [['images'], 'file', 'extensions' => 'png, jpg, jpeg, gif, svg', 'maxFiles' => 10],
            [['images'], 'required', 'on' => 'new'],
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, doc, zip, rar, docx, txt, ppt, pptx, xls, xlsx', 'maxFiles' => 10]
        ];
    }


    /*
    *   $saved_files = [
    *       0 => name,
    *       1 => name,
    *        ...
    *    ]
    *   $my_names = [
        0 => name,
        ...
    ]
    */
    public function uploadThis($type, $main_path, $my_names = false)                   // type = images|files
    {
        $random = new Security();
        $saved_files = [];
        if ($this->validate()) {
            foreach ($this->{$type} as $key => $this_file) {                        //название нужного свойства берем из значения пришедшей переменной тайп
                if (is_array($my_names)) {
                    $filename = $my_names[$key];
                } else {
                    $filename = $random->generateRandomString(10);
                }
                $filename = $filename.'.'.$this_file->extension;
                $save_as = $main_path.$filename;
                if ($this_file->saveAs($save_as)) {
                    $saved_files[] = $filename;
                }
            }
            return $saved_files;                                        //возвращаем массив имен сохраненных файлов
        } else {
            return false;
        }
    }

    public function initialPreviewGenerator($db_files, $path, $attribute = 'img_src')          //функция должна получать данные имен из бд
    {
        if (!is_array($db_files)) {
            $this_files[] = $db_files;
        } else {
            $this_files = $db_files;
        }
        
        //$this_files = (object) $this_files;
        
        $for_preview[] = null;
        $initialPreviewConfig[][] = null;

        if (!$this->new_one) {
            foreach ($this_files as $k => $photo) {
                $for_preview[$k] = Url::to('@'.$path.$photo->{$attribute});                                             //формируем массыи адресов для предпоказа фоток, тех что уже есть
                $initialPreviewConfig[$k]['caption'] = $photo->{$attribute};                                                                  //формируется массив конфигурации фоток предпоказа для картик инпута. нужен для возможности удалени уже загруженных фоток
                $initialPreviewConfig[$k]['url'] = Url::to(['delete-files', 'img_id' => $photo->id, 'img_name' => $photo->{$attribute}]);     //урл на экшен этого контроллера
                $initialPreviewConfig[$k]['key'] = $k;                                                                                      //совпадает с ключем фор_превью
                $initialPreviewConfig[$k]['extra'] = ['id'=>$k];
            }
        }

        $ret = [];
        $ret['for_preview'] = $for_preview;
        $ret['initialPreviewConfig'] = $initialPreviewConfig;
        
        return $ret;
    }

    protected function translit($s)
    {
        $s = (string) $s; // преобразуем в строковое значение
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        return $s; // возвращаем результат
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'images' => 'Фотографии',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_NEW] = ['images'];
        $scenarios[self::SCENARIO_UPDATE] = ['images'];
        return $scenarios;
    }
}
