<?php

use yii\helpers\Url;
use yii\helpers\Html;

    /*
        за отображение меню отвечает поле access:
        0 - видно всем и всегда
        1 - дополнительно видно "гостям" сайта
        2 - видно залогиненым (не гостям)
    */
?>
        
        
    <div class="top-nav s-12 l-10">
        <p class="nav-text"></p>
        <ul class="right chevron">
            <?php
            //debug($items);
            foreach ($items as $item) {
                switch ($item->access) {
                    case 0:
                        echo '<li>'.Html::a($item->name, [Url::to([$item->url])]).'</li>';
                        break;
                    case 1:
                        if (Yii::$app->user->isGuest) {
                            echo '<li>'.Html::a($item->name, [Url::to([$item->url])]).'</li>';
                        }
                        break;
                    case 2:
                        if (!Yii::$app->user->isGuest) {
                            //debug($item->navigationSubp);
                            if (isset($item->navigationSubp)) {
                                echo '<li><a>'.$item->name.'</a><ul>';
                                foreach ($item->navigationSubp as $subp) {
                                    if ($subp->url == '/user/default/logout') {
                                        echo '<li>'.Html::a($subp->name, [Url::to([$subp->url])], ['data-method' => 'POST']).'</li>';
                                    } else {
                                        echo '<li>'.Html::a($subp->name, [Url::to([$subp->url])]).'</li>';
                                    }
                                }
                                echo '</ul></li>';
                            } else {
                                echo '<li>'.Html::a($item->name, [Url::to([$item->url])]).'</li>';
                            }
                        }
                        break;
                }
            }
            if (Yii::$app->user->can('admin')) {
                echo '<li>'.Html::a('Admin', [Url::to(['/admin'])]).'</li>';
            }
            ?>
        </ul>
    </div>