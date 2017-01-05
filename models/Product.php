<?php
namespace app\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    const AK = 'AqaWO_FdSIoOXG9l869xBWFPZDTJxPFqxw1lNkxl';
    const SK = 'iNTrPkY29gVdoMqSLjmC06iEDL-mzR0Pn7qS_S1E';
    const DOMAIN = 'oi1s05pu0.bkt.clouddn.com';
    const BUCKET = 'maoburu-imoocshop';

    public static function tableName()
    {
        return '{{%product}}';
    }

    public function attributeLabels()
    {
        return [
            'cateid' => '分类',
            'title' => '标题',
            'descr' => '描述',
            'price' => '单价',
            'ishot' => '是否热卖',
            'issale' => '是否促销',
            'saleprice' => '促销价格',
            'num' => '库存',
            'ison' => '是否上架',
            'istui' => '是否推荐',
            'cover' => '封面',
            'pics' => '图片',
        ];
    }

    public function rules()
    {
        return [
            ['title', 'required', 'message' => '标题不能为空'],
            ['descr', 'required', 'message' => '描述不能为空'],
            ['cateid', 'required', 'message' => '分类不能为空'],
            ['price', 'required', 'message' => '单价不能为空'],
            [['price', 'saleprice'], 'number', 'min' => 0.01, 'message' => '价格必须为数字'],
            ['num', 'integer', 'min' => 0, 'message' => '库存必须为数字'],
            [['issale', 'ishot', 'pics', 'istui'], 'safe'],
            ['cover', 'required']
        ];
    }

    public function add($data)
    {
        if($this->load($data) && $this->save()) {
            return true;
        }
        return false;
    }
}