import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router, usePage } from '@inertiajs/react';

export default function Index({ games }) {
    const user = usePage().props.auth.user;

    const togglePublish = (game) => {
        router.patch(route('manage.games.publish', game.id));
    };

    const destroyGame = (game) => {
        if (!confirm(`Eliminar ${game.title}?`)) {
            return;
        }

        router.delete(route('manage.games.destroy', game.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Gestion de Juegos
                    </h2>
                    <Link
                        href={route('manage.games.create')}
                        className="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        Nuevo juego
                    </Link>
                </div>
            }
        >
            <Head title="Gestion de Juegos" />

            <div className="py-8">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Titulo</th>
                                    <th className="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Estado</th>
                                    <th className="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Creador</th>
                                    <th className="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-200 bg-white">
                                {games.map((game) => (
                                    <tr key={game.id}>
                                        <td className="px-4 py-3">
                                            <p className="font-medium text-gray-900">{game.title}</p>
                                            <p className="text-xs text-gray-500">{game.path}</p>
                                        </td>
                                        <td className="px-4 py-3">
                                            <span className={`rounded-full px-2.5 py-1 text-xs font-semibold ${game.is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'}`}>
                                                {game.is_published ? 'Publicado' : 'Borrador'}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3 text-sm text-gray-700">{game.user?.name ?? user.name}</td>
                                        <td className="px-4 py-3 text-right text-sm">
                                            <div className="flex justify-end gap-2">
                                                <Link
                                                    href={route('manage.games.edit', game.id)}
                                                    className="rounded border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100"
                                                >
                                                    Editar
                                                </Link>
                                                <button
                                                    type="button"
                                                    onClick={() => togglePublish(game)}
                                                    className="rounded border border-indigo-300 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-50"
                                                >
                                                    {game.is_published ? 'Despublicar' : 'Publicar'}
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => destroyGame(game)}
                                                    className="rounded border border-rose-300 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                                {games.length === 0 && (
                                    <tr>
                                        <td colSpan="4" className="px-4 py-8 text-center text-sm text-gray-500">
                                            No hay juegos registrados.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
