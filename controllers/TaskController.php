<?php

namespace app\controllers;
use app\models\Tasks;

class TaskController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	
        $tasks = Tasks::find()->orderBy(['checked'=>'asc','id'=>'desc'])->all();
    	//var_dump($tasks);
        return $this->render('index',compact('tasks'));
        
    }

    public function actionRemove()
    {
        $request = \Yii::$app->request;
        $errors = [];
        if(is_null($request->post("id"))) {
        	$errors[] = "Не передан идентификатор";
        } else {
            $task = Tasks::findOne($request->post("id"));
    		$task->delete();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $errors;
    }
    public function actionChecked()
    {
        $request = \Yii::$app->request;
        $errors = [];
        if(is_null($request->post("id"))) {
            $errors[] = "Не передан идентификатор";
        } else {
            $task = Tasks::findOne($request->post("id"));
            $task->checked = $request->post("checked");
            $task->save();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $errors;
    }
    public function actionAdd() 
    {
        $request = \Yii::$app->request;
        $errors = [];
        if(is_null($request->post("task"))) {
            $errors[]= "не передана строка";
        } elseif(empty($request->post("task"))) {
             $errors[]= "передана пустая строка";
        }
        else {
            $task = new Tasks();
            $task->task = $request->post("task");
            $task->save();
            $data['id'] = $task->id;
            $data['task'] = $task->task;
        } 
        $data['errors'] = $errors;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
    
}
