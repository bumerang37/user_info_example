<?php

use kartik\date\DatePicker;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->input('email',['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

   <? echo '<label class="form-label">Birth Date</label>';
    echo DatePicker::widget([
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

    <?= $form->field($model, 'photo')->fileInput(['id' => 'fileUpload']) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([
            \app\models\User::STATUS_INACTIVE => 'Отключен',
            \app\models\User::STATUS_ACTIVE => 'Активный',
        ])?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

