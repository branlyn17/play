export default function ClosingCta() {
    return (
        <section className="relative overflow-hidden rounded-[2rem] border border-white/10 bg-stone-900 px-6 py-10 shadow-[0_24px_80px_rgba(0,0,0,0.35)] sm:px-10">
            <div className="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-amber-300/70 to-transparent" />
            <div className="absolute -right-24 top-10 h-56 w-56 rounded-full bg-amber-300/10 blur-3xl" />

            <div className="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div className="max-w-2xl">
                    <p className="text-sm font-semibold uppercase tracking-[0.28em] text-amber-300">Siguiente paso</p>
                    <h2 className="mt-4 text-3xl font-semibold tracking-tight text-balance sm:text-4xl">
                        Una base visual lista para crecer hacia plantillas, eventos, catalogos y multilenguaje.
                    </h2>
                    <p className="mt-4 max-w-xl text-base leading-7 text-stone-300">
                        Este primer home ya esta estructurado como una aplicacion mantenible: componentes pequenos,
                        layout publico y una pagina raiz clara.
                    </p>
                </div>

                <div className="flex flex-col gap-3 sm:flex-row">
                    <button className="rounded-full bg-amber-300 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-200">
                        Comenzar arquitectura
                    </button>
                    <button className="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/5">
                        Ver componentes
                    </button>
                </div>
            </div>
        </section>
    );
}
