<?php

use App\Models\User\User;
use App\Models\Auth\Role;
use App\Models\Product\Product;
use App\Models\Product\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'yunhanshi33@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $adminRole = Role::findByName(\App\Models\Auth\Acl::ROLE_ADMIN);

        $admin->syncRoles($adminRole);

        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('product_category')->truncate();
        for($i=1; $i<=50; $i++) {
            Product::create([
                'name' => Str::random(10),
                'price' => mt_rand(1 , 1000),
                'sort_order' => mt_rand(1 , 1000),
            ]);
            Category::create([
                'name' => Str::random(10),
                'sort_order' => mt_rand(1 , 1000),
            ]);
        }
        $datas = [];
        for($i=1; $i<=50; $i++) {
            $p_id = $i;
            $c_ids = array_rand(range(1, 50),mt_rand(1 , 3));
            if (is_int($c_ids)) {
                $c_ids = [$c_ids];
            }
            foreach ($c_ids as $cid) {
                $datas[] = [
                    'product_id' =>  $p_id,
                    'category_id' =>  $cid,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ];
            }
        }
        DB::table('product_category')->insert($datas);
    }
}
