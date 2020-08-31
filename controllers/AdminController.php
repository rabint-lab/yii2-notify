<?php

namespace rabint\notify\controllers;

use rabint\helpers\user;
use Yii;
use rabint\notify\models\Notification;
use rabint\notify\models\search\NotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AdminController implements the CRUD actions for Notification model.
 */
class AdminController extends \rabint\controllers\AdminController
{

    const BULK_ACTION_SETDRAFT = 'bulk-draft';
    const BULK_ACTION_SETPUBLISH = 'bulk-publish';
    const BULK_ACTION_DELETE = 'bulk-delete';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors([
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'bulk' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * list of bulk action as static
     * @return array
     */
    public static function bulkActions()
    {
        return [
            //static::BULK_ACTION_SETPUBLISH => ['title' =>  Yii::t('app', 'set publish'),'class'=>'success','icon'=>'fas fa-check'],
            //static::BULK_ACTION_SETDRAFT => ['title' =>  Yii::t('app', 'set draft'),'class'=>'warning','icon'=>'fas fa-times'],
            static::BULK_ACTION_DELETE => ['title' => Yii::t('app', 'delete all'), 'class' => 'danger', 'icon' => 'fas fa-trash-alt'],
        ];
    }


    /**
     * bulk action
     * @return mixed
     */
    public function actionBulk($action)
    {

        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys

        if (!isset(static::bulkActions()[$action])) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Bulk action Not found!'));
            return $this->redirect(\rabint\helpers\uri::referrer());
        }
        $selection = (array)$pks;
        $err = 0;
        switch ($action) {
            case static::BULK_ACTION_SETPUBLISH:
                if (Notification::updateAll(['status' => Notification::STATUS_DRAFT], ['id' => $selection])) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Bulk action was successful'));
                } else {
                    $err++;
                }
                break;
            case static::BULK_ACTION_SETDRAFT:
                if (Notification::updateAll(['status' => Notification::STATUS_DRAFT], ['id' => $selection])) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Bulk action was successful'));
                } else {
                    $err++;
                }
                break;
            case static::BULK_ACTION_DELETE:
                if (Notification::deleteAll(['id' => $selection])) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Bulk action was successful'));
                } else {
                    $err++;
                }
                break;
        }
        if ($err) {
            Yii::$app->session->setFlash('danger', Yii::t('app', 'عملیات ناموفق بود'));
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#ajaxCrudDatatable'];
        }
        return $this->redirect(\rabint\helpers\uri::referrer());
    }

    /**
     * Lists all Notification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
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
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->user_id == null or $model->user_id == user::id()) {
            Notification::updateAll(['seen'=>Notification::SEEN_STATUS_YES],['id'=>$model->id]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Notification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Notification();

        $request = Yii::$app->request;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Item successfully created.'));

                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'forceReload' => '#ajaxCrudDatatable',
                        'title' => Yii::t('app', 'Create new') . ' ' . Yii::t('app', 'Notification'),
                        'content' => '<span class="text-success">' . Yii::t('app', 'Create {item} success', [
                                'item' => '<?= $modelClass ?>',
                            ]) . '</span>',
                        'footer' => Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a(Yii::t('app', 'Create More'), ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                }
                return $this->redirect(['index']);
            } else {
                $errors = \rabint\helpers\str::modelErrToStr($model->getErrors());
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Unable to create item.') . "<br/>" . $errors);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Notification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {


        $model = $this->findModel($id);

        $request = Yii::$app->request;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Item successfully updated.'));

                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    [
                        'forceReload' => '#ajaxCrudDatatable',
                        'title' => Yii::t('app', 'Updating') . ' ' . Yii::t('app', 'Notification'),
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Edit', [Yii::t('app', 'update'), 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
                return $this->redirect(['index']);
            } else {
                $errors = \rabint\helpers\str::modelErrToStr($model->getErrors());
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Unable to update item.') . "<br/>" . $errors);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing Notification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $request = Yii::$app->request;

        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Item successfully deleted.'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Unable to delete item.'));
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#ajaxCrudDatatable'];
        }

        return $this->redirect(['index']);

    }

    /**
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rabint', 'The requested page does not exist.'));
        }
    }
}
