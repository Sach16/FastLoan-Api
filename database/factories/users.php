<?php

use Faker\Generator;
use Whatsloan\Repositories\Designations\Designation;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Users\TrackUser;

$factory->define(User::class, function (Generator $faker) {
    $roles = [
        'DSA_OWNER',
        'DSA_MEMBER',
        'SUPER_ADMIN',
        'CONSUMER',
        'REFERRAL',
    ];

    $settings = [
        'resident_status' => '',
        'profession'      => '',
        'dob'             => $faker->dateTimeThisCentury->format('Y-m-d'),
        'age'             => $faker->numberBetween(1, 60),
        'gender'          => $faker->randomElement(['Male','Female']),
        'education'       => '',
        'marital_status'  => '',
        'company'         => '',
        'DOJ'             => $faker->date($format = 'Y-m-d', $max = 'now'),
        'exp_on_DOJ'      => $faker->numberBetween(1,20).'years',
        'net_income'      => '',
        'pan'             => '',
        'salary_bank'     => '',
        'skype'           => '',
        'facetime'        => '',
        'contact_time'    => '',
        'cibil_score'     => '',
        'cibil_status'    => '',
        'joined_as'       => $faker->word,
    ];

    return [
        'uuid'           => $faker->uuid,
        'first_name'     => $faker->lastName,
        'last_name'      => $faker->firstName,
        'email'          => $faker->email,
        'phone'          => $faker->unique()->e164PhoneNumber,
        'role'           => $faker->randomElement($roles),
        //'designation_id' => Designation::orderByRaw("RAND()")->first()->id,
        'designation_id' => $faker->randomElement(Designation::take(10)->get()->lists('id')->all()),
        'track_status' => $faker->randomElement(["TRUE", "FALSE"]),
        'settings'       => $settings,
        'api_token'      => str_random(60),
        'password'       => bcrypt('Qwerty123'),
        'remember_token' => str_random(10),
    ];
});
