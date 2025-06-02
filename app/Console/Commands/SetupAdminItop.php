<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class SetupAdminItop extends Command
{
    protected $signature = 'itop:setup-admin {--instance=0 : The iTop instance to use (e.g. 0, 1)}';
    protected $description = 'Create the initial admin user and link it to an iTop contact';

    public function handle(): int
    {
        $instanceIndex = $this->option('instance');
        $config = config("itop.{$instanceIndex}");

        if (!$config) {
            $this->error("iTop instance #{$instanceIndex} not found in config/itop.php.");
            return Command::FAILURE;
        }

        $protocol = $config['protocol'] ?? 'https';
        $url = $config['url'];
        $username = $config['user'];
        $password = $config['password'];
        $debug = $config['debug'] ?? false;

        $apiUrl = "{$protocol}://{$url}/webservices/rest.php?version=1.4";
        $this->info("Using iTop instance #{$instanceIndex} at {$apiUrl}");

        // Ask for admin contact email
        $email = $this->ask('Enter the iTop admin contact email');

        $this->info("Searching for iTop contact with email: {$email} ...");

        // Perform iTop REST call
        $response = Http::withOptions([
            'verify' => false,
        ])->asForm()->post($apiUrl, [
            'auth_user' => $username,
            'auth_pwd'  => $password,
            'json_data' => json_encode([
                'operation'     => 'core/get',
                'class'         => 'Person',
                'key'           => "SELECT Person WHERE email='$email'",
                'output_fields' => 'id, first_name, name, email',
            ]),
        ]);


        if (!$response->ok()) {
            $this->error("HTTP request failed: {$response->status()}");
            return Command::FAILURE;
        }

        $data = $response->json();

        if (!isset($data['objects']) || empty($data['objects'])) {
            $this->error("No contact found in iTop for email: {$email}");
            return Command::FAILURE;
        }

        $contact = reset($data['objects']);
        $itop_id = $contact['key'];
        $fields = $contact['fields'];

        $this->info("Found contact: {$fields['first_name']} {$fields['name']} (iTop ID: {$itop_id})");

        // Create admin user in portal DB
        $passwordPortal = $this->secret('Set a password for this admin user (portal login)');

        if (User::where('email', $email)->exists()) {
            $this->warn("A user with email {$email} already exists in the portal. Skipping creation.");
            return Command::SUCCESS;
        }

        $user = User::create([
            'name' => $fields['first_name'] . ' ' . $fields['name'],
            'first_name' => $fields['first_name'],
            'last_name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($passwordPortal),
            'itop_id' => $itop_id,
            'itop_cfg' => $instanceIndex,
            'is_active' => 1,
            'is_staff' => 1,
        ]);


        if (!$user->hasRole('Administrator')) {
            $user->assignRole('Administrator');
        }
        $this->info("User {$user->email} linked to iTop contact #{$itop_id} and granted Administrator role.");
        $this->line("Portal ID: {$user->id} — iTop ID: {$itop_id}");

        return Command::SUCCESS;
    }
}
