<?php

class m150723_104556_wx_add_ticket extends CDbMigration
{
	public function up()
	{
		$this->addColumn("umb_wxaccesstoken", 'ticket', "string");
		$this->addColumn("umb_wxaccesstoken", 'ticket_expire_at', "int");
	}

	public function down()
	{
		$this->dropColumn("umb_wxaccesstoken", 'ticket');
		$this->dropColumn("umb_wxaccesstoken", 'ticket_expire_at');
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