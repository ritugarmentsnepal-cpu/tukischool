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

                    <!-- ═══ VIEW: Chapter List (default) ═══ -->
                    <template x-if="!readerChapter">
                        <div>
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
                                    <p class="text-brand-light font-mono font-bold text-2xl" x-text="chapters.filter(c => c.status === 'ready').length"></p>
                                    <p class="text-gray-400 text-[10px] mt-1">Chapters Done</p>
                                </div>
                                <div class="glass rounded-xl p-4 text-center">
                                    <p class="text-brand-light font-mono font-bold text-2xl" x-text="chapters.length"></p>
                                    <p class="text-gray-400 text-[10px] mt-1">Total Chapters</p>
                                </div>
                            </div>

                            <!-- Select Exam -->
                            <div class="mb-8">
                                <h2 class="font-bold text-lg mb-4">Choose Your Exam</h2>
                                <div class="grid sm:grid-cols-2 gap-3">
                                    <template x-for="exam in exams" :key="exam.slug">
                                        <div class="glass rounded-xl p-4 cursor-pointer hover:border-brand-yellow/40 transition group"
                                             :class="selectedExamId == exam.id ? 'border-brand-yellow/60 glow' : ''"
                                             @click="selectExam(exam)">
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

                            <!-- Chapters List -->
                            <div x-show="chapters.length > 0" class="mb-8">
                                <h2 class="font-bold text-lg mb-4">📚 Chapters</h2>
                                <div class="space-y-3">
                                    <template x-for="ch in chapters" :key="ch.id">
                                        <div class="glass rounded-xl p-4 transition"
                                             :class="ch.status === 'ready' ? 'cursor-pointer hover:border-brand-yellow/40' : ch.status === 'locked' ? 'cursor-pointer hover:border-gray-600' : ''"
                                             @click="handleChapterClick(ch)">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg font-bold"
                                                         :class="ch.status === 'ready' ? 'bg-green-500/20 text-green-400' : ch.status === 'generating' ? 'bg-brand-yellow/20 text-brand-yellow' : 'bg-gray-700/50 text-gray-500'">
                                                        <span x-show="ch.status === 'locked'">🔒</span>
                                                        <span x-show="ch.status === 'generating'" class="animate-spin">⏳</span>
                                                        <span x-show="ch.status === 'ready'">✅</span>
                                                        <span x-show="ch.status === 'failed'">❌</span>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-semibold text-sm" x-text="ch.title"></h3>
                                                        <p class="text-xs text-gray-400 font-nepali" x-text="ch.title_nepali"></p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <template x-if="ch.status === 'locked'">
                                                        <span class="text-xs bg-brand-yellow/10 text-brand-yellow px-2 py-1 rounded-full font-mono" x-text="ch.credits_to_unlock + ' credits'"></span>
                                                    </template>
                                                    <template x-if="ch.status === 'ready'">
                                                        <span class="text-xs text-green-400">Read →</span>
                                                    </template>
                                                    <template x-if="ch.status === 'generating'">
                                                        <span class="text-xs text-brand-yellow animate-pulse">Generating...</span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <!-- Upload Syllabus Button -->
                            <div x-show="selectedExamId" class="mb-8">
                                <button @click="showUploadModal = true" class="w-full glass rounded-xl p-4 text-center cursor-pointer hover:border-brand-yellow/40 transition group border-dashed border-2 border-gray-700">
                                    <span class="text-2xl">📤</span>
                                    <p class="font-semibold text-sm mt-2 group-hover:text-brand-yellow transition">Upload Your Own Syllabus</p>
                                    <p class="text-xs text-gray-500 mt-1">Paste your syllabus text and AI will parse it into chapters</p>
                                </button>
                            </div>

                            <!-- Upload Syllabus Modal -->
                            <template x-if="showUploadModal">
                                <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm px-4" @click.self="showUploadModal = false">
                                    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 w-full max-w-lg">
                                        <h3 class="font-bold text-xl mb-1">📤 Upload Syllabus</h3>
                                        <p class="text-gray-400 text-sm mb-4">Paste your syllabus content below. AI will parse it into chapters automatically.</p>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-xs text-gray-400 mb-1 block">Syllabus Title</label>
                                                <input type="text" x-model="uploadTitle" placeholder="e.g. My Custom Syllabus" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-brand-light placeholder-gray-500 focus:border-brand-yellow focus:outline-none transition text-sm">
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-400 mb-1 block">Syllabus Content</label>
                                                <textarea x-model="uploadText" rows="8" placeholder="Paste your syllabus text here...&#10;&#10;Example:&#10;1. Constitution of Nepal&#10;2. Public Administration&#10;3. Economic Development..." class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-brand-light placeholder-gray-500 focus:border-brand-yellow focus:outline-none transition text-sm resize-none"></textarea>
                                            </div>
                                            <button @click="uploadSyllabus()" :disabled="uploadLoading || !uploadTitle || !uploadText" class="w-full bg-brand-yellow text-brand-dark font-bold py-3 rounded-lg hover:brightness-110 transition disabled:opacity-50">
                                                <span x-show="!uploadLoading">Parse with AI ✨</span>
                                                <span x-show="uploadLoading" class="flex items-center justify-center gap-2">
                                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                                    AI is parsing...
                                                </span>
                                            </button>
                                        </div>
                                        <button @click="showUploadModal = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-300 text-xl">&times;</button>
                                    </div>
                                </div>
                            </template>

                            <!-- No chapters yet -->
                            <div x-show="selectedExamId && chapters.length === 0 && !chaptersLoading" class="glass rounded-2xl p-8 text-center">
                                <p class="text-4xl mb-4">📋</p>
                                <h3 class="font-bold text-lg mb-2">No Chapters Yet</h3>
                                <p class="text-gray-400 text-sm">Select an exam above to see pre-loaded chapters, or upload your own syllabus!</p>
                            </div>

                            <!-- Unlock Confirmation Modal -->
                            <template x-if="unlockChapterPending">
                                <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm px-4" @click.self="unlockChapterPending = null">
                                    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 w-full max-w-sm text-center">
                                        <p class="text-4xl mb-3">🔓</p>
                                        <h3 class="font-bold text-lg mb-2">Unlock Chapter?</h3>
                                        <p class="text-gray-400 text-sm mb-1" x-text="unlockChapterPending.title"></p>
                                        <p class="text-brand-yellow font-mono font-bold text-xl mb-4" x-text="unlockChapterPending.credits_to_unlock + ' credits'"></p>
                                        <div class="flex gap-3">
                                            <button @click="unlockChapterPending = null" class="flex-1 glass py-3 rounded-lg font-semibold text-sm text-gray-400 hover:text-brand-light transition">Cancel</button>
                                            <button @click="confirmUnlock()" :disabled="unlockLoading" class="flex-1 bg-brand-yellow text-brand-dark py-3 rounded-lg font-bold text-sm hover:brightness-110 transition disabled:opacity-50">
                                                <span x-show="!unlockLoading">Unlock ✨</span>
                                                <span x-show="unlockLoading" class="flex items-center justify-center gap-2">
                                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                                    Generating...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- ═══ VIEW: Reader (when a chapter is selected) ═══ -->
                    <template x-if="readerChapter">
                        <div>
                            <!-- Back button -->
                            <button @click="readerChapter = null; readerTab = 'textbook'" class="flex items-center gap-2 text-gray-400 hover:text-brand-yellow transition mt-4 mb-6 text-sm">
                                ← Back to Chapters
                            </button>

                            <!-- Chapter header -->
                            <div class="mb-6">
                                <h1 class="text-xl font-bold" x-text="readerChapter.title"></h1>
                                <p class="text-brand-yellow text-sm font-nepali mt-1" x-text="readerChapter.title_nepali"></p>
                            </div>

                            <!-- Tab toggle + TTS controls -->
                            <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
                                <div class="flex gap-2">
                                    <button @click="readerTab = 'textbook'"
                                            class="px-4 py-2 rounded-full text-sm font-semibold transition"
                                            :class="readerTab === 'textbook' ? 'bg-brand-yellow text-brand-dark' : 'glass text-gray-400 hover:text-brand-light'">
                                        📖 Textbook
                                    </button>
                                    <button @click="readerTab = 'explanation'"
                                            class="px-4 py-2 rounded-full text-sm font-semibold transition"
                                            :class="readerTab === 'explanation' ? 'bg-brand-yellow text-brand-dark' : 'glass text-gray-400 hover:text-brand-light'">
                                        🗣️ Explanation
                                    </button>
                                </div>
                                <!-- TTS Controls -->
                                <div class="flex items-center gap-2">
                                    <button @click="toggleTTS()" class="w-10 h-10 rounded-full flex items-center justify-center transition"
                                            :class="ttsPlaying ? 'bg-red-500/20 text-red-400 hover:bg-red-500/30' : 'glass text-brand-yellow hover:bg-brand-yellow/10'">
                                        <span x-show="!ttsPlaying">🔊</span>
                                        <span x-show="ttsPlaying">⏸️</span>
                                    </button>
                                    <select x-model="ttsSpeed" @change="updateTTSSpeed()" class="bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-xs text-gray-400 focus:outline-none">
                                        <option value="0.7">0.7x</option>
                                        <option value="1">1x</option>
                                        <option value="1.3">1.3x</option>
                                        <option value="1.5">1.5x</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="glass rounded-2xl p-6 mb-4">
                                <div x-show="readerTab === 'textbook'" class="prose prose-invert prose-yellow max-w-none">
                                    <div x-html="formatContent(readerContent?.textbook || 'Loading...')"></div>
                                </div>
                                <div x-show="readerTab === 'explanation'" class="prose prose-invert prose-yellow max-w-none">
                                    <div x-html="formatContent(readerContent?.explanation || 'Loading...')"></div>
                                </div>
                            </div>

                            <!-- Word count -->
                            <div class="text-center mb-6">
                                <span class="text-xs text-gray-500" x-text="(readerChapter.word_count || 0) + ' words'"></span>
                            </div>

                            <!-- ═══ Q&A Chat Panel ═══ -->
                            <div class="glass rounded-2xl overflow-hidden">
                                <!-- Chat header -->
                                <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between cursor-pointer" @click="chatOpen = !chatOpen">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg">💬</span>
                                        <span class="font-bold text-sm">Ask Tuki AI</span>
                                        <span class="text-[10px] bg-green-500/20 text-green-400 px-2 py-0.5 rounded-full">Live</span>
                                    </div>
                                    <span class="text-gray-500 text-sm" x-text="chatOpen ? '▼' : '▲'"></span>
                                </div>

                                <!-- Chat body -->
                                <div x-show="chatOpen" x-transition class="flex flex-col" style="max-height: 400px;">
                                    <!-- Messages -->
                                    <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3" style="max-height: 300px;" x-ref="chatMessages">
                                        <!-- Welcome message -->
                                        <div x-show="chatMessages.length === 0" class="text-center py-6">
                                            <p class="text-3xl mb-2">🦉</p>
                                            <p class="text-gray-400 text-sm">म Tuki हुँ! यो chapter बारेमा कुनै प्रश्न सोध्नुहोस्।</p>
                                            <p class="text-gray-500 text-xs mt-1">I'm Tuki! Ask me anything about this chapter.</p>
                                        </div>

                                        <template x-for="(msg, i) in chatMessages" :key="i">
                                            <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                                                <div :class="msg.role === 'user' ? 'bg-brand-yellow/10 border-brand-yellow/20 text-brand-light' : 'bg-gray-800 border-gray-700 text-gray-300'"
                                                     class="border rounded-xl px-4 py-2.5 max-w-[85%] text-sm">
                                                    <div x-html="msg.role === 'assistant' ? formatContent(msg.text) : msg.text"></div>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Typing indicator -->
                                        <div x-show="chatLoading" class="flex justify-start">
                                            <div class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-400">
                                                <span class="flex items-center gap-1">
                                                    <span class="w-2 h-2 bg-brand-yellow rounded-full animate-bounce" style="animation-delay:0ms"></span>
                                                    <span class="w-2 h-2 bg-brand-yellow rounded-full animate-bounce" style="animation-delay:150ms"></span>
                                                    <span class="w-2 h-2 bg-brand-yellow rounded-full animate-bounce" style="animation-delay:300ms"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Input bar -->
                                    <div class="border-t border-gray-800 px-3 py-3 flex items-center gap-2">
                                        <button @click="toggleVoiceInput()" class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 transition"
                                                :class="voiceListening ? 'bg-red-500 text-white animate-pulse' : 'glass text-gray-400 hover:text-brand-yellow'">
                                            🎤
                                        </button>
                                        <input type="text" x-model="chatInput" @keydown.enter="sendChatMessage()"
                                               placeholder="Ask a question..." 
                                               class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-brand-light placeholder-gray-500 focus:border-brand-yellow focus:outline-none transition">
                                        <button @click="sendChatMessage()" :disabled="!chatInput.trim() || chatLoading" 
                                                class="w-10 h-10 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 hover:brightness-110 transition disabled:opacity-40">
                                            <span class="text-brand-dark font-bold">→</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

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
            // Chapter state
            selectedExamId: null,
            syllabusId: null,
            chapters: [],
            chaptersLoading: false,
            // Reader state
            readerChapter: null,
            readerContent: null,
            readerTab: 'textbook',
            // Upload state
            showUploadModal: false,
            uploadTitle: '',
            uploadText: '',
            uploadLoading: false,
            // Unlock state
            unlockChapterPending: null,
            unlockLoading: false,
            // Toast
            toastMsg: '',
            toastType: 'success',
            // TTS state
            ttsPlaying: false,
            ttsSpeed: '1',
            ttsUtterance: null,
            // Chat state
            chatOpen: true,
            chatInput: '',
            chatMessages: [],
            chatLoading: false,
            // Voice input
            voiceListening: false,
            voiceRecognition: null,
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
                // Auto-select first exam if logged in
                if (this.token && this.exams.length > 0) {
                    this.selectExam(this.exams[0]);
                }
            },

            async loadExams() {
                try {
                    const res = await fetch(API_URL + '/exams');
                    const data = await res.json();
                    this.exams = data.exams || [];
                } catch(e) { console.error('Failed to load exams', e); }
            },

            async uploadSyllabus() {
                this.uploadLoading = true;
                try {
                    const res = await fetch(API_URL + '/syllabi/upload', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + this.token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            exam_id: this.selectedExamId,
                            title: this.uploadTitle,
                            raw_text: this.uploadText
                        })
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        alert(data.message || 'Upload failed');
                        return;
                    }
                    alert('Syllabus parsed! ' + data.chapter_count + ' chapters created.');
                    this.showUploadModal = false;
                    this.uploadTitle = '';
                    this.uploadText = '';
                    // Reload chapters
                    await this.selectExam(this.exams.find(e => e.id === this.selectedExamId));
                } catch (e) {
                    console.error('Upload failed', e);
                    alert('An error occurred while uploading.');
                } finally {
                    this.uploadLoading = false;
                }
            },

            async selectExam(exam) {
                this.selectedExamId = exam.id;
                this.chaptersLoading = true;
                this.chapters = [];
                try {
                    // Get syllabi for this exam
                    const res = await fetch(API_URL + '/syllabi/' + exam.id, {
                        headers: {
                            'Authorization': 'Bearer ' + this.token,
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const data = await res.json();
                    if (data.syllabi && data.syllabi.length > 0) {
                        this.syllabusId = data.syllabi[0].id;
                        await this.loadChapters(this.syllabusId);
                    }
                } catch (e) { console.error('Failed to load syllabi', e); }
                this.chaptersLoading = false;
            },

            async loadChapters(syllabusId) {
                try {
                    const res = await fetch(API_URL + '/chapters/' + syllabusId);
                    const data = await res.json();
                    this.chapters = data.chapters || [];
                } catch (e) { console.error('Failed to load chapters', e); }
            },

            async handleChapterClick(ch) {
                if (ch.status === 'ready') {
                    await this.openReader(ch);
                } else if (ch.status === 'locked') {
                    this.unlockChapterPending = ch;
                }
            },

            async confirmUnlock() {
                const ch = this.unlockChapterPending;
                if (!ch) return;
                this.unlockLoading = true;
                try {
                    const res = await fetch(API_URL + '/chapters/' + ch.id + '/unlock', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + this.token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        this.showToast(data.message || 'Failed to unlock chapter', 'error');
                        return;
                    }
                    this.user.credits = data.credits_remaining;
                    localStorage.setItem('tuki_user', JSON.stringify(this.user));
                    this.unlockChapterPending = null;
                    await this.loadChapters(this.syllabusId);
                    if (data.chapter.status === 'ready') {
                        await this.openReader(data.chapter);
                    }
                } catch (e) {
                    console.error('Failed to unlock chapter', e);
                    this.showToast('An error occurred while unlocking.', 'error');
                } finally {
                    this.unlockLoading = false;
                }
            },

            showToast(msg, type = 'success') {
                this.toastMsg = msg;
                this.toastType = type;
                setTimeout(() => { this.toastMsg = ''; }, 4000);
            },

            async openReader(ch) {
                this.readerChapter = ch;
                this.readerTab = 'textbook';
                this.chatMessages = [];
                this.chatInput = '';
                this.stopTTS();
                try {
                    const res = await fetch(API_URL + '/chapters/' + ch.id + '/content', {
                        headers: {
                            'Authorization': 'Bearer ' + this.token,
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const data = await res.json();
                    this.readerContent = data.content || {};
                } catch (e) {
                    console.error('Failed to load content', e);
                    this.readerContent = { textbook: 'Failed to load content.', explanation: '' };
                }
            },

            // ── TTS (Text-to-Speech) ──

            toggleTTS() {
                if (this.ttsPlaying) {
                    this.stopTTS();
                } else {
                    this.startTTS();
                }
            },

            startTTS() {
                const text = this.readerTab === 'textbook' 
                    ? (this.readerContent?.textbook || '') 
                    : (this.readerContent?.explanation || '');
                if (!text) return;
                
                // Strip markdown
                const clean = text.replace(/[#*_\-]/g, '').replace(/\n+/g, '. ');
                
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(clean);
                utterance.rate = parseFloat(this.ttsSpeed);
                utterance.pitch = 1;
                utterance.lang = 'en-US';
                utterance.onend = () => { this.ttsPlaying = false; };
                utterance.onerror = () => { this.ttsPlaying = false; };
                this.ttsUtterance = utterance;
                this.ttsPlaying = true;
                window.speechSynthesis.speak(utterance);
            },

            stopTTS() {
                window.speechSynthesis.cancel();
                this.ttsPlaying = false;
            },

            updateTTSSpeed() {
                if (this.ttsPlaying) {
                    this.stopTTS();
                    this.startTTS();
                }
            },

            // ── Chat Q&A ──

            async sendChatMessage() {
                const q = this.chatInput.trim();
                if (!q || this.chatLoading) return;
                
                this.chatMessages.push({ role: 'user', text: q });
                this.chatInput = '';
                this.chatLoading = true;
                
                this.$nextTick(() => {
                    if (this.$refs.chatMessages) {
                        this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
                    }
                });

                try {
                    const res = await fetch(API_URL + '/chapters/' + this.readerChapter.id + '/ask', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + this.token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ question: q })
                    });
                    const data = await res.json();
                    this.chatMessages.push({ role: 'assistant', text: data.answer || data.message || 'Sorry, I could not answer that.' });
                } catch (e) {
                    console.error('Chat error', e);
                    this.chatMessages.push({ role: 'assistant', text: 'An error occurred. Please try again.' });
                } finally {
                    this.chatLoading = false;
                    this.$nextTick(() => {
                        if (this.$refs.chatMessages) {
                            this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
                        }
                    });
                }
            },

            // ── Voice Input (Speech Recognition) ──

            toggleVoiceInput() {
                if (this.voiceListening) {
                    this.stopVoiceInput();
                } else {
                    this.startVoiceInput();
                }
            },

            startVoiceInput() {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                if (!SpeechRecognition) {
                    this.showToast('Voice input not supported in this browser. Try Chrome.', 'error');
                    return;
                }
                const recognition = new SpeechRecognition();
                recognition.lang = 'ne-NP'; // Nepali, falls back to English
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;
                recognition.continuous = false;

                recognition.onresult = (event) => {
                    const transcript = event.results[0][0].transcript;
                    this.chatInput = transcript;
                    this.voiceListening = false;
                };
                recognition.onerror = (e) => {
                    console.error('Voice error', e);
                    this.voiceListening = false;
                    if (e.error === 'not-allowed') {
                        this.showToast('Microphone access denied. Please allow mic access.', 'error');
                    }
                };
                recognition.onend = () => { this.voiceListening = false; };

                this.voiceRecognition = recognition;
                this.voiceListening = true;
                recognition.start();
            },

            stopVoiceInput() {
                if (this.voiceRecognition) {
                    this.voiceRecognition.stop();
                }
                this.voiceListening = false;
            },

            formatContent(text) {
                if (!text) return '';
                // Convert markdown-like formatting to HTML
                return text
                    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.+?)\*/g, '<em>$1</em>')
                    .replace(/^### (.+)$/gm, '<h3 class="text-brand-yellow font-bold text-lg mt-6 mb-2">$1</h3>')
                    .replace(/^## (.+)$/gm, '<h2 class="text-brand-yellow font-bold text-xl mt-8 mb-3">$1</h2>')
                    .replace(/^# (.+)$/gm, '<h1 class="text-brand-yellow font-extrabold text-2xl mt-8 mb-4">$1</h1>')
                    .replace(/^- (.+)$/gm, '<li class="ml-4 list-disc text-gray-300">$1</li>')
                    .replace(/^\d+\. (.+)$/gm, '<li class="ml-4 list-decimal text-gray-300">$1</li>')
                    .replace(/\n\n/g, '</p><p class="mb-3 text-gray-300 leading-relaxed">')
                    .replace(/\n/g, '<br>')
                    ;
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
                    // Load chapters after login
                    if (this.exams.length > 0) {
                        this.selectExam(this.exams[0]);
                    }
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
                this.chapters = [];
                this.selectedExamId = null;
                this.readerChapter = null;
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
