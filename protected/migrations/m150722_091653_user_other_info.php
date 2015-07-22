<?php

class m150722_091653_user_other_info extends CDbMigration
{
	public function up()
	{
		$this->addColumn("umb_user", 'nickname', 'varchar(50)');
		$this->addColumn("umb_user", 'sex', 'int');
		$this->addColumn("umb_user", 'language', 'varchar(20)');
		$this->addColumn("umb_user", 'city', 'varchar(20)');
		$this->addColumn("umb_user", 'province', 'varchar(20)');
		$this->addColumn("umb_user", 'country', 'varchar(20)');
		$this->addColumn("umb_user", 'headimgurl', 'varchar(200)');
		$this->addColumn("umb_user", 'remark', 'varchar(100)');
		$this->addColumn("umb_user", 'subscribe_time', 'int');
		$this->addColumn("umb_user", 'groupid', 'int');
	}

	public function down()
	{
		$this->dropColumn("umb_user", 'nickname');
		$this->dropColumn("umb_user", 'sex');
		$this->dropColumn("umb_user", 'language');
		$this->dropColumn("umb_user", 'city');
		$this->dropColumn("umb_user", 'province');
		$this->dropColumn("umb_user", 'country');
		$this->dropColumn("umb_user", 'headimgurl');
		$this->dropColumn("umb_user", 'remark');
		$this->dropColumn("umb_user", 'subscribe_time');
		$this->dropColumn("umb_user", 'groupid');
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