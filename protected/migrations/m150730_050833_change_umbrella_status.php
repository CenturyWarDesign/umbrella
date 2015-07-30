<?php

class m150730_050833_change_umbrella_status extends CDbMigration
{
	public function up()
	{
		$this->alterColumn("umb_umbrella", 'status', 'int default 0');
		$this->update("umb_umbrella", array('status'=>0),"");
		
	}

	public function down()
	{
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