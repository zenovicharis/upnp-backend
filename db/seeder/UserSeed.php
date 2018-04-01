<?php


use Phinx\Seed\AbstractSeed;

class UserSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                "name" => "Admin",
                "email" => "admin@upnp.org",
                "password" => password_hash("asd", PASSWORD_DEFAULT)
            ]
        ];
        $user = $this->table('user');
        $user->insert($data)
            ->save();
    }
}
