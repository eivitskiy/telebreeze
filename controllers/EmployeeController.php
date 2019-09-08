<?php


namespace app\controllers;

use app\models\Employee;
use app\models\Job;
use Yii;
use yii\rest\ActiveController;
use yii\web\Request;

class EmployeeController extends ActiveController
{
    public $modelClass = 'app\models\Employee';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update']);

        return $actions;
    }

    /**
     * @param Request $request
     * @return Job
     */
    private function getJob(Request $request)
    {
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

        return $job;
    }

    /**
     * @param Request $request
     * @param null|Employee $employee
     * @return Employee
     */
    private function getEmployee(Request $request, $employee = null)
    {
        if(!$employee) {
            $employee = new Employee();
        }

        $employee->first_name = $request->post('first_name');
        $employee->middle_name = $request->post('middle_name');
        $employee->last_name = $request->post('last_name');
        $employee->birthday = $request->post('birthday');
        $employee->education = $request->post('education');

        $employee->job = $this->getJob($request);

        return $employee;
    }

    /**
     * @param Employee $employee
     * @return array
     */
    private function getResponse(Employee $employee)
    {
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

    public function actionCreate()
    {
        $employee = $this->getEmployee(Yii::$app->request);

        return $this->getResponse($employee);
    }

    public function actionUpdate($id)
    {
        $employee = $this->getEmployee(Yii::$app->request, Employee::findOne($id));

        return $this->getResponse($employee);
    }
}