<?php

use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DocSeeder extends Seeder
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
        $user = User::create([
            'name' => $this->randomName(),
            'email' => $this->randomEmail(),
            'symptoms' => $symptoms[array_rand($symptoms)],
            'password' => bcrypt('12345'),
            'role_id' => 1,
            'email_verified_at' => now(),
            'remember_token' => Str::random(60),
            'gender' => 'male',
            'address' => $this->randomAddress(),
            'phone_number' => $this->randomPhoneNumber(),
            'department' => $this->randomDepartment(),
            'education' => $this->randomEducation(),
            'description' => 'this is description',
        ]);
    }

    private function randomName()
    {
        $names = ['John', 'Sarah', 'Mike', 'Emma', 'Robert', 'Olivia', 'Jacob', 'Sophia'];
        return $names[array_rand($names)];
    }

    private function randomEmail()
    {
        $domains = ['gmail.com', 'yahoo.com', 'hotmail.com'];
        return Str::random(10) . '@' . $domains[array_rand($domains)];
    }
    private function randomAddress()
    {
        $streets = ['Oak Street', 'Maple Avenue', 'Pine Road', 'Elm Drive'];
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston'];
        return $streets[array_rand($streets)] . ', ' . $cities[array_rand($cities)];
    }

    private function randomPhoneNumber()
    {
        return '555-' . strval(mt_rand(100, 999)) . '-' . strval(mt_rand(1000, 9999));
    }

    private function randomDepartment()
    {
        $departments = ['Cardiology', 'Dermatology', 'Neurology', 'Pediatrics', 'Radiology'];
        return $departments[array_rand($departments)];
    }
    private function randomEducation()
    {
        $degrees = ['MBBS', 'MD', 'MS', 'DM', 'MCh'];
        $fields = ['Cardiology', 'Dermatology', 'Neurology', 'Pediatrics', 'Radiology'];
        return $degrees[array_rand($degrees)] . ' in ' . $fields[array_rand($fields)];
    }
}
