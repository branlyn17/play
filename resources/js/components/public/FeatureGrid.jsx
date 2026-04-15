export default function FeatureGrid({ items, theme }) {
    const isLight = theme === 'light';

    return (
        <section className="grid gap-4 px-2 pb-4 md:grid-cols-3">
            {items.map((feature) => (
                <article
                    key={feature.title}
                    className={`group rounded-[1.75rem] p-6 transition hover:-translate-y-1 ${
                        isLight
                            ? 'border border-slate-200 bg-white/75 shadow-[0_18px_40px_rgba(148,163,184,0.12)]'
                            : 'glass-panel'
                    }`}
                >
                    <div
                        className={`flex h-12 w-12 items-center justify-center rounded-2xl text-xl ${
                            isLight ? 'bg-indigo-50 text-indigo-600' : 'bg-white/10 text-white'
                        }`}
                    >
                        {feature.icon}
                    </div>
                    <h2 className={`mt-5 text-2xl font-semibold tracking-tight ${isLight ? 'text-slate-950' : 'text-white'}`}>
                        {feature.title}
                    </h2>
                    <p className={`mt-3 text-base leading-7 ${isLight ? 'text-slate-600' : 'text-white/62'}`}>{feature.text}</p>
                </article>
            ))}
        </section>
    );
}
