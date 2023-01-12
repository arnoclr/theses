//@ts-check
/** @type {NodeListOf<HTMLAnchorElement>} */
let as;

/** @type {HTMLInputElement | null} */
const navSearch = document.querySelector('.js-nav-search input[type=search]');
const main = document.querySelector("main");

const setInnerHTML = (/** @type {HTMLElement} */ elm, /** @type {string} */ html) => {
    elm.innerHTML = html;

    Array.from(elm.querySelectorAll("script"))
        .forEach(oldScriptEl => {
            const newScriptEl = document.createElement("script");

            Array.from(oldScriptEl.attributes).forEach(attr => {
                newScriptEl.setAttribute(attr.name, attr.value);
            });

            const scriptText = document.createTextNode(oldScriptEl.innerHTML);
            newScriptEl.appendChild(scriptText);

            oldScriptEl.parentNode?.replaceChild(newScriptEl, oldScriptEl);
        });
};

const getQueryStringFromUrl = (/** @type {string} */ url) => {
    const urlParams = new URLSearchParams(url.split("?")[1]);
    return urlParams.get("q") || "";
};

const updateResultsUI = async (/** @type {string} */ href) => {
    if (main == undefined) return;
    main.classList.add("loading");
    const resultBody = await fetch(href + "&headless=1").then(x => x.text());
    window.history.pushState({}, "", href);
    setInnerHTML(main, resultBody);
    main.classList.remove("loading");
    navSearch && (navSearch.value = getQueryStringFromUrl(href));
    detectLinksAndAttachEvents();
};

const detectLinksAndAttachEvents = () => {
    as = document.querySelectorAll("main a");

    as.forEach(a => {
        const href = a.getAttribute('href');
        a.addEventListener('click', async e => {
            if (href == undefined) return;
            e.preventDefault();
            await updateResultsUI(href);
        });
    });
};

window.onpopstate = function (e) {
    if (e.state) {
        updateResultsUI(window.location.href);
    }
};

detectLinksAndAttachEvents();