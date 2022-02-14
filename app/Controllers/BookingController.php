<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Entities\Booking;
use GuzzleHttp\Client;
use FPDF;

class BookingController extends BaseController
{
	public function index()
	{
		helper('inflector');
		$assignmentModel = model('AssignmentModel');
		if (in_groups('teachers'))
		{
			$assignments = $assignmentModel->asArray()
					->select('id,topic_id,title,marks,status')
					->where('user_id', user_id())
					->orderBy('updated_at', 'DESC')
					->paginate();
		}
		else
		{
			// To Display Assignments it is ensured that Assignment and Topic both
			// should be created by the teacher of that school only and
			// the assignment status must be sent
			$assignments = $assignmentModel->findAssignmentsToSolve(user_id());
		}
		//dd($assignments);
		// Define the Table Heading
		$_SESSION['heads'] = [
			'id'       => 'ID# | Files',
			'topic_id' => 'Topic',
			'title'    => 'Assignment',
			'marks'    => 'Marks',
			'status'   => 'Status',
		];

		$rows = [];

		$callback = function (&$value, $key) {
			return in_array($key, array_keys($_SESSION['heads']));
		};

		// Double Check for Access Rights and Locked Status
		foreach ($assignments as $assignment)
		{
			$found = model('AssignmentModel')->find($assignment['id']);
			if ($found->isAllowed())
			{
				if (! in_groups('teachers'))
				{
					if ($found->isLocked())
					{
						array_push($rows, array_filter($assignment, $callback, ARRAY_FILTER_USE_BOTH));
					}
				}
				else
				{
					array_push($rows, array_filter($assignment, $callback, ARRAY_FILTER_USE_BOTH));
				}
			}
		}

		$updateArray = function (&$value, $key) {
			switch(true){

				case ($key === 'topic_id'):
					$value = model('TopicModel')->find($value)->getTitle();
				break;

				case ($key === 'id'):
					$files = model('AssignmentModel')->getFiles($value);
					// From Route: assignment/list => assignment/#id
					$value = $value . ' | ' . '<a href="' . base_url(route_to('view-assignment-files', $value)) . '">' . counted(count($files ?? []), 'Page') . '</a>';
				break;
			}
		};

		array_walk_recursive($rows, $updateArray);

		$data = [
			'heads' => $_SESSION['heads'],
			'rows'  => $rows,
			'pager' => $assignmentModel->pager,
		];

		unset($_SESSION['heads']);

		$data['config'] = $this->config;

		return view('Booking/list-form', $data);
	}


	public function createBooking()
	{
		$data['config']   = $this->config;

		$data['booking'] = (ENVIRONMENT !== 'production') ?
													(new Fabricator(BookingModel::class))->make():
													new Booking();

		return view('Booking/create-form', $data);
	}

	public function tryToCreateBooking()
	{
		$bookingModel = new BookingModel();
		$newBooking   = new Booking();
		$newBooking->fill($this->request->getPost());
		session()->set('post_data', $this->request->getPost());
		$newBooking->setDate(
			$this->request->getPost('date')
		)
		->setStartTime(
			$this->request->getPost('startTime')
		)
		->setAmount(
			$this->request->getPost('hours')
		);


		if (! $bookingModel->save($newBooking))
		{
			return redirect()->back()->withInput()->with('errors', $bookingModel->errors());
		}

		$parser = \Config\Services::parser();
		$data   = [
			'id'    => $bookingModel->getInsertID(),
			'amount' => $newBooking->getAmount(),
		];
		return redirect()->to(base_url(route_to('make-payment', $data['id'])))
							->with(
								'message',
								$parser->setData($data)
								->renderString(lang('app.booking.btnPayTitle'))
							);
	}

