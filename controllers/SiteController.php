<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Supplier;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
    $supplierModel = new Supplier();
    $dataProvider = $supplierModel->search(Yii::$app->request->queryParams);
    return $this->render('/supplier/index', [
        'supplierModel' => $supplierModel,
        'dataProvider' => $dataProvider,
    ]);
    }

    public function actionExport(){
        $supplierModel = new Supplier();
        $params = Yii::$app->request->queryParams;
        $titleArr=["id","name","code","t_status"];
        $titleStr ="*"; 
        if(!empty($params['title'])){
            $titleStr = $params['title'];
            $titleArr = explode(",",$titleStr);
        }
        
        $dataProvider = $supplierModel->find()->select($titleStr)->asArray()->all();;
        $this->exportCsv($dataProvider,$titleArr);
    }


 //导出CSV
    private static function exportCsv($data,$title)
    {

        // 头部标题
        $fileName = 'supplier.csv';

        $header = implode(',', $title) . PHP_EOL;
        $content = '';
        foreach ($data as $k => $v) {
            $content .= implode(',', $v) . PHP_EOL;
        }
        $csvData = $header . $content;

        print(chr(0xEF) . chr(0xBB) . chr(0xBF));
        header("Content-type:text/csv;");
        header("Content-Disposition:attachment;filename=" . $fileName);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $csvData;
        die;
    }



    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
