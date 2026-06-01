import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ auth }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-200 leading-tight">Admin Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-100">
                            <h3 className="text-2xl font-bold mb-4 neon-text text-indigo-400">Welcome back, {auth.user.name}!</h3>
                            <p className="mb-6">This is your new modern Laravel + React admin panel.</p>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div className="glass-panel p-6 border-indigo-500/30">
                                    <h4 className="font-bold text-xl mb-2">Site Data</h4>
                                    <p className="text-gray-400 text-sm mb-4">Manage your site content, pricing, and services dynamically.</p>
                                    <button className="px-4 py-2 bg-indigo-600 rounded hover:bg-indigo-500 transition text-sm">Manage Data</button>
                                </div>
                                <div className="glass-panel p-6 border-cyan-500/30">
                                    <h4 className="font-bold text-xl mb-2">Messages</h4>
                                    <p className="text-gray-400 text-sm mb-4">View contact forms and chat history from your visitors.</p>
                                    <button className="px-4 py-2 bg-cyan-600 rounded hover:bg-cyan-500 transition text-sm">View Inbox</button>
                                </div>
                                <div className="glass-panel p-6 border-purple-500/30">
                                    <h4 className="font-bold text-xl mb-2">Settings</h4>
                                    <p className="text-gray-400 text-sm mb-4">Configure global site settings and API integrations.</p>
                                    <button className="px-4 py-2 bg-purple-600 rounded hover:bg-purple-500 transition text-sm">System Settings</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
