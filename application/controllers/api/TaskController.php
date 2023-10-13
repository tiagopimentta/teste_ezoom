<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'traits/ValidationTrait.php';

use chriskacerguis\RestServer\RestController;

class TaskController extends RestController
{

	use ValidationTrait;
	public function __construct()
	{
		parent::__construct();
		$this->load->library('pagination');
		$this->load->helper(['url','pagination_helper' ]);
		$this->load->model('task_model');
	}

	public function allTasks_get()
	{
		try {

			$pagination = PaginationHelper::paginate($this, $this->task_model);
			$response = $this->task_model->getTasks($pagination['page'], $pagination['itemsPerPage']);

			if ($response) {
				$this->response([
					'status' => true,
					'message' => $response,
					'pagination' => $pagination['pagination'],
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Tasks not found or do not exist',
				], RestController::HTTP_BAD_REQUEST);
			}
		} catch (\Exception $e) {
			$this->response(['error' => $e->getMessage()], 403);
		}
	}

	public function createTask_post()
	{
		try {

			$request = [
				'title'        => $this->input->post('title'),
				'description'  => $this->input->post('description')
			];

			$this->validateFields($request);

			$response = $this->task_model->createTask($request);

			if($response){
				$this->response([
					'status'    => true,
					'message' => 'new task created.',
					'data'    => $response,
				], RestController::HTTP_OK);
			}else{
				$this->response([
					'status'  => false,
					'message' => 'failed to craete task',
				], RestController::HTTP_BAD_REQUEST);
			}

		} catch (\Exception $e) {
			$this->response(['error' => $e->getMessage()], 403);
		}
	}

	public function getTask_get($id)
	{
		try {
			$response = $this->task_model->getTask($id);
			if($response){
				$this->response([
					'status'  => true,
					'status'  => 200,
					'message' => $response,
				], RestController::HTTP_OK);
			}else{
				$this->response([
					'status'  => "error",
					'message' => 'task not found or does not exist',
				], RestController::HTTP_BAD_REQUEST);
			}
		} catch (\Exception $e) {
			$this->response(['error' => $e->getMessage()], 403);
		}
	}

	public function updateTask_put($id)
	{
		try {
			$request = $this->put();

			$this->validateFields($request);

			$status = 'pendente';
			if(!empty($request['status'])){
				switch ($request['status']){
					case 'pendente':
						$status = 'pendente';
						break;
					case 'concluída':
						$status = 'concluída';
						break;
					case 'cancelada':
						$status = 'cancelada';
						break;
					default:
						$status = 'pendente';
						break;
				}
			}

			$request = [
				'title'       => $this->put('title'),
				'description' => $this->put('description'),
				'status'      => $status
			];


			$getdata = $this->task_model->getTask($id);

			$response = null;
			if(!empty($getdata)){
				$response = $this->task_model->updateTask($id, $request);
			}

			if($response){
				$this->response([
					'status'  => true,
					'message' => 'task updated',
					'data'    => $response,
				], RestController::HTTP_OK);
			}else{
				$this->response([
					'status'  => "error",
					'message' => 'task not found or does not exist',
				], RestController::HTTP_BAD_REQUEST);
			}
		} catch (\Exception $e) {
			$this->response(['error' => $e->getMessage()], 403);
		}
	}


	public function deleteTask_delete($id)
	{
		try {

			$getdata = $this->task_model->getTask($id);

			$response = null;
			if(!empty($getdata)){
				$response = $this->task_model->deleteTask($id);
			}

			if($response){
				$this->response([
					'status'  => "success",
					'message' => 'task deleted',
					'data'    => $getdata
				], RestController::HTTP_OK);
			}else{
				$this->response([
					'status'  => "error",
					'message' => 'task not found or does not exist',
				], RestController::HTTP_BAD_REQUEST);
			}

		} catch (\Exception $e) {
			$this->response(['error' => $e->getMessage()], 403);
		}
	}
}

