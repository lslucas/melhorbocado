<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;

class PermissionsTableSeeder extends Seeder{

    public function run()
    {
        if (App::environment() === 'production') {
            exit('I just stopped you getting fired. Love, ;)');
        }

        //DB::table('permissions')->truncate();

        $roleAdmin = Role::find(1);
        $roleImobiliarias = Role::find(2);
        $roleCorretores = Role::find(3);
        $roleUsuarios = Role::find(4);

        // Imóveis
        // ///////
        $permission = Permission::create([
            'name'          => 'Ver imóveis',
            'slug'          => 'ver.imoveis',
            'description'   => '',
            'model'         => 'App\RealEstate'
        ]);
        $roleUsuarios->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Criar imóveis',
            'slug'          => 'criar.imoveis',
            'description'   => '',
            'model'         => 'App\RealEstate'
        ]);
        $roleCorretores->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Editar imóveis',
            'slug'          => 'editar.imoveis',
            'description'   => '',
            'model'         => 'App\RealEstate'
        ]);
        $roleCorretores->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Status imóveis',
            'slug'          => 'status.imoveis',
            'description'   => 'Permite alterar status do item',
            'model'         => 'App\RealEstate'
        ]);
        $roleImobiliarias->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Apagar imóveis',
            'slug'          => 'apagar.imoveis',
            'description'   => '',
            'model'         => 'App\RealEstate'
        ]);
        $roleImobiliarias->attachPermission($permission);

        // Usuários
        // ////////
        $permission = Permission::create([
            'name'          => 'Ver usuários',
            'slug'          => 'ver.usuarios',
            'description'   => '',
            'model'         => 'App\User'
        ]);
        $roleUsuarios->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Editar usuários',
            'slug'          => 'editar.usuarios',
            'description'   => '',
            'model'         => 'App\User'
        ]);
        $roleCorretores->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Criar usuários',
            'slug'          => 'criar.usuarios',
            'description'   => '',
            'model'         => 'App\User'
        ]);
        $roleImobiliarias->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Status usuários',
            'slug'          => 'status.usuarios',
            'description'   => 'Permite alterar status do item',
            'model'         => 'App\User'
        ]);
        $roleImobiliarias->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Apagar usuarios',
            'slug'          => 'apagar.usuarios',
            'description'   => '',
            'model'         => 'App\User'
        ]);
        $roleImobiliarias->attachPermission($permission);

        // Imobiliárias
        // ////////
        $permission = Permission::create([
            'name'          => 'Ver imobiliárias',
            'slug'          => 'ver.imobiliarias',
            'description'   => '',
            'model'         => 'App\Estate'
        ]);
        $roleAdmin->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Editar imobiliárias',
            'slug'          => 'editar.imobiliarias',
            'description'   => '',
            'model'         => 'App\Estate'
        ]);
        $roleAdmin->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Criar imobiliárias',
            'slug'          => 'criar.imobiliarias',
            'description'   => '',
            'model'         => 'App\Estate'
        ]);
        $roleAdmin->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Status imobiliárias',
            'slug'          => 'status.imobiliarias',
            'description'   => 'Permite alterar status do item',
            'model'         => 'App\Estate'
        ]);
        $roleAdmin->attachPermission($permission);

        $permission = Permission::create([
            'name'          => 'Apagar imobiliarias',
            'slug'          => 'apagar.imobiliarias',
            'description'   => '',
            'model'         => 'App\Estate'
        ]);
        $roleAdmin->attachPermission($permission);

    }

}
