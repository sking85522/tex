import { Head, Link } from '@inertiajs/react';
import Hero3D from '@/Components/Hero3D';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

import Chatbot from "@/Components/Chatbot";

export default function Welcome({ auth, laravelVersion, phpVersion }) {
    return (
        <>
            <Head title="Welcome" />

            <div className="min-h-screen bg-gray-900 text-gray-100 selection:bg-indigo-500 selection:text-white relative overflow-hidden">
                <Hero3D />

                <header className="absolute top-0 w-full p-6 z-10 glass-panel !rounded-none !border-t-0 !border-x-0">
                    <nav className="max-w-7xl mx-auto flex justify-between items-center">
                        <div className="text-2xl font-bold tracking-tighter text-indigo-400 neon-text">
                            ELEVATE<span className="text-white">X</span>
                        </div>
                        <div className="space-x-6 hidden md:flex font-medium">
                            <Link href="/" className="hover:text-indigo-400 transition">Home</Link>
                            <Link href="/services" className="hover:text-indigo-400 transition">Services</Link>
                            <Link href="/pricing" className="hover:text-indigo-400 transition">Pricing</Link>
                            <Link href="/about" className="hover:text-indigo-400 transition">About</Link>
                        </div>
                        <div>
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="px-5 py-2 glass-panel hover:bg-gray-700/50 transition font-semibold"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="mr-4 hover:text-indigo-400 transition"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-[0_0_15px_rgba(79,70,229,0.5)] transition font-semibold"
                                    >
                                        Get Started
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main className="max-w-7xl mx-auto px-6 pt-40 pb-20 relative z-10">
                    <div className="flex flex-col items-center text-center max-w-3xl mx-auto">
                        <h1 className="text-5xl md:text-7xl font-extrabold tracking-tight mb-8">
                            Building the <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Future</span> of the Web
                        </h1>
                        <p className="text-xl text-gray-400 mb-10 leading-relaxed">
                            Experience next-generation web ecosystems powered by premium AI-first design principles, scalable cloud architecture, and immersive 3D experiences.
                        </p>

                        <div className="flex gap-4">
                            <Link href="/portfolio" className="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-bold text-lg shadow-[0_0_20px_rgba(79,70,229,0.4)] transition">
                                View Our Work
                            </Link>
                            <Link href="/contact" className="px-8 py-4 glass-panel hover:bg-gray-700/60 transition rounded-lg font-bold text-lg">
                                Contact Us
                            </Link>
                        </div>
                    </div>

                    <div className="mt-32 grid grid-cols-1 md:grid-cols-3 gap-8">
                        {/* Features */}
                        <div className="glass-panel p-8">
                            <div className="w-12 h-12 rounded-lg bg-indigo-500/20 flex items-center justify-center mb-6 border border-indigo-500/30">
                                <svg className="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <h3 className="text-2xl font-bold mb-4">High Performance</h3>
                            <p className="text-gray-400 leading-relaxed">Lightning fast load times optimized for the modern web utilizing cutting-edge caching and CDN networks.</p>
                        </div>

                        <div className="glass-panel p-8">
                            <div className="w-12 h-12 rounded-lg bg-cyan-500/20 flex items-center justify-center mb-6 border border-cyan-500/30">
                                <svg className="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                            </div>
                            <h3 className="text-2xl font-bold mb-4">Scalable Cloud</h3>
                            <p className="text-gray-400 leading-relaxed">Built on robust infrastructure that scales seamlessly with your business demands and traffic spikes.</p>
                        </div>

                        <div className="glass-panel p-8">
                            <div className="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center mb-6 border border-purple-500/30">
                                <svg className="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" /></svg>
                            </div>
                            <h3 className="text-2xl font-bold mb-4">Immersive 3D UI</h3>
                            <p className="text-gray-400 leading-relaxed">Engage users with interactive, webGL powered experiences that elevate your brand's digital presence.</p>
                        </div>
                    </div>
                </main>
                <Chatbot />
            </div>
        </>
    );
}
