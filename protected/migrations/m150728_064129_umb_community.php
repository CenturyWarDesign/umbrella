<?php

class m150728_064129_umb_community extends CDbMigration
{
	public function up()
	{
		//地理位置社区，要同步到
		$this->createTable("umb_community", array(
				'id' => 'pk',
				'communityid' => 'char(25)',
				'user_id' => 'int NOT NULL',
				'begin_time' => 'timestamp',
				'end_time' => 'timestamp',
				'lng' => 'float',
				'lat' => 'float',
				'address' => 'varchar(200)',
				'umb10' => 'int',
				'umb20' => 'int',
				'umb30' => 'int',
				'umb50' => 'int',
				'type' => 'int', //1,仅取还，3支持预约，7支持外送
				'des'=>'varchar(200)'
		),
				'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		$this->dropTable("umb_community");
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