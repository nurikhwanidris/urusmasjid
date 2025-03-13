<?php

namespace Database\Seeders;

use App\Models\Mosque;
use App\Models\User;
use App\Models\Event;
use App\Models\MosqueUser;
use App\Models\CommunityMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // System Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@urusmasjid.my',
            'ic_number' => '123456789012',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone_number' => '0162614844',
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);

        // Create Mosque Admins
        $mosqueAdmin1 = User::create([
            'name' => 'Mosque Admin 1',
            'email' => 'mosque1@urusmasjid.my',
            'ic_number' => '890123456789',
            'password' => Hash::make('password'),
            'role' => 'mosque_admin',
            'phone_number' => '0123456789',
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);

        $mosqueAdmin2 = User::create([
            'name' => 'Mosque Admin 2',
            'email' => 'mosque2@urusmasjid.my',
            'ic_number' => '901234567890',
            'password' => Hash::make('password'),
            'role' => 'mosque_admin',
            'phone_number' => '0123456788',
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);

        // Create Community Members
        $communityMember1 = User::create([
            'name' => 'Community Member 1',
            'email' => 'member1@urusmasjid.my',
            'ic_number' => '780123456789',
            'password' => Hash::make('password'),
            'role' => 'community_member',
            'phone_number' => '0123456787',
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);

        $communityMember2 = User::create([
            'name' => 'Community Member 2',
            'email' => 'member2@urusmasjid.my',
            'ic_number' => '670123456789',
            'password' => Hash::make('password'),
            'role' => 'community_member',
            'phone_number' => '0123456786',
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);

        // Create Volunteers
        $volunteer1 = User::create([
            'name' => 'Volunteer 1',
            'email' => 'volunteer1@urusmasjid.my',
            'ic_number' => '560123456789',
            'password' => Hash::make('password'),
            'role' => 'volunteer',
            'phone_number' => '0123456785',
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);

        $volunteer2 = User::create([
            'name' => 'Volunteer 2',
            'email' => 'volunteer2@urusmasjid.my',
            'ic_number' => '450123456789',
            'password' => Hash::make('password'),
            'role' => 'volunteer',
            'phone_number' => '0123456784',
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);

        // Create Khatibs
        $khatib1 = User::create([
            'name' => 'Khatib 1',
            'email' => 'khatib1@urusmasjid.my',
            'ic_number' => '340123456789',
            'password' => Hash::make('password'),
            'role' => 'khatib',
            'phone_number' => '0123456783',
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);

        $khatib2 = User::create([
            'name' => 'Khatib 2',
            'email' => 'khatib2@urusmasjid.my',
            'ic_number' => '230123456789',
            'password' => Hash::make('password'),
            'role' => 'khatib',
            'phone_number' => '0123456782',
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);

        // Create Mosques
        $mosque1 = Mosque::create([
            'name' => 'Masjid Al-Hidayah',
            'street_address' => 'Jalan Masjid 1',
            'city' => 'Kuala Lumpur',
            'state' => 'Wilayah Persekutuan',
            'postal_code' => '50000',
            'location' => 'Kuala Lumpur',
            'jakim_zone' => 'WP',
            'contact_number' => '0312345678',
            'email' => 'masjid1@urusmasjid.my',
            'website' => 'https://masjid1.urusmasjid.my',
            'type' => 'masjid',
            'verification_status' => 'verified',
            'created_by' => $mosqueAdmin1->id,
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        $mosque2 = Mosque::create([
            'name' => 'Masjid An-Nur',
            'street_address' => 'Jalan Masjid 2',
            'city' => 'Shah Alam',
            'state' => 'Selangor',
            'postal_code' => '40000',
            'location' => 'Shah Alam',
            'jakim_zone' => 'SGR01',
            'contact_number' => '0312345679',
            'email' => 'masjid2@urusmasjid.my',
            'website' => 'https://masjid2.urusmasjid.my',
            'type' => 'masjid',
            'verification_status' => 'verified',
            'created_by' => $mosqueAdmin2->id,
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        $mosque3 = Mosque::create([
            'name' => 'Masjid As-Salam',
            'street_address' => 'Jalan Masjid 3',
            'city' => 'Petaling Jaya',
            'state' => 'Selangor',
            'postal_code' => '46000',
            'location' => 'Petaling Jaya',
            'jakim_zone' => 'SGR02',
            'contact_number' => '0312345680',
            'email' => 'masjid3@urusmasjid.my',
            'website' => 'https://masjid3.urusmasjid.my',
            'type' => 'masjid',
            'verification_status' => 'pending',
            'created_by' => $mosqueAdmin1->id,
        ]);

        // Create Mosque Users (Admins)
        MosqueUser::create([
            'mosque_id' => $mosque1->id,
            'user_id' => $mosqueAdmin1->id,
            'type' => 'admin',
            'role' => 'admin',
            'is_primary' => true,
            'ic_number' => $mosqueAdmin1->ic_number,
            'phone_number' => $mosqueAdmin1->phone_number,
        ]);

        MosqueUser::create([
            'mosque_id' => $mosque2->id,
            'user_id' => $mosqueAdmin2->id,
            'type' => 'admin',
            'role' => 'admin',
            'is_primary' => true,
            'ic_number' => $mosqueAdmin2->ic_number,
            'phone_number' => $mosqueAdmin2->phone_number,
        ]);

        // Create Mosque Users (Committee Members)
        MosqueUser::create([
            'mosque_id' => $mosque1->id,
            'user_id' => $volunteer1->id,
            'type' => 'committee',
            'role' => 'Bendahari',
            'name' => $volunteer1->name,
            'ic_number' => $volunteer1->ic_number,
            'phone_number' => $volunteer1->phone_number,
            'email' => $volunteer1->email,
            'status' => 'active',
        ]);

        MosqueUser::create([
            'mosque_id' => $mosque1->id,
            'user_id' => $volunteer2->id,
            'type' => 'committee',
            'role' => 'Setiausaha',
            'name' => $volunteer2->name,
            'ic_number' => $volunteer2->ic_number,
            'phone_number' => $volunteer2->phone_number,
            'email' => $volunteer2->email,
            'status' => 'active',
        ]);

        MosqueUser::create([
            'mosque_id' => $mosque2->id,
            'user_id' => $volunteer1->id,
            'type' => 'committee',
            'role' => 'Pengerusi Aktiviti',
            'name' => $volunteer1->name,
            'ic_number' => $volunteer1->ic_number,
            'phone_number' => $volunteer1->phone_number,
            'email' => $volunteer1->email,
            'status' => 'active',
        ]);

        // Create Community Members relationship
        CommunityMember::create([
            'mosque_id' => $mosque1->id,
            'user_id' => $communityMember1->id,
            'full_name' => $communityMember1->name,
            'membership_status' => 'active',
        ]);

        CommunityMember::create([
            'mosque_id' => $mosque1->id,
            'user_id' => $communityMember2->id,
            'full_name' => $communityMember2->name,
            'membership_status' => 'active',
        ]);

        CommunityMember::create([
            'mosque_id' => $mosque2->id,
            'user_id' => $communityMember1->id,
            'full_name' => $communityMember1->name,
            'membership_status' => 'active',
        ]);

        // Create Events
        $event1 = Event::create([
            'mosque_id' => $mosque1->id,
            'title' => 'Ceramah Ramadan',
            'description' => 'Ceramah khas sempena bulan Ramadan oleh Ustaz Ahmad',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(5)->addHours(2),
            'location' => 'Dewan Utama Masjid Al-Hidayah',
            'max_participants' => 100,
            'contact_person' => 'Encik Ali',
            'contact_phone' => '0123456789',
            'contact_email' => 'ali@urusmasjid.my',
            'status' => 'active',
            'created_by' => $mosqueAdmin1->id,
        ]);

        $event2 = Event::create([
            'mosque_id' => $mosque1->id,
            'title' => 'Kelas Mengaji Al-Quran',
            'description' => 'Kelas mingguan untuk mempelajari Al-Quran dengan tajwid yang betul',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(7)->addHours(2),
            'location' => 'Bilik Kuliah 1 Masjid Al-Hidayah',
            'max_participants' => 30,
            'contact_person' => 'Puan Fatimah',
            'contact_phone' => '0123456790',
            'contact_email' => 'fatimah@urusmasjid.my',
            'status' => 'active',
            'created_by' => $mosqueAdmin1->id,
        ]);

        $event3 = Event::create([
            'mosque_id' => $mosque2->id,
            'title' => 'Gotong-Royong Perdana',
            'description' => 'Aktiviti gotong-royong membersihkan kawasan masjid dan persekitaran',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(10)->addHours(4),
            'location' => 'Kawasan Masjid An-Nur',
            'max_participants' => 50,
            'contact_person' => 'Encik Kamal',
            'contact_phone' => '0123456791',
            'contact_email' => 'kamal@urusmasjid.my',
            'status' => 'active',
            'created_by' => $mosqueAdmin2->id,
        ]);

        // Assign volunteers to events
        $event1->volunteers()->attach([
            $volunteer1->id => ['role' => 'Pengurus Program'],
            $volunteer2->id => ['role' => 'Pengurus Teknikal'],
        ]);

        $event2->volunteers()->attach([
            $volunteer1->id => ['role' => 'Pembantu Pengajar'],
        ]);

        $event3->volunteers()->attach([
            $volunteer1->id => ['role' => 'Koordinator'],
            $volunteer2->id => ['role' => 'Pengurus Peralatan'],
        ]);

        // Create Event Registrations
        $event1->registrations()->create([
            'user_id' => $communityMember1->id,
            'name' => $communityMember1->name,
            'email' => $communityMember1->email,
            'phone' => $communityMember1->phone_number,
            'registration_number' => 'REG-' . Str::random(8),
            'status' => 'confirmed',
            'attendance_status' => 'pending',
        ]);

        $event1->registrations()->create([
            'user_id' => $communityMember2->id,
            'name' => $communityMember2->name,
            'email' => $communityMember2->email,
            'phone' => $communityMember2->phone_number,
            'registration_number' => 'REG-' . Str::random(8),
            'status' => 'confirmed',
            'attendance_status' => 'pending',
        ]);

        $event2->registrations()->create([
            'user_id' => $communityMember1->id,
            'name' => $communityMember1->name,
            'email' => $communityMember1->email,
            'phone' => $communityMember1->phone_number,
            'registration_number' => 'REG-' . Str::random(8),
            'status' => 'confirmed',
            'attendance_status' => 'attended',
        ]);

        $event3->registrations()->create([
            'user_id' => $communityMember2->id,
            'name' => $communityMember2->name,
            'email' => $communityMember2->email,
            'phone' => $communityMember2->phone_number,
            'registration_number' => 'REG-' . Str::random(8),
            'status' => 'confirmed',
            'attendance_status' => 'pending',
            'notes' => 'Akan membawa peralatan sendiri',
        ]);
    }
}
