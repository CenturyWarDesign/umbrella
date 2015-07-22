<?php

class m150722_102601_message_log extends CDbMigration
{
	public function up()
	{
			$this->createTable("umb_message_log", array(
				'id' => 'pk',
				'openid' => 'varchar(50)',
				'createtime' => 'int',
				'msgtype' => 'char(10)',
				'content' => 'string',
				'msgid' => 'BIGINT',
				'picurl' => 'varchar(200)',
				'mediaid' => 'varchar(200)',
				'format' => 'char(10)',
				'recognition' => 'string',
				'thumbmediaid' => 'varchar(200)',
				'location_x' => 'float',
				'location_y' => 'float',
				'scale' => 'float',
				'lable' => 'varchar(200)',
				'title' => 'varchar(200)',
				'description' => 'varchar(200)',
				'url' => 'varchar(200)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		$this->dropTable("umb_message_log");
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