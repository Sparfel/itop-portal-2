<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class LinkAdminToItop extends Command
{
    protected $signature = 'itop:link-admin {--instance=0 : The iTop instance to use (e.g. 0, 1)}';
    protected $description = 'Link a Laravel admin account to an iTop Contact by email';

    public function handle()
    {
        $instance = (int) $this->option('instance');
        $prefix = "ITOP_{$instance}_";

        $itopUrl = env("{$prefix}URL");
        $itopProtocol = env("{$prefix}PROTOCOL", 'https');
        $itopUser = env("{$prefix}USER");
        $itopPassword = env("{$prefix}PASSWORD");

        if (!$itopUrl || !$itopUser || !$itopPassword) {
            $this->error("Missing configuration for iTop instance #$instance.");
            return;
        }

        $apiUrl = "$itopProtocol://$itopUrl/webservices/rest.php";

        $email = $this->ask('Enter the Laravel admin email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No Laravel user found with email: $email.");
            return;
        }

        $itopEmail = $this->ask("Enter the iTop contact email (leave blank to use the same email)");
        if (empty($itopEmail)) {
            $itopEmail = $email;
        }

        $this->info("Searching for iTop contact with email: $itopEmail...");

        $response = Http::post($apiUrl, [
            'operation' => 'core/get',
            'class' => 'Contact',
            'key' => "email = '$itopEmail'",
            'output_fields' => 'id, name, email',
            'auth_user' => $itopUser,
            'auth_pwd' => $itopPassword,
        ]);

        $data = $response->json();

        if (!isset($data['objects']) || empty($data['objects'])) {
            $this->error("No iTop contact found with this email.");
            return;
        }

        $objects = $data['objects'];
        $chosenId = null;

        if (count($objects) > 1) {
            $this->warn("Multiple contacts found. Please choose the correct one:");

            foreach ($objects as $object) {
                $id = $object['key'];
                $fields = $object['fields'];
                $this->line("[$id] {$fields['name']} <{$fields['email']}>");
            }

            $chosenId = $this->ask("Enter the iTop Contact ID to link");
        } else {
            $chosenId = array_key_first($objects);
        }

        $other = User::where('itop_id', $chosenId)->where('id', '!=', $user->id)->first();
        if ($other) {
            $this->error("❌ This iTop ID ($chosenId) is already linked to user: {$other->email}");
            return;
        }

        $user->itop_id = $chosenId;
        $user->itop_cfg = $instance; // ← Important si tu gères plusieurs sources
        $user->save();

        $this->info("✅ Successfully linked Laravel user to iTop Contact #$chosenId (Instance #$instance).");
    }
}
