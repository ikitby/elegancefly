<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for User model.
 */
class PaintersController extends AppController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {

        $users = User::find()->where([
            'status' => 10,
            'role' => ['Painter','Creator']
        ]);

        $usersall = $users;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => CatalogController::STATUS_PAGESIZE,
                'totalCount'        => $users->count()
            ]
        );

        $users = User::find()
            ->joinWith('userLevel')
            ->where(['auth_assignment.item_name' => 'Painter'])
            ->orWhere(['auth_assignment.item_name' => 'Creator'])
            ->andWhere(['status' => '10'])
            ->with('products'/*, 'ratings'*/)
            ->orderBy(['sales' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'users'      => $users,
            'usersall'   => $usersall,
            'pagination'    => $pagination
        ]);

    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUser($alias)
    {
        $alias = Yii::$app->request->get('alias');

        if (!$this->getPainterByAlias($alias)) throw new NotFoundHttpException('The requested page does not exist.');

        return $this->render('view', [
            'painter' => $this->getPainterByAlias($alias)
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function getPainterByAlias($alias)
    {
        return User::find()
            ->joinWith('userLevel')
            ->where(['auth_assignment.item_name' => 'Painter'])
            ->orWhere(['auth_assignment.item_name' => 'Creator'])
            ->AndWhere(['username' => $alias])
            ->one();
    }

}
