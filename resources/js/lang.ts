function loadLocaleMessages() {
    const locales = import.meta.glob('@/lang/*/*.json', { eager: true });

    const messages: Record<string, any> = {};

    for (const path in locales) {
        const matched = path.match(/lang\/([a-z0-9-_]+)\/([a-z0-9-_]+)\.json$/i);
        if (matched) {
            const [, locale, filename] = matched;
            messages[locale] = messages[locale] || {};
            messages[locale][filename] = (locales[path] as any).default;
        }
    }

    return messages;
}

export default loadLocaleMessages;
