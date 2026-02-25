/* Formularios - Mensajes Emergentes */
document.addEventListener('DOMContentLoaded', () => {

    const modalContent = document.querySelector('#modal-contactanos-content');
    const modal = document.getElementById('modal-contactanos');
    const modalContainer = modal?.querySelector('.modal__container');
    const overlay = modal?.querySelector('.modal__overlay');
    const header = modal?.querySelector('.modal__header');

    const closeSVG = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
         class="modal__close icono" data-micromodal-close>
        <path fill="var(--primario-azul)"
            d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12L5.293 6.707a1 1 0 0 1 0-1.414"/>
    </svg>`;

    let bloquearCerrar = false;

    document.addEventListener('keydown', function (e) {
        if (!bloquearCerrar) return;
        if (e.key === 'Escape' && modal?.getAttribute('aria-hidden') === 'false') {
            e.stopImmediatePropagation();
            e.preventDefault();
        }
    }, true);

    document.addEventListener('wpcf7beforesubmit', function (event) {

        const form = event.target;

        const tieneInvalidos = form.querySelectorAll('.wpcf7-not-valid').length > 0;

        const camposRequeridos = form.querySelectorAll('[aria-required="true"]');
        const tieneVacios = [...camposRequeridos].some(campo => !campo.value.trim());

        if (tieneInvalidos || tieneVacios) return;

        bloquearCerrar = true;

        overlay?.removeAttribute('data-micromodal-close');
        header?.querySelector('.modal__close')?.remove();

        modalContainer?.classList.remove('modal-result');
        modalContainer?.classList.add('modal-loading');

        if (modalContent) {
            modalContent.innerHTML = `
                <div class="loader">
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                    <div class="bar4"></div>
                    <div class="bar5"></div>
                    <div class="bar6"></div>
                    <div class="bar7"></div>
                    <div class="bar8"></div>
                    <div class="bar9"></div>
                    <div class="bar10"></div>
                    <div class="bar11"></div>
                    <div class="bar12"></div>
                </div>
            `;
        }

        MicroModal.show("modal-contactanos", {
            disableScroll: true,
            awaitOpenAnimation: true
        });

    });

    document.addEventListener('wpcf7submit', function (event) {

        if (event.detail.status === 'validation_failed') {
            MicroModal.close("modal-contactanos");
            return;
        }

        bloquearCerrar = false;

        overlay?.setAttribute('data-micromodal-close', '');

        if (!header?.querySelector('.modal__close')) {
            header?.insertAdjacentHTML('beforeend', closeSVG);
        }

        modalContainer?.classList.remove('modal-loading');
        modalContainer?.classList.add('modal-result');

        const status = event.detail.status;

        const mensaje = (typeof cfMensajes !== "undefined" && cfMensajes[status])
            ? cfMensajes[status]
            : cfMensajes?.default;

        if (!mensaje || !modalContent) return;

        const iconos = {
            mail_sent: `
                <svg viewBox="0 0 20 20" class="modal__icon success">
                    <path fill="currentColor" 
                        d="M17 14.5v-4.1a5.5 5.5 0 0 0 1-.657V14.5a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 2 14.5v-8A2.5 2.5 0 0 1 4.5 4h4.707a5.5 5.5 0 0 0-.185 1H4.5A1.5 1.5 0 0 0 3 6.5v.302l7 4.118l1.441-.848q.488.327 1.043.547l-2.23 1.312a.5.5 0 0 1-.426.038l-.082-.038L3 7.963V14.5A1.5 1.5 0 0 0 4.5 16h11a1.5 1.5 0 0 0 1.5-1.5M14.5 10a4.5 4.5 0 1 0 0-9a4.5 4.5 0 0 0 0 9m2.354-5.646l-3 3a.5.5 0 0 1-.708 0l-1-1a.5.5 0 0 1 .708-.708l.646.647l2.646-2.647a.5.5 0 0 1 .708.708"/>
                </svg>
            `,
            mail_failed: `
                <svg viewBox="0 0 20 20" class="modal__icon error">
                    <path fill="currentColor" 
                        d="M19 5.5a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0m-2.646-1.146a.5.5 0 0 0-.708-.708L14.5 4.793l-1.146-1.147a.5.5 0 0 0-.708.708L13.793 5.5l-1.147 1.146a.5.5 0 0 0 .708.708L14.5 6.207l1.146 1.147a.5.5 0 0 0 .708-.708L15.207 5.5zM17 14.5v-4.1a5.5 5.5 0 0 0 1-.657V14.5a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 2 14.5v-8A2.5 2.5 0 0 1 4.5 4h4.707a5.5 5.5 0 0 0-.185 1H4.5A1.5 1.5 0 0 0 3 6.5v.302l7 4.118l1.441-.848q.488.327 1.043.547l-2.23 1.312a.5.5 0 0 1-.426.038l-.082-.038L3 7.963V14.5A1.5 1.5 0 0 0 4.5 16h11a1.5 1.5 0 0 0 1.5-1.5"/>
                </svg>
            `,
            spam: `
                <svg viewBox="0 0 20 20" class="modal__icon spam">
                    <path fill="currentColor"
                        d="M19 5.5a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0M14.5 3a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5H16a.5.5 0 0 0 0-1h-1V3.5a.5.5 0 0 0-.5-.5M17 14.5v-4.1a5.5 5.5 0 0 0 1-.657V14.5a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 2 14.5v-8A2.5 2.5 0 0 1 4.5 4h4.707a5.5 5.5 0 0 0-.185 1H4.5A1.5 1.5 0 0 0 3 6.5v.302l7 4.118l1.441-.848q.488.327 1.043.547l-2.23 1.312a.5.5 0 0 1-.426.038l-.082-.038L3 7.963V14.5A1.5 1.5 0 0 0 4.5 16h11a1.5 1.5 0 0 0 1.5-1.5"/>
                </svg>
            `,
            default: `
                <svg viewBox="0 0 20 20" class="modal__icon default">
                    <path fill="currentColor" 
                          d="M19 5.5a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0M14.5 3a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5H16a.5.5 0 0 0 0-1h-1V3.5a.5.5 0 0 0-.5-.5M17 14.5v-4.1a5.5 5.5 0 0 0 1-.657V14.5a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 2 14.5v-8A2.5 2.5 0 0 1 4.5 4h4.707a5.5 5.5 0 0 0-.185 1H4.5A1.5 1.5 0 0 0 3 6.5v.302l7 4.118l1.441-.848q.488.327 1.043.547l-2.23 1.312a.5.5 0 0 1-.426.038l-.082-.038L3 7.963V14.5A1.5 1.5 0 0 0 4.5 16h11a1.5 1.5 0 0 0 1.5-1.5"/>
                </svg>
            `
        };

        const icono = iconos[status] ?? iconos.default;

        modalContent.innerHTML = `
            <div class="modal__mensaje">
                ${icono}
                <div class="modal__mensaje-content">
                    <h3 class="modal__titulo">${mensaje.titulo ?? ''}</h3>
                    <p class="modal__descripcion">${mensaje.descripcion ?? ''}</p>
                </div>
            </div>
        `;
    });

});

/* Cambia de posicion el captcha */
document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('.wpcf7-form');
    const turnstile = form?.querySelector('.wpcf7-turnstile');
    const submitWrapper = form?.querySelector('.wpcf7-submit')?.closest('p');

    if (turnstile && submitWrapper) {
        form.insertBefore(turnstile, submitWrapper);
    }

});

/* Validaciones formularios */
document.addEventListener('input', function (e) {
    if (e.target.name === 'your-phone') {
        e.target.value = e.target.value.replace(/\D/g, '');
    }
});

/* REDIRECCION PRIMER ERROR */
document.addEventListener('wpcf7invalid', function (event) {

    setTimeout(() => {

        const form = document.getElementById(event.detail.unitTag);
        if (!form) return;

        const primerError = form.querySelector('.wpcf7-not-valid');

        if (primerError) {

            primerError.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            primerError.focus();
        }
    }, 50);
});