export default function HeroSection({ appName, copy, slides, activeIndex, onSlideChange, theme, catalogHref = '#' }) {
    const isLight = theme === 'light';
    const activeSlide = slides[activeIndex] ?? slides[0] ?? {};

    return (
        <section className="px-2 pt-4 sm:px-4">
            <div className="mx-auto max-w-5xl text-center">
                <div
                    className={`mx-auto inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] ${
                        isLight ? 'border-sky-200 bg-white/80 text-sky-700' : 'border-white/10 bg-white/8 text-sky-200'
                    }`}
                >
                    <span className={`h-2 w-2 rounded-full ${isLight ? 'bg-sky-500' : 'bg-sky-300'}`} />
                    {copy.badge}
                </div>

                <h1 className={`mt-8 text-5xl font-semibold leading-none tracking-tight text-balance sm:text-6xl lg:text-8xl ${isLight ? 'text-slate-950' : 'text-white'}`}>
                    {copy.titleTop}
                    <span
                        className={`block bg-gradient-to-r bg-clip-text text-transparent ${
                            isLight ? 'from-sky-500 via-indigo-500 to-fuchsia-500' : 'from-indigo-400 via-violet-400 to-pink-500'
                        }`}
                    >
                        {copy.titleAccent}
                    </span>
                </h1>

                <p className={`mx-auto mt-6 max-w-3xl text-lg leading-8 sm:text-xl ${isLight ? 'text-slate-600' : 'text-slate-300'}`}>
                    {copy.subtitle}
                </p>

                <div className="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a
                        href={catalogHref}
                        className={`rounded-2xl px-8 py-4 text-lg font-semibold transition ${
                            isLight
                                ? 'bg-indigo-600 text-white shadow-[0_22px_40px_rgba(79,70,229,0.25)] hover:bg-indigo-500'
                                : 'bg-indigo-500 text-white shadow-[0_22px_40px_rgba(99,102,241,0.28)] hover:bg-indigo-400'
                        }`}
                    >
                        {copy.primaryAction}
                    </a>
                    <a
                        href={activeSlide.href ?? catalogHref}
                        className={`rounded-2xl border px-8 py-4 text-lg font-semibold transition ${
                            isLight
                                ? 'border-slate-200 bg-white/80 text-slate-800 hover:bg-white'
                                : 'border-white/10 bg-slate-800/70 text-white hover:bg-slate-800'
                        }`}
                    >
                        {copy.secondaryAction}
                    </a>
                </div>

                <div className={`mt-6 flex flex-wrap items-center justify-center gap-6 text-sm ${isLight ? 'text-slate-500' : 'text-slate-400'}`}>
                    {copy.bullets.map((bullet) => (
                        <div key={bullet} className="flex items-center gap-2">
                            <span className="text-emerald-500">✓</span>
                            <span>{bullet}</span>
                        </div>
                    ))}
                </div>
            </div>

            <div
                className={`mx-auto mt-12 max-w-5xl overflow-hidden rounded-[2rem] border p-3 shadow-[0_30px_80px_rgba(15,23,42,0.18)] transition-colors duration-500 ${
                    isLight ? 'border-slate-200 bg-slate-100/90' : 'border-white/10 bg-slate-800/55'
                }`}
            >
                <div className="flex items-center gap-2 px-2 pb-3">
                    <span className="h-3 w-3 rounded-full bg-rose-400" />
                    <span className="h-3 w-3 rounded-full bg-amber-300" />
                    <span className="h-3 w-3 rounded-full bg-emerald-400" />
                </div>

                <div className="relative h-[20rem] overflow-hidden rounded-[1.4rem] sm:h-[24rem] lg:h-[30rem]">
                    {slides.map((slide, index) => {
                        const isActive = index === activeIndex;

                        return (
                            <article
                                key={slide.id}
                                className={`absolute inset-0 transition-all duration-700 ${
                                    isActive ? 'translate-x-0 opacity-100' : 'pointer-events-none translate-x-10 opacity-0'
                                }`}
                            >
                                <div
                                    className="absolute inset-0"
                                    style={{
                                        background: slide.background,
                                    }}
                                />
                                {slide.imageUrl ? (
                                    <img
                                        src={slide.imageUrl}
                                        alt={slide.title}
                                        className="absolute inset-0 h-full w-full object-cover object-top"
                                        loading={index === 0 ? 'eager' : 'lazy'}
                                    />
                                ) : null}
                                <div className={`absolute inset-0 ${isLight ? 'bg-[linear-gradient(180deg,rgba(255,255,255,0.08),rgba(255,255,255,0.1))]' : 'bg-[linear-gradient(180deg,rgba(15,23,42,0.05),rgba(15,23,42,0.18))]'}`} />

                                <div className="relative flex h-full flex-col justify-between p-6 sm:p-8">
                                    <div className="flex items-center justify-between">
                                        <div className={`rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] ${isLight ? 'bg-white/80 text-slate-700' : 'bg-white/10 text-white/80'}`}>
                                            {slide.category}
                                        </div>
                                        <div className={`rounded-full px-3 py-1 text-xs uppercase tracking-[0.24em] ${isLight ? 'bg-slate-900/8 text-slate-600' : 'bg-black/15 text-white/60'}`}>
                                            {slide.year}
                                        </div>
                                    </div>

                                    <div className="flex-1" />

                                    <div className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                                        <div className={`rounded-full px-4 py-2 text-xs uppercase tracking-[0.24em] ${isLight ? 'bg-white/80 text-slate-700' : 'bg-white/10 text-white/75'}`}>
                                            {slide.label}
                                        </div>
                                        <div
                                            className={`float-slow max-w-sm rounded-[1.45rem] border px-5 py-4 text-left backdrop-blur-2xl ${
                                                isLight
                                                    ? 'border-white/70 bg-white/82 text-slate-900 shadow-[0_24px_60px_rgba(15,23,42,0.14)]'
                                                    : 'border-white/16 bg-slate-950/58 text-white shadow-[0_24px_70px_rgba(2,6,23,0.34)]'
                                            }`}
                                        >
                                            <div className="flex items-center justify-between gap-4">
                                                <p className={`text-[11px] font-semibold uppercase tracking-[0.26em] ${isLight ? 'text-slate-500' : 'text-white/55'}`}>{copy.previewLabel}</p>
                                                <span className={`h-1.5 w-10 rounded-full ${isLight ? 'bg-indigo-500' : 'bg-white/70'}`} />
                                            </div>
                                            <h2 className="mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">{slide.title}</h2>
                                            <p className={`mt-2 text-sm leading-6 ${isLight ? 'text-slate-600' : 'text-white/72'}`}>{slide.caption}</p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        );
                    })}
                </div>

                <div className={`flex flex-col gap-4 px-2 pt-4 sm:flex-row sm:items-center sm:justify-between ${isLight ? 'text-slate-600' : 'text-slate-300'}`}>
                    <div>
                        <p className={`text-sm uppercase tracking-[0.24em] ${isLight ? 'text-slate-500' : 'text-white/55'}`}>{copy.sliderLabel}</p>
                        <p className="mt-2 text-lg font-semibold">{activeSlide.microcopy}</p>
                    </div>

                    <div className="flex items-center gap-3">
                        <div className={`h-1.5 w-28 overflow-hidden rounded-full ${isLight ? 'bg-slate-300/60' : 'bg-white/10'}`}>
                            <div key={activeIndex} className={`shimmer-track h-full rounded-full ${isLight ? 'bg-indigo-500' : 'bg-white'}`} />
                        </div>
                        <div className="flex gap-2">
                            {slides.map((slide, index) => (
                                <button
                                    key={slide.id}
                                    type="button"
                                    aria-label={slide.title}
                                    onClick={() => onSlideChange(index)}
                                    className={`h-2.5 rounded-full transition-all ${
                                        activeIndex === index
                                            ? isLight
                                                ? 'w-10 bg-indigo-500'
                                                : 'w-10 bg-white'
                                            : isLight
                                              ? 'w-2.5 bg-slate-300 hover:bg-slate-400'
                                              : 'w-2.5 bg-white/25 hover:bg-white/45'
                                    }`}
                                />
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
