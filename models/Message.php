<?php

namespace app\models;

use yii\db\ActiveRecord;

class Message extends ActiveRecord
{
    public $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'required'],
            [['message'], 'string', 'max' => 250],
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert) || \Yii::$app->user->isGuest) {
            return false;
        }

        $this->user_id = \Yii::$app->user->id;

        return true;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'user';

        return $fields;
    }

    /**
     * @param int $id
     * @return array
     */
    public static function getLastMessagesById($id)
    {
        return self::find()
            ->where('id > :id', [':id' => $id])
            ->with('user')
            ->orderBy(['id' => SORT_ASC])
            ->asArray()
            ->all();
    }

    /**
     * @return array
     */
    public static function getLastMessages()
    {
        return self::find()
            ->where(
                'DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :delta SECOND) <= writing',
                [':delta' => \Yii::$app->params['updateInterval']]
            )->orderBy(['id' => SORT_ASC])->with('user')->asArray()->all();

    }
}
