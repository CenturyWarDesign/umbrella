<?php

class m150722_073547_umb_user_add_times extends CDbMigration
{
	public function up()
	{
		$this->addColumn("umb_user", 'times', 'int default 0');
	}

	public function down()
	{
		$this->dropColumn("umb_user", 'times');
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