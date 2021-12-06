<?php

namespace app\controllers;

use app\models\EditProfileForm;
use app\models\RegisterForm;
use app\models\UploadForm;
use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['edit-profile','create','update','delete'],
                    'rules' => [
                        [
                            'actions' => ['edit-profile','create','update','delete'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) ) {
                $model->setPassword($model->password);
                $model->generateAuthKey();
                $model->generateEmailVerificationToken();
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->generateAuthKey();
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionRegister() {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', 'Спасибо за регистрацию. Пожалуйста проверьте входящие письма в вашей почте.');
            return $this->goHome();
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionEditProfile() {
        $user = $this->findModel(Yii::$app->user->getId());
        $model = new EditProfileForm($user);
        if (Yii::$app->request->isPost) {
            if ($model->load($this->request->post()) && $model->edit()) {

                Yii::$app->session->setFlash('success', 'Изменения успешно сохранены! Профиль будет изменен.');
                return $this->goHome();
            }
        }

        return $this->render('edit_profile',[
            'model' => $model
        ]);
    }

    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile  = UploadedFile::getInstance($model, 'photo');
            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        if (empty($user->auth_key)) {
            $user->generateAuthKey();
        }

        $model = new EditProfileForm($user);
        if (Yii::$app->request->isPost) {
            if ($model->load($this->request->post()) && $model->edit()) {


                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = User::findOne($id);
        $photo = $user->photo;

        if (!empty($photo)) {
            unlink(Yii::$app->basePath . DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.User::$uploadsPath.DIRECTORY_SEPARATOR.User::getUploadsFolderByType(USER::UPLOADS_TYPE_PHOTO).'/'.$photo);
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);

    }

    public function actionRemoveProfilePhoto($id) {
         User::removeProfilePhoto($id);
        return $this->redirect(['user/edit-profile']);
    }

    public function actionDeleteProfile()
    {
        $this->findModel(Yii::$app->user->getId())->delete();
        return $this->response->redirect(Yii::$app->getHomeUrl());
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findFormModel($id) {
        if (($model = EditProfileForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }




}
