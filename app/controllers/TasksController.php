<?php

class TasksController extends BaseController {
	
	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('auth');
	}
	
	protected $layout = "layouts.main";
	
	public function getIndex($user_id = false) {
		if(!$user_id) {
			$user_id = Auth::user()->id;
		}
		
		$tasks = Task::where('user_id', '=', $user_id)->paginate(30);
		
		return Response::json(array('tasks' => $tasks->toArray()));
	}
	
	public function postNew() {
		
		$validator = Validator::make(Input::all(), Task::$rules);
		
		if($validator->passes()) {
			//Good
			$task = new Task;
			$task->user_id = Input::get('user_id');
			$task->title = Input::get('title');
			$task->description = Input::get('description');
			
			$task->save();
			
			$msg = 'Your task has been saved';
			
			if (Request::ajax()) {
				return Response::json(array('message' => $msg, 'task' => $task->toArray()));
			}
			
			return Redirect::to('tasks')->with('message', $msg);
		}
		
		$msg = 'The following errors occurred';
		
		if (Request::ajax()) {
			return Response::json(array('message' => $msg, 'errors' => $validator->messages()->toArray()), 400);
		}
		
		return Redirect::to('tasks/add')->with('message', $msg)->withErrors($validator)->withInput();

	}
	
	
}