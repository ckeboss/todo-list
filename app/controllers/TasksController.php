<?php

class TasksController extends BaseController {
	
	public function __construct() {
		$this->beforeFilter('csrf', array('on' => array('post', 'delete')));
		$this->beforeFilter('auth');
	}
	
	public function getIndex() {
		$tasks = Task::where('user_id', '=', Auth::user()->id)->paginate(30);
		
		return Response::json(array('tasks' => $tasks->toArray()));
	}
	
	public function getOthers() {
		$tasks = Task::with(
			array(
				'user' => function($q){
					$q->select(array('id', 'name'));
				}
			)
		)->where('user_id', '!=', Auth::user()->id)->paginate(30);
		
		return Response::json(array('tasks' => $tasks->toArray()));
	}
	
	public function getNew() {
		$users = User::lists('name', 'id');
		
		return View::make('tasks/new')->with('users', $users);
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
				$task = Task::with(array(
								'user' => function($q){
									$q->select(array('id', 'name'));
								}
							))->where('id', '=', $task->id)->first();
				return Response::json(
					array(
						'message' => $msg, 
						'task' =>
							$task->toArray()));
			}
			
			return Redirect::to('tasks')->with('message', $msg);
		}
		
		$msg = 'The following errors occurred';
		
		if (Request::ajax()) {
			return Response::json(array('message' => $msg, 'errors' => $validator->messages()->toArray()), 400);
		}
		
		return Redirect::to('tasks/add')->with('message', $msg)->withErrors($validator)->withInput();

	}
	
	public function deleteDelete() {
		Task::destroy(Input::get('id'));
		
		if (Request::ajax()) {
			return Response::json(array('message' => 'Deleted'));
		}
	}
	
	public function postDone() {
		$task = Task::find(Input::get('id'));
		//We should check to see if we accually have a task
		$task->finished = 1;
		
		$task->save();
		
		if (Request::ajax()) {
			return Response::json(array('message' => 'Saved'));
		}
	}
	
	public function postNotDone() {
		$task = Task::find(Input::get('id'));
		//We should check to see if we accually have a task
		$task->finished = 0;
		
		$task->save();
		
		if (Request::ajax()) {
			return Response::json(array('message' => 'Saved'));
		}		
	}
	
	
}