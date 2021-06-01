<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\main\components\widgets\Alert;
use yii\helpers\Url;

?>

<footer>
    <!-- Main Footer -->
    <section class="section background-dark">
        <div class="line">
            <div class="margin">
                <!-- Collumn 1 -->
                <div class="s-12 m-12 l-4 margin-m-bottom-2x">
                    <?php if ($items[0]['show_it']) : ?>
                        <h4 class="text-uppercase text-strong"><?= $items[0]['theme'] ?></h4>
                        <p class="text-size-20"><em><?= $items[0]['text'] ?></em></p>
                    <?php endif; ?>

                    <?php if ($items[1]['show_it']) : ?>
                        <div class="line">
                            <h4 class="text-uppercase text-strong margin-top-30"><?= $items[1]['theme'] ?></h4>
                            <div class="margin">
                                <div class="s-12 m-12 l-4 margin-m-bottom">
                                    <a class="image-hover-zoom" href="/"><img src="<?= Url::to(['@'.Yii::$app->params['footer.img.path'].$items[1]['img_src']]) ?>" alt=""></a>
                                </div>
                                <div class="s-12 m-12 l-8 margin-m-bottom">
                                    <p><?= $items[1]['text'] ?></p>
                                    <a class="text-more-info text-primary-hover" href="/">Подробнее...</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Collumn 2 -->
                <div class="s-12 m-12 l-4 margin-m-bottom-2x">
                    <h4 class="text-uppercase text-strong">Контакты</h4>
                    <?php foreach ($items_col_2 as $item_col_2) : ?>
                        <?php if ($item_col_2->show_it) : ?>
                            <div class="line">
                                <div class="s-1 m-1 l-1 text-center">
                                    <i class="<?= $item_col_2->ico ?> text-primary text-size-12"></i>
                                </div>
                                <div class="s-11 m-11 l-11 margin-bottom-10">
                                    <p><b><?= $item_col_2->sub_title ?>:</b> <?= $item_col_2->content ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Collumn 3 -->
                <div class="s-12 m-12 l-4">
                    <h4 class="text-uppercase text-strong">Оставьте сообщение</h4>
                    <?php Pjax::begin() ?>
                    <?= Alert::widget([
                        'options' => [
                            'class' => 'footer-alert',
                        ],
                    ]) ?>
                    <?php $form = ActiveForm::begin([
                        'id' => 'messages-footer',
                        //'action' => ['/main/contact/footer'],
                        'options' => [
                            'class' => 'customform text-white',
                            'data-pjax' => true,
                        ],
                    ]);
                    echo $form->errorSummary($messages);
                    ?>
                    <div class="line">
                        <div class="margin">
                            <div class="s-12 m-12 l-6">
                                <?php
                                echo $form->field($messages, 'email', [
                                    'inputOptions' => ['autocomplete' => 'off'],
                                    'options' => [
                                        'tag' => false,
                                        'class' => 's-12 m-12 l-6',
                                    ],
                                    ])->textInput([
                                            //'name' => 'footer-email',
                                            'class' => 'required-my email border-radius',
                                            'placeholder' => 'Ваш e-mail',
                                            'title' => 'Ваш e-mail',
                                            //'type' => 'text',
                                        ])->label(false);

                                echo Html::error($messages, 'email', ['class' => 'daterror']);
                                ?>
                            </div>

                            <div class="s-12 m-12 l-6">
                                <?php
                                echo $form->field($messages, 'name', [
                                    'inputOptions' => ['autocomplete' => 'off'],
                                    'options' => [
                                        'tag' => false,
                                    ],
                                    ])->textInput([
                                            //'name' => 'footer-email',
                                            'class' => 'name border-radius',
                                            'placeholder' => 'Ваше имя',
                                            'title' => 'Ваше имя',
                                            //'type' => 'text',
                                        ])->label(false);
                                ?>
                            </div>
                            
                            <div class="s-12">
                                <?php
                                echo $form->field($messages, 'text', [
                                    'inputOptions' => ['autocomplete' => 'off'],
                                    'options' => [
                                        'tag' => false,
                                    ],
                                    ])->textArea([
                                            //'name' => 'footer-text',
                                            'class' => 'required-my message border-radius',
                                            'placeholder' => 'Ваше сообщение',
                                            'rows' => 5,
                                        ])->label(false);
                                ?>
                            </div>

                            <div class="s-12">
                                <button class="submit-form button background-primary border-radius text-white" type="submit">Отправить</button>
                            </div>
                        </div>
                    </div>
                    <?php
                    ActiveForm::end();
                    Pjax::end();
                    ?>
                </div>
            </div>
        </div>
    </section>