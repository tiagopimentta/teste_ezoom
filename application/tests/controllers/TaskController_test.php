<?php

use GuzzleHttp\Client;

const url = "http://localhost:9000/";
const task_id = "1";

class TaskController_test extends TestCase
{

	public function testCreateTask()
	{
		$request = [
			'title'       => 'novo registro',
			'description' => 'descrição qualquer, pode usar alguns caracteres que são utilizados em texto'
		];

		try {
			$client = new Client();
			$response = $client->post(url. 'teste_ezoom/api/task/create', [
				'headers'          => [
					'Content-Type' => 'application/json',
					'X-API-KEY'    => '7440bba936e6a7272ade8bfa8a148ead97a32f89'
				],
				'body' => json_encode($request)
			]);

			$this->assertEquals(200, $response->getStatusCode());
			$this->assertNotEmpty($response->getBody());
			$responseContent = json_decode($response->getBody(), true);
			$this->assertNotEmpty($responseContent);

		} catch (Exception $e) {
			echo "Erro na solicitação: " . $e->getMessage();
		}
	}

	public function testGetAllTasks()
	{
		try {
			$client = new Client();
			$response = $client->get(url."teste_ezoom/api/tasks", [
				'headers' => [
					'Content-Type' => 'application/json',
					'X-API-KEY'    => '7440bba936e6a7272ade8bfa8a148ead97a32f89'
				]
			]);

			$this->assertEquals(200, $response->getStatusCode());
			$this->assertNotEmpty($response->getBody());

			$responseContent = json_decode($response->getBody(), true);

			$this->assertNotEmpty($responseContent);
			$this->assertArrayHasKey('status', $responseContent);
			$this->assertTrue($responseContent['status']);
			$this->assertArrayHasKey('message', $responseContent);
			$this->assertArrayHasKey('pagination', $responseContent);

			if (is_array($responseContent['pagination'])) {
				$this->assertArrayHasKey('page', $responseContent['pagination']);
				$this->assertArrayHasKey('itemsPerPage', $responseContent['pagination']);
				$this->assertArrayHasKey('pagination', $responseContent['pagination']);
			}

			// Teste a estrutura e os dados específicos da resposta aqui

		} catch (Exception $e) {
			$this->fail("Erro na solicitação: " . $e->getMessage());
		}
	}

	public function testGetTask()
	{

		try {

			$client = new Client();
			$response = $client->get(url ."teste_ezoom/api/task/".task_id, [
				'headers' => [
					'Content-Type' => 'application/json',
					'X-API-KEY'    => '7440bba936e6a7272ade8bfa8a148ead97a32f89'
				]
			]);


			$this->assertEquals(200, $response->getStatusCode());
			$this->assertNotEmpty($response->getBody());

			$responseContent = json_decode($response->getBody(), true);

			$this->assertNotEmpty($responseContent);
			$this->assertArrayHasKey('status', $responseContent);
			$this->assertTrue($responseContent['status']);
			$this->assertArrayHasKey('data', $responseContent);
			$this->assertArrayHasKey('id', $responseContent['data']);
			$this->assertEquals(task_id, $responseContent['data']['id']);

		} catch (Exception $e) {
			echo "Erro na solicitação: " . $e->getMessage();
		}

	}


	public function testUpdateTask()
	{
		$updateData = [
			'title'       => 'Título atualizado',
			'description' => 'Descrição atualizada',
			'status'      => 'concluída',
		];

		try {
			$client = new Client();
			$response = $client->put(url . "teste_ezoom/api/task/update/".task_id, [
				'headers' => [
					'Content-Type' => 'application/json',
					'X-API-KEY'    => '7440bba936e6a7272ade8bfa8a148ead97a32f89'
				],
				'body' => json_encode($updateData)
			]);

			$this->assertEquals(200, $response->getStatusCode());
			$this->assertNotEmpty($response->getBody());

			$responseContent = json_decode($response->getBody(), true);

			$this->assertNotEmpty($responseContent);
			$this->assertTrue($responseContent['status']);
			$this->assertArrayHasKey('data', $responseContent);

			$this->assertEquals($updateData['title'], $responseContent['data']['title']);
			$this->assertEquals($updateData['description'], $responseContent['data']['description']);
			$this->assertEquals($updateData['status'], $responseContent['data']['status']);

		} catch (Exception $e) {
			$this->fail("Erro na solicitação: " . $e->getMessage());
		}
	}


	public function testDeleteTask()
	{
		try {
			$client = new Client();
			$response = $client->delete(url ."teste_ezoom/api/task/delete/".task_id, [
				'headers' => [
					'Content-Type' => 'application/json',
					'X-API-KEY'    => '7440bba936e6a7272ade8bfa8a148ead97a32f89'
				]
			]);

			$this->assertEquals(200, $response->getStatusCode());
			$this->assertNotEmpty($response->getBody());

			$responseContent = json_decode($response->getBody(), true);

			$this->assertNotEmpty($responseContent);
			$this->assertTrue($responseContent['status']);
			$this->assertArrayHasKey('message', $responseContent);
			$this->assertEquals('Task deleted successfully', $responseContent['message']);

		} catch (Exception $e) {
			$this->fail("Erro na solicitação: " . $e->getMessage());
		}
	}

}


