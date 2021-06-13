<?php

namespace rabint\notify\controllers;

use rabint\helpers\user;
use Yii;
use rabint\notify\models\Notification;
use rabint\notify\models\search\NotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Notification model.
 */
class DefaultController extends \rabint\controllers\PanelController {

    var $layout = '@theme/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $ret = parent::behaviors();
        return $ret + [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'remove-all' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Notification models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new NotificationSearch();
        $params = Yii::$app->request->queryParams;
        $params['NotificationSearch']['user_id'] = user::id();
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notification model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        if ($model->user_id !=user::id()) {
            throw new \yii\web\ForbiddenHttpException(\Yii::t('app', 'you dont have Access to this page!'));
        }
        if ($model->seen == Notification::SEEN_STATUS_NO) {
            $model->scenario = Notification::SCENARIO_SEEN;
            $model->seen = Notification::SEEN_STATUS_YES;
            $model->updated_at = time();
            $model->save();
        }
//        if (!empty($model->link)) {
//            redirect($model->link);
//        }
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionRemoveAll() {
        return;
        
        if (user::isGuest()) {
            throw new ForbiddenHttpException(\Yii::t('app', 'درخواست نامعتبر است'));
        }

        $res = Notification::deleteAll(['user_id' => user::id()]);
        if ($res) {
            Yii::$app->session->setFlash('success', \Yii::t('app', 'همه اعلان های شما حذف شد'));
        } else {
            Yii::$app->session->setFlash('danger', \Yii::t('app', 'متاسفانه در هنگام حذف اعلان ها خطایی رخ داده است.'));
        }
        return $this->redirect(['index']);
    }
    
    public function actionReadAll() {
        
        if (user::isGuest()) {
            throw new ForbiddenHttpException(\Yii::t('app', 'درخواست نامعتبر است'));
        }

        $res = Notification::updateAll(['seen'=>Notification::SEEN_STATUS_YES], ['user_id' => user::id(),'seen'=> Notification::SEEN_STATUS_NO]);
        
//        if ($res) {
//            Yii::$app->session->setFlash('success', \Yii::t('app', 'عملیات با موفقیت انجام شد'));
//        } else {
//            Yii::$app->session->setFlash('danger', \Yii::t('app', 'هنگام انجام عملیات خطایی رخ داد.'));
//        }
        
        return $this->redirect(['index']);
    }

    public function actionDelete($id) {
        return;
        $model = $this->findModel($id);
        if ($model->user_id != user::id()) {
            throw new \yii\web\ForbiddenHttpException(\Yii::t('app', 'you dont have Access to this page!'));
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Notification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
