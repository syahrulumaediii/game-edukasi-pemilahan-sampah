<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="turbo-visit-control" content="reload">
    <meta name="turbo-cache-control" content="no-cache">
    <title>Detektif Sampah - Sesi Kelas</title>
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Poppins & Fredoka Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tone.js for clean synthesis and sequencer background music -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tone/14.8.49/Tone.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            transition: background 0.8s ease;
        }
        .font-fredoka {
            font-family: 'Fredoka', sans-serif;
        }
        [x-cloak] { display: none !important; }
        .tap-scale:active {
            transform: scale(0.92);
        }
        @keyframes floatHint {
            0% { transform: translateY(12px) scale(0.92); opacity: 0; }
            60% { transform: translateY(-4px) scale(1.02); opacity: 1; }
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }
        .animate-float-hint {
            animation: floatHint 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        @keyframes subtleShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px) rotate(-1deg); }
            40%, 80% { transform: translateX(6px) rotate(1deg); }
        }
        .animate-subtle-shake {
            animation: subtleShake 0.4s ease-in-out;
        }

        /* --- BACKGROUND STYLES & THEMES --- */
        #game-background {
            position: fixed;
            inset: 0;
            z-index: -10;
            overflow: hidden;
            transition: background 0.8s ease;
        }
        .theme-forest {
            background: linear-gradient(to bottom, #dbeafe, #bbf7d0, #86efac);
        }
        .theme-ocean {
            background: linear-gradient(to bottom, #bae6fd, #7dd3fc, #0284c7);
        }
        .theme-space {
            background: linear-gradient(to bottom, #09090b, #0f172a, #1e1b4b);
        }
        .theme-garden {
            background: linear-gradient(to bottom, #fef08a, #fed7aa, #fbcfe8);
        }
        .theme-balloon {
            background: linear-gradient(to bottom, #e0f2fe, #fef08a, #fbcfe8);
        }

        /* --- ANIMATIONS & FLOATING ELEMENTS --- */
        /* Awan Melayang */
        @keyframes floatCloud {
            0% { transform: translateX(-15vw); }
            100% { transform: translateX(115vw); }
        }
        .cloud {
            position: absolute;
            font-size: 3.5rem;
            opacity: 0.55;
            animation: floatCloud 50s linear infinite;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }
        .cloud-1 { top: 8%; animation-duration: 65s; }
        .cloud-2 { top: 22%; animation-duration: 45s; animation-delay: -15s; }

        /* Dedaunan & Kelopak Bunga Gugur */
        @keyframes fallLeaf {
            0% { transform: translateY(-8vh) translateX(0) rotate(0deg); opacity: 0; }
            10% { opacity: 0.75; }
            90% { opacity: 0.75; }
            100% { transform: translateY(108vh) translateX(120px) rotate(360deg); opacity: 0; }
        }
        .leaf, .petal {
            position: absolute;
            font-size: 1.6rem;
            animation: fallLeaf 16s linear infinite;
        }
        .leaf-1 { left: 8%; animation-duration: 13s; animation-delay: 0s; }
        .leaf-2 { left: 32%; animation-duration: 19s; animation-delay: -4s; }
        .leaf-3 { left: 68%; animation-duration: 16s; animation-delay: -2s; }
        .leaf-4 { left: 88%; animation-duration: 22s; animation-delay: -8s; }

        .petal-1 { left: 12%; animation-duration: 11s; animation-delay: 0s; }
        .petal-2 { left: 38%; animation-duration: 15s; animation-delay: -3s; }
        .petal-3 { left: 62%; animation-duration: 13s; animation-delay: -6s; }
        .petal-4 { left: 84%; animation-duration: 17s; animation-delay: -1s; }

        /* Gelembung & Balon Naik */
        @keyframes riseBubble {
            0% { transform: translateY(108vh) translateX(0) scale(0.8); opacity: 0; }
            15% { opacity: 0.65; }
            85% { opacity: 0.65; }
            100% { transform: translateY(-8vh) translateX(60px) scale(1.2); opacity: 0; }
        }
        .bubble, .balloon {
            position: absolute;
            animation: riseBubble 16s linear infinite;
        }
        .bubble { font-size: 1.4rem; }
        .balloon { font-size: 2.8rem; filter: drop-shadow(0 6px 8px rgba(0,0,0,0.1)); }

        .bubble-1 { left: 7%; animation-duration: 14s; animation-delay: 0s; }
        .bubble-2 { left: 28%; animation-duration: 9s; animation-delay: -2s; }
        .bubble-3 { left: 48%; animation-duration: 17s; animation-delay: -5s; }
        .bubble-4 { left: 72%; animation-duration: 11s; animation-delay: -1s; }
        .bubble-5 { left: 88%; animation-duration: 15s; animation-delay: -7s; }

        .balloon-1 { left: 18%; animation-duration: 20s; animation-delay: 0s; }
        .balloon-2 { left: 52%; animation-duration: 24s; animation-delay: -6s; }
        .balloon-3 { left: 78%; animation-duration: 18s; animation-delay: -12s; }

        /* Ikan Berenang */
        @keyframes swimFish {
            0% { transform: translateX(-15vw) scaleX(1); }
            49% { transform: translateX(115vw) scaleX(1); }
            50% { transform: translateX(115vw) scaleX(-1); }
            99% { transform: translateX(-15vw) scaleX(-1); }
            100% { transform: translateX(-15vw) scaleX(1); }
        }
        .fish {
            position: absolute;
            font-size: 2.2rem;
            animation: swimFish 32s linear infinite;
        }
        .fish-1 { top: 38%; animation-duration: 26s; }
        .fish-2 { top: 72%; animation-duration: 38s; animation-delay: -12s; }

        /* Bintang Berkedip */
        @keyframes twinkleStar {
            0%, 100% { opacity: 0.25; transform: scale(0.75) rotate(0deg); }
            50% { opacity: 1; transform: scale(1.25) rotate(180deg); }
        }
        .star {
            position: absolute;
            animation: twinkleStar 3.5s ease-in-out infinite;
        }
        .star-1 { top: 12%; left: 12%; font-size: 1.6rem; animation-delay: 0s; }
        .star-2 { top: 18%; left: 82%; font-size: 1.1rem; animation-delay: 0.6s; }
        .star-3 { top: 42%; left: 8%; font-size: 1.3rem; animation-delay: 1.2s; }
        .star-4 { top: 68%; left: 88%; font-size: 1.6rem; animation-delay: 1.8s; }
        .star-5 { top: 82%; left: 18%; font-size: 2.2rem; animation-delay: 2.4s; }

        /* Benda Angkasa */
        @keyframes floatSpace {
            0% { transform: rotate(0deg) translate(0, 0); }
            50% { transform: rotate(180deg) translate(12px, 18px); }
            100% { transform: rotate(360deg) translate(0, 0); }
        }
        .space-obj {
            position: absolute;
            animation: floatSpace 24s linear infinite;
        }
        .space-1 { top: 28%; left: 68%; font-size: 3.2rem; }
        .space-2 { top: 58%; left: 12%; font-size: 2.8rem; animation-duration: 28s; }

        /* Lebah Ceria */
        @keyframes buzzBee {
            0% { transform: translate(0, 0) scaleX(1); }
            25% { transform: translate(40px, -25px) scaleX(1); }
            50% { transform: translate(80px, 0px) scaleX(-1); }
            75% { transform: translate(40px, 25px) scaleX(-1); }
            100% { transform: translate(0, 0) scaleX(1); }
        }
        .bee {
            position: absolute;
            font-size: 1.6rem;
            animation: buzzBee 6.5s ease-in-out infinite;
        }
        .bee-1 { top: 28%; left: 18%; }
        .bee-2 { top: 62%; left: 78%; animation-delay: -3.5s; }

        /* Pesawat Kertas */
        @keyframes glidePlane {
            0% { transform: translate(-15vw, 35vh) rotate(12deg); opacity: 0; }
            8% { opacity: 0.8; }
            92% { opacity: 0.8; }
            100% { transform: translate(115vw, -5vh) rotate(12deg); opacity: 0; }
        }
        .paper-plane {
            position: absolute;
            font-size: 2.2rem;
            animation: glidePlane 20s linear infinite;
        }

        /* --- PARTIKEL KLIK INTERAKTIF --- */
        .click-particle {
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            font-size: 1.7rem;
            transform: translate(-50%, -50%);
            animation: particleFloatUp 0.85s cubic-bezier(0.15, 0.85, 0.35, 1) forwards;
        }

        @keyframes particleFloatUp {
            0% {
                transform: translate(-50%, -50%) scale(0.4) rotate(0deg);
                opacity: 1;
            }
            40% {
                opacity: 1;
            }
            100% {
                transform: translate(-50%, calc(-50% - 90px)) scale(1.6) rotate(270deg);
                opacity: 0;
            }
        }
        /* --- MODE DUEL CYBERPUNK & MECHANICAL KEYBOARD SWITCH STYLES --- */
        .cyber-duel-bg {
            background: radial-gradient(circle at center, #0f2b3e 0%, #081321 70%, #030810 100%);
            background-image: 
                linear-gradient(rgba(6, 182, 212, 0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(6, 182, 212, 0.07) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Metallic Top Frame Header Badge */
        .metallic-header {
            background: linear-gradient(180deg, #374151 0%, #1f2937 25%, #111827 75%, #030712 100%);
            border: 3px solid #6b7280;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.8), inset 0 2px 4px rgba(255, 255, 255, 0.3);
        }
        .metallic-badge-title {
            background: linear-gradient(180deg, #1f2937 0%, #0f172a 100%);
            border: 2px solid #06b6d4;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4), inset 0 1px 2px rgba(255, 255, 255, 0.2);
        }

        /* Mechanical Switch Housing Plate */
        .mech-tray {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border: 2px solid #334155;
            border-radius: 1.5rem;
            box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.8), 0 4px 12px rgba(0, 0, 0, 0.5);
            padding: 8px;
        }

        /* Mechanical Keycap 3D Component */
        .mech-keycap {
            position: relative;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.15) 0%, rgba(15, 23, 42, 0.8) 100%);
            border-radius: 1rem;
            padding: 10px 4px 8px 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.12s ease;
            cursor: pointer;
            user-select: none;
        }
        .mech-keycap:hover {
            transform: translateY(-2px);
        }
        .mech-keycap:active {
            transform: translateY(3px) scale(0.97);
        }

        /* LED Glow Accents for P1 (Red) and P2 (Blue) */
        .mech-keycap-p1 {
            border: 2px solid #ef4444;
            box-shadow: 0 0 16px rgba(239, 68, 68, 0.6), inset 0 0 12px rgba(239, 68, 68, 0.3), 0 4px 0 #991b1b;
        }
        .mech-keycap-p1:active {
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.8), inset 0 0 16px rgba(239, 68, 68, 0.5), 0 1px 0 #991b1b;
        }

        .mech-keycap-p2 {
            border: 2px solid #3b82f6;
            box-shadow: 0 0 16px rgba(59, 130, 246, 0.6), inset 0 0 12px rgba(59, 130, 246, 0.3), 0 4px 0 #1e40af;
        }
        .mech-keycap-p2:active {
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.8), inset 0 0 16px rgba(59, 130, 246, 0.5), 0 1px 0 #1e40af;
        }

        /* Key Switch Mechanical Stem (The Switch Keycap Stem Visual) */
        .switch-stem {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
            color: #ffffff;
            position: relative;
            box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.4), 0 3px 6px rgba(0, 0, 0, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        .switch-stem-green {
            background: linear-gradient(180deg, #22c55e 0%, #15803d 100%);
        }
        .switch-stem-yellow {
            background: linear-gradient(180deg, #eab308 0%, #a16207 100%);
            color: #000000;
        }
        .switch-stem-red {
            background: linear-gradient(180deg, #ef4444 0%, #b91c1c 100%);
        }

        /* Pulsing Glow Arrow Animations */
        @keyframes pulseArrowRed {
            0%, 100% { transform: translateX(0); opacity: 0.6; }
            50% { transform: translateX(4px); opacity: 1; text-shadow: 0 0 10px #ef4444; }
        }
        @keyframes pulseArrowBlue {
            0%, 100% { transform: translateX(0); opacity: 0.6; }
            50% { transform: translateX(-4px); opacity: 1; text-shadow: 0 0 10px #3b82f6; }
        }
        .animate-pulse-arrow-red {
            animation: pulseArrowRed 1.2s infinite ease-in-out;
        }
        .animate-pulse-arrow-blue {
            animation: pulseArrowBlue 1.2s infinite ease-in-out;
        }
    </style>
</head>
<body x-data="gameEngine()" x-init="preloadAssets()" @click="spawnClickParticle($event); startBgmOnFirstInteraction()" class="flex flex-col items-center justify-center min-h-screen p-4 overflow-x-hidden">

    <!-- INTERACTIVE BACKGROUND -->
    <div id="game-background" :class="'theme-' + currentTheme">
        <!-- Forest Theme Elements -->
        <template x-if="currentTheme === 'forest'">
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="cloud cloud-1">☁️</div>
                <div class="cloud cloud-2">☁️</div>
                <div class="leaf leaf-1">🍃</div>
                <div class="leaf leaf-2">🍂</div>
                <div class="leaf leaf-3">🍁</div>
                <div class="leaf leaf-4">🍃</div>
            </div>
        </template>

        <!-- Ocean Theme Elements -->
        <template x-if="currentTheme === 'ocean'">
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="bubble bubble-1">🫧</div>
                <div class="bubble bubble-2">🫧</div>
                <div class="bubble bubble-3">🫧</div>
                <div class="bubble bubble-4">🫧</div>
                <div class="bubble bubble-5">🫧</div>
                <div class="fish fish-1">🐟</div>
                <div class="fish fish-2">🐠</div>
            </div>
        </template>

        <!-- Space Theme Elements -->
        <template x-if="currentTheme === 'space'">
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="star star-1">⭐</div>
                <div class="star star-2">✨</div>
                <div class="star star-3">⭐</div>
                <div class="star star-4">✨</div>
                <div class="star star-5">🌌</div>
                <div class="space-obj space-1">🪐</div>
                <div class="space-obj space-2">🚀</div>
            </div>
        </template>

        <!-- Garden Theme Elements -->
        <template x-if="currentTheme === 'garden'">
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="petal petal-1">🌸</div>
                <div class="petal petal-2">🌸</div>
                <div class="petal petal-3">🌺</div>
                <div class="petal petal-4">🌼</div>
                <div class="bee bee-1">🐝</div>
                <div class="bee bee-2">🐝</div>
            </div>
        </template>

        <!-- Balloon Theme Elements -->
        <template x-if="currentTheme === 'balloon'">
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="balloon balloon-1">🎈</div>
                <div class="balloon balloon-2">🎈</div>
                <div class="balloon balloon-3">🎈</div>
                <div class="paper-plane">✈️</div>
                <div class="absolute inset-0 flex items-center justify-center opacity-10">
                    <span class="text-[15rem] select-none pointer-events-none">🌈</span>
                </div>
            </div>
        </template>
    </div>

    <!-- FLOATING THEME SWITCHER -->
    <div style="position: fixed; top: 16px; right: 16px; z-index: 9999;">
        <button @click.stop="showThemeMenu = !showThemeMenu" 
                style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 24px; border-radius: 9999px; background-color: white; border: 2px solid #34d399; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); cursor: pointer; transition: all 0.2s;" 
                class="tap-scale"
                title="Ganti Tema Latar 🎨">
            🎨
        </button>
        
        <!-- Theme Selection Drawer -->
        <div x-show="showThemeMenu" 
             @click.outside="showThemeMenu = false" 
             x-transition:enter="transition ease-out duration-250 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             style="position: absolute; right: 0; margin-top: 12px; background-color: white; border-radius: 24px; padding: 16px; width: 240px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 4px solid #34d399; display: flex; flex-direction: column; gap: 8px; z-index: 10000;"
             x-cloak>
            <h3 class="font-fredoka" style="font-weight: bold; text-align: center; color: #064e3b; font-size: 14px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin: 0; display: flex; align-items: center; justify-content: center; gap: 6px;">
                <span>🎨</span> Pilih Tema Latar
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <!-- Forest Theme -->
                <button @click.stop="setTheme('forest'); showThemeMenu = false" 
                        style="padding: 10px; border-radius: 16px; border: 2px solid #e5e7eb; display: flex; align-items: center; gap: 12px; cursor: pointer; text-align: left; width: 100%; transition: all 0.2s;"
                        :style="currentTheme === 'forest' ? 'border-color: #10b981; background-color: #ecfdf5; box-shadow: 0 0 0 2px #a7f3d0;' : 'border-color: #e5e7eb; background: white;'">
                    <span style="font-size: 24px;">🌲</span>
                    <div style="display: flex; flex-direction: column;">
                        <h4 class="font-fredoka" style="font-weight: bold; font-size: 14px; color: #064e3b; margin: 0; line-height: 1.2;">Hutan Ceria</h4>
                        <span style="font-size: 10px; color: #6b7280; font-weight: 500;">Daun Berguguran</span>
                    </div>
                </button>
                
                <!-- Ocean Theme -->
                <button @click.stop="setTheme('ocean'); showThemeMenu = false" 
                        style="padding: 10px; border-radius: 16px; border: 2px solid #e5e7eb; display: flex; align-items: center; gap: 12px; cursor: pointer; text-align: left; width: 100%; transition: all 0.2s;"
                        :style="currentTheme === 'ocean' ? 'border-color: #0ea5e9; background-color: #f0f9ff; box-shadow: 0 0 0 2px #bae6fd;' : 'border-color: #e5e7eb; background: white;'">
                    <span style="font-size: 24px;">🌊</span>
                    <div style="display: flex; flex-direction: column;">
                        <h4 class="font-fredoka" style="font-weight: bold; font-size: 14px; color: #0c4a6e; margin: 0; line-height: 1.2;">Bawah Laut</h4>
                        <span style="font-size: 10px; color: #6b7280; font-weight: 500;">Gelembung & Ikan</span>
                    </div>
                </button>
                
                <!-- Space Theme -->
                <button @click.stop="setTheme('space'); showThemeMenu = false" 
                        style="padding: 10px; border-radius: 16px; border: 2px solid #e5e7eb; display: flex; align-items: center; gap: 12px; cursor: pointer; text-align: left; width: 100%; transition: all 0.2s;"
                        :style="currentTheme === 'space' ? 'border-color: #6366f1; background-color: #eef2ff; box-shadow: 0 0 0 2px #c7d2fe;' : 'border-color: #e5e7eb; background: white;'">
                    <span style="font-size: 24px;">🚀</span>
                    <div style="display: flex; flex-direction: column;">
                        <h4 class="font-fredoka" style="font-weight: bold; font-size: 14px; color: #1e1b4b; margin: 0; line-height: 1.2;">Dunia Angkasa</h4>
                        <span style="font-size: 10px; color: #6b7280; font-weight: 500;">Bintang Berkedip</span>
                    </div>
                </button>
                
                <!-- Garden Theme -->
                <button @click.stop="setTheme('garden'); showThemeMenu = false" 
                        style="padding: 10px; border-radius: 16px; border: 2px solid #e5e7eb; display: flex; align-items: center; gap: 12px; cursor: pointer; text-align: left; width: 100%; transition: all 0.2s;"
                        :style="currentTheme === 'garden' ? 'border-color: #ec4899; background-color: #fdf2f8; box-shadow: 0 0 0 2px #fbcfe8;' : 'border-color: #e5e7eb; background: white;'">
                    <span style="font-size: 24px;">🌸</span>
                    <div style="display: flex; flex-direction: column;">
                        <h4 class="font-fredoka" style="font-weight: bold; font-size: 14px; color: #831843; margin: 0; line-height: 1.2;">Taman Bunga</h4>
                        <span style="font-size: 10px; color: #6b7280; font-weight: 500;">Kelopak Sakura</span>
                    </div>
                </button>
                
                <!-- Balloon Theme -->
                <button @click.stop="setTheme('balloon'); showThemeMenu = false" 
                        style="padding: 10px; border-radius: 16px; border: 2px solid #e5e7eb; display: flex; align-items: center; gap: 12px; cursor: pointer; text-align: left; width: 100%; transition: all 0.2s;"
                        :style="currentTheme === 'balloon' ? 'border-color: #f59e0b; background-color: #fffbeb; box-shadow: 0 0 0 2px #fde68a;' : 'border-color: #e5e7eb; background: white;'">
                    <span style="font-size: 24px;">🎈</span>
                    <div style="display: flex; flex-direction: column;">
                        <h4 class="font-fredoka" style="font-weight: bold; font-size: 14px; color: #78350f; margin: 0; line-height: 1.2;">Dunia Balon</h4>
                        <span style="font-size: 10px; color: #6b7280; font-weight: 500;">Balon & Pelangi</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- HIMBAUAN MODE LANDSCAPE KHUSUS HP (UI PRO & HIGH CONTRAST SOLID) -->
    <div x-show="gameMode === 'duel' && isMobile && isPortrait && !dismissMobileNotice" 
         x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.self="dismissMobileNotice = true"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 text-center select-none"
         style="background-color: rgba(3, 7, 18, 0.85); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);"
         x-cloak>
        
        <!-- Solid Dark Card Container (100% Opaque, No Bleed-Through) -->
        <div class="rounded-3xl p-6 sm:p-7 max-w-[300px] w-full text-center relative overflow-hidden"
             style="background-color: #111c2a; border: 2px solid #10b981; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.9), 0 0 20px rgba(16, 185, 129, 0.35);">
            
            <!-- Close Button Top Right -->
            <button type="button" @click="dismissMobileNotice = true" title="Tutup Himbauan" 
                    class="absolute top-3 right-3 text-gray-400 hover:text-white text-xs font-bold w-7 h-7 rounded-full flex items-center justify-center transition"
                    style="background-color: #1e293b; border: 1px solid #334155;">✕</button>

            <!-- Animated Rotate Phone Visual SVG -->
            <div class="relative w-28 h-24 mx-auto mb-4 flex items-center justify-center">
                <!-- Circular Green Arrow SVG -->
                <svg class="w-24 h-24 animate-spin" style="animation-duration: 7s;" viewBox="0 0 100 100" fill="none">
                    <!-- Curved Green Arrow Line -->
                    <path d="M 50 12 A 38 38 0 0 1 88 50" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                    <path d="M 88 50 L 95 40 M 88 50 L 78 43" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                    
                    <path d="M 50 88 A 38 38 0 0 1 12 50" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                    <path d="M 12 50 L 5 60 M 12 50 L 22 57" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                </svg>

                <!-- Phone Portrait (Pink Outline, Top-Left) -->
                <div class="absolute w-9 h-16 rounded-xl transform -translate-y-3 -translate-x-2 rotate-[-20deg]"
                     style="border: 2.5px solid #ec4899; background-color: rgba(131, 24, 67, 0.7); box-shadow: 0 4px 10px rgba(0,0,0,0.5);"></div>

                <!-- Phone Landscape (Emerald Outline & Solid Dark Body, Bottom-Right) -->
                <div class="absolute w-16 h-9 rounded-xl flex items-center justify-center transform translate-y-2 translate-x-1"
                     style="border: 2.5px solid #10b981; background-color: #030712; box-shadow: 0 6px 15px rgba(0,0,0,0.8);">
                    <div class="w-2 h-2 rounded-full" style="background-color: #34d399; box-shadow: 0 0 8px #34d399;"></div>
                </div>
            </div>

            <!-- Title -->
            <h3 class="font-fredoka font-extrabold text-lg sm:text-xl tracking-wider uppercase leading-snug mb-2"
                style="color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.9);">
                MAINKAN DALAM<br>LANDSCAPE
            </h3>

            <!-- Horizontal Line Divider -->
            <div class="w-full my-3" style="height: 2px; background: linear-gradient(90deg, transparent, #334155, transparent);"></div>

            <!-- Body Text -->
            <p class="text-xs sm:text-sm font-semibold leading-relaxed px-1"
               style="color: #cbd5e1; text-shadow: 0 1px 2px rgba(0,0,0,0.8);">
                Untuk pengalaman 2 player yang terbaik, mohon putar HP kamu ke posisi mendatar.
            </p>
        </div>
    </div>

    <!-- GAME ENGINE CONTAINER -->
    <div class="w-full rounded-[2.5rem] shadow-2xl border-4 overflow-hidden relative transition-all duration-300" :class="gameMode === 'duel' ? 'max-w-5xl border-gray-700 bg-slate-900 text-white' : 'max-w-md border-emerald-500 bg-white'" x-cloak>
        
        <!-- ================= FASE 1: LOADING SCREEN ================= -->
        <div x-show="gameState === 'loading'" class="p-8 py-16 text-center space-y-6">
            <div class="text-6xl animate-bounce">🕵️‍♂️🔎</div>
            <h2 class="font-fredoka font-bold text-2xl text-emerald-800">Menyiapkan Game...</h2>
            <div class="w-full bg-emerald-100 rounded-full h-4 overflow-hidden border border-emerald-200">
                <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-4 rounded-full transition-all duration-300" :style="`width: ${loadingProgress}%`"></div>
            </div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-widest">Memuat Gambar Sampah & Musik</p>
        </div>

        <!-- ================= FASE 2: WELCOME / LOGIN SCREEN ================= -->
        <div x-show="gameState === 'welcome'" class="p-8 text-center space-y-6">
            <!-- Header Mascot -->
            <div class="space-y-2">
                <div class="text-7xl">🕵️‍♂️</div>
                <h1 class="font-fredoka font-bold text-3xl text-emerald-800 tracking-wide">DETEKTIF SAMPAH</h1>
                <p class="text-xs text-emerald-600 bg-emerald-50 py-1.5 px-3 rounded-full inline-block font-semibold">Sesi: {{ $session->title }}</p>
            </div>

            <!-- Form Identitas (semua mode pakai 1 input nama) -->
            <div class="space-y-4 text-left">

                <!-- Input Nama -->
                <div>
                    <label class="font-fredoka font-bold text-sm text-gray-700 block mb-1">
                        <span x-show="gameMode === 'duel'">⚔️ Siapa Namamu? (Mode Duel)</span>
                        <span x-show="gameMode !== 'duel'">Siapa Namamu?</span>
                    </label>
                    <input type="text" x-model="studentName" placeholder="Tulis nama panggilanmu..."
                        class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-4 text-center font-fredoka font-semibold text-lg text-gray-800 shadow-inner placeholder:text-gray-300">
                    <p x-show="showNameError" class="text-xs text-red-500 font-bold mt-1">⚠️ Tolong tulis namamu dulu ya!</p>
                </div>

                <!-- Info & Opsi Peran Khusus Mode Duel -->
                <template x-if="gameMode === 'duel'">
                    <div class="space-y-3">
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3 text-xs text-amber-800 font-semibold flex items-start gap-2">
                            <span class="text-base mt-0.5">ℹ️</span>
                            <span>Mode <strong>Duel Kelas</strong>! Lawan kamu juga membuka halaman ini di device masing-masing. Pilihlah posisi bertandingmu!</span>
                        </div>

                        <!-- Opsi Peran Player -->
                        <div>
                            <label class="font-fredoka font-bold text-sm text-gray-700 block mb-1.5">Kamu Bermain Sebagai Apa?</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" @click="playerRole = 'p1'" :class="playerRole === 'p1' ? 'bg-red-500 text-white ring-4 ring-red-200 border-red-600' : 'bg-red-50 hover:bg-red-100 text-red-800 border-red-200'" class="py-2.5 px-1 flex flex-col items-center justify-center rounded-2xl transition duration-150 border-2 shadow-xs tap-scale">
                                    <span class="font-fredoka font-bold text-xs sm:text-sm">🔴 Pemain 1</span>
                                    <span class="text-[8px] sm:text-[9px] font-semibold opacity-85 mt-0.5">Sisi Kiri (A,S,D)</span>
                                </button>
                                <button type="button" @click="playerRole = 'p2'" :class="playerRole === 'p2' ? 'bg-blue-500 text-white ring-4 ring-blue-200 border-blue-600' : 'bg-blue-50 hover:bg-blue-100 text-blue-800 border-blue-200'" class="py-2.5 px-1 flex flex-col items-center justify-center rounded-2xl transition duration-150 border-2 shadow-xs tap-scale">
                                    <span class="font-fredoka font-bold text-xs sm:text-sm">🔵 Pemain 2</span>
                                    <span class="text-[8px] sm:text-[9px] font-semibold opacity-85 mt-0.5">Sisi Kanan (J,K,L)</span>
                                </button>
                                <button type="button" @click="playerRole = 'both'" :class="playerRole === 'both' ? 'bg-purple-600 text-white ring-4 ring-purple-200 border-purple-700' : 'bg-purple-50 hover:bg-purple-100 text-purple-800 border-purple-200'" class="py-2.5 px-1 flex flex-col items-center justify-center rounded-2xl transition duration-150 border-2 shadow-xs tap-scale">
                                    <span class="font-fredoka font-bold text-xs sm:text-sm">👥 1 Layar</span>
                                    <span class="text-[8px] sm:text-[9px] font-semibold opacity-85 mt-0.5">Berdua Luring</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Input Kelas -->
                <div>
                    <label class="font-fredoka font-bold text-sm text-gray-700 block mb-2">Kamu Kelas Berapa?</label>
                    <div class="grid grid-cols-3 gap-3">
                        <button @click="selectGrade('2')" :class="studentGrade === '2' ? 'bg-emerald-500 text-white ring-4 ring-emerald-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800'" class="py-3.5 flex flex-col items-center justify-center rounded-2xl transition duration-150 border-2 border-emerald-100 shadow-sm tap-scale">
                            <span class="font-fredoka font-bold text-2xl">2</span>
                            <span class="text-[9px] font-semibold opacity-85 mt-0.5">2 Bak Sampah</span>
                        </button>
                        <button @click="selectGrade('3')" :class="studentGrade === '3' ? 'bg-emerald-500 text-white ring-4 ring-emerald-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800'" class="py-3.5 flex flex-col items-center justify-center rounded-2xl transition duration-150 border-2 border-emerald-100 shadow-sm tap-scale">
                            <span class="font-fredoka font-bold text-2xl">3</span>
                            <span class="text-[9px] font-semibold opacity-85 mt-0.5">2 Bak Sampah</span>
                        </button>
                        <button @click="selectGrade('4-6')" :class="studentGrade === '4-6' ? 'bg-emerald-500 text-white ring-4 ring-emerald-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800'" class="py-3.5 flex flex-col items-center justify-center rounded-2xl transition duration-150 border-2 border-emerald-100 shadow-sm tap-scale">
                            <span class="font-fredoka font-bold text-2xl">4-6</span>
                            <span class="text-[9px] font-semibold opacity-85 mt-0.5">3 Bak (+B3)</span>
                        </button>
                    </div>
                    <p x-show="showGradeError" class="text-xs text-red-500 font-bold mt-1">⚠️ Pilih kelasmu dulu ya!</p>
                </div>

                <!-- Pengaturan Suara -->
                <div class="flex items-center justify-between bg-emerald-50/60 p-3 rounded-2xl border border-emerald-100 text-xs">
                    <span class="font-fredoka font-bold text-emerald-900 flex items-center gap-1.5">
                        <span>🔊</span> Suara & Musik Anak
                    </span>
                    <button type="button" @click="toggleMusic()" class="px-2.5 py-1 rounded-xl text-xs font-bold transition flex items-center gap-1" :class="!isMusicMuted ? 'bg-emerald-500 text-white shadow-sm' : 'bg-gray-200 text-gray-500'">
                        <span x-text="!isMusicMuted ? '🎵 Musik Aktif' : '🔇 Musik Mati'"></span>
                    </button>
                </div>

            </div>

            <!-- Tombol Mulai -->
            <button @click="startGame()" class="w-full py-4.5 font-fredoka font-bold text-xl rounded-2xl shadow-lg border-b-4 transform active:translate-y-0.5 active:border-b-2 transition-all tap-scale"
                :class="gameMode === 'duel'
                    ? 'bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white border-red-700'
                    : 'bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white border-emerald-700'">
                <span x-show="gameMode !== 'duel'">MULAI MAIN! 🎮</span>
                <span x-show="gameMode === 'duel'">⚔️ SIAP DUEL!</span>
            </button>
        </div>

        <!-- ================= FASE 2.5: WAITING DUEL SCREEN ================= -->
        <div x-show="gameState === 'waiting_duel'" class="p-8 py-16 text-center space-y-6" x-cloak>
            <div class="text-6xl animate-bounce inline-block">⏳</div>
            <h2 class="font-fredoka font-bold text-2xl text-emerald-850">Menunggu Gurumu...</h2>
            <p class="text-sm text-gray-500 font-medium leading-relaxed">
                Halo <strong x-text="studentName" class="text-emerald-700 font-bold"></strong>, pendaftaran berhasil!<br>
                Harap bersiap di tempat dudukmu ya, duel akan segera dimulai secara serentak oleh gurumu dari layar proyektor. ⚡
            </p>
            <div class="px-4 py-2.5 bg-emerald-50 rounded-xl border border-emerald-100 inline-block text-xs font-semibold text-emerald-800">
                Mode Permainan: Duel Kelas ⚔️
            </div>
        </div>

        <!-- ================= FASE 3: GAMEPLAY SCREEN ================= -->
        <div x-show="gameState === 'playing'" class="flex flex-col justify-between min-h-[490px] relative">
            
            <!-- Top HUD (Lobby Standar / Belajar) -->
            <template x-if="gameMode !== 'duel'">
                <div class="p-4 sm:p-5 bg-emerald-50 border-b-2 border-emerald-100 flex items-center justify-between">
                    <!-- Score Display -->
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🏆</span>
                        <div>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider block leading-none">Skor Detektif</span>
                            <span x-text="score" class="font-fredoka font-bold text-lg text-emerald-800">0</span>
                        </div>
                    </div>

                    <!-- Combo/Streak display -->
                    <div class="flex items-center gap-1.5" x-show="combo >= 3">
                        <span class="text-xl animate-bounce">🔥</span>
                        <span x-text="`x${combo}`" class="font-fredoka font-extrabold text-orange-600 text-lg">x3</span>
                    </div>

                    <!-- Sound Controls Quick Toggle (Music & SFX) -->
                    <div class="flex items-center gap-1 bg-white/90 px-2 py-1 rounded-2xl border border-emerald-200 shadow-xs">
                        <button @click="toggleMusic()" :title="isMusicMuted ? 'Nyalakan Musik' : 'Matikan Musik'" class="text-xs p-1 rounded-lg hover:bg-emerald-100 transition tap-scale" :class="isMusicMuted ? 'opacity-40 grayscale' : ''">
                            <span x-show="!isMusicMuted">🎵</span>
                            <span x-show="isMusicMuted">🔇</span>
                        </button>
                        <button @click="toggleSfx()" :title="isSfxMuted ? 'Nyalakan Suara' : 'Matikan Suara'" class="text-xs p-1 rounded-lg hover:bg-emerald-100 transition tap-scale" :class="isSfxMuted ? 'opacity-40 grayscale' : ''">
                            <span x-show="!isSfxMuted">🔊</span>
                            <span x-show="isSfxMuted">🔈</span>
                        </button>
                    </div>

                    <!-- Timer Display -->
                    <div class="flex items-center gap-2">
                        <span class="text-xl">⏰</span>
                        <div class="text-right">
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider block leading-none">Sisa Waktu</span>
                            <span x-text="`${timer}s`" :class="timer <= 5 ? 'text-red-600 animate-pulse font-extrabold' : 'text-emerald-800 font-bold'" class="font-fredoka text-lg">45s</span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Top HUD (Mode Duel 1 Frame) -->
            <template x-if="gameMode === 'duel'">
                <div class="metallic-header p-3 sm:p-4 border-b-2 border-gray-700 flex items-center justify-between gap-2 sm:gap-4 relative z-20">
                    <!-- P1 Score on left -->
                    <div class="flex items-center gap-2 bg-gradient-to-r from-red-950/80 to-red-900/40 p-2 sm:px-3 sm:py-1.5 rounded-2xl border border-red-500/50 shadow-md">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-tr from-red-700 to-red-400 border border-red-300 shadow-[0_0_10px_#ef4444] flex items-center justify-center shrink-0">
                            <span class="text-xs sm:text-sm">🔴</span>
                        </div>
                        <div>
                            <span class="text-[9px] sm:text-[10px] text-red-300 font-bold uppercase tracking-wider block leading-none" x-text="p1Name || 'PLAYER 1'">PLAYER 1</span>
                            <span x-text="p1Score" class="font-fredoka font-extrabold text-base sm:text-xl text-white drop-shadow-[0_0_8px_rgba(239,68,68,0.8)]">0</span>
                        </div>
                        <span class="text-xs text-yellow-400 font-bold ml-1 animate-pulse" x-show="p1Combo >= 3" x-text="`🔥 x${p1Combo}`"></span>
                    </div>

                    <!-- Center Title & Shared Timer Capsule -->
                    <div class="flex flex-col items-center">
                        <div class="metallic-badge-title px-3 sm:px-5 py-1 rounded-full text-center shadow-md mb-1">
                            <span class="font-fredoka font-extrabold text-xs sm:text-sm md:text-base text-cyan-300 tracking-wider uppercase block leading-none" style="text-shadow: 0 0 10px rgba(6, 182, 212, 0.8);">DUEL PEMILAHAN SAMPAH</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-gray-900/90 px-3 py-1 rounded-full border border-gray-700 shadow-inner">
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest leading-none">WAKTU:</span>
                            <span x-text="`${timer}s`" :class="timer <= 5 ? 'text-red-500 animate-pulse font-extrabold' : 'text-cyan-400 font-bold'" class="font-fredoka text-sm sm:text-base leading-none">45s</span>
                        </div>
                    </div>

                    <!-- P2 Score on right -->
                    <div class="flex items-center gap-2 text-right justify-end bg-gradient-to-l from-blue-950/80 to-blue-900/40 p-2 sm:px-3 sm:py-1.5 rounded-2xl border border-blue-500/50 shadow-md">
                        <span class="text-xs text-yellow-400 font-bold mr-1 animate-pulse" x-show="p2Combo >= 3" x-text="`🔥 x${p2Combo}`"></span>
                        <div>
                            <span class="text-[9px] sm:text-[10px] text-blue-300 font-bold uppercase tracking-wider block leading-none" x-text="p2Name || 'PLAYER 2'">PLAYER 2</span>
                            <span x-text="p2Score" class="font-fredoka font-extrabold text-base sm:text-xl text-white drop-shadow-[0_0_8px_rgba(59,130,246,0.8)]">0</span>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-tr from-blue-700 to-blue-400 border border-blue-300 shadow-[0_0_10px_#3b82f6] flex items-center justify-center shrink-0">
                            <span class="text-xs sm:text-sm">🔵</span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Progress Bar Waktu -->
            <div class="w-full bg-gray-900 h-2">
                <div class="bg-gradient-to-r h-2 transition-all duration-100" :class="timer <= 5 ? 'from-red-500 to-red-600 shadow-[0_0_10px_#ef4444]' : 'from-cyan-400 to-teal-400 shadow-[0_0_10px_#06b6d4]'" :style="`width: ${(timer / maxTimer) * 100}%`"></div>
            </div>

            <!-- Single Player Arena (If not duel) -->
            <template x-if="gameMode !== 'duel'">
                <div class="flex flex-col flex-1 justify-between bg-white text-gray-800">
                    <!-- Banner Edukasi Informatif saat Salah Jawab -->
                    <div x-show="showWrongBanner" 
                         x-transition:enter="transition ease-out duration-250 transform" 
                         x-transition:enter-start="opacity-0 -translate-y-2 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-200 transform" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95" 
                         class="mx-4 mt-2.5 p-3 bg-amber-50 rounded-2xl border-2 border-amber-300 shadow-md flex items-center gap-3 animate-float-hint z-20 shrink-0" 
                         x-cloak>
                        <div class="text-2xl animate-bounce">💡</div>
                        <div class="flex-1 text-left">
                            <p class="text-[10px] font-extrabold text-amber-800 uppercase tracking-wide flex items-center gap-1">
                                <span>Info Detektif Cilik</span>
                                <span class="text-xs">📢</span>
                            </p>
                            <p class="text-xs sm:text-sm font-bold text-gray-800 leading-tight" x-html="wrongBannerText"></p>
                        </div>
                        <button @click="showWrongBanner = false" class="text-gray-400 hover:text-gray-600 font-bold text-xs p-1">✕</button>
                    </div>

                    <!-- Center: Sampah display -->
                    <div class="flex-1 p-4 sm:p-6 flex flex-col items-center justify-center relative bg-gradient-to-b from-white to-emerald-50/20" :class="isShaking ? 'animate-subtle-shake' : ''">
                        <!-- Container Kaca Pembesar -->
                        <div class="w-56 h-56 sm:w-64 sm:h-64 bg-white rounded-full border-8 sm:border-[10px] border-emerald-500 shadow-2xl flex items-center justify-center relative overflow-hidden aspect-square transform hover:scale-[1.02] transition-transform duration-200 group">
                            <template x-if="currentQuestion">
                                <img :src="getImageUrl(currentQuestion.gambar)" 
                                     :alt="currentQuestion.nama_sampah" 
                                     class="w-full h-full object-cover select-none pointer-events-none transition-transform duration-300"
                                     x-on:error="$event.target.src='https://placehold.co/300?text=' + encodeURIComponent(currentQuestion ? currentQuestion.nama_sampah : 'Sampah')">
                            </template>
                            <!-- Glass Reflection Effect -->
                            <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-transparent via-white/10 to-white/40 pointer-events-none"></div>
                        </div>

                        <!-- Label Nama Sampah -->
                        <template x-if="currentQuestion">
                            <h2 x-text="currentQuestion.nama_sampah" class="font-fredoka font-extrabold text-2xl sm:text-3xl text-emerald-950 mt-4 tracking-wide uppercase drop-shadow-xs text-center px-2">Apel</h2>
                        </template>
                    </div>

                    <!-- Bottom: Tombol Bak Sampah -->
                    <div class="p-6 bg-white border-t border-gray-100 grid gap-4" :class="(studentGrade === '2' || studentGrade === '3') ? 'grid-cols-2' : 'grid-cols-3'">
                        <!-- Bak Hijau -->
                        <button @click="sortWaste('organik')" class="py-4.5 bg-gradient-to-b from-emerald-400 to-emerald-600 hover:from-emerald-500 hover:to-emerald-700 text-white rounded-3xl shadow-md border-b-4 border-emerald-700 flex flex-col items-center justify-center gap-1 tap-scale">
                            <span class="text-3xl">🟢</span>
                            <span class="font-fredoka font-bold text-xs uppercase tracking-wider">Organik</span>
                        </button>
                        <!-- Bak Kuning -->
                        <button @click="sortWaste('anorganik')" class="py-4.5 bg-gradient-to-b from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white rounded-3xl shadow-md border-b-4 border-yellow-700 flex flex-col items-center justify-center gap-1 tap-scale">
                            <span class="text-3xl">🟡</span>
                            <span class="font-fredoka font-bold text-xs uppercase tracking-wider">Anorganik</span>
                        </button>
                        <!-- Bak Merah -->
                        <template x-if="studentGrade === '4-6'">
                            <button @click="sortWaste('b3')" class="py-4.5 bg-gradient-to-b from-red-400 to-red-600 hover:from-red-500 hover:to-red-700 text-white rounded-3xl shadow-md border-b-4 border-red-700 flex flex-col items-center justify-center gap-0.5 tap-scale px-1">
                                <span class="text-3xl">🔴</span>
                                <span class="font-fredoka font-bold text-xs uppercase tracking-wider leading-none">B3</span>
                                <span class="text-[7.5px] font-bold opacity-90 uppercase tracking-tight text-center leading-none mt-0.5">Bahan Berbahaya & Beracun</span>
                            </button>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Duel Player Arena (If duel) -->
            <template x-if="gameState === 'playing' && gameMode === 'duel'">
                <div class="cyber-duel-bg grid grid-cols-2 divide-x-2 divide-cyan-500/40 flex-1 relative overflow-hidden text-white">
                    
                    <!-- Center Vertical Neon Laser Beam -->
                    <div class="absolute inset-y-0 left-1/2 -translate-x-1/2 w-1 bg-gradient-to-b from-cyan-400 via-cyan-300 to-teal-400 shadow-[0_0_15px_#06b6d4] pointer-events-none z-10"></div>

                    <!-- Player 1 (Left Column) -->
                    <div class="flex flex-col justify-between p-3 sm:p-5 relative transition-all" :class="[p1IsShaking ? 'animate-subtle-shake' : '', playerRole === 'p2' ? 'opacity-40 grayscale-[30%]' : '']">
                        
                        <!-- Lock Overlay if player is Player 2 -->
                        <template x-if="playerRole === 'p2'">
                            <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-[1.5px] z-30 flex flex-col items-center justify-center p-2 text-center">
                                <span class="text-3xl animate-bounce">🔒</span>
                                <span class="font-fredoka font-bold text-xs text-red-300 uppercase mt-1">Sisi Pemain 1</span>
                                <span class="text-[9px] text-gray-300 font-medium">Hanya dapat dikontrol oleh Pemain 1</span>
                            </div>
                        </template>

                        <!-- Progress Bar / Indicator P1 -->
                        <div class="w-full bg-slate-900/90 rounded-full h-2.5 overflow-hidden border border-red-500/40 p-0.5">
                            <div class="bg-gradient-to-r from-red-600 to-red-400 h-full rounded-full transition-all duration-200 shadow-[0_0_8px_#ef4444]" :style="`width: ${Math.min(100, (p1Score / 1000) * 100)}%`"></div>
                        </div>

                        <!-- Trash display Card P1 -->
                        <div class="flex-1 flex flex-col items-center justify-center py-2 sm:py-4">
                            <!-- Image Frame -->
                            <div class="w-28 h-28 sm:w-36 sm:h-36 bg-gray-200/90 rounded-3xl border-4 border-gray-400 shadow-[0_0_20px_rgba(239,68,68,0.3)] flex items-center justify-center relative overflow-hidden aspect-square transform hover:scale-[1.02] transition-transform duration-200">
                                <template x-if="p1CurrentQuestion">
                                    <img :src="getImageUrl(p1CurrentQuestion.gambar)" 
                                         :alt="p1CurrentQuestion.nama_sampah" 
                                         class="w-full h-full object-cover select-none pointer-events-none"
                                         x-on:error="$event.target.src='https://placehold.co/150?text=' + encodeURIComponent(p1CurrentQuestion ? p1CurrentQuestion.nama_sampah : 'Sampah')">
                                </template>
                            </div>
                            <!-- Label Nama Sampah P1 -->
                            <template x-if="p1CurrentQuestion">
                                <h3 x-text="p1CurrentQuestion.nama_sampah" class="font-fredoka font-extrabold text-sm sm:text-base text-white mt-2.5 tracking-wider uppercase text-center px-1 drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]">OBAT KEDALUWARSA</h3>
                            </template>
                        </div>

                        <!-- Mechanical Keyboard Switches P1 -->
                        <div class="space-y-1.5 z-20">
                            <!-- Hint Arrow Text -->
                            <div class="flex items-center justify-start gap-1 text-[9px] sm:text-[10px] text-gray-300 italic font-semibold pl-1">
                                <span class="text-red-400 animate-pulse-arrow-red font-bold">>>></span>
                                <span>Tekan Tombol Keyboard yang Sesuai</span>
                            </div>

                            <!-- Switches Housing Tray -->
                            <div class="mech-tray grid gap-2" :class="(studentGrade === '2' || studentGrade === '3') ? 'grid-cols-2' : 'grid-cols-3'">
                                <!-- Key Organik (A) -->
                                <button type="button" @click="sortWaste('p1', 'organik')" :disabled="playerRole === 'p2'" class="mech-keycap mech-keycap-p1 group">
                                    <div class="switch-stem switch-stem-green mb-1 group-hover:scale-105 transition-transform">
                                        <span class="text-white drop-shadow-md">A</span>
                                    </div>
                                    <span class="font-fredoka font-bold text-[8px] sm:text-[9.5px] text-white uppercase tracking-wider leading-none">ORGANIK (A)</span>
                                </button>
                                
                                <!-- Key Anorganik (S) -->
                                <button type="button" @click="sortWaste('p1', 'anorganik')" :disabled="playerRole === 'p2'" class="mech-keycap mech-keycap-p1 group">
                                    <div class="switch-stem switch-stem-yellow mb-1 group-hover:scale-105 transition-transform">
                                        <span class="text-black drop-shadow-md">S</span>
                                    </div>
                                    <span class="font-fredoka font-bold text-[8px] sm:text-[9.5px] text-white uppercase tracking-wider leading-none">ANORGANIK (S)</span>
                                </button>
                                
                                <!-- Key B3 (D) -->
                                <template x-if="studentGrade === '4-6'">
                                    <button type="button" @click="sortWaste('p1', 'b3')" :disabled="playerRole === 'p2'" class="mech-keycap mech-keycap-p1 group">
                                        <div class="switch-stem switch-stem-red mb-1 group-hover:scale-105 transition-transform">
                                            <span class="text-white drop-shadow-md">D</span>
                                        </div>
                                        <span class="font-fredoka font-bold text-[8px] sm:text-[9.5px] text-white uppercase tracking-wider leading-none">B3 (D)</span>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- Player 2 (Right Column) -->
                    <div class="flex flex-col justify-between p-3 sm:p-5 relative transition-all" :class="[p2IsShaking ? 'animate-subtle-shake' : '', playerRole === 'p1' ? 'opacity-40 grayscale-[30%]' : '']">
                        
                        <!-- Lock Overlay if player is Player 1 -->
                        <template x-if="playerRole === 'p1'">
                            <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-[1.5px] z-30 flex flex-col items-center justify-center p-2 text-center">
                                <span class="text-3xl animate-bounce">🔒</span>
                                <span class="font-fredoka font-bold text-xs text-blue-300 uppercase mt-1">Sisi Pemain 2</span>
                                <span class="text-[9px] text-gray-300 font-medium">Hanya dapat dikontrol oleh Pemain 2</span>
                            </div>
                        </template>

                        <!-- Progress Bar / Indicator P2 -->
                        <div class="w-full bg-slate-900/90 rounded-full h-2.5 overflow-hidden border border-blue-500/40 p-0.5">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-400 h-full rounded-full transition-all duration-200 shadow-[0_0_8px_#3b82f6]" :style="`width: ${Math.min(100, (p2Score / 1000) * 100)}%`"></div>
                        </div>

                        <!-- Trash display Card P2 -->
                        <div class="flex-1 flex flex-col items-center justify-center py-2 sm:py-4">
                            <!-- Image Frame Circle -->
                            <div class="w-28 h-28 sm:w-36 sm:h-36 bg-gray-200/90 rounded-full border-4 border-gray-400 shadow-[0_0_20px_rgba(59,130,246,0.3)] flex items-center justify-center relative overflow-hidden aspect-square transform hover:scale-[1.02] transition-transform duration-200">
                                <template x-if="p2CurrentQuestion">
                                    <img :src="getImageUrl(p2CurrentQuestion.gambar)" 
                                         :alt="p2CurrentQuestion.nama_sampah" 
                                         class="w-full h-full object-cover select-none pointer-events-none"
                                         x-on:error="$event.target.src='https://placehold.co/150?text=' + encodeURIComponent(p2CurrentQuestion ? p2CurrentQuestion.nama_sampah : 'Sampah')">
                                </template>
                            </div>
                            <!-- Label Nama Sampah P2 -->
                            <template x-if="p2CurrentQuestion">
                                <h3 x-text="p2CurrentQuestion.nama_sampah" class="font-fredoka font-extrabold text-sm sm:text-base text-white mt-2.5 tracking-wider uppercase text-center px-1 drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]">KULIT JERUK</h3>
                            </template>
                        </div>

                        <!-- Mechanical Keyboard Switches P2 -->
                        <div class="space-y-1.5 z-20">
                            <!-- Hint Arrow Text -->
                            <div class="flex items-center justify-end gap-1 text-[9px] sm:text-[10px] text-gray-300 italic font-semibold pr-1">
                                <span>Tekan Tombol Keyboard yang Sesuai</span>
                                <span class="text-blue-400 animate-pulse-arrow-blue font-bold"><<<</span>
                            </div>

                            <!-- Switches Housing Tray -->
                            <div class="mech-tray grid gap-2" :class="(studentGrade === '2' || studentGrade === '3') ? 'grid-cols-2' : 'grid-cols-3'">
                                <!-- Key Organik (J) -->
                                <button type="button" @click="sortWaste('p2', 'organik')" :disabled="playerRole === 'p1'" class="mech-keycap mech-keycap-p2 group">
                                    <div class="switch-stem switch-stem-green mb-1 group-hover:scale-105 transition-transform">
                                        <span class="text-white drop-shadow-md">J</span>
                                    </div>
                                    <span class="font-fredoka font-bold text-[8px] sm:text-[9.5px] text-white uppercase tracking-wider leading-none">ORGANIK (J)</span>
                                </button>
                                
                                <!-- Key Anorganik (K) -->
                                <button type="button" @click="sortWaste('p2', 'anorganik')" :disabled="playerRole === 'p1'" class="mech-keycap mech-keycap-p2 group">
                                    <div class="switch-stem switch-stem-yellow mb-1 group-hover:scale-105 transition-transform">
                                        <span class="text-black drop-shadow-md">K</span>
                                    </div>
                                    <span class="font-fredoka font-bold text-[8px] sm:text-[9.5px] text-white uppercase tracking-wider leading-none">ANORGANIK (K)</span>
                                </button>
                                
                                <!-- Key B3 (L) -->
                                <template x-if="studentGrade === '4-6'">
                                    <button type="button" @click="sortWaste('p2', 'b3')" :disabled="playerRole === 'p1'" class="mech-keycap mech-keycap-p2 group">
                                        <div class="switch-stem switch-stem-red mb-1 group-hover:scale-105 transition-transform">
                                            <span class="text-white drop-shadow-md">L</span>
                                        </div>
                                        <span class="font-fredoka font-bold text-[8px] sm:text-[9.5px] text-white uppercase tracking-wider leading-none">B3 (L)</span>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
        </div>

        <!-- ================= FASE 4: POST-GAME / RESULTS SCREEN ================= -->
        <div x-show="gameState === 'results'" class="p-8 text-center space-y-6 max-h-[90vh] overflow-y-auto" x-cloak>
            
            <!-- Result Title -->
            <div class="space-y-1">
                <div class="text-6xl animate-bounce">🏆</div>
                <h2 class="font-fredoka font-bold text-2xl text-emerald-800">Bermain Selesai!</h2>
                <p class="text-xs text-gray-400">Kerja bagus, Detektif Cilik!</p>
            </div>

            <!-- Skor & Medal Card (Lobby Standar / Belajar) -->
            <template x-if="gameMode !== 'duel'">
                <div class="bg-gradient-to-b from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-lg space-y-2 relative overflow-hidden">
                    <p class="text-xs font-semibold opacity-90 uppercase tracking-widest">Skor Akhirmu</p>
                    <h3 x-text="score" class="font-fredoka font-extrabold text-5xl tracking-wide">1250</h3>
                    
                    <div class="pt-4 border-t border-white/20 flex justify-between text-xs font-medium px-2">
                        <div>
                            <span class="opacity-80 block text-[9px] uppercase">Benar</span>
                            <strong x-text="`${correctCount}/${totalSorted}`" class="text-sm">12/15</strong>
                        </div>
                        <div>
                            <span class="opacity-80 block text-[9px] uppercase">Gelar Detektif</span>
                            <strong x-text="detectiveTitle" class="text-sm">Master Kompos</strong>
                        </div>
                        <div x-show="globalRank !== null">
                            <span class="opacity-80 block text-[9px] uppercase">Peringkat</span>
                            <strong x-text="`#${globalRank}`" class="text-sm">#3</strong>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Skor & Hasil Duel (Mode Duel 1 Frame) -->
            <template x-if="gameMode === 'duel'">
                <div class="space-y-4">
                    <!-- Winner Declaration Card -->
                    <div class="bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 rounded-[2rem] p-5 text-white shadow-md text-center border-b-4 border-amber-600">
                        <h3 class="font-fredoka font-extrabold text-lg sm:text-xl tracking-wide uppercase">Hasil Pertarungan! ⚔️</h3>
                        <p class="font-fredoka font-bold text-base sm:text-lg mt-1" x-text="p1Score > p2Score ? p1Name + ' Menang! 🎉🏆' : (p2Score > p1Score ? p2Name + ' Menang! 🎉🏆' : 'Sama Kuat! Duel Seri! 🤝')"></p>
                    </div>

                    <!-- Scores Side-by-Side -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Player 1 Score Card -->
                        <div class="bg-gradient-to-b from-red-500 to-red-600 rounded-[2rem] p-5 text-white shadow-md space-y-1 text-center">
                            <span class="text-[10px] font-semibold opacity-90 uppercase block truncate" x-text="p1Name">Pemain 1</span>
                            <span class="font-fredoka font-bold text-3xl sm:text-4xl tracking-wide block mt-1" x-text="p1Score">0</span>
                            <span class="text-[9px] block opacity-80 mt-1" x-text="'Benar: ' + p1CorrectCount + '/' + p1TotalSorted">Benar: 0/0</span>
                        </div>

                        <!-- Player 2 Score Card -->
                        <div class="bg-gradient-to-b from-teal-500 to-teal-600 rounded-[2rem] p-5 text-white shadow-md space-y-1 text-center">
                            <span class="text-[10px] font-semibold opacity-90 uppercase block truncate" x-text="p2Name">Pemain 2</span>
                            <span class="font-fredoka font-bold text-3xl sm:text-4xl tracking-wide block mt-1" x-text="p2Score">0</span>
                            <span class="text-[9px] block opacity-80 mt-1" x-text="'Benar: ' + p2CorrectCount + '/' + p2TotalSorted">Benar: 0/0</span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Fakta Edukasi Super Singkat -->
            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-left">
                <h4 class="font-fredoka font-bold text-xs text-emerald-800 mb-1 flex items-center">
                    <span class="mr-1">💡</span> Tahukah Kamu?
                </h4>
                <p x-text="randomFact" class="text-xs text-emerald-950 font-medium leading-relaxed">
                    Botol plastik butuh ratusan tahun untuk hancur di tanah!
                </p>
            </div>

            <!-- Review Kesalahan (Self-Correction) -->
            <div x-show="gameMode !== 'duel' && mistakeList.length > 0" class="text-left space-y-3">
                <h4 class="font-fredoka font-bold text-sm text-gray-800 flex items-center">
                    <span class="mr-1.5">🔍</span> Review Salah (Ayo Belajar!)
                </h4>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    <template x-for="item in mistakeList">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                            <!-- Image Thumbnail -->
                            <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 p-1 shrink-0 overflow-hidden flex items-center justify-center shadow-xs">
                                <img :src="getImageUrl(item.image)" class="w-full h-full object-contain rounded-lg" x-on:error="$event.target.src='https://placehold.co/100?text=' + encodeURIComponent(item.name)">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 x-text="item.name" class="font-bold text-xs text-gray-800 truncate">Kulit Telur</h5>
                                <p class="text-[9px] text-red-500 font-semibold mt-0.5">
                                    Kamu pilih: <span class="capitalize" x-text="item.chosen"></span> • 
                                    <span class="text-emerald-600">Harusnya: <span class="capitalize" x-text="item.correct"></span></span>
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-100">
                <button @click="resetGame()" class="py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-fredoka font-bold text-sm rounded-2xl shadow-md border-b-4 border-emerald-700 tap-scale">
                    Main Lagi 🔄
                </button>
                <a href="/" class="py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-bold text-sm rounded-2xl transition text-center flex items-center justify-center">
                    Keluar 🚪
                </a>
            </div>
        </div>

        <!-- ================= FASE 3.5: POP-UP BELAJAR MODAL (Belajar Mandiri) ================= -->
        <div x-show="showBelajarModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-md"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-md"
             x-transition:leave-end="opacity-0 backdrop-blur-none"
             class="fixed inset-0 bg-emerald-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4 overflow-y-auto" 
             x-cloak>
            <div x-show="showBelajarModal"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                 class="bg-white rounded-[2rem] p-5 sm:p-6 text-center w-full max-w-sm border-4 border-emerald-500 shadow-2xl flex flex-col items-center justify-between space-y-3.5 my-auto max-h-[92vh] overflow-y-auto relative">
                
                <!-- Status Header -->
                <div class="space-y-0.5">
                    <span x-show="lastAnswerCorrect" class="text-4xl block animate-bounce">🎉</span>
                    <span x-show="!lastAnswerCorrect" class="text-4xl block animate-pulse">😢</span>
                    <h3 x-text="lastAnswerCorrect ? 'HEBAT! BENAR!' : 'YAH, KURANG TEPAT!'" 
                        :class="lastAnswerCorrect ? 'text-emerald-600' : 'text-red-500'" 
                        class="font-fredoka font-extrabold text-xl mt-1 tracking-wider"></h3>
                </div>

                <!-- Objek / Sampah info (Besar & Jelas) -->
                <div class="bg-emerald-50 rounded-2xl border-2 border-emerald-200 flex items-center justify-center w-32 h-32 sm:w-36 sm:h-36 shadow-inner shrink-0 overflow-hidden relative">
                    <template x-if="lastQuestion">
                        <img :src="getImageUrl(lastQuestion.gambar)" 
                             :alt="lastQuestion.nama_sampah" 
                             class="w-full h-full object-cover"
                             x-on:error="$event.target.src='https://placehold.co/200?text=' + encodeURIComponent(lastQuestion ? lastQuestion.nama_sampah : 'Sampah')">
                    </template>
                </div>

                <div class="space-y-1">
                    <h4 x-text="lastQuestion ? lastQuestion.nama_sampah : ''" class="font-fredoka font-extrabold text-lg text-emerald-950 uppercase tracking-wide"></h4>
                    <div>
                        <span x-show="lastQuestion && lastQuestion.kategori === 'organik'" class="px-3 py-1 text-xs font-bold text-emerald-800 bg-emerald-100 rounded-full border border-emerald-200 shadow-xs inline-flex items-center gap-1">
                            <span>Kategori: Organik</span> <span>🟢</span>
                        </span>
                        <span x-show="lastQuestion && lastQuestion.kategori === 'anorganik'" class="px-3 py-1 text-xs font-bold text-yellow-800 bg-yellow-100 rounded-full border border-yellow-200 shadow-xs inline-flex items-center gap-1">
                            <span>Kategori: Anorganik</span> <span>🟡</span>
                        </span>
                        <span x-show="lastQuestion && lastQuestion.kategori === 'b3'" class="px-3 py-1 text-xs font-bold text-red-800 bg-red-100 rounded-full border border-red-200 shadow-xs inline-flex items-center gap-1">
                            <span>Kategori: B3</span> <span>🔴</span>
                        </span>
                    </div>
                </div>

                <!-- Fakta Edukasi Text -->
                <div class="bg-emerald-50/60 rounded-2xl p-3.5 text-xs font-medium text-emerald-950 leading-relaxed border border-emerald-100 text-left w-full shadow-inner">
                    <div class="flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700 mb-1">
                        <span>💡</span>
                        <span>Tahukah Kamu?</span>
                    </div>
                    <p x-text="lastQuestion ? lastQuestion.fakta_edukasi : ''" class="font-semibold"></p>
                </div>

                <!-- Lanjut Button -->
                <button @click="nextFromBelajarModal()" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-fredoka font-bold text-base rounded-2xl shadow-lg border-b-4 border-emerald-700 transition active:translate-y-0.5 tap-scale flex items-center justify-center gap-1.5 shrink-0">
                    <span>Lanjut Bermain</span>
                    <span class="text-lg">➡️</span>
                </button>
            </div>
        </div>

    </div>

    <!-- JAVASCRIPT GAME ENGINE LOGIC -->
    <script>
        let toneSynth = null;
        let toneBassSynth = null;
        let toneSeq = null;
        let toneBassSeq = null;
        let isToneInitialized = false;

        function gameEngine() {
            return {
                init() {
                    this.checkMobileAndOrientation();
                    window.addEventListener('resize', () => this.checkMobileAndOrientation());
                    window.addEventListener('orientationchange', () => this.checkMobileAndOrientation());

                    window.addEventListener('keydown', (e) => {
                        if (this.gameState !== 'playing' || this.gameMode !== 'duel') return;
                        const key = e.key.toLowerCase();
                        
                        // Player 1 (Left): A (Organik), S (Anorganik), D (B3)
                        if (key === 'a' || key === 's' || key === 'd') {
                            if (this.playerRole === 'p2') return; // P2 tidak bisa mengontrol P1
                            if (key === 'a') this.sortWaste('p1', 'organik');
                            if (key === 's') this.sortWaste('p1', 'anorganik');
                            if (key === 'd' && this.studentGrade === '4-6') this.sortWaste('p1', 'b3');
                        }
                        
                        // Player 2 (Right): J (Organik), K (Anorganik), L (B3)
                        if (key === 'j' || key === 'k' || key === 'l') {
                            if (this.playerRole === 'p1') return; // P1 tidak bisa mengontrol P2
                            if (key === 'j') this.sortWaste('p2', 'organik');
                            if (key === 'k') this.sortWaste('p2', 'anorganik');
                            if (key === 'l' && this.studentGrade === '4-6') this.sortWaste('p2', 'b3');
                        }
                    });
                },
                checkMobileAndOrientation() {
                    this.isMobile = (window.innerWidth <= 850) || ('ontouchstart' in window) || /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
                    this.isPortrait = window.innerHeight > window.innerWidth;
                },
                // Background & Theme settings
                currentTheme: localStorage.getItem('detektif_theme') || 'forest',
                showThemeMenu: false,
                setTheme(theme) {
                    this.currentTheme = theme;
                    localStorage.setItem('detektif_theme', theme);
                    this.playSynthSound('select');
                },
                spawnClickParticle(e) {
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT' || e.target.tagName === 'TEXTAREA' || e.target.closest('button')) {
                        return;
                    }
                    
                    const x = e.clientX;
                    const y = e.clientY;
                    
                    const particle = document.createElement('div');
                    particle.className = 'click-particle';
                    
                    let emoji = '✨';
                    if (this.currentTheme === 'forest') {
                        const forestEmojis = ['🍃', '🍂', '🍁', '🦋'];
                        emoji = forestEmojis[Math.floor(Math.random() * forestEmojis.length)];
                    } else if (this.currentTheme === 'ocean') {
                        const oceanEmojis = ['🫧', '🫧', '🐠', '🐟'];
                        emoji = oceanEmojis[Math.floor(Math.random() * oceanEmojis.length)];
                    } else if (this.currentTheme === 'space') {
                        const spaceEmojis = ['⭐', '✨', '☄️', '🪐'];
                        emoji = spaceEmojis[Math.floor(Math.random() * spaceEmojis.length)];
                    } else if (this.currentTheme === 'garden') {
                        const gardenEmojis = ['🌸', '🌸', '🐝', '🌼'];
                        emoji = gardenEmojis[Math.floor(Math.random() * gardenEmojis.length)];
                    } else if (this.currentTheme === 'balloon') {
                        const balloonEmojis = ['🎈', '✨', '🎈', '🎉'];
                        emoji = balloonEmojis[Math.floor(Math.random() * balloonEmojis.length)];
                    }
                    
                    particle.innerText = emoji;
                    particle.style.left = `${x}px`;
                    particle.style.top = `${y}px`;
                    
                    document.body.appendChild(particle);
                    
                    setTimeout(() => {
                        particle.remove();
                    }, 850);
                },
                async startBgmOnFirstInteraction() {
                    this.initAudio();
                    await Tone.start();
                    if (Tone.context.state === 'suspended') {
                        await Tone.context.resume();
                    }
                    if (this.audioCtx && this.audioCtx.state === 'suspended') {
                        this.audioCtx.resume();
                    }
                    this.startBgMusic();
                },

                // Sesi data dari server
                sessionCode: '{{ $session->game_code }}',
                getImageUrl(path) {
                    if (!path) return 'https://placehold.co/100?text=';
                    return path.startsWith('/') ? path : '/' + path;
                },
                allQuestions: @json($questions),
                gameMode: '{{ $session->game_mode }}',
                isStartedFromServer: {{ $session->is_started ? 'true' : 'false' }},
                
                // Game States
                gameState: 'loading',
                loadingProgress: 0,
                
                // Student Identity
                studentName: '',
                playerRole: 'p1', // 'p1', 'p2', atau 'both'
                isMobile: false,
                isPortrait: false,
                dismissMobileNotice: false,
                p1Name: '',
                p2Name: '',
                studentGrade: '',
                showNameError: false,
                showGradeError: false,
                duelStep: 1, // 1 = input P1, 2 = input P2

                // Playing states
                filteredQuestions: [],
                currentIndex: 0,
                currentQuestion: null,
                timer: 45,
                maxTimer: 45,
                timerInterval: null,
                isShaking: false,

                // Player 1 (Left) states
                p1Questions: [],
                p1CurrentIndex: 0,
                p1CurrentQuestion: null,
                p1Score: 0,
                p1Combo: 0,
                p1CorrectCount: 0,
                p1TotalSorted: 0,
                p1IsShaking: false,
                p1MistakeList: [],
                p1QuestionsShownIds: [],
                p1QuestionsWrongIds: [],

                // Player 2 (Right) states
                p2Questions: [],
                p2CurrentIndex: 0,
                p2CurrentQuestion: null,
                p2Score: 0,
                p2Combo: 0,
                p2CorrectCount: 0,
                p2TotalSorted: 0,
                p2IsShaking: false,
                p2MistakeList: [],
                p2QuestionsShownIds: [],
                p2QuestionsWrongIds: [],
                
                // Mode Belajar States
                showBelajarModal: false,
                lastQuestion: null,
                lastAnswerCorrect: true,

                // Educational Wrong Hint Banner
                showWrongBanner: false,
                wrongBannerText: '',
                wrongBannerTimeout: null,

                // Mode Duel States
                duelPollInterval: null,
                
                // Stats
                score: 0,
                combo: 0,
                correctCount: 0,
                totalSorted: 0,
                globalRank: null,

                // Arrays for submit score analytics
                questionsShownIds: [],
                questionsWrongIds: [],
                mistakeList: [], // { name, category, chosen, correct, image, fact }

                // Facts and Titles list
                randomFact: '',
                detectiveTitle: 'Detektif Cilik',

                // Audio State & Engine
                audioCtx: null,
                bgmMasterGain: null,
                sfxMasterGain: null,
                isMusicMuted: false,
                isSfxMuted: false,
                bgMusicInterval: null,
                bgMusicStep: 0,

                // PRELOAD ASSETS
                preloadAssets() {
                    let loaded = 0;
                    const total = this.allQuestions.length;

                    if (total === 0) {
                        this.loadingProgress = 100;
                        this.gameState = 'welcome';
                        return;
                    }

                    this.allQuestions.forEach((q) => {
                        const img = new Image();
                        img.src = this.getImageUrl(q.gambar);
                        img.onload = () => {
                            loaded++;
                            this.loadingProgress = Math.round((loaded / total) * 100);
                            if (loaded === total) {
                                setTimeout(() => {
                                    this.gameState = 'welcome';
                                }, 500);
                            }
                        };
                        img.onerror = () => {
                            loaded++;
                            this.loadingProgress = Math.round((loaded / total) * 100);
                            if (loaded === total) {
                                setTimeout(() => {
                                    this.gameState = 'welcome';
                                }, 500);
                            }
                        };
                    });
                },

                // SELECT GRADE
                selectGrade(grade) {
                    this.studentGrade = grade;
                    this.showGradeError = false;
                    this.playSynthSound('select');
                },

                // AUDIO CONTROLS
                toggleMusic() {
                    this.isMusicMuted = !this.isMusicMuted;
                    if (isToneInitialized) {
                        toneSynth.volume.value = this.isMusicMuted ? -99 : -6;
                        toneBassSynth.volume.value = this.isMusicMuted ? -99 : -10;
                    }
                    if (!this.isMusicMuted && (this.gameState === 'welcome' || this.gameState === 'waiting_duel' || this.gameState === 'playing')) {
                        this.startBgMusic();
                    } else if (this.isMusicMuted) {
                        this.stopBgMusic();
                    }
                },

                toggleSfx() {
                    this.isSfxMuted = !this.isSfxMuted;
                    if (this.sfxMasterGain && this.audioCtx) {
                        this.sfxMasterGain.gain.setValueAtTime(this.isSfxMuted ? 0 : 0.2, this.audioCtx.currentTime);
                    }
                },

                getKategoriLabel(kategori) {
                    if (kategori === 'organik') return 'Organik (Bak Hijau 🟢)';
                    if (kategori === 'anorganik') return 'Anorganik (Bak Kuning 🟡)';
                    if (kategori === 'b3') return 'B3 Berbahaya (Bak Merah 🔴)';
                    return kategori;
                },


                // START GAME
                startGame() {
                    // Validasi — semua mode pakai studentName
                    this.showNameError = !this.studentName.trim();
                    this.showGradeError = !this.studentGrade;

                    if (this.showNameError || this.showGradeError) {
                        this.playSynthSound('error');
                        return;
                    }

                    // Setup Audio Context
                    this.initAudio();

                    // Filter Questions based on difficulty
                    if (this.studentGrade === '2' || this.studentGrade === '3') {
                        this.filteredQuestions = this.allQuestions.filter(q => q.kategori !== 'b3');
                        this.timer = 60;
                        this.maxTimer = 60;
                    } else {
                        this.filteredQuestions = [...this.allQuestions];
                        this.timer = 45;
                        this.maxTimer = 45;
                    }

                    // Shuffle filtered questions
                    this.filteredQuestions = this.filteredQuestions.sort(() => Math.random() - 0.5);

                    // LOGIKA MODE DUEL: Tahan jika belum di-start oleh guru
                    if (this.gameMode === 'duel' && !this.isStartedFromServer) {
                        this.gameState = 'waiting_duel';
                        this.playSynthSound('select');

                        // Jalankan polling status setiap 2 detik
                        this.duelPollInterval = setInterval(() => {
                            fetch(`/play/${this.sessionCode}/status`)
                                .then(res => res.json())
                                .then(data => {
                                    if (data.is_started) {
                                        clearInterval(this.duelPollInterval);
                                        this.isStartedFromServer = true;
                                        this.launchActiveGame();
                                    }
                                })
                                .catch(err => console.error('Error polling status:', err));
                        }, 2000);
                    } else {
                        this.launchActiveGame();
                    }
                },

                // LAUNCH ACTIVE GAMEPLAY SCREEN
                launchActiveGame() {
                    if (this.gameMode === 'duel') {
                        // Atur nama pemain berdasarkan playerRole
                        if (this.playerRole === 'p1') {
                            this.p1Name = this.studentName || 'Pemain 1';
                            this.p2Name = 'Pemain 2';
                        } else if (this.playerRole === 'p2') {
                            this.p1Name = 'Pemain 1';
                            this.p2Name = this.studentName || 'Pemain 2';
                        } else {
                            this.p1Name = (this.studentName || 'Pemain 1') + ' (P1)';
                            this.p2Name = 'Pemain 2 (P2)';
                        }

                        // Reset P1 stats
                        this.p1Score = 0;
                        this.p1Combo = 0;
                        this.p1CorrectCount = 0;
                        this.p1TotalSorted = 0;
                        this.p1CurrentIndex = 0;
                        this.p1QuestionsShownIds = [];
                        this.p1QuestionsWrongIds = [];
                        this.p1MistakeList = [];
                        this.p1IsShaking = false;

                        this.p2Score = 0;
                        this.p2Combo = 0;
                        this.p2CorrectCount = 0;
                        this.p2TotalSorted = 0;
                        this.p2CurrentIndex = 0;
                        this.p2QuestionsShownIds = [];
                        this.p2QuestionsWrongIds = [];
                        this.p2MistakeList = [];
                        this.p2IsShaking = false;

                        // Separate shuffles for each player to prevent copying
                        this.p1Questions = [...this.filteredQuestions].sort(() => Math.random() - 0.5);
                        this.p2Questions = [...this.filteredQuestions].sort(() => Math.random() - 0.5);

                        this.p1CurrentQuestion = this.p1Questions[0];
                        this.p2CurrentQuestion = this.p2Questions[0];

                        this.p1QuestionsShownIds.push(this.p1CurrentQuestion.id);
                        this.p2QuestionsShownIds.push(this.p2CurrentQuestion.id);
                    } else {
                        // Reset single player gameplay stats
                        this.score = 0;
                        this.combo = 0;
                        this.correctCount = 0;
                        this.totalSorted = 0;
                        this.currentIndex = 0;
                        this.questionsShownIds = [];
                        this.questionsWrongIds = [];
                        this.mistakeList = [];
                        this.globalRank = null;
                        this.showWrongBanner = false;

                        // Load first question
                        this.loadQuestion();
                    }

                    // Change state
                    this.gameState = 'playing';

                    // Play start sound & BGM Backsound
                    this.playSynthSound('start');
                    this.startBgMusic();

                    // Start Timer
                    this.startTimerLoop();
                },

                // START TIMER LOOP
                startTimerLoop() {
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.timerInterval = setInterval(() => {
                        // Jangan kurangi waktu jika pop-up belajar sedang aktif
                        if (this.showBelajarModal) return;

                        this.timer--;
                        if (this.timer <= 5 && this.timer > 0) {
                            this.playSynthSound('tick');
                        }
                        if (this.timer <= 0) {
                            this.endGame();
                        }
                    }, 1000);
                },

                // LOAD QUESTION
                loadQuestion() {
                    if (this.currentIndex >= this.filteredQuestions.length) {
                        // Jika soal habis sebelum waktu habis, acak ulang dan loop kembali
                        this.filteredQuestions = this.filteredQuestions.sort(() => Math.random() - 0.5);
                        this.currentIndex = 0;
                    }

                    this.currentQuestion = this.filteredQuestions[this.currentIndex];
                    
                    // Rekam ID soal yang ditampilkan
                    if (!this.questionsShownIds.includes(this.currentQuestion.id)) {
                        this.questionsShownIds.push(this.currentQuestion.id);
                    }
                },

                // SORT WASTE (BAK CLICKED)
                sortWaste(arg1, arg2) {
                    if (arg1 === 'p1' || arg1 === 'p2') {
                        // Duel Mode (Check Role Permission)
                        const player = arg1;
                        if (this.playerRole === 'p1' && player !== 'p1') return; // Blir jika P1 klik P2
                        if (this.playerRole === 'p2' && player !== 'p2') return; // Blir jika P2 klik P1

                        const chosenCategory = arg2;

                        if (player === 'p1') {
                            if (!this.p1CurrentQuestion) return;
                            const correctCategory = this.p1CurrentQuestion.kategori;
                            this.p1TotalSorted++;
                            let isCorrect = (chosenCategory === correctCategory);

                            if (isCorrect) {
                                this.p1CorrectCount++;
                                this.p1Combo++;
                                let pts = 100;
                                if (this.p1Combo >= 3) pts += 20;
                                this.p1Score += pts;
                                this.playSynthSound('correct');
                            } else {
                                this.p1Combo = 0;
                                this.p1Score = Math.max(0, this.p1Score - 20);
                                if (!this.p1QuestionsWrongIds.includes(this.p1CurrentQuestion.id)) {
                                    this.p1QuestionsWrongIds.push(this.p1CurrentQuestion.id);
                                }
                                this.p1MistakeList.push({
                                    name: this.p1CurrentQuestion.nama_sampah,
                                    category: correctCategory,
                                    chosen: chosenCategory,
                                    correct: correctCategory,
                                    image: this.p1CurrentQuestion.gambar,
                                    fact: this.p1CurrentQuestion.fakta_edukasi
                                });
                                this.p1IsShaking = true;
                                setTimeout(() => { this.p1IsShaking = false; }, 400);
                                this.playSynthSound('wrong');
                            }

                            // Advance to next P1 question
                            this.p1CurrentIndex++;
                            if (this.p1CurrentIndex >= this.p1Questions.length) {
                                this.p1Questions = [...this.p1Questions].sort(() => Math.random() - 0.5);
                                this.p1CurrentIndex = 0;
                            }
                            this.p1CurrentQuestion = this.p1Questions[this.p1CurrentIndex];
                            if (!this.p1QuestionsShownIds.includes(this.p1CurrentQuestion.id)) {
                                this.p1QuestionsShownIds.push(this.p1CurrentQuestion.id);
                            }
                        } else {
                            // Player 2
                            if (!this.p2CurrentQuestion) return;
                            const correctCategory = this.p2CurrentQuestion.kategori;
                            this.p2TotalSorted++;
                            let isCorrect = (chosenCategory === correctCategory);

                            if (isCorrect) {
                                this.p2CorrectCount++;
                                this.p2Combo++;
                                let pts = 100;
                                if (this.p2Combo >= 3) pts += 20;
                                this.p2Score += pts;
                                this.playSynthSound('correct');
                            } else {
                                this.p2Combo = 0;
                                this.p2Score = Math.max(0, this.p2Score - 20);
                                if (!this.p2QuestionsWrongIds.includes(this.p2CurrentQuestion.id)) {
                                    this.p2QuestionsWrongIds.push(this.p2CurrentQuestion.id);
                                }
                                this.p2MistakeList.push({
                                    name: this.p2CurrentQuestion.nama_sampah,
                                    category: correctCategory,
                                    chosen: chosenCategory,
                                    correct: correctCategory,
                                    image: this.p2CurrentQuestion.gambar,
                                    fact: this.p2CurrentQuestion.fakta_edukasi
                                });
                                this.p2IsShaking = true;
                                setTimeout(() => { this.p2IsShaking = false; }, 400);
                                this.playSynthSound('wrong');
                            }

                            // Advance to next P2 question
                            this.p2CurrentIndex++;
                            if (this.p2CurrentIndex >= this.p2Questions.length) {
                                this.p2Questions = [...this.p2Questions].sort(() => Math.random() - 0.5);
                                this.p2CurrentIndex = 0;
                            }
                            this.p2CurrentQuestion = this.p2Questions[this.p2CurrentIndex];
                            if (!this.p2QuestionsShownIds.includes(this.p2CurrentQuestion.id)) {
                                this.p2QuestionsShownIds.push(this.p2CurrentQuestion.id);
                            }
                        }
                    } else {
                        // Single Player Mode
                        const chosenCategory = arg1;
                        if (!this.currentQuestion) return;

                        const correctCategory = this.currentQuestion.kategori;
                        const itemName = this.currentQuestion.nama_sampah;
                        this.totalSorted++;

                        let isCorrect = (chosenCategory === correctCategory);

                        if (isCorrect) {
                            // JAWABAN BENAR
                            this.correctCount++;
                            this.combo++;
                            
                            // Kalkulasi poin
                            let pts = 100;
                            if (this.combo >= 3) {
                                // Bonus combo streak 🔥
                                pts += 20;
                            }
                            this.score += pts;

                            this.playSynthSound('correct');
                            this.showWrongBanner = false;
                        } else {
                            // JAWABAN SALAH
                            this.combo = 0;
                            this.score = Math.max(0, this.score - 20); // Pengurangan skor, minimum 0

                            // Animasi visual shake
                            this.isShaking = true;
                            setTimeout(() => { this.isShaking = false; }, 400);

                            // Rekam ID untuk analytics
                            if (!this.questionsWrongIds.includes(this.currentQuestion.id)) {
                                this.questionsWrongIds.push(this.currentQuestion.id);
                            }

                            // Rekam ke list review kesalahan
                            this.mistakeList.push({
                                name: itemName,
                                category: correctCategory,
                                chosen: chosenCategory,
                                correct: correctCategory,
                                image: this.currentQuestion.gambar,
                                fact: this.currentQuestion.fakta_edukasi
                            });

                            // Mainkan Audio Lucu & Menarik Saat Salah (Cartoon Uh-Oh & Boing!)
                            this.playSynthSound('wrong');

                            // Di Mode Standar / Duel: Tampilkan Banner Edukasi & Ucapkan Suara Petunjuk
                            if (this.gameMode !== 'belajar') {
                                this.showInformativeWrongHint(itemName, correctCategory);
                                this.speakWrongHint(itemName, correctCategory);
                            } else {
                                this.showWrongBanner = false;
                            }
                        }

                        // LOGIKA MODE BELAJAR MANDIRI
                        if (this.gameMode === 'belajar') {
                            this.showWrongBanner = false;
                            this.lastQuestion = this.currentQuestion;
                            this.lastAnswerCorrect = isCorrect;
                            this.showBelajarModal = true;
                            
                            // Hentikan BGM agar anak bisa mendengar suara dubbing dengan jelas
                            this.stopBgMusic();

                            // Jalankan Text-to-Speech AI
                            this.speakFact(this.currentQuestion.fakta_edukasi);
                        } else {
                            // Pindah ke soal berikutnya langsung (Mode default/duel)
                            this.currentIndex++;
                            this.loadQuestion();
                        }
                    }
                },

                // TAMPILKAN BANNER PETUNJUK EDUKASI
                showInformativeWrongHint(itemName, correctCategory) {
                    if (this.wrongBannerTimeout) {
                        clearTimeout(this.wrongBannerTimeout);
                    }
                    const badgeClass = correctCategory === 'organik' 
                        ? 'text-emerald-700 bg-emerald-100' 
                        : (correctCategory === 'anorganik' ? 'text-yellow-800 bg-yellow-100' : 'text-red-700 bg-red-100');
                    
                    this.wrongBannerText = `Ups! <strong>${itemName}</strong> harusnya masuk ke <span class="px-1.5 py-0.5 rounded-md ${badgeClass} font-extrabold uppercase">${correctCategory}</span> ya!`;
                    this.showWrongBanner = true;

                    this.wrongBannerTimeout = setTimeout(() => {
                        this.showWrongBanner = false;
                    }, 3200);
                },

                // SUARA PETUNJUK EDUKATIF RAMAH ANAK SAAT SALAH
                speakWrongHint(itemName, correctCategory) {
                    if (this.isSfxMuted || !window.speechSynthesis) return;

                    // Ducking BGM: kecilkan backsound saat dubbing berbicara
                    this.duckBgMusic(true);

                    window.speechSynthesis.cancel();
                    const text = `Ups! ${itemName} itu sampah ${correctCategory} ya!`;
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';

                    const voices = window.speechSynthesis.getVoices();
                    const idVoice = voices.find(v => v.lang.includes('id-ID') || v.lang.includes('id_ID'));
                    if (idVoice) utterance.voice = idVoice;

                    // Karakter suara kartun ceria & ramah
                    utterance.pitch = 1.35;
                    utterance.rate = 1.1;

                    utterance.onend = () => {
                        this.duckBgMusic(false);
                    };
                    utterance.onerror = () => {
                        this.duckBgMusic(false);
                    };

                    window.speechSynthesis.speak(utterance);
                },

                // LANJUT DARI MODAL BELAJAR
                nextFromBelajarModal() {
                    // Batalkan dubbing yang sedang berjalan
                    if (window.speechSynthesis) {
                        window.speechSynthesis.cancel();
                    }

                    this.showBelajarModal = false;
                    
                    // Lanjutkan BGM
                    this.startBgMusic();

                    // Pindah ke soal berikutnya
                    this.currentIndex++;
                    this.loadQuestion();
                },

                // DUBBING AI MENGGUNAKAN WEB SPEECH API SYNTHESIS
                speakFact(text) {
                    if (this.isSfxMuted || !window.speechSynthesis) return;

                    // Batalkan dubbing sebelumnya agar tidak bertumpuk
                    window.speechSynthesis.cancel();

                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';

                    const voices = window.speechSynthesis.getVoices();
                    const idVoice = voices.find(voice => voice.lang.includes('id-ID') || voice.lang.includes('id_ID'));
                    if (idVoice) {
                        utterance.voice = idVoice;
                    }

                    // Nada ramah ceria
                    utterance.pitch = 1.25; 
                    utterance.rate = 1.02;

                    window.speechSynthesis.speak(utterance);
                },

                // END GAME
                endGame() {
                    clearInterval(this.timerInterval);
                    if (this.duelPollInterval) {
                        clearInterval(this.duelPollInterval);
                        this.duelPollInterval = null;
                    }
                    if (window.speechSynthesis) {
                        window.speechSynthesis.cancel();
                    }
                    this.showWrongBanner = false;
                    this.gameState = 'results';
                    this.playSynthSound('gameover');
                    
                    // Stop background music
                    this.stopBgMusic();

                    if (this.gameMode === 'duel') {
                        // Tentukan pesan fakta edukasi acak dari kesalahan salah satu pemain
                        const allMistakes = [...this.p1MistakeList, ...this.p2MistakeList];
                        if (allMistakes.length > 0) {
                            const randomMistake = allMistakes[Math.floor(Math.random() * allMistakes.length)];
                            this.randomFact = randomMistake.fact || 'Sampah organik bisa diolah jadi kompos!';
                        } else if (this.allQuestions.length > 0) {
                            const randomQ = this.allQuestions[Math.floor(Math.random() * this.allQuestions.length)];
                            this.randomFact = randomQ.fakta_edukasi || 'Mari pilah sampah setiap hari!';
                        } else {
                            this.randomFact = 'Sampah plastik butuh ratusan tahun untuk hancur!';
                        }

                        // Kirim skor kedua pemain ke server
                        this.submitScoreToDatabase('p1');
                        this.submitScoreToDatabase('p2');
                    } else {
                        // Dapatkan Gelar Detektif Cilik berdasarkan performa
                        const accuracy = this.correctCount / (this.totalSorted || 1);
                        if (accuracy >= 0.9) {
                            this.detectiveTitle = '🕵️‍♂️ Detektif Master';
                        } else if (accuracy >= 0.7) {
                            this.detectiveTitle = '🕵️‍♀️ Agen Lingkungan';
                        } else if (accuracy >= 0.4) {
                            this.detectiveTitle = '🌱 Sahabat Bumi';
                        } else {
                            this.detectiveTitle = '🧹 Relawan Hijau';
                        }

                        // Tentukan pesan fakta edukasi acak
                        if (this.mistakeList.length > 0) {
                            const randomMistake = this.mistakeList[Math.floor(Math.random() * this.mistakeList.length)];
                            this.randomFact = randomMistake.fact || 'Sampah organik bisa diolah jadi kompos!';
                        } else if (this.allQuestions.length > 0) {
                            const randomQ = this.allQuestions[Math.floor(Math.random() * this.allQuestions.length)];
                            this.randomFact = randomQ.fakta_edukasi || 'Mari pilah sampah setiap hari!';
                        } else {
                            this.randomFact = 'Sampah plastik butuh ratusan tahun untuk hancur!';
                        }

                        // Submit skor ke backend Laravel
                        this.submitScoreToDatabase();
                    }
                },

                // SUBMIT SCORE TO SERVER
                submitScoreToDatabase(player) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let name = this.studentName;
                    let finalScore = this.score;
                    let correct = this.correctCount;
                    let total = this.totalSorted || 1;
                    let shown = this.questionsShownIds;
                    let wrong = this.questionsWrongIds;

                    if (player === 'p1') {
                        name = this.p1Name;
                        finalScore = this.p1Score;
                        correct = this.p1CorrectCount;
                        total = this.p1TotalSorted || 1;
                        shown = this.p1QuestionsShownIds;
                        wrong = this.p1QuestionsWrongIds;
                    } else if (player === 'p2') {
                        name = this.p2Name;
                        finalScore = this.p2Score;
                        correct = this.p2CorrectCount;
                        total = this.p2TotalSorted || 1;
                        shown = this.p2QuestionsShownIds;
                        wrong = this.p2QuestionsWrongIds;
                    }

                    fetch(`/play/${this.sessionCode}/submit-score`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            nama_siswa: name,
                            kelas: this.studentGrade,
                            skor_akhir: finalScore,
                            jawaban_benar: correct,
                            total_sampah: total,
                            questions_shown: shown,
                            questions_wrong: wrong
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success' && !player) {
                            this.globalRank = data.ranking;
                        }
                    })
                    .catch(err => console.error('Error submitting score:', err));
                },

                // RESET GAME (PLAY AGAIN)
                resetGame() {
                    this.gameState = 'welcome';
                    this.score = 0;
                    this.combo = 0;
                    this.correctCount = 0;
                    this.totalSorted = 0;
                    this.globalRank = null;
                    this.mistakeList = [];
                    this.showWrongBanner = false;

                    this.p1Score = 0;
                    this.p1Combo = 0;
                    this.p1CorrectCount = 0;
                    this.p1TotalSorted = 0;
                    this.p1MistakeList = [];

                    this.p2Score = 0;
                    this.p2Combo = 0;
                    this.p2CorrectCount = 0;
                    this.p2TotalSorted = 0;
                    this.p2MistakeList = [];
                    
                    if (this.duelPollInterval) {
                        clearInterval(this.duelPollInterval);
                        this.duelPollInterval = null;
                    }
                    this.isStartedFromServer = false;
                    // Reset duel registration flow
                    this.duelStep = 1;
                    this.p1Name = '';
                    this.p2Name = '';
                    if (window.speechSynthesis) {
                        window.speechSynthesis.cancel();
                    }

                    // Stop background music
                    this.stopBgMusic();

                    // Restart welcome lobby BGM
                    setTimeout(() => {
                        this.startBgMusic();
                    }, 400);
                },

                // ================= SYNTH AUDIO & MUSIC ENGINE =================
                initAudio() {
                    try {
                        if (!this.audioCtx) {
                            window.AudioContext = window.AudioContext || window.webkitAudioContext;
                            this.audioCtx = new AudioContext();

                            // Master BGM Gain Node
                            this.bgmMasterGain = this.audioCtx.createGain();
                            this.bgmMasterGain.gain.setValueAtTime(this.isMusicMuted ? 0 : 0.12, this.audioCtx.currentTime);
                            this.bgmMasterGain.connect(this.audioCtx.destination);

                            // Master SFX Gain Node
                            this.sfxMasterGain = this.audioCtx.createGain();
                            this.sfxMasterGain.gain.setValueAtTime(this.isSfxMuted ? 0 : 0.25, this.audioCtx.currentTime);
                            this.sfxMasterGain.connect(this.audioCtx.destination);
                        }
                        if (this.audioCtx.state === 'suspended') {
                            this.audioCtx.resume();
                        }
                    } catch(e) {
                        console.error('Web Audio API not supported', e);
                    }
                },

                initTone() {
                    if (isToneInitialized) return;
                    
                    try {
                        toneSynth = new Tone.Synth({
                            oscillator: { type: "triangle" },
                            envelope: { attack: 0.01, decay: 0.15, sustain: 0.2, release: 0.3 }
                        }).toDestination();
                        toneSynth.volume.value = this.isMusicMuted ? -99 : -6;

                        toneBassSynth = new Tone.Synth({
                            oscillator: { type: "sine" },
                            envelope: { attack: 0.01, decay: 0.2, sustain: 0.3, release: 0.4 }
                        }).toDestination();
                        toneBassSynth.volume.value = this.isMusicMuted ? -99 : -10;

                        const melody = ["C5","E5","G5","E5","D5","F5","A5","F5","E5","G5","C6","G5","D5","F5","A5","G5"];
                        const bassline = ["C3","C3","G3","G3","F3","F3","C3","C3"];

                        toneSeq = new Tone.Sequence((time, note) => {
                            toneSynth.triggerAttackRelease(note, "8n", time);
                        }, melody, "8n");

                        toneBassSeq = new Tone.Sequence((time, note) => {
                            toneBassSynth.triggerAttackRelease(note, "4n", time);
                        }, bassline, "4n");

                        Tone.Transport.bpm.value = 132;
                        isToneInitialized = true;
                    } catch(e) {
                        console.error('Failed to initialize Tone.js', e);
                    }
                },

                // DUCK BGM VOLUME SAAT SUARA EDUKASI BERBICARA
                duckBgMusic(shouldDuck) {
                    if (!isToneInitialized || this.isMusicMuted) return;
                    try {
                        const targetMelodyVolume = shouldDuck ? -24 : -6;
                        const targetBassVolume = shouldDuck ? -28 : -10;
                        toneSynth.volume.rampTo(targetMelodyVolume, 0.15);
                        toneBassSynth.volume.rampTo(targetBassVolume, 0.15);
                    } catch(e) {
                        console.error('Error ducking BGM', e);
                    }
                },

                // ================= CHEERFUL KIDS BACKSOUND (BGM) =================
                startBgMusic() {
                    if (this.isMusicMuted) return;
                    this.initAudio();
                    this.initTone();

                    try {
                        if (Tone.Transport.state !== 'started') {
                            toneSeq.start(0);
                            toneBassSeq.start(0);
                            Tone.Transport.start();
                        }
                    } catch(e) {
                        console.error('Error starting BGM', e);
                    }
                },

                stopBgMusic() {
                    if (isToneInitialized) {
                        try {
                            toneSeq.stop();
                            toneBassSeq.stop();
                            Tone.Transport.stop();
                        } catch(e) {
                            console.error('Error stopping BGM', e);
                        }
                    }
                },

                // ================= SOUND EFFECTS (SFX) =================
                playSynthSound(type) {
                    if (this.isSfxMuted) return;
                    this.initAudio();
                    if (!this.audioCtx || this.audioCtx.state === 'suspended') return;

                    const now = this.audioCtx.currentTime;

                    if (type === 'start') {
                        // Fanfare gembira pembuka
                        const osc = this.audioCtx.createOscillator();
                        const gain = this.audioCtx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(261.63, now); // C4
                        osc.frequency.exponentialRampToValueAtTime(523.25, now + 0.35); // C5
                        gain.gain.setValueAtTime(0.2, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.35);
                        osc.connect(gain);
                        gain.connect(this.sfxMasterGain);
                        osc.start(now);
                        osc.stop(now + 0.35);
                    }
                    else if (type === 'select') {
                        // Klik tombol ceria
                        const osc = this.audioCtx.createOscillator();
                        const gain = this.audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(587.33, now); // D5
                        gain.gain.setValueAtTime(0.12, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.08);
                        osc.connect(gain);
                        gain.connect(this.sfxMasterGain);
                        osc.start(now);
                        osc.stop(now + 0.08);
                    }
                    else if (type === 'correct') {
                        // Suara Bintang Menang (Ding-Ding-Ting!)
                        const notes = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
                        notes.forEach((freq, i) => {
                            const osc = this.audioCtx.createOscillator();
                            const gain = this.audioCtx.createGain();
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(freq, now + (i * 0.06));

                            gain.gain.setValueAtTime(0.18, now + (i * 0.06));
                            gain.gain.exponentialRampToValueAtTime(0.001, now + (i * 0.06) + 0.22);

                            osc.connect(gain);
                            gain.connect(this.sfxMasterGain);
                            osc.start(now + (i * 0.06));
                            osc.stop(now + (i * 0.06) + 0.23);
                        });
                    }
                    else if (type === 'wrong') {
                        // ================= AUDIO KARTUN LUCU & MENARIK SAAT SALAH =================
                        // Efek "Wah-Wah-Wah" Kartun Melorot + "Boing!" Pegas yang menghibur anak
                        const wahNotes = [
                            { startFreq: 311.13, endFreq: 290, time: 0 },    // Eb4 -> D4
                            { startFreq: 293.66, endFreq: 270, time: 0.12 }, // D4 -> Db4
                            { startFreq: 277.18, endFreq: 230, time: 0.24 }  // Db4 -> Bb3
                        ];

                        wahNotes.forEach(w => {
                            const osc = this.audioCtx.createOscillator();
                            const gain = this.audioCtx.createGain();
                            osc.type = 'sawtooth';

                            // Efek vibrato meliuk
                            osc.frequency.setValueAtTime(w.startFreq, now + w.time);
                            osc.frequency.linearRampToValueAtTime(w.endFreq, now + w.time + 0.11);

                            gain.gain.setValueAtTime(0.12, now + w.time);
                            gain.gain.exponentialRampToValueAtTime(0.001, now + w.time + 0.11);

                            osc.connect(gain);
                            gain.connect(this.sfxMasterGain);
                            osc.start(now + w.time);
                            osc.stop(now + w.time + 0.12);
                        });

                        // Suara Pegas Kartun "Boing!" di akhir (0.36s)
                        const boingOsc = this.audioCtx.createOscillator();
                        const boingGain = this.audioCtx.createGain();
                        boingOsc.type = 'sine';

                        boingOsc.frequency.setValueAtTime(190, now + 0.36);
                        boingOsc.frequency.exponentialRampToValueAtTime(540, now + 0.44);
                        boingOsc.frequency.exponentialRampToValueAtTime(260, now + 0.65);

                        boingGain.gain.setValueAtTime(0.16, now + 0.36);
                        boingGain.gain.exponentialRampToValueAtTime(0.001, now + 0.65);

                        boingOsc.connect(boingGain);
                        boingGain.connect(this.sfxMasterGain);

                        boingOsc.start(now + 0.36);
                        boingOsc.stop(now + 0.66);
                    }
                    else if (type === 'tick') {
                        // Tik-tok jam detektif
                        const osc = this.audioCtx.createOscillator();
                        const gain = this.audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(880, now);
                        gain.gain.setValueAtTime(0.08, now);
                        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.05);
                        osc.connect(gain);
                        gain.connect(this.sfxMasterGain);
                        osc.start(now);
                        osc.stop(now + 0.05);
                    }
                    else if (type === 'error') {
                        // Buzz peringatan form
                        const osc = this.audioCtx.createOscillator();
                        const gain = this.audioCtx.createGain();
                        osc.type = 'square';
                        osc.frequency.setValueAtTime(220, now);
                        gain.gain.setValueAtTime(0.1, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.15);
                        osc.connect(gain);
                        gain.connect(this.sfxMasterGain);
                        osc.start(now);
                        osc.stop(now + 0.15);
                    }
                    else if (type === 'gameover') {
                        // Fanfare kemenangan selesai game
                        const fanfare = [523.25, 659.25, 783.99, 1046.50];
                        fanfare.forEach((freq, idx) => {
                            const osc = this.audioCtx.createOscillator();
                            const gain = this.audioCtx.createGain();
                            osc.type = 'triangle';
                            osc.frequency.setValueAtTime(freq, now + (idx * 0.14));

                            gain.gain.setValueAtTime(0.18, now + (idx * 0.14));
                            gain.gain.exponentialRampToValueAtTime(0.001, now + (idx * 0.14) + 0.4);

                            osc.connect(gain);
                            gain.connect(this.sfxMasterGain);
                            osc.start(now + (idx * 0.14));
                            osc.stop(now + (idx * 0.14) + 0.41);
                        });
                    }
                }
            };
        }
    </script>
</body>
</html>
