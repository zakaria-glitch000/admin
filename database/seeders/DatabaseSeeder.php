<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. استدعاء سيديّز الصلاحيات والأدوار ديال Spatie أولاً
        $this->call([
            PermissionTableSeeder::class,
            AdminSeeder::class,
        ]);

        // 2. Ticket Statuses (بدون statut Fermé, و ترتيب الأ IDs من 1 حتى 4)
        $statuses = [
            ['id' => 1, 'nom' => 'Nouveau', 'couleur' => 'Jaune', 'ordre' => 1, 'est_final' => false],
            ['id' => 2, 'nom' => 'En cours', 'couleur' => 'Orange', 'ordre' => 2, 'est_final' => false],
            ['id' => 3, 'nom' => 'Traité', 'couleur' => 'Vert', 'ordre' => 3, 'est_final' => true],
            ['id' => 4, 'nom' => 'Abondonneé', 'couleur' => 'Rouge', 'ordre' => 4, 'est_final' => true],
        ];
        DB::table('ticket_statuses')->insertOrIgnore($statuses);

        // 3. Ticket Priorities (Normale, Urgent بالأحمر, Sans délai)
        $priorities = [
            ['id' => 1, 'nom' => 'Normale', 'couleur' => 'Bleu Ciel', 'delai_sla_heures' => 48],
            ['id' => 2, 'nom' => 'Urgent', 'couleur' => 'Rouge', 'delai_sla_heures' => 12],
            ['id' => 3, 'nom' => 'Sans délai', 'couleur' => 'Gris', 'delai_sla_heures' => 0],
        ];
        DB::table('ticket_priorities')->insertOrIgnore($priorities);

        // 4. Ticket Categories (الجديدة)
        $ticketCategories = [
            ['id' => 1, 'nom' => 'Software'],
            ['id' => 2, 'nom' => 'Hardware'],
            ['id' => 3, 'nom' => 'Reseau'],
            ['id' => 4, 'nom' => 'Autre'],
            ['id' => 5, 'nom' => 'Hard/Soft'],
        ];
        DB::table('ticket_categories')->insertOrIgnore($ticketCategories);

        // 5. Machine Categories
        $machineCategories = [
            ['id' => 1, 'nom' => 'Tpv', 'slug' => 'tpv'],
            ['id' => 2, 'nom' => 'Imprimante', 'slug' => 'imprimante'],
            ['id' => 3, 'nom' => 'Lecteur code barre', 'slug' => 'lecteur-code-barre'],
            ['id' => 4, 'nom' => 'Imprimante ticket', 'slug' => 'imprimante-ticket'],
            ['id' => 5, 'nom' => 'Imprimante cab', 'slug' => 'imprimante-cab'],
            ['id' => 6, 'nom' => 'Balance', 'slug' => 'balance'],
            ['id' => 7, 'nom' => 'Tirroir', 'slug' => 'tirroir'],
        ];
        DB::table('machine_categories')->insertOrIgnore($machineCategories);
    }
}