	public function makePayment(int $id)
	{
		$found = model('BookingModel')->find($id);
		if (! $found)
		{
			return redirect()->to(base_url(route_to('create-booking')))
					->with('error', lang('app.assignment.notFound'));
		}

		$client = new Client(['base_uri' => env('PG_URI')]);
		$requestBody = [
			'customer_details' => [
				'customer_id'    => $found->mobile,
				'customer_email' => $found->mobile . '@example.com',
				'customer_phone' => $found->mobile
			],
			'order_meta' => [
				'return_url' => base_url(route_to('status', $id)) . '?order_id={order_id}&order_token={order_token}',
				'notify_url' => base_url(route_to('webhook', $id)),
			],
			'order_expiry_time' => date(DATE_ATOM, time() + 960),
			'order_amount'      => $found->amount,
			'order_note'        => $found->getBookedSlot(),
			'order_currency'    =>'INR',
		];

		$response = $client->request('POST', 'orders', [
			'body' => json_encode($requestBody),//'{"customer_details":{"customer_id":"9876543210","customer_email":"john@example.com","customer_phone":"9876543210"},"order_expiry_time":"2022-02-15T00:00:00Z","order_amount":10.15,"order_currency":"INR"}',
			'headers' => [
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'x-api-version' => '2022-01-01',
				'x-client-id' => env('PG_APP_ID'),
				'x-client-secret' => env('PG_SECRET'),
			],
		]);

		$data['pg_resp'] = json_decode($response->getBody());

		$data['id']     = $id;
		$data['booking']  = $found;
		
		$data['config'] = $this->config;
		return view('Booking/view-form', $data);
	}

	public function tryToMakePayment(int $id)
	{

	}

	public function setStatus(int $id)
	{

		$found = model('BookingModel')->find($id);
		if (! $found)
		{
			return redirect()->to(base_url(route_to('create-booking')))
					->with('error', lang('app.assignment.notFound'));
		}


		$client = new Client(['base_uri' => env('PG_URI')]);

		$response = $client->request('GET', 'orders/' . $this->request->getVar('order_id'), [
			'headers' => [
				'Accept' => 'application/json',
				'x-api-version' => '2022-01-01',
				'x-client-id' => env('PG_APP_ID'),
				'x-client-secret' => env('PG_SECRET'),
			],
		]);

		$data['pg_resp'] = json_decode($response->getBody());

		$data['id']     = $id;
		$data['booking']  = $found;
		
		$data['config'] = $this->config;
		return view('Booking/status-form', $data);
	}

	public function webhook(int $id)
	{
		$bookingModel = new BookingModel();

		$bookingOrder = model('BookingModel')->find($id);
		if (! $bookingOrder)
		{
			return redirect()->to(base_url(route_to('create-booking')))
					->with('error', lang('app.assignment.notFound'));
		}
		
		$data['pg_resp'] = json_encode($this->request->getPost());

		$postData = $this->request->getPost();
		$orderId = $orderDetails["orderId"];
		$referenceId = $orderDetails["referenceId"];
		$orderAmount = $orderDetails["orderAmount"];
		$status = $orderDetails["txStatus"];
		$message = $orderDetails["txMsg"];
		$transactionTime = $orderDetails["txTime"];
		$paymentMode = $orderDetails["paymentMode"];
		$accesskey = env('PG_SECRET');
		$dataToHash = $orderId.$orderAmount.$referenceId.$status.$paymentMode.$message.$transactionTime;
		$hash_hmac = hash_hmac('sha256', $dataToHash, $accesskey, true) ;
		$signature =  base64_encode($hash_hmac);

		if($signature == $this->request->getPost('signature')){

			$bookingOrder
				->setStatus($status)
				->setPgResp($this->request->getRawInput());
			
			
			if (! $bookingModel->save($bookingOrder))
			{
				return redirect()->back()->withInput()->with('errors', $bookingModel->errors());
			}
		} 

	}

	public function printReceipt(int $id)
	{
		$bookingOrder = model('BookingModel')->find($id);
		if (! $bookingOrder)
		{
			return redirect()->to(base_url(route_to('create-booking')))
					->with('error', lang('app.assignment.notFound'));
		}
		$pdf = new FPDF();		
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(140,10,'Booking Receipt #' . $id,0,1);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(140,10,$bookingOrder->passenger,1);
		$pdf->Cell(0,10,'Status: ' . $bookingOrder->getStatus(),1,1);
		$pdf->Cell(140,10,'Slot: ' . $bookingOrder->getBookedSlot(),1);
		$pdf->Cell(0,10,'Rs. ' . $bookingOrder->getAmount(),1,1,'C');

		$pdf->Cell(0,10,'Mobile: ' . $bookingOrder->getMobile(),1,1);
		$pdf->Cell(0,10,'Address: ' . $bookingOrder->getAddress(),1,1);
		$pdf->Cell(0,10,'Purpose: ' . $bookingOrder->getPurpose(),1,1);


		$this->response->setHeader('Content-Type', 'application/pdf');
		$pdf->Output();
	}
}
