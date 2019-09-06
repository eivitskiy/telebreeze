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

    public function rules()
    {
        return [
            [['id'], 'exist'],
            [['title'], 'required', 'when' => function () {
                return empty($this->id);
            }],
            [['title'], 'string', 'length' => [4, 16]],
        ];
    }
}