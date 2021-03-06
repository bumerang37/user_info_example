<?php

use app\models\User;
use yii\bootstrap4\Html;;
use yii\bootstrap4\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'first_name',
            'last_name',
            'patronymic',
            'birthday',
            'photo',
            'city',
            'auth_key',
            'password_reset_token',
            'email:email',
            [
                    'attribute' => 'status',
//                    'format' => 'boolean',
                    'value' => function($model) {
                      return User::getStatusList()[$model->status];
                    },
//
            ],

            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
