<?php
/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model \app\models\EditProfileForm */


use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;;

$this->title = 'Настройки профиля';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['id' => 'form-edit','options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-4 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <?php if (!empty($model->photo)) { ?>
<!--                    --><?php //var_dump(\app\models\User::getPhotoLinkByUserId($model->user->id)); ?>
                    <?= Html::a('Удалить фото профиля'.Html::img(\app\models\User::getPhotoLinkByUserId($model->user->id),['class' => 'rounded-circle mt-5 profile-photo','width' => '150px']),Url::to(['user/remove-profile-photo','id' => $model->user->id])); ?>
                <?php } else { ?>
                        <?= Html::img(\app\models\User::getPhotoLinkByUserId(null),['class' => 'rounded-circle mt-5 profile-photo','width' => '150px']); ?>
               <? } ?>

                <div class="d-flex align-items-center mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Загрузить</span>
                        </div>
                        <div class="custom-file">
                            <?= $form->field($model, 'photo')->fileInput(['class' => 'custom-file-input', 'id' => 'fileUpload'])->label('') ?>
                            <label id="file-name" class="custom-file-label text-left" for="fileUpload">Выберите файл</label>
                        </div>
                    </div>
                </div>

                <div class="pt-5 text">
                    <div class="font-weight-bold"><?=$model->username ?></div>
                    <div class="text-black-50"><?=$model->email ?></div>
                </div>

<!--                <span>fd</span>-->
            </div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right"><?=$this->title?></h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6"><?= $form->field($model, 'first_name',[
                            "template" => "<label class='labels'>Имя</label>\n{input}\n{hint}\n{error}"
                        ])->textInput(['autofocus' => true,'class' => 'form-control','placeholder' => 'Имя']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'patronymic',[
                            "template" => "<label class='labels'>Отчество</label>\n{input}\n{hint}\n{error}"
                        ])->textInput(['autofocus' => true,'class' => 'form-control','placeholder' => 'Отчество']) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'last_name',[
                            "template" => "<label class='labels'>Фамилия</label>\n{input}\n{hint}\n{error}"
                        ])->textInput(['autofocus' => true,'class' => 'form-control','placeholder' => 'Фамилия']) ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo DatePicker::widget([
                            'model' => $model,
                            'attribute' => 'birthday',
                            'language' => 'ru',
                            'options' => ['placeholder' => 'Введите день рождения ...'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'language' => 'ru',
                                'format' => 'yyyy-mm-dd',
                                'todayHighLight' => true
                            ]
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <?= $form->field($model, 'email',[
                            "template" => "<label class='labels'>Email</label>\n{input}\n{hint}\n{error}"
                        ])->textInput(['autofocus' => true,'class' => 'form-control','placeholder' => 'Введите email']) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'city',[
                            "template" => "<label class='labels'>Город</label>\n{input}\n{hint}\n{error}"
                        ])->textInput(['autofocus' => true,'class' => 'form-control','placeholder' => 'Введите название города проживания']) ?>
                    </div>
                </div>
                <div class="mt-5 text-center">
                    <?= Html::submitButton('Сохранить профиль', ['class' => 'btn btn-primary profile-button', 'name' => 'saveProfile-button']) ?>
                    <?= Html::a('Удалить профиль',Url::to(['user/delete-profile']),['class' => 'btn btn-danger profile-button', 'name' => 'saveProfile-button']) ?>
                </div>

            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><span>Настройки авторизации</span>

                    <span class="border px-3 p-2 ">
                        <span>Логин</span><br>
                        <div class="col text-center">
                        <i class="fa fa-plus"></i>&nbsp;</br>
                        </div>
                        <span>Пароль</span>
                    </span>
                </div><br>
                <div class="col-md-12"><?= $form->field($model, 'username',[
                        "template" => "<label class='labels'>Логин</label>\n{input}\n{hint}\n{error}"
                    ])->textInput(['autofocus' => true,'class' => 'form-control','placeholder' => 'Имя пользователя']) ?></div> <br>

                <div class="col-md-12"><?= $form->field($model, 'password',[
                        "template" => "<label class='labels'>Пароль</label>\n{input}\n{hint}\n{error}"
                    ])->passwordInput(['autofocus' => true,'class' => 'form-control','placeholder' => 'Изменить пароль']) ?></div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>


<?php
$script = <<< JS
  $(document).ready(function () {
       $('#fileUpload').change(function() {
            var file = $('#fileUpload')[0].files[0].name;
            $('#file-name').text(file);
       })
  })
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
