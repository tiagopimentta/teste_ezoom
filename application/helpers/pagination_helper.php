<?php

class PaginationHelper
{
	public static function paginate($controller, $model, $pageKey = 'page', $itemsPerPage = 10)
	{
		$page = $controller->input->get($pageKey) ?? 1;

		$config['base_url'] = base_url($controller->router->class . '/' . $controller->router->method);
		$config['total_rows'] = $model->countTasks();
		$config['per_page'] = $itemsPerPage;
		$config['use_page_numbers'] = true;

		$controller->load->library('pagination');
		$controller->pagination->initialize($config);

		return [
			'page' => $page,
			'itemsPerPage' => $itemsPerPage,
			'pagination' => $controller->pagination->create_links(),
		];
	}
}
