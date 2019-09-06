<?php


namespace app\models;


use DateTime;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\TimestampBehavior;
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

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'birthday',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'birthday',
                ],
                'value' => function () {
                    return date('Y-m-d', strtotime($this->birthday));
                },
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_FIND => 'birthday',
                ],
                'value' => function () {
                    return date('d.m.Y', strtotime($this->birthday));
                }
            ],
            'saveRelations' => [
                'class' => SaveRelationsBehavior::className(),
                'relations' => [
                    'job',
                ],
            ],
        ];
    }

    public function validateBirthday($attribute)
    {
        $birthday = DateTime::createFromFormat('d.m.Y', $this->$attribute);
        $errors = DateTime::getLastErrors();

        if (!empty($errors['warning_count'])) {
            $this->addError($attribute, 'The format of Birthday is invalid.');
        } else {
            $minDate = new DateTime('01.01.1950');
            $maxDate = new DateTime();

            if ($birthday < $minDate || $birthday > $maxDate) {
                $this->addError($attribute, 'The date of Birthday is invalid.');
            }
        }
    }

    public function validateEducation($attribute)
    {
        $errors = [];

        if (!is_array($this->$attribute)) {
            $errors[] = 'The value of Education is invalid.';
        }

        foreach ($this->$attribute as $item_id => $item) {
            if (!is_array($item)) {
                $errors[$item_id][] = 'The item of Education is invalid.';
            } else {
                foreach ($item as $k => $v) {
                    if (!is_string($v)) {
                        $errors[$item_id][$k][] = "The value of $k is invalid.";
                    }
                    if (empty($v)) {
                        $errors[$item_id][$k][] = "The value of $k is invalid.";
                    }
                }
            }
        }

        if (count($errors)) {
            $this->addError($attribute, $errors);
        }
    }

    public function rules()
    {
        return [
            [['first_name', 'middle_name', 'last_name', 'birthday'], 'required'],
            ['birthday', 'validateBirthday'],
            [['education'], 'validateEducation'],
        ];
    }
}