import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import PrimaryButton from '@/Components/PrimaryButton';
import InputError from '@/Components/InputError';
import { Head, useForm, Link } from '@inertiajs/react';
import { useRef, useState, useEffect } from 'react';

export default function FaceVerification() {
    const videoRef = useRef(null);
    const canvasRef = useRef(null);
    const [stream, setStream] = useState(null);
    const [cameraError, setCameraError] = useState('');

    const { post, processing, errors, reset, transform } = useForm({
        webcam_image: null,
    });

    useEffect(() => {
        startCamera();
        return () => stopCamera();
    }, []);

    // Re-start camera if we encounter any errors and we're not processing
    useEffect(() => {
        if (!processing && Object.keys(errors).length > 0) {
            startCamera();
        }
    }, [processing, errors]);

    const startCamera = async () => {
        if (stream && stream.active) return; // Already running

        if (!window.isSecureContext) {
            setCameraError('La cámara no está disponible porque la conexión no es segura (HTTPS). Por favor, intenta usar localhost.');
            return;
        }

        try {
            const mediaStream = await navigator.mediaDevices.getUserMedia({ video: true });
            setStream(mediaStream);
            setCameraError('');
            if (videoRef.current) {
                videoRef.current.srcObject = mediaStream;
            }
        } catch (err) {
            console.error("Error de cámara:", err);
            if (err.name === 'NotAllowedError') {
                setCameraError('Permiso denegado. Por favor, permite el acceso a la cámara en tu navegador.');
            } else if (err.name === 'NotFoundError') {
                setCameraError('No se encontró ninguna cámara conectada a este dispositivo.');
            } else if (err.name === 'NotReadableError') {
                setCameraError('La cámara parece estar en uso por otra aplicación o pestaña.');
            } else {
                setCameraError('Error al acceder a la cámara: ' + err.message);
            }
        }
    };

    const stopCamera = () => {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            setStream(null);
        }
    };

    const captureAndVerify = () => {
        if (!videoRef.current || !canvasRef.current) return;

        const video = videoRef.current;
        const canvas = canvasRef.current;

        if (!video.videoWidth || !video.videoHeight) {
            setCameraError('La cámara aún no está lista. Espera un momento e inténtalo de nuevo.');
            return;
        }

        const context = canvas.getContext('2d');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob((blob) => {
            if (!blob) {
                setCameraError('No se pudo capturar la imagen de la cámara. Inténtalo de nuevo.');
                return;
            }

            const file = new File([blob], "webcam.jpg", { type: "image/jpeg" });

            transform(() => ({ webcam_image: file }));
            post(route('face.verify'), {
                forceFormData: true,
                preserveScroll: true,
                onFinish: () => {
                    transform((formData) => formData);
                    reset('webcam_image');
                },
            });
            
        }, 'image/jpeg');
    };

    return (
        <AuthenticatedLayout>
            <Head title="Verificación Facial" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900 flex flex-col items-center">
                            <h2 className="text-2xl font-semibold mb-6">Verificación de Seguridad</h2>
                            
                            <p className="mb-6 text-center text-gray-600">
                                Para acceder a esta sección, necesitamos confirmar tu identidad utilizando tu rostro registrado.
                            </p>

                            {/* Show ALL validation errors */}
                            {Object.keys(errors).length > 0 && (
                                <div className="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative w-full text-center">
                                    {Object.values(errors).map((error, index) => (
                                        <div key={index} className="block sm:inline">{error} </div>
                                    ))}
                                    {errors.message?.includes('No tienes un rostro enrolado') && (
                                        <div className="mt-2">
                                            <Link href={route('profile.edit')} className="underline font-bold">Ir a mi perfil</Link>
                                        </div>
                                    )}
                                </div>
                            )}

                            {cameraError && (
                                <div className="mb-4 text-red-600 bg-red-50 p-4 rounded-lg w-full text-center">
                                    {cameraError}
                                </div>
                            )}

                            <div className="relative border-4 border-gray-200 rounded-lg overflow-hidden bg-black mb-6 flex justify-center items-center" style={{ width: '400px', height: '300px' }}>
                                
                                <video 
                                    ref={videoRef} 
                                    autoPlay 
                                    playsInline 
                                    className={`h-full object-cover ${processing ? 'opacity-30' : ''}`}
                                    style={{ transform: 'scaleX(-1)' }} // Mirror the video
                                ></video>

                                {processing && (
                                    <div className="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                         <div className="text-white text-lg animate-pulse">Analizando identidad...</div>
                                    </div>
                                )}
                            </div>

                            {/* Hidden canvas for capturing the frame */}
                            <canvas ref={canvasRef} className="hidden"></canvas>

                            {processing ? (
                                <PrimaryButton disabled>
                                    Verificando...
                                </PrimaryButton>
                            ) : (
                                <PrimaryButton 
                                    onClick={captureAndVerify} 
                                    className="scale-110"
                                    disabled={!!cameraError}
                                >
                                    Verificar Identidad
                                </PrimaryButton>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
