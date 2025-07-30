@extends('layouts.app')

@section('content')
<style>
    /* Remove any margin/padding from body and html */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        overflow-x: hidden;
    }
    
    /* Ensure main content takes full width - Override default styles */
    .main-content {
        margin: 0 !important;
        padding: 0 !important;
        max-width: none !important;
        width: 100% !important;
        min-height: 100vh !important;
    }
    
    /* Override any container restrictions */
    .app-container {
        max-width: none !important;
        width: 100% !important;
    }
    
    /* Ensure our content div takes full width */
    .full-width-content {
        width: 100vw !important;
        margin: 0 !important;
        padding: 0 !important;
        max-width: none !important;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out;
    }
    
    .animate-slideInLeft {
        animation: slideInLeft 0.8s ease-out;
    }
    
    /* 3D Pin effect ONLY for profile card - Exact React/Motion replication */
    .pin-container {
        position: relative;
        cursor: pointer;
        z-index: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
        width: fit-content;
        /* Restaurando animação de flutuação contínua */
        animation: profileFloat 4s ease-in-out infinite;
    }
    
    @keyframes profileFloat {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-12px);
        }
    }
    
    /* Main 3D perspective container - exactly like React version */
    .pin-perspective-main {
        perspective: 1000px;
        transform: rotateX(70deg) translateZ(0deg);
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: 0.09375rem;
        margin-top: 1rem;
        transform-origin: center;
        -webkit-transform: translate(-50%, -50%) rotateX(70deg) translateZ(0deg);
        transform: translate(-50%, -50%) rotateX(70deg) translateZ(0deg);
    }
    
    /* Main content that transforms on hover */
    .pin-content {
        position: absolute;
        left: 50%;
        top: 50%;
        padding: 1rem;
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        border-radius: 1rem;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        background: black;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.7s ease;
        overflow: hidden;
        transform: translate(-50%, -50%) rotateX(0deg) scale(1);
    }
    
    .pin-container:hover .pin-content {
        transform: translate(-50%, -50%) rotateX(40deg) scale(0.8);
        border-color: rgba(255, 255, 255, 0.2);
    }
    
    /* Pin Info Overlay (appears on hover) - exactly like PinPerspective */
    .pin-info {
        pointer-events: none;
        width: 24rem;
        height: 15rem;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        z-index: 2;
        transition: opacity 0.5s ease;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .pin-container:hover .pin-info {
        opacity: 1;
    }
    
    .pin-info-inner {
        width: 100%;
        height: 100%;
        margin-top: -1.75rem;
        flex: none;
        position: relative;
    }
    
    /* Badge at top - exactly like React version */
    .pin-badge {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        z-index: 1;
    }
    
    .pin-badge-content {
        position: relative;
        display: flex;
        gap: 0.5rem;
        align-items: center;
        border-radius: 9999px;
        background: rgb(9, 9, 11);
        padding: 0.125rem 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        white-space: nowrap;
    }
    
    .pin-badge-text {
        position: relative;
        z-index: 1;
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        display: inline-block;
        padding: 0.125rem 0;
    }
    
    .pin-badge-line {
        position: absolute;
        bottom: 0;
        left: 1.125rem;
        height: 1px;
        width: calc(100% - 2.25rem);
        background: linear-gradient(to right, rgba(52, 211, 153, 0), rgba(52, 211, 153, 0.9), rgba(52, 211, 153, 0));
        transition: opacity 0.5s ease;
        opacity: 0.4;
    }
    
    /* Ripple effects container - exactly positioned like React version */
    .pin-ripples-container {
        perspective: 1000px;
        transform: rotateX(70deg) translateZ(0);
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: 0.09375rem;
        margin-top: 1rem;
        -webkit-transform: translate(-50%, -50%) rotateX(70deg) translateZ(0);
        transform: translate(-50%, -50%) rotateX(70deg) translateZ(0);
    }
    
    /* Individual ripples - exactly like motion.div animations */
    .pin-ripple {
        position: absolute;
        left: 50%;
        top: 50%;
        height: 11.25rem;
        width: 11.25rem;
        border-radius: 50%;
        background: rgba(14, 165, 233, 0.08);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        transform: translate(-50%, -50%);
    }
    
    @keyframes rippleAnimation {
        0% { 
            opacity: 0; 
            transform: translate(-50%, -50%) scale(0);
        }
        50% { 
            opacity: 1; 
            transform: translate(-50%, -50%) scale(1);
        }
        100% { 
            opacity: 0; 
            transform: translate(-50%, -50%) scale(1);
        }
    }
    
    .pin-ripple-1 {
        animation: rippleAnimation 6s infinite;
        animation-delay: 0s;
    }
    
    .pin-ripple-2 {
        animation: rippleAnimation 6s infinite;
        animation-delay: 2s;
    }
    
    .pin-ripple-3 {
        animation: rippleAnimation 6s infinite;
        animation-delay: 4s;
    }
    
    /* Beam effects - exactly like React version */
    .pin-beam-blur {
        position: absolute;
        right: 50%;
        bottom: 50%;
        background: linear-gradient(to bottom, transparent, #06b6d4);
        transform: translateY(14px);
        width: 1px;
        height: 5rem;
        filter: blur(2px);
        transition: height 0.3s ease;
    }
    
    .pin-container:hover .pin-beam-blur {
        height: 10rem;
    }
    
    .pin-beam-sharp {
        position: absolute;
        right: 50%;
        bottom: 50%;
        background: linear-gradient(to bottom, transparent, #06b6d4);
        transform: translateY(14px);
        width: 1px;
        height: 5rem;
        transition: height 0.3s ease;
    }
    
    .pin-container:hover .pin-beam-sharp {
        height: 10rem;
    }
    
    .pin-dot-blur {
        position: absolute;
        right: 50%;
        bottom: 50%;
        background: #0891b2;
        transform: translateY(14px) translateX(1.5px);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        z-index: 1;
        filter: blur(3px);
    }
    
    .pin-dot-sharp {
        position: absolute;
        right: 50%;
        bottom: 50%;
        background: #67e8f9;
        transform: translateY(14px) translateX(0.5px);
        width: 2px;
        height: 2px;
        border-radius: 50%;
        z-index: 1;
    }
    
    /* Glass morphism effect */
    .glass-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    
    .glass-card:hover {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    
    /* Enhanced gradient background */
    .gradient-bg {
        background: linear-gradient(135deg, #1e1b4b 0%, #1e3a8a 25%, #3730a3 50%, #581c87 75%, #7e22ce 100%);
    }
    
    /* Ensure content below profile card stays visible */
    .content-below-profile {
        position: relative;
        z-index: 10;
    }
</style>

<div class="min-h-screen gradient-bg relative overflow-hidden full-width-content">
    <!-- Minimal animated background elements -->
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-20 left-20 w-64 h-64 bg-blue-500 opacity-10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-500 opacity-10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-500 opacity-5 rounded-full blur-3xl animate-pulse"></div>
    </div>
    
    <!-- Header Navigation -->
    <div class="glass-card border-b border-white border-opacity-20">
        <div class="w-full px-2 sm:px-4 lg:px-6">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center animate-slideInLeft">
                    <div class="relative">
                        <div class="w-12 h-12 mr-3 flex items-center justify-center">
                            <img src="{{ asset('logo.png') }}" alt="UNIDOC Logo" class="w-12 h-12 object-contain">
                        </div>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">UNIDOC</h1>
                        <p class="text-xs text-gray-300 -mt-1">Sobre o Criador</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('landing') }}" class="text-white hover:text-blue-300 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-white hover:bg-opacity-10">
                        <span class="font-medium">← Voltar ao Início</span>
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-gray-300 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-white hover:bg-opacity-10">Entrar</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="w-full px-2 sm:px-4 lg:px-6 py-20 relative z-10">
        <div class="text-center mb-16 animate-fadeInUp">
            <h2 class="text-5xl md:text-6xl font-bold text-white mb-6">
                Sobre o 
                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">Criador</span>
            </h2>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Conheça Vambert Capita, o desenvolvedor por trás do UNIDOC e sua paixão por tecnologia e inovação
            </p>
        </div>

        <!-- Profile Section Centralizada -->
        <div class="text-center mb-20">
            <!-- Advanced 3D Pin Profile Card (ONLY 3D element on page) - Exact React/Motion replica -->
            <div class="pin-container mb-8 mx-auto w-fit relative flex justify-center overflow-hidden" style="z-index: 1; max-height: 300px;">
                <!-- Main perspective container - positioned exactly like React version -->
                <div class="pin-perspective-main">
                    <!-- Content that transforms on hover -->
                    <div class="pin-content">
                        <a href="https://www.instagram.com/vambert_vandunem/" target="_blank" class="w-72 h-72 rounded-2xl flex items-center justify-center relative overflow-hidden block">
                            <div class="w-full h-full bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-2xl flex items-center justify-center relative overflow-hidden">
                                <!-- Animated background pattern -->
                                <div class="absolute inset-0 opacity-20">
                                    <div class="absolute top-4 left-4 w-8 h-8 bg-white rounded-full animate-pulse"></div>
                                    <div class="absolute top-12 right-6 w-6 h-6 bg-yellow-400 rounded-full animate-bounce"></div>
                                    <div class="absolute bottom-8 left-8 w-4 h-4 bg-blue-300 rounded-full animate-ping"></div>
                                    <div class="absolute bottom-12 right-12 w-5 h-5 bg-green-400 rounded-full animate-pulse"></div>
                                </div>
                                
                                <!-- Main profile icon -->
                                <svg class="w-32 h-32 text-white relative z-10 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                
                                <!-- Glow effect on hover -->
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 via-purple-400/20 to-pink-400/20 rounded-2xl opacity-0 hover:opacity-100 transition-opacity duration-500"></div>
                            </div>
                            
                            <!-- Status indicator -->
                            <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-green-500 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Pin Info Overlay (appears on hover) - exactly positioned like React -->
                <div class="pin-info">
                    <div class="pin-info-inner">
                        <!-- Pin Badge at top -->
                        <div class="pin-badge">
                            <div class="pin-badge-content">
                                <span class="pin-badge-text">
                                    Vambert Capita - Full-Stack Developer
                                </span>
                                <div class="pin-badge-line"></div>
                            </div>
                        </div>
                        
                        <!-- Ripple Effects container -->
                        <div class="pin-ripples-container">
                            <div class="pin-ripple pin-ripple-1"></div>
                            <div class="pin-ripple pin-ripple-2"></div>
                            <div class="pin-ripple pin-ripple-3"></div>
                        </div>
                        
                        <!-- Beam Effects - positioned exactly like React version -->
                        <div class="pin-beam-blur"></div>
                        <div class="pin-beam-sharp"></div>
                        <div class="pin-dot-blur"></div>
                        <div class="pin-dot-sharp"></div>
                    </div>
                </div>
            </div>
            
            <div class="relative z-20">
                <h3 class="text-5xl font-bold text-white mb-4">Vambert Capita</h3>
                <p class="text-2xl text-blue-300 mb-8 font-medium">Desenvolvedor Full-Stack & Criador do UNIDOC</p>
            </div>
            
            <!-- Contact Info -->
            <div class="space-y-6 mb-10 relative z-20">
                <div class="flex items-center justify-center text-gray-300 text-lg">
                    <svg class="w-6 h-6 mr-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <a href="mailto:vambert_quaresma@gmail.com" class="hover:text-blue-400 transition-colors">
                        vambert_quaresma@gmail.com
                    </a>
                </div>
                <div class="flex items-center justify-center text-gray-300 text-lg">
                    <svg class="w-6 h-6 mr-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Angola, Luanda</span>
                </div>
            </div>
            
            <!-- Social Links - Only Instagram as active -->
            <div class="flex space-x-6 justify-center relative z-20">
                <!-- Instagram - Only functional link -->
                <a href="https://www.instagram.com/vambert_vandunem/" target="_blank" class="w-16 h-16 bg-gradient-to-br from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 rounded-xl flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-pink-500/25 group">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.621 5.367 11.988 11.988 11.988s11.988-5.367 11.988-11.988C24.005 5.367 18.638.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.315-1.357S3.777 13.684 3.777 12.387s.49-2.448 1.357-3.315 2.018-1.357 3.315-1.357 2.448.49 3.315 1.357 1.357 2.018 1.357 3.315-.49 2.448-1.357 3.315-2.018 1.357-3.315 1.357zm7.718-9.9a.9.9 0 11-1.8 0 .9.9 0 011.8 0zm3.238 3.315a.9.9 0 11-1.8 0 .9.9 0 011.8 0z"/>
                    </svg>
                </a>
                
                <!-- Other social links - disabled/grayed out -->
                <div class="w-16 h-16 bg-gray-700 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
                    <svg class="w-7 h-7 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                    </svg>
                </div>
                <div class="w-16 h-16 bg-gray-700 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
                    <svg class="w-7 h-7 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </div>
                <div class="w-16 h-16 bg-gray-700 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
                    <svg class="w-7 h-7 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Information Grid - Cards "Sobre Mim" e "Missão & Visão" lado a lado -->
        <div class="grid lg:grid-cols-2 gap-16 items-start mb-20 content-below-profile">
            <!-- Bio -->
            <div class="glass-card rounded-3xl p-10 transition-all duration-300 hover:bg-white hover:bg-opacity-15">
                <h4 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    Sobre Mim
                </h4>
                <p class="text-gray-300 leading-relaxed mb-4 text-lg">
                    Desenvolvedor apaixonado por tecnologia e inovação, com foco em criar soluções que realmente fazem a diferença na vida das pessoas. O UNIDOC nasceu da necessidade de uma gestão documental moderna, segura e eficiente.
                </p>
                <p class="text-gray-300 leading-relaxed text-lg">
                    Com experiência em desenvolvimento full-stack, minha missão é democratizar o acesso a ferramentas de gestão profissional através de interfaces intuitivas e tecnologia de ponta.
                </p>
            </div>
            
            <!-- Mission -->
            <div class="glass-card rounded-3xl p-10 transition-all duration-300 hover:bg-white hover:bg-opacity-15">
                <h4 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <span class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </span>
                    Missão & Visão
                </h4>
                <p class="text-gray-300 leading-relaxed text-lg">
                    "Transformar a gestão documental através da tecnologia, oferecendo soluções acessíveis e poderosas que capacitam empresas e indivíduos a organizarem seus documentos de forma segura, eficiente e intuitiva."
                </p>
            </div>
        </div>
        
        <!-- Skills and Experience Section - Simple Glass Cards -->
        <div class="grid md:grid-cols-1 lg:grid-cols-3 gap-8 mb-20 content-below-profile">
            <!-- Tech Stack -->
            <div class="glass-card rounded-3xl p-8 transition-all duration-300 hover:bg-white hover:bg-opacity-15">
                <h4 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <span class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </span>
                    Tecnologias
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-red-500 bg-opacity-20 border border-red-500 border-opacity-30 rounded-xl p-3 text-center hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-2xl mb-1">⚡</div>
                        <div class="text-white font-medium text-sm">Laravel</div>
                    </div>
                    <div class="bg-blue-600 bg-opacity-20 border border-blue-600 border-opacity-30 rounded-xl p-3 text-center hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-2xl mb-1">🎨</div>
                        <div class="text-white font-medium text-sm">Tailwind</div>
                    </div>
                    <div class="bg-orange-500 bg-opacity-20 border border-orange-500 border-opacity-30 rounded-xl p-3 text-center hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-2xl mb-1">🗄️</div>
                        <div class="text-white font-medium text-sm">MySQL</div>
                    </div>
                    <div class="bg-blue-500 bg-opacity-20 border border-blue-500 border-opacity-30 rounded-xl p-3 text-center hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-2xl mb-1">⚛️</div>
                        <div class="text-white font-medium text-sm">React</div>
                    </div>
                    <div class="bg-cyan-500 bg-opacity-20 border border-cyan-500 border-opacity-30 rounded-xl p-3 text-center hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-2xl mb-1">🔧</div>
                        <div class="text-white font-medium text-sm">Cisco Packet Tracer</div>
                    </div>
                </div>
            </div>
            
            <!-- Achievements -->
            <div class="glass-card rounded-3xl p-8 transition-all duration-300 hover:bg-white hover:bg-opacity-15">
                <h4 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <span class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </span>
                    Conquistas
                </h4>
                <div class="space-y-4">
                    <div class="text-center hover:scale-105 transition-transform duration-300">
                        <div class="text-3xl font-bold text-blue-400 mb-1">5</div>
                        <div class="text-gray-300 text-sm">Anos de Experiência</div>
                    </div>
                    <div class="text-center hover:scale-105 transition-transform duration-300">
                        <div class="text-3xl font-bold text-green-400 mb-1">10</div>
                        <div class="text-gray-300 text-sm">Projetos Entregues</div>
                    </div>
                    <div class="text-center hover:scale-105 transition-transform duration-300">
                        <div class="text-3xl font-bold text-yellow-400 mb-1">24/7</div>
                        <div class="text-gray-300 text-sm">Suporte Dedicado</div>
                    </div>
                </div>
            </div>
            
            <!-- Experience -->
            <div class="glass-card rounded-3xl p-8 transition-all duration-300 hover:bg-white hover:bg-opacity-15">
                <h4 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <span class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
                        </svg>
                    </span>
                    Experiência
                </h4>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-400 pl-4 hover:transform hover:translate-x-2 transition-transform duration-300">
                        <h5 class="text-white font-semibold">UNIDOC</h5>
                        <p class="text-blue-300 text-sm">Founder & Lead Developer</p>
                        <p class="text-gray-400 text-xs">2024 - Presente</p>
                    </div>
                    <div class="border-l-4 border-green-400 pl-4 hover:transform hover:translate-x-2 transition-transform duration-300">
                        <h5 class="text-white font-semibold">Freelancer</h5>
                        <p class="text-green-300 text-sm">Full-Stack Developer</p>
                        <p class="text-gray-400 text-xs">2019 - Presente</p>
                    </div>
                    <div class="border-l-4 border-purple-400 pl-4 hover:transform hover:translate-x-2 transition-transform duration-300">
                        <h5 class="text-white font-semibold">Diversos Projetos</h5>
                        <p class="text-purple-300 text-sm">Web Developer</p>
                        <p class="text-gray-400 text-xs">2019 - 2024</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- UNIDOC Project Section -->
        <div class="glass-card rounded-3xl p-12 mb-20 transition-all duration-300 hover:bg-white hover:bg-opacity-15 content-below-profile">
            <div class="text-center mb-12">
                <h3 class="text-4xl font-bold text-white mb-6 animate-fadeInUp">O Projeto UNIDOC</h3>
                <p class="text-xl text-gray-300 max-w-4xl mx-auto leading-relaxed">
                    Uma plataforma completa de gestão documental que nasceu da necessidade real do mercado angolano
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-12">
                <div class="bg-red-500/10 p-6 rounded-2xl border border-red-500/20 transition-all duration-300 hover:bg-red-500/15">
                    <h4 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <span class="text-3xl mr-3">🎯</span>
                        O Problema
                    </h4>
                    <ul class="space-y-3 text-gray-300">
                        <li class="flex items-start hover:transform hover:translate-x-2 transition-transform duration-300">
                            <span class="text-red-400 mr-2">•</span>
                            <span>Dificuldade na organização de documentos empresariais</span>
                        </li>
                        <li class="flex items-start hover:transform hover:translate-x-2 transition-transform duration-300">
                            <span class="text-red-400 mr-2">•</span>
                            <span>Falta de ferramentas de gestão acessíveis no mercado local</span>
                        </li>
                        <li class="flex items-start hover:transform hover:translate-x-2 transition-transform duration-300">
                            <span class="text-red-400 mr-2">•</span>
                            <span>Necessidade de soluções seguras e confiáveis</span>
                        </li>
                        <li class="flex items-start hover:transform hover:translate-x-2 transition-transform duration-300">
                            <span class="text-red-400 mr-2">•</span>
                            <span>Ausência de suporte técnico especializado</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-green-500/10 p-6 rounded-2xl border border-green-500/20 transition-all duration-300 hover:bg-green-500/15">
                    <h4 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <span class="text-3xl mr-3">✨</span>
                        A Solução
                    </h4>
                    <ul class="space-y-3 text-gray-300">
                        <li class="flex items-start hover:transform hover:translate-x-2 transition-transform duration-300">
                            <span class="text-green-400 mr-2">•</span>
                            <span>Interface intuitiva e moderna para facilitar o uso</span>
                        </li>
                        <li class="flex items-start hover:transform hover:translate-x-2 transition-transform duration-300">
                            <span class="text-green-400 mr-2">•</span>
                            <span>Segurança máxima com criptografia de nível militar</span>
                        </li>
                        <li class="flex items-start hover:transform hover:translate-x-2 transition-transform duration-300">
                            <span class="text-green-400 mr-2">•</span>
                            <span>Preços acessíveis para empresas e pessoas físicas</span>
                        </li>
                        <li class="flex items-start hover:transform hover:translate-x-2 transition-transform duration-300">
                            <span class="text-green-400 mr-2">•</span>
                            <span>Suporte técnico 24/7 em português</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Project Highlights -->
            <div class="mt-12 grid md:grid-cols-3 gap-6">
                <div class="bg-blue-500/20 p-4 rounded-xl border border-blue-500/30 text-center transition-all duration-300 hover:bg-blue-500/25">
                    <div class="text-3xl mb-2">🚀</div>
                    <h5 class="text-white font-bold mb-1">Performance</h5>
                    <p class="text-blue-300 text-sm">10x mais rápido</p>
                </div>
                <div class="bg-purple-500/20 p-4 rounded-xl border border-purple-500/30 text-center transition-all duration-300 hover:bg-purple-500/25">
                    <div class="text-3xl mb-2">🔒</div>
                    <h5 class="text-white font-bold mb-1">Segurança</h5>
                    <p class="text-purple-300 text-sm">Nível militar</p>
                </div>
                <div class="bg-yellow-500/20 p-4 rounded-xl border border-yellow-500/30 text-center transition-all duration-300 hover:bg-yellow-500/25">
                    <div class="text-3xl mb-2">💡</div>
                    <h5 class="text-white font-bold mb-1">Inovação</h5>
                    <p class="text-yellow-300 text-sm">Tecnologia avançada</p>
                </div>
            </div>
        </div>
        
        <!-- Contact Section -->
        <div class="glass-card text-center rounded-3xl p-12 transition-all duration-300 hover:bg-white hover:bg-opacity-15 content-below-profile">
            <h3 class="text-4xl font-bold text-white mb-6 animate-fadeInUp">Vamos Conversar?</h3>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                Tenho sempre prazer em conversar sobre tecnologia, projetos inovadores ou como o UNIDOC pode ajudar sua empresa
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-8">
                <a href="mailto:vambert_quaresma@gmail.com" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold text-lg rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Enviar Email
                </a>
                
                <a href="{{ route('landing') }}" class="inline-flex items-center px-8 py-4 border-2 border-white text-white font-bold text-lg rounded-xl hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Voltar ao UNIDOC
                </a>
            </div>
            
            <!-- Social links - Only Instagram functional -->
            <div class="flex justify-center space-x-6">
                <!-- Instagram - Functional -->
                <a href="https://www.instagram.com/vambert_vandunem/" target="_blank" class="w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 rounded-xl flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-pink-500/25 group">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.621 5.367 11.988 11.988 11.988s11.988-5.367 11.988-11.988C24.005 5.367 18.638.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.315-1.357S3.777 13.684 3.777 12.387s.49-2.448 1.357-3.315 2.018-1.357 3.315-1.357 2.448.49 3.315 1.357 1.357 2.018 1.357 3.315-.49 2.448-1.357 3.315-2.018 1.357-3.315 1.357zm7.718-9.9a.9.9 0 11-1.8 0 .9.9 0 011.8 0zm3.238 3.315a.9.9 0 11-1.8 0 .9.9 0 011.8 0z"/>
                    </svg>
                </a>
                
                <!-- Other social links - Disabled -->
                <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                    </svg>
                </div>
                <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </div>
                <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
