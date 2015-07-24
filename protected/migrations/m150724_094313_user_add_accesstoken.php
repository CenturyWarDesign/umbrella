<?php

class m150724_094313_user_add_accesstoken extends CDbMigration
{
	public function up()
	{
		$this->addColumn("umb_user", "accesstoken", "varchar(200)");
		$this->addColumn("umb_user", "refresh_token", "varchar(200)");
		$this->addColumn("umb_user", "expires_in", "int");
		$this->addColumn("umb_user", "token_refreshed", "bool");
	}

	public function down()
	{
		$this->dropColumn("umb_user", "accesstoken");
		$this->dropColumn("umb_user", "refresh_token");
		$this->dropColumn("umb_user", "expires_in");
		$this->dropColumn("umb_user", "token_refreshed");
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