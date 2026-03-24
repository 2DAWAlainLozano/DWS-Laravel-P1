import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        description: '',
        path: '',
        is_published: false,
    });

    const submit = (event) => {
        event.preventDefault();
        post(route('manage.games.store'));
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Nuevo Juego</h2>}
        >
            <Head title="Nuevo Juego" />

            <div className="py-8">
                <div className="mx-auto max-w-3xl rounded-lg bg-white p-6 shadow sm:px-8">
                    <form onSubmit={submit} className="space-y-6">
                        <div>
                            <label className="mb-1 block text-sm font-medium text-gray-700">Titulo</label>
                            <input
                                type="text"
                                value={data.title}
                                onChange={(e) => setData('title', e.target.value)}
                                className="w-full rounded border-gray-300"
                                required
                            />
                            <InputError message={errors.title} className="mt-2" />
                        </div>

                        <div>
                            <label className="mb-1 block text-sm font-medium text-gray-700">Descripcion</label>
                            <textarea
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                                className="h-28 w-full rounded border-gray-300"
                                required
                            />
                            <InputError message={errors.description} className="mt-2" />
                        </div>

                        <div>
                            <label className="mb-1 block text-sm font-medium text-gray-700">URL del juego</label>
                            <input
                                type="url"
                                value={data.path}
                                onChange={(e) => setData('path', e.target.value)}
                                className="w-full rounded border-gray-300"
                                placeholder="https://mi-juego.example.com"
                                required
                            />
                            <InputError message={errors.path} className="mt-2" />
                        </div>

                        <label className="flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                checked={data.is_published}
                                onChange={(e) => setData('is_published', e.target.checked)}
                                className="rounded border-gray-300"
                            />
                            Publicar al guardar
                        </label>

                        <div className="flex justify-end gap-3">
                            <Link
                                href={route('manage.games.index')}
                                className="rounded border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                disabled={processing}
                                className="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-50"
                            >
                                Guardar juego
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
