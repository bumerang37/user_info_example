<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <? if (!Yii::$app->user->isGuest) { ?>
    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php } ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'first_name',
            'last_name',
            'patronymic',
            //'birthday',
            //'photo',
            //'city',
            //'auth_key',
            //'password',
            //'password_reset_token',
            //'email:email',
            //'status',
            //'created_at',
            //'updated_at',

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

    <?php Pjax::end(); ?>

</div>
