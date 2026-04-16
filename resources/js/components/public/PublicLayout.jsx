import Footer from './Footer';
import Header from './Header';

export default function PublicLayout({ children, appName, auth, footerCopy, headerProps, theme }) {
    const isLight = theme === 'light';

    return (
        <div
            className={`relative min-h-screen overflow-hidden transition-colors duration-500 ${
                isLight ? 'bg-[#eef6ff] text-slate-950' : 'bg-[#0f1833] text-white'
            }`}
        >
            <div className="pointer-events-none absolute inset-0">
                <div className={`absolute -left-24 top-10 h-80 w-80 rounded-full blur-3xl ${isLight ? 'bg-cyan-300/30' : 'bg-cyan-400/12'}`} />
                <div className={`absolute right-0 top-0 h-[28rem] w-[28rem] rounded-full blur-3xl ${isLight ? 'bg-indigo-300/20' : 'bg-fuchsia-400/10'}`} />
                <div className={`absolute bottom-0 left-1/3 h-72 w-72 rounded-full blur-3xl ${isLight ? 'bg-sky-300/20' : 'bg-amber-300/10'}`} />
                <div
                    className={`absolute inset-0 ${
                        isLight
                            ? 'bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.8),_transparent_40%),linear-gradient(180deg,rgba(255,255,255,0.4),rgba(219,234,254,0.9))]'
                            : 'bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.07),_transparent_38%),linear-gradient(180deg,rgba(2,6,23,0.2),rgba(2,6,23,0.7))]'
                    }`}
                />
            </div>

            <div className="relative mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-4 sm:px-6 lg:px-8">
                <Header appName={appName} auth={auth} theme={theme} {...headerProps} />
                <main className="flex-1 space-y-6 pb-8">{children}</main>
                <Footer appName={appName} copy={footerCopy} theme={theme} />
            </div>
        </div>
    );
}
