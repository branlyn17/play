export default function Footer({ appName, copy, theme }) {
    const isLight = theme === 'light';

    return (
        <footer className={`mt-10 border-t px-2 py-6 text-sm ${isLight ? 'border-slate-200 text-slate-500' : 'border-white/10 text-white/50'}`}>
            <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p>{appName} · {copy.left}</p>
                <p>{copy.right}</p>
            </div>
        </footer>
    );
}
