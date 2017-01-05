<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class User extends ActiveRecord
{
    public $repass;
    public $rememberMe = true;
    public $loginname;

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'userpass' => '用户密码',
            'useremail' => '用户邮箱',
            'repass' => '确认密码',
            'rememberMe' => '记住我',
            'loginname' =>'用户名/电子邮箱',
        ];
    }

    public function rules()
    {
        return [
            ['loginname', 'required', 'message' => '登录用户名不能为空', 'on' => ['login',]],
            ['username', 'required', 'message' => '用户名不能为空', 'on' => ['useradd', 'regbymail']],
            ['username', 'unique', 'message' => '用户名已被注册', 'on' => ['useradd', 'regbymail']],
            ['useremail', 'required', 'message' => '电子邮箱不能为空', 'on' => ['useradd', 'regbymail']],
            ['useremail', 'email', 'message' => '电子邮箱格式不正确', 'on' => ['useradd', 'regbymail']],
            ['useremail', 'unique', 'message' => '电子邮箱已被注册', 'on' => ['useradd', 'regbymail']],
            ['userpass', 'required', 'message' => '密码不能为空', 'on' => ['useradd', 'login', 'regbymail']],
            ['userpass', 'validatepass', 'on' => 'login'],
            ['repass', 'required', 'message' => '确认密码不能为空', 'on' => ['useradd']],
            ['repass', 'required', 'message' => '确认密码不能为空', 'on' => ['useradd']],
            ['repass', 'compare', 'compareAttribute' => 'userpass', 'message' => '两次密码输入不一致', 'on' => ['useradd']],
        ];
    }

    public function validatepass()
    {
        if(!$this->hasErrors()) {
            $loginname = 'username';
            if(preg_match('/@/', $this->loginname)) {
                $loginname = 'useremail';
            }
            $data = self::find()->where($loginname.'= :loginname and userpass = :pass', [':loginname' => $this->loginname, ':pass' => md5($this->userpass)])->one();
            if(is_null($data)) {
                $this->addError('userpass', '用户名或密码错误');
            }
        }
    }

    public function reg($data, $scenario = 'useradd')
    {
        $this->scenario = $scenario;
        if($this->load($data) && $this->validate()) {
            $this->createtime = time();
            $this->userpass = md5($this->userpass);
            if($this->save(false)) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['userid' => 'userid']);
    }

    public function login($data)
    {
        $this->scenario = 'login';
        if($this->load($data) && $this->validate()) {
            $lifetime = $this->rememberMe ? 3600 * 24 : 0;
            $session = Yii::$app->session;
            session_set_cookie_params($lifetime);
            $session['loginname'] = $this->loginname;
            $session['isLogin'] = 1;
            return (bool)$session['isLogin'];
        }
        return false;
    }

    public function regByMail($data)
    {
        $data['User']['username'] = uniqid('xarrow_');
        $data['User']['userpass'] = uniqid();
        $this->scenario = 'regbymail';
        if($this->load($data) && $this->validate()) {
            $mailer = Yii::$app->mailer->compose('createuser', ['userpass' => $data['User']['userpass'], 'username' => $data['User']['username']]);
            $mailer->setFrom('ixarrow@163.com');
            $mailer->setTo($data['User']['useremail']);
            $mailer->setSubject('慕课商城-新建用户');
            if($mailer->send() && $this->reg($data, 'regbymail')) {
                return true;
            }
        }
        return false;
    }
}

?>