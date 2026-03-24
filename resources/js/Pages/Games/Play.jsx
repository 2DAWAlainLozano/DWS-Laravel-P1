import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Play({ game }) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between gap-3">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">{game.title}</h2>
                    <Link
                        href={route('games.catalog')}
                        className="rounded border border-gray-300 px-3 py-1.5 text-sm font-semibold text-gray-700 hover:bg-gray-100"
                    >
                        Volver al catalogo
                    </Link>
                </div>
            }
        >
            <Head title={game.title} />

            <div className="py-8">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="mb-4 rounded-lg bg-white p-4 shadow">
                        <p className="text-sm text-gray-600">{game.description}</p>
                    </div>

                    <div className="overflow-hidden rounded-lg bg-black shadow">
                        <iframe
                            src={game.path}
                            title={game.title}
                            className="h-[70vh] w-full"
                            allow="autoplay; fullscreen"
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
