<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Transaction;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'customer_id' => 5,
        'transaction_reference' => $faker->slug(20),
        'nip_session' => $faker->slug(20),
        'amount' => $faker->randomNumber(5),
        'sender_account_number' => Str::random(10),
        'receiver_account_number' => Str::random(10),
        'narration' => Str::random(10),
        'transaction_type' => $faker->randomElement(['nip','local','bills']),
        'channel' => $faker->randomElement(['mobile','web']),
        'bank' => $faker->randomElement(['044','057']),
        'device' => $faker->randomElement(['jdbfbdfbfjkdf','jkbakbdjkbsdjbajkbd']),
        'status' => $faker->randomElement([0,1])
    ];
});
