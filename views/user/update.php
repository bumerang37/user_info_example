<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

\app\assets\AppAsset::register($this);

$this->title = 'Update User: ' . $model->user->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
