<?php

class UsersController extends BaseController {
	
	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('auth', array('only' => 'getLogout'));
	}
	
	public function postRegister() {
		
		$validator = Validator::make(Input::all(), User::$rules);
		
		if($validator->passes()) {
			//Good
			$user = new User;
			$user->name = Input::get('name');
			$user->email = Input::get('email');
			$user->password = Hash::make(Input::get('password'));
			
			$user->save();
			
			$msg = 'Thank you for registering, please login below.';
			
			if (Request::ajax()) {
				return Response::json(array('message' => $msg));
			}
			
			return Redirect::to('users/login')->with('message', $msg);
		}
		
		$msg = 'The following errors occurred';
		
		if (Request::ajax()) {
			return Response::json(array('message' => $msg, 'errors' => $validator->messages()->toArray()), 400);
		}
		
		return Redirect::to('users/register')->with('message', $msg)->withErrors($validator)->withInput();
	}
	
	public function postLogin() {
		if (Request::ajax()) {
			if (Auth::attempt(array('email'=>Input::get('email'), 'password'=>Input::get('password')))) {
				return Response::json(array('message' => 'You have been logged in!', 'user_id' => Auth::user()->id, 'name' => Auth::user()->name));
			}
			
			return Response::json(array('message' => 'Your username or password was incorrect.'));
		}
	}
	
	public function getLogout() {
		Auth::logout();
		
		$msg = 'You are now logged out.';
		
		if (Request::ajax()) {
			return Response::json(array('message' => $msg));
		}
		
		return Redirect::to('/')->with('message', $msg);
	}
	
	
}