<?php


use Phinx\Migration\AbstractMigration;

class VolountieerMigration extends AbstractMigration
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
        $table = $this->table('volountieer');
        $table->addColumn('ime_prezime', 'text')
            ->addColumn('datum', 'date')
            ->addColumn('adresa', 'text', ['null' => true])
            ->addColumn('grad', 'text', ['null' => true])
            ->addColumn('telefon', 'integer', ['null' => true])
            ->addColumn('email', 'text', ['null' => true])
            ->addColumn('str_sprema', 'text')
            ->addColumn('zanimanje', 'text', ['null' => true])
            ->addColumn('hobi', 'text')
            ->addColumn('iskustvo', 'text')
            //checkbox
            ->addColumn('podrucje_rada', 'text')
            ->addColumn('poslovi', 'text')
            ->addColumn('nedeljni_sati', 'text')
            ->addColumn('vreme', 'text')
            ->addColumn('dodatna_obuka', 'text')
            ->create();
    }
}
