<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Bican\Roles\Models\Role;

class RoleTableSeeder extends Seeder{

    public function run()
    {
        if (App::environment() === 'production') {
            exit('I just stopped you getting fired. Love, Amo.');
        }

        //DB::table('roles')->truncate();
        Role::create([
            'id'            => 1,
            'name'          => 'Admin',
            'slug'          => 'admin',
            'description'   => 'Use this account with extreme caution. When using this account it is possible to cause irreversible damage to the system.',
            'level'         => 4
        ]);

        Role::create([
            'id'            => 2,
            'name'          => 'Imobiliárias',
            'slug'          => 'imobiliarias',
            'description'   => 'Pode cadastrar imóveis/editar e criar sub-administradores (corretores)',
            'level'         => 3
        ]);

        Role::create([
            'id'            => 3,
            'name'          => 'Corretores de Imóveis',
            'slug'          => 'corretores',
            'description'   => 'São os funcionários das imobiliárias, podem cadastrar imóveis próprios e editar os mesmos',
            'level'         => 2
        ]);

        Role::create([
            'id'            => 4,
            'name'          => 'Usuários',
            'slug'          => 'usuarios',
            'description'   => 'Podem visualizar os imóveis e interagir na visualização de páginas no front-end apenas',
            'level'         => 1
        ]);
    }

}
