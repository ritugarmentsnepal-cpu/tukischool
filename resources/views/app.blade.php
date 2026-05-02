<!DOCTYPE html>
<html lang="ne" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="AI-powered exam preparation for Nepal's Lok Sewa & bank exams. Learn with an AI teacher that reads and explains — just like real Nepali coaching.">
    <meta name="theme-color" content="#FACC15">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tuki School — AI Tutor Nepal</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Mukta:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://www.gstatic.com/firebasejs/10.11.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.11.1/firebase-auth-compat.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: { yellow: '#FACC15', dark: '#0A0A0A', light: '#FAFAFA', highlight: '#FEF3C7', 'highlight-dark': '#3F2E0A', word: '#FCD34D', 'word-dark': '#78510C' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'Mukta', 'system-ui', 'sans-serif'],
                        nepali: ['Mukta', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-size: 14px; line-height: 1.6; }
        .glass { background: rgba(250,204,21,0.06); backdrop-filter: blur(16px); border: 1px solid rgba(250,204,21,0.12); }
        .glow { box-shadow: 0 0 30px rgba(250,204,21,0.15); }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes pulse-glow { 0%,100% { box-shadow: 0 0 20px rgba(250,204,21,0.2); } 50% { box-shadow: 0 0 40px rgba(250,204,21,0.4); } }
        .animate-fade-up { animation: fadeUp 0.6s ease-out forwards; }
        .animate-glow { animation: pulse-glow 3s ease-in-out infinite; }
        .credit-pop { animation: fadeUp 0.4s ease-out; }
    </style>
