export default function PublicHome() {
    const highlights = [
        'Invitaciones digitales elegantes y faciles de compartir.',
        'Panel flexible para eventos, plantillas y catalogos.',
        'Base preparada para crecer hacia multilenguaje y SEO.',
    ];

    return (
        <div className="min-h-screen bg-stone-950 text-stone-50">
            <section className="mx-auto flex min-h-screen max-w-6xl flex-col justify-center gap-12 px-6 py-16 lg:px-10">
                <div className="max-w-3xl">
                    <p className="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-amber-300">
                        Invita Plus
                    </p>

                    <h1 className="text-5xl font-semibold tracking-tight text-balance sm:text-6xl">
                        Invitaciones digitales modernas para eventos que merecen una mejor presencia.
                    </h1>

                    <p className="mt-6 max-w-2xl text-lg leading-8 text-stone-300">
                        Esta es una base publica montada con Laravel + React. Desde aqui podemos evolucionar hacia una
                        experiencia mas dinamica sin perder el orden del backend.
                    </p>
                </div>

                <div className="grid gap-4 md:grid-cols-3">
                    {highlights.map((item) => (
                        <article
                            key={item}
                            className="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-[0_20px_60px_rgba(0,0,0,0.28)] backdrop-blur"
                        >
                            <div className="mb-4 h-2 w-16 rounded-full bg-amber-300" />
                            <p className="text-base leading-7 text-stone-200">{item}</p>
                        </article>
                    ))}
                </div>

                <div className="flex flex-col gap-4 sm:flex-row">
                    <a
                        href="#"
                        className="inline-flex items-center justify-center rounded-full bg-amber-300 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-200"
                    >
                        Ver demo inicial
                    </a>

                    <a
                        href="#"
                        className="inline-flex items-center justify-center rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/5"
                    >
                        Explorar estructura
                    </a>
                </div>
            </section>
        </div>
    );
}
