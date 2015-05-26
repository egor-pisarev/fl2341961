<?php

namespace egorpisarev\document\controllers;

use Yii;
use egorpisarev\document\models\Document;
use egorpisarev\document\models\DocumentSearch;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use egorpisarev\document\models\Attachment;
use yii\data\ActiveDataProvider;



/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Document();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getAttachments(),
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'dataProvider'=>$dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->delete()){
            FileHelper::removeDirectory($model->getAttachmentsPath());
        }

        return $this->redirect(['index']);
    }

    /**
     * Upload a document attachments
     * @param integer $id
     * @return mixed
     */

    public function actionUpload($id)
    {
        $fileName = 'file';

        $model = new Document();
        $model->id = $id;

        $uploadPath = $model->getAttachmentsPath();

        FileHelper::createDirectory($model->getAttachmentsPath());

        if (isset($_FILES[$fileName])) {
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($uploadPath . '/' . $file->name)) {

                $attachment = new Attachment();
                $attachment->filename = $file->name;
                $attachment->size = $file->size;
                $attachment->document_id = $id;

                if(!$attachment->save()){
                    //TODO Delete file
                    throw new HttpException(500,'Can`t save attachment');
                }

                echo \yii\helpers\Json::encode($file);
            }
        }

        return false;
    }


    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getViewPath()
    {
        return Yii::getAlias('@vendor/egorpisarev/yii2-document/views/document');
    }

}
