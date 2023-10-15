<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tables extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'INT'
			),
			'token' => array(
				'type' => 'VARCHAR',
				'constraint' => 40
			),
			'level' => array(
				'type' => 'INT'
			),
			'ignore_limits' => array(
				'type' => 'TINYINT',
				'default' => 1
			),
			'is_private_key' => array(
				'type' => 'TINYINT',
				'default' => 1
			),
			'ip_addresses' => array(
				'type' => 'TEXT',
				'null' => TRUE
			),
			'date_created' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('auth_tokens');
		$this->db->query('ALTER TABLE auth_tokens MODIFY date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP');

		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'auto_increment' => TRUE
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => 50
			),
			'description' => array(
				'type' => 'VARCHAR',
				'constraint' => 100
			),
			'status' => array(
				'type' => "ENUM('pendente', 'concluída', 'cancelada')",
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('tasks');

		$data = array(
			'id' => 1,
			'user_id' => 0,
			'token' => '7440bba936e6a7272ade8bfa8a148ead97a32f89',
			'level' => 0,
			'ignore_limits' => 0,
			'is_private_key' => 0,
			'ip_addresses' => NULL,
			'date_created' => '2023-10-15 18:35:38'
		);
		$this->db->insert('auth_tokens', $data);
	}

	public function down()
	{
		$this->dbforge->drop_table('auth_tokens');
		$this->dbforge->drop_table('tasks');
	}
}
