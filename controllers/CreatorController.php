<?php 
namespace kriptograf\menu\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

use kriptograf\menu\models\Menu;
use kriptograf\menu\models\MenuItem;
use kriptograf\menu\models\Search;

class CreatorController extends \yii\web\Controller
{
	/**
	 * List all menu
	 * @return string
	 */
	public function actionIndex()
	{
		$searchModel = new Search();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Create menu
	 * @return string|Response
	 */
	public function actionCreate()
	{
		$model = new Menu();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->scenario = 'insert';
            if($model->save())
			{
				return $this->redirect(['index']);
			}

        }
        return $this->render('create', ['model' => $model]);
	}

	/**
	 * View list items from this menu
	 * Redirect to controller Items
	 * @param $id
	 * @return array|string
	 */
	public function actionView($id)
	{
		return $this->redirect(['/menu/items/index', 'id'=>$id]);
	}

	/**
	 * Edit menu
	 * @param $id
	 * @return array|string
	 * @throws NotFoundHttpException
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$request = Yii::$app->request;
	
		 if ($request->isAjax) 
		 {
			\Yii::$app->response->format = Response::FORMAT_JSON;

			switch (true) {
				case $request->isGet : return ['success' => true, 'menu' => $model->menu];
				case $request->post('update'):
					$model->menu = $request->post('menu');
					return $model->save() ? ['success' => true] : ['success' => false];
				default: return ['success' => false];
			}
		}
	
		return $this->render('update');
	}

	/**
	 * @param $id
	 * @return Response
	 * @throws NotFoundHttpException
	 * @throws \Exception
	 * @throws \Throwable
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * @param $id
	 * @return static
	 * @throws NotFoundHttpException
	 */
    protected function findModel($id)
	{
	    if (($model = Menu::findOne($id)) !== null) {
		    return $model;
	    } else {
		    throw new NotFoundHttpException('The requested menu does not exist.');
	    }
    }




}
