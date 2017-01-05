<?php

namespace app\modules\controllers;

use Yii;
use yii\web\Controller;

class CommonController extends Controller
{
    public function init()
    {
        if (Yii::$app->session['admin']['isLogin'] != 1) {
            return $this->redirect(['/admin/public/login']);
        }
    }
}