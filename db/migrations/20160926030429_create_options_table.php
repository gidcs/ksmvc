<?php

use Phinx\Migration\AbstractMigration;

class CreateOptionsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
      $table = $this->table('options');
      $table->addColumn('name','string',array('limit' => 255))
        ->addColumn('value','string',array('limit' => 255))
        ->addColumn('created_at', 'timestamp', array('null' => true))
        ->addColumn('updated_at', 'timestamp', array('null' => true))
        ->addIndex('name', array('unique' => true))
        ->create();

      //insert data from config.php
      require('config/config.php');
      require('app/database.php');
      if(!empty($app_info)){
        foreach($app_info as $k=>$v){
          if(strcmp($k,'smtp')==0){
            foreach($v as $kk=>$vv){
              Option::create([ 
                'name' => $k."_".$kk,
                'value' => $vv,
              ]);
            }
          }
          else{
            Option::create([
              'name' => $k,
              'value' => $v
            ]);
          }
        }
      }
    }

    public function down(){
      $this->dropTable('options');
    }
}
