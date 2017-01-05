<?php

namespace app\controllers;

use app\controllers\CommonController;
use app\models\User;
use Yii;

class MemberController extends CommonController
{

    public function actionAuth()
    {
        $this->layout = 'layout2';
        if(Yii::$app->request->isGet) {
            $url = Yii::$app->request->referrer;
            if(empty($url)) {
                $url = '/';
            }
            Yii::$app->session->setFlash('referrer', $url);
        }
        $model = new User;
        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->login($post)) {
                $url = Yii::$app->session->getFlash('referrer');
                return $this->redirect($url);
            }
        }
        return $this->render('auth', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->session->remove('loginname');
        Yii::$app->session->remove('isLogin');
        if(!isset(Yii::$app->session['isLogin'])) {
            return $this->goback(Yii::$app->request->referrer);
        }
    }

    public function actionReg()
    {
        $model = new User;
        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if($model->regByMail($post)) {
                Yii::$app->session->setFlash('info', '电子邮件发送成功');
            }
        }
        $this->layout = 'layout2';
        return $this->render('auth', ['model' => $model]);
    }
}