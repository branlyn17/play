import './bootstrap';
import { createElement } from 'react';
import { createRoot } from 'react-dom/client';

import PublicLandingPage from './pages/PublicLandingPage';

const appElement = document.getElementById('app');
const pages = {
    'public-home': PublicLandingPage,
};

if (appElement) {
    const pageName = appElement.dataset.page;
    const PageComponent = pages[pageName];
    const props = appElement.dataset.props ? JSON.parse(appElement.dataset.props) : {};

    if (PageComponent) {
        createRoot(appElement).render(createElement(PageComponent, props));
    }
}
