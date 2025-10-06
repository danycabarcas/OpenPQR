<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;

class ExtraThemesSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            // --- CLAROS ---

            [
                'name' => 'Sky Blue',
                'slug' => 'sky-blue',
                'is_dark' => false,
                'variables' => [
                    '--brand' => '#3b82f6',
                    '--brand-hover' => '#60a5fa',
                    '--bg' => '#f8fafc',
                    '--text' => '#0f172a',
                    '--card-bg' => '#ffffff',
                    '--border' => '#e2e8f0',
                    '--sidebar-bg' => '#1e3a8a',
                    '--sidebar-text' => '#e0e7ff',
                    '--sidebar-active' => '#93c5fd',
                ]
            ],
            [
                'name' => 'Sunrise',
                'slug' => 'sunrise',
                'is_dark' => false,
                'variables' => [
                    '--brand' => '#f59e0b',
                    '--brand-hover' => '#fbbf24',
                    '--bg' => '#fff7ed',
                    '--text' => '#78350f',
                    '--card-bg' => '#ffffff',
                    '--border' => '#fde68a',
                    '--sidebar-bg' => '#78350f',
                    '--sidebar-text' => '#fef3c7',
                    '--sidebar-active' => '#fbbf24',
                ]
            ],
            [
                'name' => 'Mint',
                'slug' => 'mint',
                'is_dark' => false,
                'variables' => [
                    '--brand' => '#10b981',
                    '--brand-hover' => '#34d399',
                    '--bg' => '#ecfdf5',
                    '--text' => '#064e3b',
                    '--card-bg' => '#ffffff',
                    '--border' => '#d1fae5',
                    '--sidebar-bg' => '#064e3b',
                    '--sidebar-text' => '#a7f3d0',
                    '--sidebar-active' => '#34d399',
                ]
            ],
            [
                'name' => 'Rose',
                'slug' => 'rose',
                'is_dark' => false,
                'variables' => [
                    '--brand' => '#e11d48',
                    '--brand-hover' => '#fb7185',
                    '--bg' => '#fff1f2',
                    '--text' => '#881337',
                    '--card-bg' => '#ffffff',
                    '--border' => '#fecdd3',
                    '--sidebar-bg' => '#9f1239',
                    '--sidebar-text' => '#fecdd3',
                    '--sidebar-active' => '#fb7185',
                ]
            ],
            [
                'name' => 'Coral',
                'slug' => 'coral',
                'is_dark' => false,
                'variables' => [
                    '--brand' => '#fb7185',
                    '--brand-hover' => '#fda4af',
                    '--bg' => '#fff5f5',
                    '--text' => '#4a1d1d',
                    '--card-bg' => '#ffffff',
                    '--border' => '#fbcfe8',
                    '--sidebar-bg' => '#9f1239',
                    '--sidebar-text' => '#fecdd3',
                    '--sidebar-active' => '#fb7185',
                ]
            ],
            [
                'name' => 'Lavender',
                'slug' => 'lavender',
                'is_dark' => false,
                'variables' => [
                    '--brand' => '#8b5cf6',
                    '--brand-hover' => '#a78bfa',
                    '--bg' => '#faf5ff',
                    '--text' => '#3b0764',
                    '--card-bg' => '#ffffff',
                    '--border' => '#ede9fe',
                    '--sidebar-bg' => '#4c1d95',
                    '--sidebar-text' => '#ddd6fe',
                    '--sidebar-active' => '#a78bfa',
                ]
            ],
            [
                'name' => 'Citrus',
                'slug' => 'citrus',
                'is_dark' => false,
                'variables' => [
                    '--brand' => '#84cc16',
                    '--brand-hover' => '#a3e635',
                    '--bg' => '#f7fee7',
                    '--text' => '#365314',
                    '--card-bg' => '#ffffff',
                    '--border' => '#d9f99d',
                    '--sidebar-bg' => '#365314',
                    '--sidebar-text' => '#ecfccb',
                    '--sidebar-active' => '#a3e635',
                ]
            ],

            // --- OSCUROS ---

            [
                'name' => 'Carbon Dark',
                'slug' => 'carbon-dark',
                'is_dark' => true,
                'variables' => [
                    '--brand' => '#22d3ee',
                    '--brand-hover' => '#67e8f9',
                    '--bg' => '#0a0f1a',
                    '--text' => '#e2e8f0',
                    '--card-bg' => '#111827',
                    '--border' => '#1f2937',
                    '--sidebar-bg' => '#0f172a',
                    '--sidebar-text' => '#cbd5e1',
                    '--sidebar-active' => '#22d3ee',
                ]
            ],
            [
                'name' => 'Crimson Night',
                'slug' => 'crimson-night',
                'is_dark' => true,
                'variables' => [
                    '--brand' => '#f43f5e',
                    '--brand-hover' => '#fb7185',
                    '--bg' => '#111827',
                    '--text' => '#f9fafb',
                    '--card-bg' => '#1f2937',
                    '--border' => '#374151',
                    '--sidebar-bg' => '#0f172a',
                    '--sidebar-text' => '#e5e7eb',
                    '--sidebar-active' => '#fb7185',
                ]
            ],
            [
                'name' => 'Emerald Dark',
                'slug' => 'emerald-dark',
                'is_dark' => true,
                'variables' => [
                    '--brand' => '#10b981',
                    '--brand-hover' => '#34d399',
                    '--bg' => '#0d1a15',
                    '--text' => '#ecfdf5',
                    '--card-bg' => '#1b2930',
                    '--border' => '#1f4037',
                    '--sidebar-bg' => '#102e26',
                    '--sidebar-text' => '#a7f3d0',
                    '--sidebar-active' => '#34d399',
                ]
            ],
            [
                'name' => 'Royal Dark',
                'slug' => 'royal-dark',
                'is_dark' => true,
                'variables' => [
                    '--brand' => '#6366f1',
                    '--brand-hover' => '#818cf8',
                    '--bg' => '#0f172a',
                    '--text' => '#e0e7ff',
                    '--card-bg' => '#1e293b',
                    '--border' => '#334155',
                    '--sidebar-bg' => '#1e1b4b',
                    '--sidebar-text' => '#c7d2fe',
                    '--sidebar-active' => '#818cf8',
                ]
            ],
            [
                'name' => 'Amber Dark',
                'slug' => 'amber-dark',
                'is_dark' => true,
                'variables' => [
                    '--brand' => '#f59e0b',
                    '--brand-hover' => '#fbbf24',
                    '--bg' => '#0f0a00',
                    '--text' => '#fef3c7',
                    '--card-bg' => '#292524',
                    '--border' => '#3f3f46',
                    '--sidebar-bg' => '#1c1917',
                    '--sidebar-text' => '#fde68a',
                    '--sidebar-active' => '#fbbf24',
                ]
            ],
            [
                'name' => 'Cyber Neon',
                'slug' => 'cyber-neon',
                'is_dark' => true,
                'variables' => [
                    '--brand' => '#00ffff',
                    '--brand-hover' => '#7fffd4',
                    '--bg' => '#000013',
                    '--text' => '#e0f2fe',
                    '--card-bg' => '#0f172a',
                    '--border' => '#1e3a8a',
                    '--sidebar-bg' => '#020617',
                    '--sidebar-text' => '#bae6fd',
                    '--sidebar-active' => '#00ffff',
                ]
            ],
        ];

        foreach ($themes as $t) {
            Theme::updateOrCreate(['slug' => $t['slug']], $t);
        }
    }
}
