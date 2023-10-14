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
		$this->load->helper(['url','pagination_helper']);
		$this->load->model('task_model');
	}

	public function allTasks_get()
	{
		try {

			$pagination = PaginationHelper::paginate($this, $this->task_model);
			$response = $this->task_model->getTasks($pagination['page'], $pagination['itemsPerPage']);

			if (!empty($response)) {
				$this->response([
					'status' => true,
					'message' => $response,
					'pagination' => $pagination,
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

		$request = json_decode(file_get_contents('php://input'), true);

		try {

			$this->validateFields($request);

			$request = [
				'title' => $request['title'],
				'description' => $request['description']
			];

			$response = $this->task_model->createTask($request);

			if ($response) {
				$this->response([

					'status' => true,
					'message' => 'new task created.',
					'data' => $response,
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'failed to create task',
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
			if ($response) {
				$this->response([
					'status' => true,
					'data' => $response,
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'task not found or does not exist',
					
				], RestController::HTTP_BAD_REQUEST);
			}
		} catch (\Exception $e) {
			$this->response(['error' => $e->getMessage()], 403);
		}
	}

	public function updateTask_put($id)
	{
		if (empty($id) || !is_numeric($id) || $id <= 0) {
			$this->response(['error' => 'Invalid ID'], 400);
			return;
		}

		try {
			$request = $this->put();
			$this->validateFields($request);

			$status = in_array($request['status'], ['pendente', 'concluída', 'cancelada']) ? $request['status'] : 'pendente';

			$request = [
				'title' => $this->put('title'),
				'description' => $this->put('description'),
				'status' => $status
			];

			$response = $this->task_model->updateTask($id, $request);

			if (!$response) {
				$this->response([
					'status' => false,
					'message' => 'task not found or does not exist',
				], RestController::HTTP_BAD_REQUEST);
			}

			if (!is_numeric($response->id) || $response->id <= 0) {
				$this->response(['error' => 'Invalid ID in the response'], 400);
				return;
			}

			$this->response([
				'status' => true,
				'data' => $response,
			], RestController::HTTP_OK);
		} catch (\Exception $e) {
			$this->response(['error' => $e->getMessage()], 403);
		}
	}

	public function deleteTask_delete($id)
	{
		if(!empty($id) && is_numeric($id)) {
			try {

				$getdata = $this->task_model->getTask($id);

				$response = null;
				if (!empty($getdata)) {
					$response = $this->task_model->deleteTask($id);
				}

				if ($response) {
					$this->response([
						'status' => true,
						'message' => 'Task deleted successfully',
						'data' => $getdata
					], RestController::HTTP_OK);
				} else {
					$this->response([
						'status' => false,
						'message' => 'Task not found or does not exist',
					], RestController::HTTP_BAD_REQUEST);
				}

			} catch (\Exception $e) {
				$this->response(['error' => $e->getMessage()], 403);
			}
		}
	}

}

