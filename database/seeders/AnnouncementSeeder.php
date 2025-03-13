<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Mosque;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get mosques and admin users
        $mosques = Mosque::where('verification_status', 'verified')->take(2)->get();
        $adminUsers = User::where('role', 'mosque_admin')->take(2)->get();

        if ($mosques->isEmpty() || $adminUsers->isEmpty()) {
            $this->command->info('No verified mosques or admin users found. Skipping announcement seeding.');

            return;
        }

        // Create announcements for each mosque
        foreach ($mosques as $index => $mosque) {
            $adminUser = $adminUsers[$index % $adminUsers->count()];

            // Published general announcement
            Announcement::create([
                'mosque_id' => $mosque->id,
                'title' => 'Jadual Solat Jumaat',
                'content' => 'Dimaklumkan bahawa solat Jumaat akan diadakan pada jam 1:00 petang setiap minggu. Jemaah diminta hadir awal untuk mendengar khutbah.',
                'type' => 'general',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'created_by' => $adminUser->id,
            ]);

            // Published important announcement
            Announcement::create([
                'mosque_id' => $mosque->id,
                'title' => 'Program Iftar Ramadan',
                'content' => 'Masjid akan mengadakan program iftar sepanjang bulan Ramadan. Sumbangan dari jemaah amat dialu-alukan untuk menjayakan program ini.',
                'type' => 'important',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'expires_at' => now()->addDays(30),
                'created_by' => $adminUser->id,
            ]);

            // Draft announcement
            Announcement::create([
                'mosque_id' => $mosque->id,
                'title' => 'Gotong-Royong Perdana',
                'content' => 'Aktiviti gotong-royong akan diadakan pada hujung minggu ini. Semua ahli kariah dijemput untuk sama-sama membersihkan kawasan masjid.',
                'type' => 'general',
                'status' => 'draft',
                'created_by' => $adminUser->id,
            ]);

            // Emergency announcement
            Announcement::create([
                'mosque_id' => $mosque->id,
                'title' => 'Penutupan Sementara Masjid',
                'content' => 'Masjid akan ditutup sementara pada 15 April 2025 untuk kerja-kerja penyelenggaraan. Solat Jumaat akan diadakan di dewan komuniti berdekatan.',
                'type' => 'emergency',
                'status' => 'published',
                'published_at' => now()->subDay(),
                'expires_at' => now()->addDays(15),
                'created_by' => $adminUser->id,
            ]);

            // Expired announcement
            Announcement::create([
                'mosque_id' => $mosque->id,
                'title' => 'Ceramah Maulidur Rasul',
                'content' => 'Ceramah sempena Maulidur Rasul akan diadakan pada 12 Mac 2025. Penceramah jemputan ialah Ustaz Ahmad bin Abdullah.',
                'type' => 'general',
                'status' => 'published',
                'published_at' => now()->subDays(30),
                'expires_at' => now()->subDays(15),
                'created_by' => $adminUser->id,
            ]);
        }
    }
}
