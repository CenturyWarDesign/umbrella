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
					'create_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
					'update_at' => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
				));
	}

	public function down()
	{
		$this->dropTable('umb_user');
		return false;
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