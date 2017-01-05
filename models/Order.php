<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\OrderDetail;
use app\models\Product;
use app\models\User;
use app\models\Address;
use Yii;

class Order extends ActiveRecord
{
    const CREATEORDER = 0;
    const CHECKORDER = 100;
    const PAYFAILED = 201;
    const PAYSUCCESS = 202;
    const SENDED = 220;
    const RECEIVED = 260;

    public static $status = [
        self::CREATEORDER   => '订单初始化',
        self::CHECKORDER    => '待支付',
        self::PAYFAILED     => '支付失败',
        self::PAYSUCCESS    => '等待发货',
        self::SENDED        => '已发货',
        self::RECEIVED      => '已签收',
    ];

    public $products;
    public $zhstatus;
    public $username;
    public $address;

    public static function tableName()
    {
        return "{{%order}}";
    }

    public function rules()
    {
        return [
            [['userid', 'status'], 'required', 'on' => ['add']],
            [['addressid', 'expressid', 'amuont', 'status'], 'required', 'on' => ['update']],
            ['expressno', 'required', 'message' => '请输入快递单号', 'on' => 'send'],
            ['createtime', 'safe', 'on' => ['add']],
        ];
    }

    public function getDetail($orders) {
        foreach ($orders as $order) {
            $order = self::getData($order);
        }
        return $orders;
    }

    public function getData($order) {
        $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $order->orderid])->all();
        $products = [];
        foreach ($details as $detail) {
            $product = Product::find()->where('productid = :pid', [':pid' => $detail->productid])->one();
            if (empty($product)) {
                continue;
            }
            $product->num = $detail->productnum;
            $products[] = $product;
        }
        $order->products = $products;
        $user = User::find()->where('userid = :uid', [':uid' => $order->userid])->one();
        if (!empty($user)) {
            $order->username = $user->username;
        }
        $order->address = Address::find()->where('addressid = :aid', [':aid' => $order->addressid])->one();
        if (!empty($order->address)) {
            $order->address = $order->address->address;
        }
        $order->zhstatus = self::$status[$order->status];
        return $order;
    }

}