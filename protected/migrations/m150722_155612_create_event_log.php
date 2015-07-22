<?php

class m150722_155612_create_event_log extends CDbMigration
{
	public function up()
	{
		$this->createTable("umb_event_log", array(
				'id' => 'pk',
				'openid' => 'varchar(50)',
				'createtime' => 'int',
				'event' => 'varchar(20)',
				'eventkey' => 'varchar(200)',
				'ticket' => 'varchar(200)',
				'latitude' => 'float',
				'longitude' => 'float',
				'precision' => 'float'
		),
				'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		$this->dropTable("umb_event_log");
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