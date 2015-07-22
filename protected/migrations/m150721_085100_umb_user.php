<?php

class m150721_085100_umb_user extends CDbMigration
{
	public function up()
	{
		$this->createTable("umb_user", array(
					'id' => 'pk',
					'udid' => 'string NOT NULL',
					'username' => 'string',
					'password' => 'string',
					'email' => 'string',
					'type' => 'char(10)',
					'x' => 'float',
					'y' => 'float',
					'locate' => 'varchar(100)',
					'status' => 'int',
					'create_at' => 'timestamp',
					'update_at' => 'timestamp',
				),
				'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		$this->dropTable('umb_user');
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
	public function getDbConnection()
	{
		return Yii::app()->getComponent('db');
	}
	
}