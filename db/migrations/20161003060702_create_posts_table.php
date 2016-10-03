<?php

use Phinx\Migration\AbstractMigration;

class CreatePostsTable extends AbstractMigration
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
    public function change()
    {
      $table = $this->table('posts');
      $table->addColumn('title', 'string')
            ->addColumn('content', 'text')
            ->addColumn('user_id', 'integer')
            ->addColumn('created_at', 'timestamp', array('null' => true))
            ->addColumn('updated_at', 'timestamp', array('null' => true))
            ->addIndex('user_id')
            ->create();
    }
}
