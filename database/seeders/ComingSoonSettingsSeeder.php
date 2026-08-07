<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class ComingSoonSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'coming_soon_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'coming_soon',
                'description' => 'Enable or disable the coming soon banner'
            ],
            [
                'key' => 'coming_soon_require_password',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'coming_soon',
                'description' => 'Whether visitors can enter a passcode to unlock the frontpage'
            ],
            // coming_soon_password is deliberately not seeded: these rows are written
            // with updateOrCreate, so a default here would wipe a live passcode on
            // every re-seed. getComingSoonSettings() already falls back to ''.
            [
                'key' => 'coming_soon_title',
                'value' => 'Coming Soon',
                'type' => 'string',
                'group' => 'coming_soon',
                'description' => 'Title displayed in the coming soon banner'
            ],
            [
                'key' => 'coming_soon_message',
                'value' => 'We\'re working hard to bring you something amazing. Stay tuned!',
                'type' => 'string',
                'group' => 'coming_soon',
                'description' => 'Message displayed in the coming soon banner'
            ],
            [
                'key' => 'coming_soon_background_color',
                'value' => '#000000',
                'type' => 'string',
                'group' => 'coming_soon',
                'description' => 'Background color of the coming soon banner'
            ],
            [
                'key' => 'coming_soon_text_color',
                'value' => '#ffffff',
                'type' => 'string',
                'group' => 'coming_soon',
                'description' => 'Text color of the coming soon banner'
            ],
            [
                'key' => 'coming_soon_accent_color',
                'value' => '#8b5cf6',
                'type' => 'string',
                'group' => 'coming_soon',
                'description' => 'Accent color for highlights in the coming soon banner'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
