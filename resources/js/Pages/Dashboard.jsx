import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Dashboard() {
    const user = usePage().props.auth.user;
    const canManage = ['administrador', 'gestor'].includes(user?.role?.name);

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="space-y-4 p-6 text-gray-900">
                            <p>
                                Sesion iniciada como <strong>{user?.role?.name ?? 'sin rol'}</strong>.
                            </p>

                            <div className="flex flex-wrap gap-3">
                                <Link
                                    href={route('games.catalog')}
                                    className="rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500"
                                >
                                    Ver catalogo
                                </Link>
                                {canManage && (
                                    <Link
                                        href={route('manage.games.index')}
                                        className="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                                    >
                                        Gestionar juegos
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
