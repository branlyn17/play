const blocks = [
    {
        title: 'Presentacion memorable',
        text: 'Cada evento necesita una portada clara, emocional y lista para compartirse en segundos.',
    },
    {
        title: 'Informacion bien ordenada',
        text: 'Ubicacion, agenda, RSVP, regalos y detalles importantes en una sola experiencia visual.',
    },
    {
        title: 'Escala para multiples productos',
        text: 'La misma base puede servir para invitaciones, paginas publicas, catalogos y landings tematicas.',
    },
];

export default function ExperienceShowcase() {
    return (
        <section className="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <article className="relative overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-stone-900 via-stone-900 to-rose-950/40 p-8 shadow-[0_24px_80px_rgba(0,0,0,0.32)]">
                <div className="absolute -left-16 top-8 h-40 w-40 rounded-full bg-amber-300/10 blur-3xl" />
                <div className="absolute bottom-0 right-0 h-56 w-56 rounded-full bg-rose-400/10 blur-3xl" />

                <div className="relative">
                    <p className="text-sm font-semibold uppercase tracking-[0.28em] text-amber-300">Experiencia publica</p>
                    <h2 className="mt-4 text-3xl font-semibold tracking-tight text-balance sm:text-4xl">
                        Una interfaz pensada para transmitir elegancia sin perder claridad.
                    </h2>
                    <p className="mt-4 max-w-2xl text-base leading-7 text-stone-300">
                        React nos permite convertir la portada en una experiencia modular. Cada bloque puede crecer
                        despues hacia datos reales, variaciones por plantilla y contenido administrable.
                    </p>

                    <div className="mt-8 grid gap-4 md:grid-cols-3">
                        {blocks.map((block) => (
                            <div key={block.title} className="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <h3 className="text-lg font-semibold text-white">{block.title}</h3>
                                <p className="mt-3 text-sm leading-6 text-stone-300">{block.text}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </article>

            <aside className="rounded-[2rem] border border-white/10 bg-stone-900/90 p-6 shadow-[0_24px_80px_rgba(0,0,0,0.28)]">
                <p className="text-sm font-semibold uppercase tracking-[0.28em] text-amber-300">Stack inicial</p>
                <div className="mt-6 space-y-5">
                    <div className="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                        <p className="text-sm text-stone-400">Backend</p>
                        <p className="mt-2 text-xl font-semibold text-white">Laravel controla rutas, controllers y SEO base.</p>
                    </div>
                    <div className="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                        <p className="text-sm text-stone-400">Frontend</p>
                        <p className="mt-2 text-xl font-semibold text-white">React compone vistas y bloques reutilizables.</p>
                    </div>
                    <div className="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                        <p className="text-sm text-stone-400">Estilos</p>
                        <p className="mt-2 text-xl font-semibold text-white">Tailwind acelera prototipos sin perder orden.</p>
                    </div>
                </div>
            </aside>
        </section>
    );
}
