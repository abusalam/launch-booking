<?php namespace App\Controllers;

use App\Models\UserModel;
use Config\Services;

/**
 * Home Page Controller
 *
 * @package ePathshala
 */
class Home extends BaseController
{
	public function index()
	{
		$collection = Services::routes(true);
		$methods    = [
			'get',
			'head',
			'post',
			'patch',
			'put',
			'delete',
			'options',
			'trace',
			'connect',
			'cli',
		];

		$tbody = [];
		foreach ($methods as $method)
		{
			$routes = $collection->getRoutes($method);

			foreach ($routes as $route => $handler)
			{
				// filter for strings, as callbacks aren't displayable
				if (is_string($handler))
				{
					$tbody[] = [
						strtoupper($method),
						$route,
						$handler,
					];
				}
			}
		}

		$thead = [
			'Method',
			'Route',
			'Handler',
		];

		$userModel      = new UserModel();
		$data['users']  = $userModel->findColumn('id');
		$data['config'] = $this->config;
		$data['thead']  = $thead;
		$data['tbody']  = $tbody;
		
		//$data['slots'] = new Booking();

		return view('Booking/create-form', $data);
	}

	public function ci()
	{
		return view('welcome_message');
	}

}
