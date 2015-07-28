<?php

class m150728_034030_create_umbrella extends CDbMigration
{
	public function up()
	{
		$this->createTable("umb_umbrella", array(
					'id' => 'pk',
					'umbrellaid' => 'char(25) NOT NULL',
					'create_userid' => 'int NOT NULL',
					'now_userid' => 'int NOT NULL',
					'des' => 'string',
					'img' => 'varchar(200)',
					'price' => 'float',//10,20,30,40,50
					'status' => 'int',//正常0，借出1，维修-1，超期-2，丢失-3，预约外借2,未审核-100，审核未通过-10
					'create_at' => 'timestamp',
					'update_at' => 'timestamp',
				),
				'ENGINE=InnoDB DEFAULT CHARSET=utf8');
		$this->createIndex("create_userid", "umb_umbrella", 'create_userid');
		$this->createIndex("now_userid", "umb_umbrella", 'now_userid');
		$this->createIndex("status", "umb_umbrella", 'status');
	}

	public function down()
	{
		$this->dropTable("umb_umbrella");
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