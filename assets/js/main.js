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

    document.addEventListener('wpcf7beforesubmit', function () {

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

        let mensaje = "";

        switch (event.detail.status) {

            case 'mail_sent':
                mensaje = "✅ Mensaje enviado correctamente.";
                break;

            case 'validation_failed':
                mensaje = "⚠️ Completa los campos obligatorios.";
                break;

            case 'mail_failed':
                mensaje = "❌ Error enviando mensaje.";
                break;

            case 'spam':
                mensaje = "🚫 Actividad sospechosa detectada.";
                break;

            default:
                mensaje = "⚠️ Ocurrió un problema.";
        }

        if (modalContent) modalContent.innerHTML = mensaje;

    });

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