<?php

$dir = __DIR__ . '/public/images/sampah';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$assets = [
    'kulit_pisang' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <path d="M 50 15 C 40 30 20 40 20 60 C 20 75 35 85 50 85 C 65 85 80 75 80 60 C 80 40 60 30 50 15 Z" fill="#fef08a" />
            <path d="M 50 15 C 45 35 30 50 20 60 C 35 60 45 45 50 15 Z" fill="#facc15" />
            <path d="M 50 15 C 55 35 70 50 80 60 C 65 60 55 45 50 15 Z" fill="#eab308" />
            <path d="M 50 15 L 50 25" stroke="#854d0e" stroke-width="4" stroke-linecap="round" />
            <circle cx="35" cy="65" r="3" fill="#ca8a04" />
            <circle cx="65" cy="65" r="3" fill="#ca8a04" />
            <circle cx="50" cy="72" r="3" fill="#ca8a04" />
        </svg>
    ',
    'daun_kering' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <path d="M 50 15 C 70 35 75 60 50 85 C 25 60 30 35 50 15 Z" fill="#d97706" />
            <line x1="50" y1="15" x2="50" y2="85" stroke="#78350f" stroke-width="3" />
            <path d="M 50 30 Q 65 40 60 45 M 50 45 Q 68 55 62 60 M 50 60 Q 65 70 58 75" stroke="#78350f" stroke-width="2" stroke-linecap="round" fill="none" />
            <path d="M 50 30 Q 35 40 40 45 M 50 45 Q 32 55 38 60 M 50 60 Q 35 70 42 75" stroke="#78350f" stroke-width="2" stroke-linecap="round" fill="none" />
        </svg>
    ',
    'apel_busuk' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="55" r="32" fill="#991b1b" />
            <circle cx="48" cy="53" r="32" fill="#b91c1c" />
            <!-- Rotten spots -->
            <circle cx="35" cy="45" r="8" fill="#78350f" opacity="0.8" />
            <circle cx="65" cy="65" r="6" fill="#78350f" opacity="0.8" />
            <!-- Stem -->
            <path d="M 50 25 Q 52 10 60 12" stroke="#78350f" stroke-width="4" fill="none" stroke-linecap="round" />
            <!-- Leaf -->
            <path d="M 53 20 C 60 15 65 20 60 25 C 55 25 50 22 53 20 Z" fill="#15803d" />
        </svg>
    ',
    'sisa_sayur' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <path d="M 30 75 C 25 55 35 30 50 30 C 65 30 75 55 70 75 Z" fill="#22c55e" />
            <path d="M 40 75 C 38 60 42 40 50 40 C 58 40 62 60 60 75 Z" fill="#4ade80" />
            <!-- Carrot outline or scrap -->
            <path d="M 75 75 L 85 45 L 65 65 Z" fill="#f97316" />
            <path d="M 85 45 Q 90 35 88 30" stroke="#16a34a" stroke-width="3" fill="none" />
        </svg>
    ',
    'roti_berjamur' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Toast shape -->
            <path d="M 30 35 C 30 25 45 20 50 25 C 55 20 70 25 70 35 L 70 75 C 70 80 65 85 50 85 C 35 85 30 80 30 75 Z" fill="#fde047" stroke="#ca8a04" stroke-width="4" />
            <!-- Mold spots -->
            <circle cx="40" cy="45" r="6" fill="#047857" opacity="0.7" />
            <circle cx="43" cy="48" r="4" fill="#065f46" opacity="0.7" />
            <circle cx="58" cy="65" r="7" fill="#047857" opacity="0.7" />
            <circle cx="38" cy="70" r="5" fill="#047857" opacity="0.7" />
        </svg>
    ',
    'cangkang_telur' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Egg shell cracked -->
            <path d="M 25 60 C 25 40 40 25 50 25 C 60 25 75 40 75 60 C 75 65 70 75 50 75 C 30 75 25 65 25 60 Z" fill="#fef3c7" />
            <!-- Crack lines -->
            <path d="M 50 25 L 48 45 L 55 50 L 50 75" stroke="#d97706" stroke-width="3" fill="none" stroke-linejoin="round" />
            <path d="M 48 45 L 35 48" stroke="#d97706" stroke-width="3" fill="none" />
            <path d="M 55 50 L 68 48" stroke="#d97706" stroke-width="3" fill="none" />
        </svg>
    ',
    'tulang_ayam' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <rect x="44" y="25" width="12" height="50" rx="6" fill="#f3f4f6" stroke="#d1d5db" stroke-width="2" />
            <!-- Joint circles top -->
            <circle cx="40" cy="25" r="10" fill="#f3f4f6" stroke="#d1d5db" stroke-width="2" />
            <circle cx="60" cy="25" r="10" fill="#f3f4f6" stroke="#d1d5db" stroke-width="2" />
            <!-- Joint circles bottom -->
            <circle cx="40" cy="75" r="10" fill="#f3f4f6" stroke="#d1d5db" stroke-width="2" />
            <circle cx="60" cy="75" r="10" fill="#f3f4f6" stroke="#d1d5db" stroke-width="2" />
        </svg>
    ',
    'ampas_teh' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Tea bag -->
            <polygon points="35,30 65,30 70,75 30,75" fill="#f9fafb" stroke="#d1d5db" stroke-width="3" />
            <!-- Tea leaves inside -->
            <ellipse cx="50" cy="55" rx="15" ry="12" fill="#78350f" opacity="0.6" />
            <!-- String and tag -->
            <path d="M 50 30 Q 50 15 65 10" stroke="#9ca3af" stroke-width="2" fill="none" />
            <rect x="62" y="5" width="12" height="10" fill="#ef4444" />
        </svg>
    ',
    'botol_plastik' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <path d="M 42 20 L 58 20 L 58 30 L 65 38 L 65 80 C 65 83 62 85 58 85 L 42 85 C 38 85 35 83 35 80 L 35 38 L 42 30 Z" fill="#e0f2fe" stroke="#38bdf8" stroke-width="4" />
            <!-- Cap -->
            <rect x="44" y="12" width="12" height="8" rx="2" fill="#0284c7" />
            <!-- Label -->
            <rect x="35" y="48" width="30" height="16" fill="#38bdf8" opacity="0.8" />
            <!-- Waves on label -->
            <path d="M 35 56 Q 42 50 50 56 Q 58 62 65 56" stroke="#ffffff" stroke-width="2" fill="none" />
        </svg>
    ',
    'kaleng_soda' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <rect x="35" y="20" width="30" height="60" rx="6" fill="#f87171" stroke="#dc2626" stroke-width="4" />
            <ellipse cx="50" cy="20" rx="15" ry="4" fill="#d1d5db" stroke="#9ca3af" stroke-width="2" />
            <ellipse cx="50" cy="80" rx="15" ry="4" fill="#9ca3af" />
            <!-- Cola text placeholder -->
            <path d="M 40 45 Q 50 35 60 45" stroke="#ffffff" stroke-width="4" fill="none" stroke-linecap="round" />
            <circle cx="50" cy="58" r="6" fill="#ffffff" />
        </svg>
    ',
    'kardus_bekas' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Box perspective -->
            <polygon points="25,45 50,30 75,45 75,75 50,85 25,75" fill="#d97706" stroke="#b45309" stroke-width="3" />
            <!-- Box flaps open -->
            <polygon points="25,45 50,30 50,15 20,30" fill="#ca8a04" stroke="#b45309" stroke-width="2" />
            <polygon points="75,45 50,30 50,15 80,30" fill="#ca8a04" stroke="#b45309" stroke-width="2" />
        </svg>
    ',
    'botol_kaca' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <path d="M 44 20 L 56 20 L 56 35 L 62 42 L 62 82 C 62 84 60 86 56 86 L 44 86 C 40 86 38 84 38 82 L 38 42 L 44 35 Z" fill="#d1fae5" stroke="#059669" stroke-width="4" />
            <!-- Cap -->
            <rect x="46" y="14" width="8" height="6" fill="#b45309" />
            <!-- Reflections -->
            <line x1="42" y1="46" x2="42" y2="76" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.7" />
        </svg>
    ',
    'kertas_koran' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Folded newspaper -->
            <polygon points="20,25 65,20 80,75 35,80" fill="#f3f4f6" stroke="#9ca3af" stroke-width="3" />
            <polygon points="35,80 80,75 75,70 30,75" fill="#e5e7eb" stroke="#9ca3af" stroke-width="2" />
            <!-- Text lines -->
            <line x1="30" y1="35" x2="60" y2="32" stroke="#4b5563" stroke-width="3" />
            <line x1="32" y1="45" x2="58" y2="42" stroke="#4b5563" stroke-width="2" />
            <line x1="34" y1="55" x2="70" y2="52" stroke="#4b5563" stroke-width="2" />
            <line x1="36" y1="65" x2="68" y2="62" stroke="#4b5563" stroke-width="2" />
        </svg>
    ',
    'sedotan_plastik' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Bendy straw -->
            <path d="M 25 85 L 60 35 L 75 40" fill="none" stroke="#f472b6" stroke-width="8" stroke-linecap="round" />
            <!-- Stripes -->
            <path d="M 25 85 L 60 35 L 75 40" fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-dasharray="8 8" />
        </svg>
    ',
    'kantong_kresek' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Plastic bag -->
            <path d="M 30 40 L 30 80 C 30 85 40 88 50 88 C 60 88 70 85 70 80 L 70 40 L 60 20 L 55 20 L 60 40 L 40 40 L 45 20 L 40 20 Z" fill="#e2e8f0" stroke="#94a3b8" stroke-width="3" />
        </svg>
    ',
    'baterai_bekas' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <rect x="35" y="25" width="30" height="55" rx="4" fill="#1e293b" stroke="#0f172a" stroke-width="4" />
            <!-- Positive terminal -->
            <rect x="45" y="18" width="10" height="7" rx="1" fill="#fbbf24" />
            <!-- Bottom gold strip -->
            <rect x="35" y="65" width="30" height="15" rx="2" fill="#fbbf24" />
            <!-- Danger symbol / lighting -->
            <polygon points="50,35 42,48 48,48 46,60 54,46 48,46" fill="#ef4444" />
        </svg>
    ',
    'lampu_bohlam' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Glass bulb -->
            <circle cx="50" cy="45" r="25" fill="#fef9c3" stroke="#eab308" stroke-width="4" />
            <path d="M 38 60 L 62 60 L 58 75 L 42 75 Z" fill="#d1d5db" stroke="#9ca3af" stroke-width="3" />
            <!-- Contact point -->
            <ellipse cx="50" cy="75" rx="6" ry="3" fill="#4b5563" />
            <!-- Filament -->
            <path d="M 45 45 Q 50 35 55 45" stroke="#eab308" stroke-width="2" fill="none" />
        </svg>
    ',
    'obat_kedaluwarsa' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Pill blister pack -->
            <rect x="25" y="25" width="50" height="50" rx="6" fill="#e5e7eb" stroke="#9ca3af" stroke-width="3" />
            <!-- Foil circles -->
            <circle cx="37" cy="37" r="8" fill="#d1d5db" stroke="#9ca3af" stroke-width="2" />
            <circle cx="63" cy="37" r="8" fill="#d1d5db" stroke="#9ca3af" stroke-width="2" />
            <circle cx="37" cy="63" r="8" fill="#d1d5db" stroke="#9ca3af" stroke-width="2" />
            <circle cx="63" cy="63" r="8" fill="#d1d5db" stroke="#9ca3af" stroke-width="2" />
            <!-- Cross red -->
            <line x1="50" y1="42" x2="50" y2="58" stroke="#ef4444" stroke-width="4" stroke-linecap="round" />
            <line x1="42" y1="50" x2="58" y2="50" stroke="#ef4444" stroke-width="4" stroke-linecap="round" />
        </svg>
    ',
    'semprotan_nyamuk' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Spray can -->
            <rect x="38" y="28" width="24" height="58" rx="4" fill="#a855f7" stroke="#7e22ce" stroke-width="3" />
            <path d="M 38 28 C 38 22 62 22 62 28 Z" fill="#d1d5db" stroke="#7e22ce" stroke-width="2" />
            <!-- Cap/nozzle -->
            <rect x="47" y="16" width="6" height="6" fill="#ef4444" />
            <!-- Poison skull hint or cross -->
            <line x1="44" y1="45" x2="56" y2="57" stroke="#ffffff" stroke-width="4" />
            <line x1="56" y1="45" x2="44" y2="57" stroke="#ffffff" stroke-width="4" />
        </svg>
    ',
    'termometer_rusak' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <!-- Broken thermometer -->
            <line x1="25" y1="75" x2="65" y2="35" stroke="#9ca3af" stroke-width="8" stroke-linecap="round" />
            <!-- Mercury red bulb -->
            <circle cx="25" cy="75" r="8" fill="#ef4444" />
            <!-- Crack line -->
            <path d="M 42 58 L 48 58 L 46 50 L 52 50" stroke="#ef4444" stroke-width="3" fill="none" />
            <!-- Drops -->
            <circle cx="15" cy="80" r="3" fill="#ef4444" />
        </svg>
    ',
    'botol_deterjen' => '
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <path d="M 35 30 L 65 30 L 65 80 C 65 83 62 85 58 85 L 35 85 C 31 85 28 83 28 80 L 28 45 L 35 30 Z" fill="#f43f5e" stroke="#be123c" stroke-width="4" />
            <!-- Cap -->
            <rect x="42" y="20" width="16" height="10" fill="#3b82f6" />
            <!-- Handle hole -->
            <rect x="48" y="45" width="10" height="20" rx="4" fill="#ecf7f4" stroke="#be123c" stroke-width="2" />
        </svg>
    '
];

foreach ($assets as $name => $svg) {
    file_put_contents("$dir/$name.svg", trim($svg));
}

echo "Assets generated successfully!\n";
