<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_id = $this->addCmsRoles();
        $this->addCmsUser($role_id);
        $this->addCmsModules();
        $this->mailTemplates();
        $this->user_group();
    }

    public function addCmsRoles()
    {
        $role_id = \DB::table('cms_roles')->insertGetId([
            'name'           => 'Super Admin',
            'slug'           => Str::slug('Super Admin'),
            'is_super_admin' => '1',
            'created_at'     => Carbon::now()
        ]);
        \DB::table('cms_roles')->insertGetId([
            'name'           => 'agent',
            'slug'           => Str::slug('agent'),
            'is_super_admin' => '0', 
            'created_at'     => Carbon::now() 
        ]); 
        \DB::table('cms_roles')->insertGetId([ 
            'name'           => 'admin',
            'slug'           => Str::slug('admin'),
            'is_super_admin' => '0',
            'created_at'     => Carbon::now()
        ]);
        return $role_id;
    }

    public function addCmsUser($role_id)
    {
        \DB::table('cms_users')->insert([
            'cms_role_id' => '1',
            'name'        => 'RetroCube',
            'username'    => 'retrocube',
            'slug'        => 'retrocube',
            'email'       => 'admin@retrocube.com',
            'mobile_no'   => '1-8882051816',
            'password'    => Hash::make('admin@123$'),
            'created_at'  => Carbon::now(),
            'is_email_verify'=> '1',
        ]);

        \DB::table('cms_users')->insert([
            'cms_role_id' => '3',
            'name'        => 'super admin',
            'username'    => 'superadmin',
            'slug'        => 'super-admin',
            'email'       => 'realtyadmin@retrocube.com',
            'mobile_no'   => '1-8882051817',
            'password'    => Hash::make('admin@123$'),
            'created_at'  => Carbon::now(),
            'is_email_verify'=> '1',
        ]);
    }

    public function addCmsModules()
    {
        $data = [
            [
                'parent_id'    => 0,
                'name'         => 'Cms Roles Management',
                'route_name'   => 'cms-roles-management.index',
                'icon'         => 'fa fa-key',
                'status'       => '1',
                'sort_order'   => 1,
                'created_at'   => Carbon::now()
            ],
            [
                'parent_id'    => 0,
                'name'         => 'Cms Users Management',
                'route_name'   => 'cms-users-management.index',
                'icon'         => 'fa fa-users',
                'status'       => '1',
                'sort_order'   => 2,
                'created_at'   => Carbon::now()
            ],
            [
                'parent_id'    => 0,
                'name'         => 'Application Setting',
                'route_name'   => 'admin.application-setting',
                'icon'         => 'fa fa-cog',
                'status'       => '1',
                'sort_order'   => 3,
                'created_at'   => Carbon::now()
            ],
            [
                'parent_id'    => 0,
                'name'         => 'Users Management',
                'route_name'   => 'app-users.index',
                'icon'         => 'fa fa-users',
                'status'       => '1',
                'sort_order'   => 4,
                'created_at'   => Carbon::now()
            ],
            [
                'parent_id'    => 0,
                'name'         => 'Property Management',
                'route_name'   => 'admin-property.index',
                'icon'         => 'fa fa-list',
                'status'       => '1',
                'sort_order'   => 5,
                'created_at'   => Carbon::now()
            ],
            [
                'parent_id'    => 0,
                'name'         => 'Customer Management',
                'route_name'   => 'admin-customer.index',
                'icon'         => 'fa fa-list',
                'status'       => '1',
                'sort_order'   => 5,
                'created_at'   => Carbon::now()
            ],
            [
                'parent_id'    => 0,
                'name'         => 'Faqs Management',
                'route_name'   => 'admin-faqs.index',
                'icon'         => 'fa fa-list',
                'status'       => '1',
                'sort_order'   => 5,
                'created_at'   => Carbon::now()
            ],
            [
                'parent_id'    => 0,
                'name'         => 'App Content',
                'route_name'   => 'admin-app-content.index',
                'icon'         => 'fa fa-list',
                'status'       => '1',
                'sort_order'   => 6,
                'created_at'   => Carbon::now()
            ],
        ];
        \DB::table('cms_modules')->insert($data);
    }

    public function mailTemplates()
    {
        $forgot_password_template = file_get_contents(__DIR__ . '/forgot_password_template_body.blade.php',FILE_USE_INCLUDE_PATH);
        $user_register_template   =  file_get_contents(__DIR__ . '/user_register_template_body.blade.php',FILE_USE_INCLUDE_PATH);
        $customer_invite_template =  file_get_contents(__DIR__ . '/customer_invite_template_body.blade.php',FILE_USE_INCLUDE_PATH);
		$data =
            [
                [
                    'identifier' => 'forgot-password',
                    'subject'    => 'Forgot Password Confirmation',
                    'body'       => $forgot_password_template,
                    'wildcard'   => '[USERNAME],[LINK],[YEAR],[APP_NAME]',
                    'created_at' => Carbon::now()
                ],
                [
                    'identifier' => 'user_registration',
                    'subject'    => 'Welcome to [APP_NAME]',
                    'body'       => $user_register_template,
                    'wildcard'   => '[USERNAME],[LINK],[YEAR],[APP_NAME]',
                    'created_at' => Carbon::now()
                ],
				[
                    'identifier' => 'customer_invite',
                    'subject'    => 'Welcome to [APP_NAME]',
                    'body'       => $customer_invite_template,
                    'wildcard'   => '[NAME],[AGENT_NAME],[EMAIL],[PASSWORD],[APP_NAME],[VERIFY_LINK]',
                    'created_at' => Carbon::now()
                ]
            ];
        \DB::table('mail_templates')->insert($data);
    }

    public function user_group()
    {
        \DB::table('user_groups')
            ->insert([
                [
                    'title' => 'Agent',
                    'slug'  => 'agent',
                    'status'=> '1',
                    'created_at' => Carbon::now()
                ],
                [
                    'title' => 'Customer',
                    'slug'  => 'customer',
                    'status'=> '1',
                    'created_at' => Carbon::now()
                ]
            ]);
    }
}
