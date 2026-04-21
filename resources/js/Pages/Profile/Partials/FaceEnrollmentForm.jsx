import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import { Transition } from '@headlessui/react';
import { useForm, usePage } from '@inertiajs/react';

export default function FaceEnrollmentForm({ className = '' }) {
    const user = usePage().props.auth.user;

    const { data, setData, post, errors, processing, recentlySuccessful, reset } =
        useForm({
            image: null,
        });

    const submit = (e) => {
        e.preventDefault();

        post(route('profile.face.enrollment'), {
            forceFormData: true,
            onSuccess: () => reset(),
        });
    };

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">
                    Facial Enrollment
                </h2>

                <p className="mt-1 text-sm text-gray-600">
                    Enroll your face for future authentication. Please upload a clear photo of your face.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="face_image" value="Face Image" />

                    <input
                        id="face_image"
                        type="file"
                        className="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                        onChange={(e) => setData('image', e.target.files[0])}
                    />

                    <InputError className="mt-2" message={errors.image} />
                </div>

                {user.face_photo && (
                    <div className="mt-4">
                        <p className="text-sm text-gray-600 mb-2">Current enrolled face:</p>
                        <img 
                            src={`/storage/${user.face_photo}`} 
                            alt="Current face" 
                            className="h-32 w-32 object-cover rounded-lg border-2 border-indigo-500 shadow-sm" 
                        />
                    </div>
                )}

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>Enroll Face</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600">
                            Enrolled.
                        </p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
