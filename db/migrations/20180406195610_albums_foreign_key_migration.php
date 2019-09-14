<?php


use Phinx\Migration\AbstractMigration;

class AlbumsForeignKeyMigration extends AbstractMigration
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
        $table = $this->table('images');
        $table->addColumn('album_id', 'integer', ['null' => true])
            ->addForeignKey('album_id', 'albums', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])->save();
    }
}
