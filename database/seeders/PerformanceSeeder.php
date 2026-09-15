<?php

namespace Database\Seeders;

use App\Models\AgendaItem;
use App\Models\Demand;
use App\Models\FieldActivity;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Voter;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerformanceSeeder extends Seeder
{
    public function run(): void
    {
        // Desativa activity log durante o seed (muito mais rápido)
        activity()->disableLogging();

        $this->command->info('Creating base user and workspace...');

        $user = User::factory()->create([
            'name' => 'Performance Admin',
            'email' => 'perf@campaignflow.test',
            'password' => Hash::make('password'),
        ]);

        $workspace = Workspace::factory()->create([
            'name' => 'Performance Workspace',
            'owner_id' => $user->id,
            'type' => 'campaign',
            'status' => 'active',
        ]);

        $workspace->users()->attach($user->id, [
            'role' => 'admin',
            'is_primary' => true,
        ]);

        // Teams
        $this->command->info('Creating teams...');
        $teams = Team::factory()
            ->count(20)
            ->create(['workspace_id' => $workspace->id]);

        // Voters em lotes (evita estourar memória)
        $this->command->info('Creating 50.000 voters...');
        $statuses = ['supporter', 'undecided', 'opponent', 'unknown'];
        $origins = ['door_to_door', 'event', 'social', 'import', 'referral'];

        $bar = $this->command->getOutput()->createProgressBar(50);
        $bar->start();

        for ($i = 0; $i < 50; $i++) {
            $rows = [];
            $now = now();

            for ($j = 0; $j < 1000; $j++) {
                $rows[] = [
                    'workspace_id' => $workspace->id,
                    'name' => fake()->name(),
                    'cpf' => sprintf('%011d', ($i * 1000) + $j), // único por workspace
                    'phone' => fake()->numerify('(##) #####-####'),
                    'email' => fake()->unique()->safeEmail(),
                    'neighborhood' => fake()->randomElement(['Centro', 'Norte', 'Sul', 'Leste', 'Oeste']),
                    'city' => 'Bologna',
                    'state' => 'BO',
                    'status' => fake()->randomElement($statuses),
                    'origin' => fake()->randomElement($origins),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // insert em batch = muito mais rápido que factory()->create()
            DB::table('voters')->insert($rows);
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();

        // Field activities
        $this->command->info('Creating 5.000 field activities...');
        FieldActivity::factory()
            ->count(5000)
            ->create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'team_id' => $teams->random()->id,
            ]);

        // Demands
        $this->command->info('Creating 2.000 demands...');
        Demand::factory()
            ->count(2000)
            ->create([
                'workspace_id' => $workspace->id,
                'created_by' => $user->id,
            ]);

        // Transactions
        $this->command->info('Creating 1.000 transactions...');
        Transaction::factory()
            ->count(1000)
            ->create([
                'workspace_id' => $workspace->id,
                'created_by' => $user->id,
            ]);

        // Agenda
        $this->command->info('Creating 500 agenda items...');
        AgendaItem::factory()
            ->count(500)
            ->create([
                'workspace_id' => $workspace->id,
                'created_by' => $user->id,
            ]);

        activity()->enableLogging();

        $this->command->info('Done!');
        $this->command->info("Workspace ID: {$workspace->id}");
        $this->command->info('Login: perf@campaignflow.test / password');
    }
}
