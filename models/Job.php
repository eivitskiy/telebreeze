<?php


namespace app\models;


use yii\db\ActiveRecord;

class Job extends ActiveRecord
{
    public static function tableName()
    {
        return 'jobs';
    }

    public function attributes()
    {
        return [
            'id',
            'title'
        ];
    }

    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['job_id', 'id']);
    }
}