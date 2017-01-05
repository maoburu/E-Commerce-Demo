<?php
namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\models\Category;
use app\models\Cart;
use app\models\User;
use app\models\Product;

class CommonController extends Controller
{
    public function init()
    {
        $menu = Category::getMenu();
        $this->view->params['menu'] = $menu;
        $data = [];
        $data['products'] = [];
        $total = 0;
        if (Yii::$app->session['isLogin']) {
            $usermodel = User::find()->where('username = :name', [':name' => Yii::$app->session['loginname']])->one();
            if (!empty($usermodel) && !empty($usermodel->userid)) {
                $userid = $usermodel->userid;
                $cart = Cart::find()->where('userid = :uid', ['uid' => $userid])->asArray()->all();
                foreach ($cart as $k => $pro) {
                    $product = Product::find()->where('productid = :pid',[':pid' => $pro['productid']])->one();
                    $data['products'][$k]['cover'] = $product->cover;
                    $data['products'][$k]['title'] = $product->title;
                    $data['products'][$k]['productnum'] = $pro['productnum'];
                    $data['products'][$k]['price'] = $pro['price'];
                    $data['products'][$k]['productid'] = $pro['productid'];
                    $data['products'][$k]['cartid'] = $pro['cartid'];
                    $total += $data['products'][$k]['price'] * $data['products'][$k]['productnum'];
                }
            }
        }
        $data['total'] = $total;
        $this->view->params['cart'] = $data;
        // echo count($this->view->params['cart']['total']);exit;
        $tui = Product::find()->where('istui = "1" and ison = "1"')->orderby('createtime desc')->limit(3)->all();
        $new = Product::find()->where('ison = "1"')->orderby('createtime desc')->limit(3)->all();
        $hot = Product::find()->where('ishot = "1" and ison = "1"')->orderby('createtime desc')->limit(3)->all();
        $sale = Product::find()->where('issale = "1" and ison = "1"')->orderby('createtime desc')->limit(3)->all();
        $this->view->params['tui'] = (array)$tui;
        $this->view->params['new'] = (array)$new;
        $this->view->params['hot'] = (array)$hot;
        $this->view->params['sale'] = (array)$sale;
    }
}