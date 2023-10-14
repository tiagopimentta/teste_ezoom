<?php
trait ValidationTrait {
	private $pattern = '/^[A-Za-z0-9áàâãéèêíïóôõöúçñü \-.,_]+$/';

	private function generateErrorMessage($field) {
		$this->response([
			"status" => false,
			"message" => "the $field field is mandatory and must contain only text or numbers and some characters (,.-)",
		]);
	}

	public function validateFields($fields) {

		$requiredFilds = ['title', 'description'];

		foreach ($requiredFilds as $field) {
			if (!array_key_exists($field, $fields)) {
				$message = $this->generateErrorMessage($field);
				throw new Exception($message);
			}

			$value = $fields[$field];

			if (empty($value) || !preg_match($this->pattern, $value)) {
				$message = $this->generateErrorMessage($field);
				throw new Exception($message);
			}
		}
	}
}
