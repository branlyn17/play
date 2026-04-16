import './bootstrap';
import { createElement } from 'react';
import { createRoot } from 'react-dom/client';

import PublicCatalogPage from './pages/PublicCatalogPage';
import PublicLandingPage from './pages/PublicLandingPage';
import PublicTemplateEditorPage from './pages/PublicTemplateEditorPage';

const appElement = document.getElementById('app');
const pages = {
    'public-catalog': PublicCatalogPage,
    'public-home': PublicLandingPage,
    'public-template_editor': PublicTemplateEditorPage,
};

if (appElement) {
    const pageName = appElement.dataset.page;
    const PageComponent = pages[pageName];
    const props = appElement.dataset.props ? JSON.parse(appElement.dataset.props) : {};

    if (PageComponent) {
        createRoot(appElement).render(createElement(PageComponent, props));
    }
}
