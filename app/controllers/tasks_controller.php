<?php


class TaskController extends BaseController {

    public static function index() {
        $tasks = Task::all();

        View::make('task/task_list.html', array('tasks' => $tasks));
    }
    
    public static function show($id) {
        
        $task = Task::find($id);
        
        View::make('task/task_show.html', array('task'=>$task));
    }

}
