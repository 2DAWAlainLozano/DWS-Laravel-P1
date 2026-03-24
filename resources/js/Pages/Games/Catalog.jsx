import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Catalog({ games }) {
    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Catalogo de Juegos</h2>}
        >
            <Head title="Catalogo" />

            <div className="py-8">
                <div className="mx-auto grid max-w-7xl gap-6 px-4 sm:grid-cols-2 lg:grid-cols-3 sm:px-6 lg:px-8">
                    {games.map((game) => (
                        <article key={game.id} className="rounded-lg bg-white p-5 shadow">
                            <h3 className="text-lg font-semibold text-gray-900">{game.title}</h3>
                            <p className="mt-2 line-clamp-3 text-sm text-gray-600">{game.description}</p>
                            <Link
                                href={route('games.play', game.id)}
                                className="mt-4 inline-flex rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500"
                            >
                                Jugar
                            </Link>
                        </article>
                    ))}

                    {games.length === 0 && (
                        <div className="col-span-full rounded-lg bg-white p-8 text-center text-sm text-gray-500 shadow">
                            Aun no hay juegos publicados.
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
