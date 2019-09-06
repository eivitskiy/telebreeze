<?php


namespace app\controllers;

use app\models\Employee;
use app\models\Job;
use Yii;
use yii\rest\ActiveController;

class EmployeeController extends ActiveController
{
    public $modelClass = 'app\models\Employee';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update']);

        return $actions;
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;

        $job = null;
        if ($request->post('job_id')) {
            $job = Job::findOne($request->post('job_id'));
        } elseif ($request->post('job_string')) {
            $job = Job::find()->where(['title' => $request->post('job_string')])->one();
        }

        if(!$job) {
            $job = new Job();
            $job->id = $request->post('job_id');
            $job->title = $request->post('job_string');
        }

        $employee = new Employee();
        $employee->first_name = $request->post('first_name');
        $employee->middle_name = $request->post('middle_name');
        $employee->last_name = $request->post('last_name');
        $employee->birthday = $request->post('birthday');
        $employee->education = $request->post('education');
        $employee->job = $job;

        $errors = [];

        if ($employee->validate()) {
            $status = $employee->save();
        } else {
            $status = false;
            $errors = $employee->errors;
        }

        return [
            'success' => $status,
            'errors' => (object)$errors,
            'data' => (object)$employee
        ];
    }

    public function actionUpdate($id)
    {
        $employee = Employee::findOne($id);

        $request = Yii::$app->request;

        $job = null;
        if ($request->post('job_id')) {
            $job = Job::findOne($request->post('job_id'));
        } elseif ($request->post('job_string')) {
            $job = Job::find()->where(['title' => $request->post('job_string')])->one();
        }

        if(!$job) {
            $job = new Job();
            $job->id = $request->post('job_id');
            $job->title = $request->post('job_string');
        }

        $employee->first_name = $request->post('first_name');
        $employee->middle_name = $request->post('middle_name');
        $employee->last_name = $request->post('last_name');
        $employee->birthday = $request->post('birthday');
        $employee->education = $request->post('education');
        $employee->job = $job;

        $errors = [];

        if ($employee->validate()) {
            $status = $employee->save();
        } else {
            $status = false;
            $errors = $employee->errors;
        }

        return [
            'success' => $status,
            'errors' => (object)$errors,
            'data' => (object)$employee
        ];
    }
}