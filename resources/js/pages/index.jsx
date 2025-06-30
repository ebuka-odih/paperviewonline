import { Head } from '@inertiajs/react';

export default function Index() {
    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen bg-gray-900 flex items-center justify-center">
                <div className="text-center">
                    <h1 className="text-6xl font-bold text-white mb-4">
                        Welcome
                    </h1>
                    <p className="text-xl text-gray-500">
                        Welcome to our application
                    </p>
                </div>
            </div>
        </>
    );
} 