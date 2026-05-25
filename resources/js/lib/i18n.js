export function createTranslator(i18n = {}) {
    const messages = i18n.messages ?? {};

    return function t(key, fallback = '') {
        if (typeof key !== 'string' || key === '') {
            return fallback;
        }

        return messages[key] ?? (fallback || key);
    };
}