</head>
<body class="bg-brand-dark text-brand-light min-h-screen font-sans antialiased" x-data="app()" x-cloak>

    <!-- ═══════════════════════════════════════════ -->
    <!-- LANDING PAGE (shown when not logged in) -->
    <!-- ═══════════════════════════════════════════ -->
    <template x-if="!token">
        <div class="min-h-screen flex flex-col">
            <!-- Nav -->
            <nav class="fixed top-0 w-full z-50 glass">
                <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 bg-brand-yellow rounded-lg flex items-center justify-center">
                            <span class="text-brand-dark font-bold text-lg">T</span>
                        </div>
                        <span class="font-bold text-xl text-brand-light">Tuki<span class="text-brand-yellow">School</span></span>
                    </div>
                    <button @click="showLogin = true" class="bg-brand-yellow text-brand-dark font-semibold px-5 py-2 rounded-full text-sm hover:brightness-110 transition">
                        Get Started
                    </button>
                </div>
            </nav>

            <!-- Hero -->
            <section class="flex-1 flex items-center justify-center px-4 pt-20 pb-16">
                <div class="max-w-2xl text-center">
                    <div class="inline-flex items-center gap-2 bg-brand-yellow/10 border border-brand-yellow/20 rounded-full px-4 py-1.5 mb-6 animate-fade-up">
                        <span class="w-2 h-2 bg-brand-yellow rounded-full animate-pulse"></span>
                        <span class="text-brand-yellow text-xs font-medium">AI-Powered Learning for Nepal</span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6 animate-fade-up" style="animation-delay:0.1s">
                        Your AI Teacher<br>
                        <span class="text-brand-yellow">पढ्छ र बुझाउँछ</span>
                    </h1>
                    <p class="text-lg text-gray-400 mb-8 max-w-lg mx-auto animate-fade-up font-nepali" style="animation-delay:0.2s">
                        Lok Sewa र Bank exam को तयारी — AI ले किताब पढ्छ, अनि कोचिङ टिचर जस्तै बुझाउँछ। Voice मा सोध, Voice मा जवाफ पाउ।
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center animate-fade-up" style="animation-delay:0.3s">
                        <button @click="showLogin = true" class="bg-brand-yellow text-brand-dark font-bold px-8 py-3.5 rounded-full text-base hover:brightness-110 transition animate-glow">
                            🎓 Start Learning Free
                        </button>
                        <a href="#how-it-works" class="border border-gray-700 text-gray-300 font-medium px-8 py-3.5 rounded-full text-base hover:border-brand-yellow/50 hover:text-brand-yellow transition">
                            How it Works →
                        </a>
                    </div>
                    <p class="text-xs text-gray-500 mt-4 animate-fade-up" style="animation-delay:0.4s">
                        150 free credits on signup • No subscription required
                    </p>
                </div>
            </section>

            <!-- Features -->
            <section id="how-it-works" class="py-20 px-4 border-t border-gray-800/50">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-2xl md:text-3xl font-bold text-center mb-12">
                        How <span class="text-brand-yellow">Tuki School</span> Works
                    </h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        <template x-for="(f, i) in features" :key="i">
                            <div class="glass rounded-2xl p-6 hover:border-brand-yellow/30 transition group">
                                <div class="text-3xl mb-4" x-text="f.icon"></div>
                                <h3 class="font-bold text-lg mb-2 group-hover:text-brand-yellow transition" x-text="f.title"></h3>
                                <p class="text-gray-400 text-sm" x-text="f.desc"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </section>

            <!-- Exam Cards -->
            <section class="py-20 px-4 border-t border-gray-800/50">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-2xl md:text-3xl font-bold text-center mb-4">Prepare for <span class="text-brand-yellow">Your Exam</span></h2>
                    <p class="text-gray-400 text-center mb-12 text-sm">Choose your target exam and start learning immediately</p>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="exam in exams" :key="exam.slug">
                            <div class="glass rounded-xl p-5 hover:border-brand-yellow/40 transition cursor-pointer group" @click="showLogin = true">
                                <div class="flex items-start justify-between mb-3">
                                    <span class="text-2xl">📋</span>
                                    <span x-show="exam.is_featured" class="text-[10px] bg-brand-yellow/20 text-brand-yellow px-2 py-0.5 rounded-full font-medium">Popular</span>
                                </div>
                                <h3 class="font-bold mb-1 group-hover:text-brand-yellow transition" x-text="exam.name"></h3>
                                <p class="text-brand-yellow/80 text-sm font-nepali" x-text="exam.name_nepali"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </section>

            <!-- Pricing -->
            <section class="py-20 px-4 border-t border-gray-800/50">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Simple <span class="text-brand-yellow">Pay-Per-Use</span> Credits</h2>
                    <p class="text-gray-400 mb-12 text-sm">No subscriptions. No bundles. Credits never expire.</p>
                    <div class="grid sm:grid-cols-3 lg:grid-cols-5 gap-3">
                        <template x-for="pack in creditPacks" :key="pack.code">
                            <div class="glass rounded-xl p-4 hover:border-brand-yellow/40 transition relative" :class="pack.popular ? 'border-brand-yellow/40 glow' : ''">
                                <span x-show="pack.popular" class="absolute -top-2.5 left-1/2 -translate-x-1/2 bg-brand-yellow text-brand-dark text-[10px] font-bold px-3 py-0.5 rounded-full">BEST</span>
                                <p class="font-bold text-lg text-brand-yellow font-mono" x-text="'Rs. ' + pack.price"></p>
                                <p class="text-xs text-gray-400 mt-1"><span x-text="pack.total" class="text-brand-light font-semibold"></span> credits</p>
                                <p x-show="pack.bonus > 0" class="text-[10px] text-green-400 mt-1">+<span x-text="pack.bonus"></span> bonus</p>
                            </div>
                        </template>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="border-t border-gray-800/50 py-8 px-4 text-center text-xs text-gray-500">
                <p>© 2026 Tuki School. AI-generated educational content. <a href="#" class="text-brand-yellow hover:underline">Terms</a> · <a href="#" class="text-brand-yellow hover:underline">Privacy</a></p>
            </footer>

            <!-- Login Modal -->
            <template x-if="showLogin">
                <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm px-4" @click.self="showLogin = false">
                    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 w-full max-w-sm animate-fade-up">
                        <div class="text-center mb-6">
                            <div class="w-14 h-14 bg-brand-yellow rounded-xl flex items-center justify-center mx-auto mb-3">
                                <span class="text-brand-dark font-bold text-2xl">T</span>
                            </div>
                            <h3 class="font-bold text-xl">Welcome to Tuki School</h3>
                            <p class="text-gray-400 text-sm mt-1">Sign in with your email</p>
                        </div>
                        <div id="firebase-login-container">
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">Email Address</label>
                                    <input type="email" x-model="loginEmail" placeholder="student@example.com" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-brand-light placeholder-gray-500 focus:border-brand-yellow focus:outline-none transition text-base">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">Password</label>
                                    <input type="password" x-model="loginPassword" placeholder="••••••••" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-brand-light placeholder-gray-500 focus:border-brand-yellow focus:outline-none transition text-base">
                                </div>
                                <button @click="emailLogin()" :disabled="loginLoading" class="w-full bg-brand-yellow text-brand-dark font-bold py-3 rounded-lg hover:brightness-110 transition disabled:opacity-50">
                                    <span x-show="!loginLoading" x-text="isRegistering ? 'Create Account' : 'Sign In'"></span>
                                    <span x-show="loginLoading" class="flex items-center justify-center gap-2">
                                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                        Processing...
                                    </span>
                                </button>
                                <div class="text-center mt-3">
                                    <button @click="isRegistering = !isRegistering" class="text-brand-yellow text-xs hover:underline">
                                        <span x-text="isRegistering ? 'Already have an account? Sign In' : 'Need an account? Create one'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-500 text-center mt-4">By signing in, you agree to our Terms & AI content disclosure</p>
                        <button @click="showLogin = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-300 text-xl">&times;</button>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <!-- ═══════════════════════════════════════════ -->
    <!-- DASHBOARD (shown when logged in) -->
    <!-- ═══════════════════════════════════════════ -->
    <template x-if="token">
        <div class="min-h-screen flex flex-col">
            <!-- Top Bar -->
            <nav class="fixed top-0 w-full z-50 glass">
                <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-brand-yellow rounded-lg flex items-center justify-center">
                            <span class="text-brand-dark font-bold">T</span>
                        </div>
                        <span class="font-bold text-lg">Tuki<span class="text-brand-yellow">School</span></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5 bg-brand-yellow/10 border border-brand-yellow/20 rounded-full px-3 py-1">
                            <span class="text-brand-yellow text-xs">💰</span>
                            <span class="text-brand-yellow font-mono font-bold text-sm" x-text="user?.credits || 0"></span>
                        </div>
                        <button @click="logout()" class="text-gray-400 hover:text-brand-yellow text-xs">Logout</button>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 pt-16 pb-8 px-4">
                <div class="max-w-4xl mx-auto">
                    <!-- Welcome -->
                    <div class="mb-8 mt-4">
                        <h1 class="text-2xl font-bold">
                            नमस्ते, <span class="text-brand-yellow" x-text="user?.name || 'Student'"></span> 👋
                        </h1>
                        <p class="text-gray-400 text-sm mt-1">Ready to learn today?</p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-3 gap-3 mb-8">
                        <div class="glass rounded-xl p-4 text-center">
                            <p class="text-brand-yellow font-mono font-bold text-2xl" x-text="user?.credits || 0"></p>
                            <p class="text-gray-400 text-[10px] mt-1">Credits</p>
                        </div>
                        <div class="glass rounded-xl p-4 text-center">
                            <p class="text-brand-light font-mono font-bold text-2xl">0</p>
                            <p class="text-gray-400 text-[10px] mt-1">Chapters Done</p>
                        </div>
                        <div class="glass rounded-xl p-4 text-center">
                            <p class="text-brand-light font-mono font-bold text-2xl">0h</p>
                            <p class="text-gray-400 text-[10px] mt-1">Study Time</p>
                        </div>
                    </div>

                    <!-- Select Exam -->
                    <div class="mb-8">
                        <h2 class="font-bold text-lg mb-4">Choose Your Exam</h2>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <template x-for="exam in exams" :key="exam.slug">
                                <div class="glass rounded-xl p-4 cursor-pointer hover:border-brand-yellow/40 transition group" :class="user?.current_exam_id == exam.id ? 'border-brand-yellow/60 glow' : ''">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold group-hover:text-brand-yellow transition" x-text="exam.name"></h3>
                                            <p class="text-sm text-gray-400 font-nepali" x-text="exam.name_nepali"></p>
                                        </div>
                                        <span x-show="exam.is_featured" class="text-[10px] bg-brand-yellow/20 text-brand-yellow px-2 py-0.5 rounded-full">Popular</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Coming Soon placeholder -->
                    <div class="glass rounded-2xl p-8 text-center">
                        <p class="text-4xl mb-4">🚀</p>
                        <h3 class="font-bold text-lg mb-2">Chapters Coming Soon</h3>
                        <p class="text-gray-400 text-sm">Upload your syllabus or select a pre-loaded exam to start learning with your AI teacher.</p>
                    </div>
                </div>
            </main>
        </div>
    </template>

    <script>
    const BASE_URL = '{{ url("/") }}';
    const API_URL = BASE_URL + '/api';

    // Initialize Firebase
    const firebaseConfig = {
      apiKey: "AIzaSyAPEbSFuL1KKDNoyMRa2RonpW8G4i7BKok",
      authDomain: "tukischool-66d42.firebaseapp.com",
      projectId: "tukischool-66d42",
      storageBucket: "tukischool-66d42.firebasestorage.app",
      messagingSenderId: "367583274288",
      appId: "1:367583274288:web:02eb3fff134fdfe84014f5",
      measurementId: "G-Q2GHP8DJYN"
    };
    firebase.initializeApp(firebaseConfig);

    function app() {
        return {
            token: localStorage.getItem('tuki_token'),
            user: JSON.parse(localStorage.getItem('tuki_user') || 'null'),
            showLogin: false,
            loginEmail: '',
            loginPassword: '',
            isRegistering: false,
            loginLoading: false,
            exams: [],
            features: [
                { icon: '📖', title: 'AI Reads the Textbook', desc: 'Your AI teacher reads formal textbook content with word-by-word highlighting — exactly like reading in class.' },
                { icon: '🗣️', title: 'Then Explains Simply', desc: 'After reading, the AI explains in colloquial Nepali — just like coaching teachers do. पढ्ने र बुझाउने!' },
                { icon: '🎤', title: 'Ask Doubts by Voice', desc: 'Tap the mic anytime to ask a doubt. Get instant voice answers grounded in your chapter content.' },
            ],
            creditPacks: [
                { code: 'starter', price: 100, base: 100, bonus: 0, total: 100, popular: false },
                { code: 'regular', price: 500, base: 500, bonus: 50, total: 550, popular: false },
                { code: 'popular', price: 1000, base: 1000, bonus: 200, total: 1200, popular: true },
                { code: 'best_value', price: 2500, base: 2500, bonus: 750, total: 3250, popular: false },
                { code: 'mega', price: 5000, base: 5000, bonus: 2000, total: 7000, popular: false },
            ],

            async init() {
                await this.loadExams();
            },

            async loadExams() {
                try {
                    const res = await fetch(API_URL + '/exams');
                    const data = await res.json();
                    this.exams = data.exams || [];
                } catch(e) { console.error('Failed to load exams', e); }
            },

            async emailLogin() {
                this.loginLoading = true;
                try {
                    let userCredential;
                    if (this.isRegistering) {
                        userCredential = await firebase.auth().createUserWithEmailAndPassword(this.loginEmail, this.loginPassword);
                    } else {
                        userCredential = await firebase.auth().signInWithEmailAndPassword(this.loginEmail, this.loginPassword);
                    }
                    
                    const idToken = await userCredential.user.getIdToken();

                    const response = await fetch(API_URL + '/auth/firebase-login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ firebase_token: idToken })
                    });

                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Login failed');

                    localStorage.setItem('tuki_token', data.token);
                    localStorage.setItem('tuki_user', JSON.stringify(data.user));
                    this.token = data.token;
                    this.user = data.user;
                    this.showLogin = false;
                    this.loginEmail = '';
                    this.loginPassword = '';
                } catch (error) {
                    console.error('Error during email login', error);
                    alert(error.message);
                } finally {
                    this.loginLoading = false;
                }
            },

            logout() {
                if (this.token) {
                    fetch(API_URL + '/auth/logout', { 
                        method: 'POST', 
                        headers: { 
                            'Authorization': 'Bearer ' + this.token, 
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        } 
                    });
                }
                localStorage.removeItem('tuki_token');
                localStorage.removeItem('tuki_user');
                this.token = null;
                this.user = null;
            }
        }
    }

    // Register service worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    }
    </script>
</body>
</html>
