<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->setAutoRoute(false);

// There should be no route to be defined until user updates his profile
// thus we can redirect all routes to profile until updated
// session('has_no_profile') is set at Login Event
if (session('has_no_profile')) {

	$routes->get('/', 'AccountController::profile', [
		'as'     => 'profile',
		'filter' => 'login'
		]);
	$routes->post('/', 'AccountController::tryToUpdateProfile', [
		'as' => 'profile',
		'filter' => 'login'
		]);

} else {

	$routes->get('/', 'Home::index',[
		'as' => 'home'
	]);
	//$routes->get('ci', 'Home::ci');

	$routes->get('profile', 'AccountController::profile', [
		'as'     => 'profile',
		'filter' => 'login'
		]);
		
	// Shows the topic creation form
	$routes->get('topic', 'TopicController::create', [
		'as' => 'create-topic',
		'filter' => 'role:teachers'
		]);
	$routes->post('booking', 'BookingController::tryToBook', [
		'as' => 'create-booking'
		]);

	// Shows the school creation form
	$routes->group(
		'school',
		['filter' => 'role:admins'],
		function($routes) {
			$routes->get('list', 'SchoolController::index', [
				'as' => 'schools'
				]);
			$routes->get('new', 'SchoolController::create', [
				'as' => 'create-school',
				]);
			$routes->post('new', 'SchoolController::tryToCreate', [
				'as' => 'create-school',
				]);
			$routes->get('(:num)', 'SchoolController::update/$1', [
				'as' => 'update-school',
				]);
			$routes->post('(:num)', 'SchoolController::tryToUpdate/$1', [
				'as' => 'update-school',
				]);
		}
	);
	// Account Management Route Group
	$routes->group(
		'account',
		['filter' => 'role:admins'],
		function($routes) {
			$routes->get('new', 'AccountController::create', [
				'as' => 'create'
				]);
			$routes->post('profile', 'AccountController::tryToUpdateProfile', [
				'as' => 'profile',
				'filter' => 'login'
				]);
			$routes->post('new', 'AccountController::tryToCreateUser', [
				'as' => 'create'
				]);
			$routes->get('(:num)', 'AccountController::update/$1', [
				'as' => 'update-user'
				]);
			$routes->post('(:num)', 'AccountController::tryToUpdate/$1', [
				'as' => 'update-user'
				]);
			$routes->get('list', 'AccountController::index', [
				'as' => 'accounts'
				]);
		}
	);

	// File Management Route Group
	$routes->group(
		'file',
		['namespace' => 'App\Controllers'],
		function($routes) {

		// Display the Image File
		$routes->get('(:num)', 'FileController::view/$1', [
			'as' => 'show-file',
			'filter' => 'role:teachers,students'
			]);

		// Lists all the files the user has uploaded
		$routes->get('list', 'FileController::index', [
			'as' => 'list-files',
			'filter' => 'role:teachers,students'
			]);

		// Deletes the Image File cannot be moved to FileController 
		// as the redirect route(previous_url) gets updated due to image loading
		$routes->get('(:num)/delete/(:any)/(:num)', 'FileController::delete/$1/$2/$3', [
			'as' => 'del-file',
			'filter' => 'role:teachers,students'
			]);

		// Rotates the Image File
		$routes->get('(:num)/rotate/(:num)/(:any)/(:num)', 'FileController::rotate/$1/$2/$3/$4', [
			'as' => 'rotate-file',
			'filter' => 'role:teachers,students'
			]);
		}
	);

	// Assignment Route Group
	$routes->group(
		'assignment',
		['namespace' => 'App\Controllers'],
		function($routes) {
			
			// List of All Assignments
			$routes->get('', 'AssignmentController::index', [
				'as' => 'view-assignments',
				'filter' => 'role:teachers,students'
				]);

			// Shows the Assignment Creation form
			$routes->get('new', 'AssignmentController::create', [
				'as' => 'create-assignment',
				'filter' => 'role:teachers'
				]);
			$routes->post('new', 'AssignmentController::tryToCreate', [
				'as' => 'create-assignment',
				'filter' => 'role:teachers'
				]);

			// Upload Images for Selected Assignment
			$routes->get('(:num)/file', 'AssignmentController::attachFile/$1', [
				'as' => 'add-assignment-file',
				'filter' => 'role:teachers'
				]);
			$routes->post('(:num)/file', 'AssignmentController::tryToAttachFile/$1', [
				'as' => 'add-assignment-file',
				'filter' => 'role:teachers'
				]);

			// Shows the Assignment Images
			$routes->get('(:num)', 'AssignmentController::view/$1', [
				'as' => 'view-assignment-files',
				'filter' => 'role:teachers,students'
				]);

			// Lock the Assignment
			$routes->get('(:num)/lock', 'AssignmentController::lock/$1', [
				'as' => 'lock-assignment',
				'filter' => 'role:teachers'
				]);

			// List of answers to check for this assignment
			$routes->get('(:num)/answers', 'AssignmentController::answers/$1', [
				'as' => 'check-answers',
				'filter' => 'role:teachers'
				]);
			
			// Shows the Answer Creation form
			$routes->get('(:num)/answer', 'AnswerController::create/$1', [
				'as' => 'create-answer',
				'filter' => 'role:students'
				]);
			$routes->post('(:num)/answer', 'AnswerController::tryToCreate/$1', [
				'as' => 'create-answer',
				'filter' => 'role:students'
				]);
			
		}
	);

	// Solve Answer Route Group
	$routes->group(
		'answer',
		['namespace' => 'App\Controllers'],
		function($routes) {

			// List of All Answers
			$routes->get('', 'AnswerController::index', [
				'as' => 'list-answers',
				'filter' => 'role:teachers,students'
				]);

			
			// Lock the Answer
			$routes->get('(:num)/lock', 'AnswerController::lock/$1', [
				'as' => 'lock-answer',
				'filter' => 'role:students'
				]);

			// Shows the Answer Images
			$routes->get('(:num)', 'AnswerController::view/$1', [
				'as' => 'view-answer-files',
				'filter' => 'role:teachers,students'
				]);

			// Check a single answer image in canvas
			$routes->get('(:num)/check/(:num)', 'AnswerController::check/$1/$2', [
				'as' => 'check-answer-file',
				'filter' => 'role:teachers'
				]);

			// Save a single answer image after assess
			$routes->post('(:num)/check/(:num)/save', 'AnswerController::saveChecked/$1/$2', [
				'as' => 'save-answer-file',
				'filter' => 'role:teachers'
				]);

			// Check the Answer Images
			$routes->post('(:num)/marks', 'AnswerController::saveMarks/$1', [
				'as' => 'save-marks',
				'filter' => 'role:teachers'
				]);
		}
	);
}