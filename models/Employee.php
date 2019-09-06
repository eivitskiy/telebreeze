<?php


namespace app\models;


use yii\db\ActiveRecord;

class Employee extends ActiveRecord
{
    public static function tableName()
    {
        return 'employees';
    }

    public function attributes()
    {
        return [
            'id',
            'first_name',
            'middle_name',
            'last_name',
            'birthday',
            'job_id',
            'education'
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['job_id']);
        $fields[] = 'job';

        return $fields;
    }

    public function getJob()
    {
        return $this->hasOne(Job::className(), ['id' => 'job_id']);
    }
}