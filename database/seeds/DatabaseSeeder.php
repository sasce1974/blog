<?php

use App\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('roles')->insert([
            ['name'=>'Admin'], ['name'=>'Moderator'], ['name'=>'Author']
        ]);

        DB::table('users')->insert([
            'name'=>'Admin',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'role_id' => 1,
            'remember_token' => Str::random(10),
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now(),
        ]);

        $this->command->info("Admin user created. \nUse username: 'admin@email.com' and password:'password' to log in as admin user!");

        DB::table('categories')->insert([
            ['name'=>'IT'],
            ['name'=>'WEB Design'],
            ['name'=>'Beauty'],
            ['name'=>'Sport'],
            ['name'=>'Travel'],
            ['name'=>'Hobby'],
            ['name'=>'Other']
        ]);

        factory(App\User::class, 20)->create()
            ->each(function ($u){
                $u->posts()->saveMany(factory(\App\Post::class, rand(0,5))->make())
                    ->each(function ($p){
                        $p->comments()->saveMany(factory(\App\Comment::class, rand(0,10))->make());
                    });
            });

        foreach (Post::all() as $post){
            $numbers = [];
            for($i=0; $i< rand(1,5); $i++){
                $numbers[] = rand(1,7);
            }
            $post->categories()->attach($numbers);
        }

        $this->command->info('Database seeded! All created users have password:password');
    }
}
