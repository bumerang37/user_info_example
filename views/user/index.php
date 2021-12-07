<?php

use app\models\User;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index ">

    <h1><?= Html::encode($this->title) ?></h1>

    <? if (!Yii::$app->user->isGuest) { ?>
<!--            <div class="container">-->
        <div class="btn-toolbar">

                <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
               <?= Html::a('  Выгрузить пользователей в xml', ['user/xml'], ['class' => 'btn btn-primary btn-sm fa fa-save']) ?>

                <div class="form-block">
                <?php $form = ActiveForm::begin(['id' => 'form-xml-import',
                    'action' => \yii\helpers\Url::to(['user/import-xml']),
                    'options' => ['enctype' => 'multipart/form-data',
                        'class' => 'form-inline']
                ]); ?>

                <?= Html::submitButton('  Загрузить пользователей из xml ',
                    ['class' => 'btn btn-secondary btn-sm fa fa-arrow-up float-right', 'name' => 'saveProfile-button'])
                ?>
                    <div class="custom-file d-block form-group border">
                        <?= $form->field($model, 'file')->fileInput(['class' => 'custom-file-input', 'id' => 'fileUpload'])->label('') ?>
                        <label id="file-name" class="custom-file-label" for="fileUpload">Выберите xml файл для импорта</label>
                    </div>
                <?php ActiveForm::end() ?>
                </div>

    <?php } ?>

<!--    --><?php //Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => ''],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email',
            'first_name',
            'last_name',
            'patronymic',
            'birthday',
            [
                    'attribute' => 'photo',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return  User::isPhotoExistByUserId($model->id) ? Html::img(\app\models\User::getPhotoLinkByUserId($model->id),['class' => 'rounded-circle mt-5 profile-photo','width' => '150px']) : $model->photo;
                    }
            ],
            'city',
            [

                'attribute' => 'status',
                    'value' => function($model) {
                        return User::getStatusList()[$model->status];
                    },


                'filter' => [0=> \app\models\User::STATUS_INACTIVE_LABEL, 1=> \app\models\User::STATUS_ACTIVE_LABEL],
            ],
//            'created_at:date',
//            'updated_at:date',

            [
                    'class' => 'yii\grid\ActionColumn',
                    'visibleButtons' =>
                        [
                            'update' => !Yii::$app->user->isGuest,
                            'delete' =>  !Yii::$app->user->isGuest
                        ]
            ],
        ],
    ]); ?>

<!--    --><?php //Pjax::end(); ?>

</div>
    <?php
    $script = <<< JS
  $(document).ready(function () {
       $('#fileUpload').change(function() {
            var file = $('#fileUpload')[0].files[0].name;
            $('#file-name').text(file);
       })
       
       $('form').submit(function () {
           
       });
  })
JS;

    $this->registerJs($script, yii\web\View::POS_READY);
    ?>
