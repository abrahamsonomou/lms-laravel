<?php

namespace Database\Seeders;

use App\Models\Etudiant\Etudiant;
use App\Models\Formateur\Formateur;
use App\Models\Rbac\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $comptes = [
            ['prenom' => 'Admin', 'nom' => 'LMS', 'email' => 'admin@lms.test', 'role' => 'SUPER_ADMIN'],
            ['prenom' => 'Fatou', 'nom' => 'Formatrice', 'email' => 'teacher@lms.test', 'role' => 'FORMATEUR'],
            ['prenom' => 'Amadou', 'nom' => 'Étudiant', 'email' => 'student@lms.test', 'role' => 'ETUDIANT'],
        ];

        foreach ($comptes as $compte) {
            $user = User::query()->updateOrCreate(
                ['email' => $compte['email']],
                [
                    'prenom' => $compte['prenom'],
                    'nom' => $compte['nom'],
                    'name' => "{$compte['prenom']} {$compte['nom']}",
                    'password' => Hash::make('password'),
                    'active' => true,
                ],
            );

            if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            $role = Role::query()->where('code', $compte['role'])->first();

            if ($role !== null) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }

            if ($compte['role'] === 'FORMATEUR') {
                Formateur::query()->updateOrCreate(['user_id' => $user->id], ['active' => true]);
            }

            if ($compte['role'] === 'ETUDIANT') {
                Etudiant::query()->updateOrCreate(['user_id' => $user->id], ['active' => true]);
            }
        }
    }
}
