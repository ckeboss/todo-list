<?php

class UsersController extends BaseController {
	
	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('auth', array('only' => 'getDashboard'));
	}
	
	protected $layout = "layouts.main";
	
	public function getRegister() {
		$this->layout->content = View::make('users.register');
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
			
			return Redirect::to('users/login')->with('message', 'Thank you for registering, please login below.');
		}
		
		return Redirect::to('users/register')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
	}
	
	public function getLogin() {
		$this->layout->content = View::make('users.login');
	}
	
	public function postLogin() {
		if (Auth::attempt(array('email'=>Input::get('email'), 'password'=>Input::get('password')))) {
			return Redirect::to('users/dashboard')->with('message', 'You are now logged in!');	
		}
		
		return Redirect::to('users/login')->with('message', 'Your username/password combination was incorrect')->withInput();
	}
	
	public function getDashboard() {
		$this->layout->content = View::make('users.dashboard');
	}
	
	
}