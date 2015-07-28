<?php

class m150728_060942_umb_borrow_log extends CDbMigration
{
	public function up()
	{
		$this->createTable("umb_borrow_log", array(
				'id' => 'pk',
				'umbrellaid' => 'char(25) NOT NULL',
				'user_id' => 'int NOT NULL',
				'borrowed_from' => 'int NOT NULL',
				'borrowed_locate' => 'varchar(20)',
				'borrowed_x' => 'float',
				'borrowed_y' => 'float',
				'borrowed_at' => 'timestamp',
				'repaid_at' => 'timestamp',
				'borrowed_type' => 'int', //1,直接，2预约，3外送
				'des'=>'varchar(200)'
		),
				'ENGINE=InnoDB DEFAULT CHARSET=utf8');
		$this->createIndex("user_id", "umb_borrow_log", 'user_id');
		$this->createIndex("borrowed_from", "umb_borrow_log", 'borrowed_from');
	}

	public function down()
	{
		$this->dropTable("umb_borrow_log");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}