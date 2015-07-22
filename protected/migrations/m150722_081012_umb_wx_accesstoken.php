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
		));
		$this->createIndex('appid', "umb_wxaccesstoken", 'appid');
		$this->insert('umb_wxaccesstoken', array('appid'=>'wxf6de13c469cfd0f0','appsecret'=>'137bac8913e64a3d1b2a3597f9a4bf0b','create_at'=>date("Y-m-d H:i:s",time())));
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