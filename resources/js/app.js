import './bootstrap';
import { createElement } from 'react';
import { createRoot } from 'react-dom/client';

import PublicHome from './components/PublicHome';

const appElement = document.getElementById('app');

if (appElement?.dataset.page === 'public-home') {
    createRoot(appElement).render(createElement(PublicHome));
}
