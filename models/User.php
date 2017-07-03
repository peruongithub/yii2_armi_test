<?php

namespace app\models;

use yii\base\NotSupportedException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CREATE = 'create';
    public $username;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->username = $this->name;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['user_id' => 'id']);
    }

    public static function getActiveUsers()
    {
        $sessionTable = \Yii::$app->session->sessionTable;
        $userTable = self::tableName();
        return new ActiveDataProvider([
            'query' => self::find()
                ->innerJoin(\Yii::$app->session->sessionTable, "$sessionTable.user_id = $userTable.id")
                ->where(
                    "DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :delta SECOND) <= $sessionTable.last_activity",
                    [':delta' => \Yii::$app->params['userActivityInterval']]
                )->orderBy(['time_last_visit' => SORT_ASC]),
            'pagination' => false
        ]);
    }

    /**
     * @param $name
     * @return bool
     */
    public static function isLogedin($name)
    {
        $sessionTable = \Yii::$app->session->sessionTable;
        $userTable = self::tableName();
        $result = self::find()
            ->innerJoin(\Yii::$app->session->sessionTable, "$sessionTable.user_id = $userTable.id")
            ->where(
                "DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :delta SECOND) <= $sessionTable.last_activity",
                [':delta' => \Yii::$app->params['userActivityInterval']]
            )
            ->andWhere("$userTable.name = :name", [':name' => $name])
            ->one();

        return $result ? true : false;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->ip = $this->getRealIp();
        $this->city = (\Yii::$app->geoip->ip($this->ip))->city;
        $this->city = null === $this->city ? 'Unknown' : $this->city;
        $this->time_last_visit = new Expression('DEFAULT');

        return true;
    }

    /**
     * Простая заглушка для localhost
     * @return string
     */
    protected function getRealIp()
    {
        $ip = \Yii::$app->getRequest()->getUserIP();
        if ($_SERVER['SERVER_ADDR'] !== $ip) {
            return $ip;
        }
        $url = 'http://api.2ip.ua/geo.json?ip=';
        $data = '{"ip":"' . $ip . '"}';
        if ($stream = fopen($url, 'r')) {
            $response = stream_get_contents($stream);
            $data = false === $response ? $data : $response;
            fclose($stream);
        }
        $data = json_decode($data);

        return $data->ip;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->username = $this->name;
    }

    public function afterRefresh()
    {
        parent::afterRefresh();
        $this->username = $this->name;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'name' => 'Имя',
            'ip' => 'IP адрес',
            'city' => 'Город',
            'time_create' => 'Дата, время регистрации',
            'time_last_visit' => 'Дата, время последней активности'
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_UPDATE => [
            ],
            self::SCENARIO_CREATE => [
                'name'
            ],
            self::SCENARIO_LOGIN => [
                'name'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'unique'],
            [['name'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/ui', 'message' => 'только латинские буквы и цифры'],
            [['name'], 'required'],
            [['name'], 'string', 'min' => 3, 'tooShort' => 'Не менее 5 символов', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['name' => $username]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {

    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {

    }
}
