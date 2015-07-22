<?php

class m150722_081012_umb_wx_accesstoken extends CDbMigration
{
	public function up()
	{
		$this->createTable("umb_wxaccesstoken", array(
				'id' => 'pk',
				'appid' => 'varchar(50)',
				'appsecret' => 'varchar(50)',
				'accesstoken' => 'string NOT NULL',
				'create_at' => 'timestamp',
				'expire_at' => 'int'
		),'ENGINE=InnoDB DEFAULT CHARSET=utf8');
		$this->createIndex('appid', "umb_wxaccesstoken", 'appid');
	}

	public function down()
	{
		$this->dropTable("umb_wxaccesstoken");
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