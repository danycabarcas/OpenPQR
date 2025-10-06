<?php
// database/seeders/ThemesSeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemesSeeder extends Seeder {
  public function run(): void {
    $themes = [
      [
        'name'=>'Ocean', 'slug'=>'ocean', 'is_dark'=>false,
        'variables'=>[
          '--brand'=>'#0ea5e9', '--brand-contrast'=>'#ffffff',
          '--bg'=>'#f7f9fc', '--text'=>'#0f172a',
          '--card-bg'=>'#ffffff', '--border'=>'#e5e7eb',
          '--sidebar-bg'=>'#1f2937', '--sidebar-text'=>'#e5e7eb', '--sidebar-active'=>'#38bdf8'
        ]
      ],
      [
        'name'=>'Sunset', 'slug'=>'sunset', 'is_dark'=>false,
        'variables'=>[
          '--brand'=>'#f97316', '--brand-contrast'=>'#1f2937',
          '--bg'=>'#fff7ed', '--text'=>'#111827',
          '--card-bg'=>'#ffffff', '--border'=>'#f3f4f6',
          '--sidebar-bg'=>'#1f2937', '--sidebar-text'=>'#f3f4f6', '--sidebar-active'=>'#fb923c'
        ]
      ],
      [
        'name'=>'Emerald', 'slug'=>'emerald', 'is_dark'=>false,
        'variables'=>[
          '--brand'=>'#10b981', '--brand-contrast'=>'#06241a',
          '--bg'=>'#f5fbf8', '--text'=>'#0b1720',
          '--card-bg'=>'#ffffff', '--border'=>'#e6f2ec',
          '--sidebar-bg'=>'#1f2937', '--sidebar-text'=>'#d1fae5', '--sidebar-active'=>'#34d399'
        ]
      ],
      [
        'name'=>'Slate (Dark)', 'slug'=>'slate-dark', 'is_dark'=>true,
        'variables'=>[
            '--brand'           => '#60a5fa',
            '--brand-hover'     => '#93c5fd',

            '--bg'              => '#0b1220',
            '--text'            => '#e5e7eb',

            '--card-bg'         => '#0f172a',
            '--border'          => '#1f2937',

            '--sidebar-bg'      => '#0f172a',
            '--sidebar-text'    => '#cbd5e1',
            '--sidebar-active'  => '#60a5fa',
        ]
        ],

    ];
    foreach ($themes as $th) Theme::updateOrCreate(['slug'=>$th['slug']], $th);
  }
}
