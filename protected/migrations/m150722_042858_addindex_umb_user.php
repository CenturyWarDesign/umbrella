<?php

class m150722_042858_addindex_umb_user extends CDbMigration
{
	public function up()
	{
		$this->createIndex('udid', 'umb_user', 'udid',true);
	}

	public function down()
	{
		$this->dropIndex('udid', 'umb_user');
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