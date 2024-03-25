<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $symptoms = [
            'Runny nose, cough, sneezing',
            'Fever, chills, headache',
            'Body aches, cough',
            'Itchy eyes, sneezing',
            'Nausea, abdominal pain, diarrhea',
            'Throbbing pain, sensitivity to light',
            'Pimples, blackheads, whiteheads'
        ];

        $user=User::create([
                'name'=> Str::random(10),
                'email'=> Str::random(10).'@gmail.com',
                'symptoms'=>$symptoms[array_rand($symptoms)],
                'password'=>bcrypt('12345'),
                'role_id'=>3,
                'email_verified_at'=>now(),
                'remember_token'=>Str::random(60),
                'gender'=>'male',
        ]);
        $user->save();
    }
      
}
