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
        sessionCode: window.gameConfig?.sessionCode || '',
        getImageUrl(path) {
            if (!path) return 'https://placehold.co/100?text=';
            return path.startsWith('/') ? path : '/' + path;
        },
        allQuestions: window.gameConfig?.allQuestions || [],
        gameMode: window.gameConfig?.gameMode || 'standar',
        isStartedFromServer: !!(window.gameConfig?.isStartedFromServer),
        
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

window.gameEngine = gameEngine;

if (window.Alpine) {
    window.Alpine.data('gameEngine', gameEngine);
} else {
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('gameEngine', gameEngine);
    });
}
