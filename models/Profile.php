<?php

namespace app\models;

use yii\db\ActiveRecord;

class Profile extends ActiveRecord
{
    public $repass;
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /*public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'useremail' => '电子邮箱',
            'userpass' => '用户密码',
            'repass' => '确认密码',
        ];
    }

    public function rules()
    {
        return [
            ['username', 'required', 'message' => '用户名不能为空', 'on' => ['useradd']],
            ['username', 'unique', 'message' => '用户名已被注册', 'on' => ['useradd']],
            ['useremail', 'required', 'message' => '电子邮箱不能为空', 'on' => ['useradd']],
            ['useremail', 'email', 'message' => '电子邮箱格式不正确', 'on' => ['useradd']],
            ['useremail', 'unique', 'message' => '电子邮箱已被注册', 'on' => ['useradd']],
            ['userpass', 'required', 'message' => '密码不能为空', 'on' => ['useradd']],
            ['repass', 'required', 'message' => '确认密码不能为空', 'on' => ['useradd']],
            ['repass', 'required', 'message' => '确认密码不能为空', 'on' => ['useradd']],
            ['repass', 'compare', 'compareAttribute' => 'userpass', 'message' => '两次密码输入不一致', 'on' => ['useradd']],
        ];
    }

    public function reg($data)
    {
        $this->scenario = 'useradd';
        if($this->load($data) && $this->validate()) {
            $this->userpass = md5($this->userpass);
            if($this->save(false)){
                return true;
            }
            return false;
        }
        return false;
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['userid' => 'userid']);
    }*/

}

?>