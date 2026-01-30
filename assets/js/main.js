/* Formularios - Mensajes Emergentes */
document.addEventListener('DOMContentLoaded', () => {

    let cf7Estado = null;

    window.addEventListener('wpcf7invalid', () => cf7Estado = 'invalid');
    window.addEventListener('wpcf7spam', () => cf7Estado = 'spam');
    window.addEventListener('wpcf7mailfailed', () => cf7Estado = 'mailfailed');
    window.addEventListener('wpcf7mailsent', () => cf7Estado = 'success');

    const output = document.querySelector('.wpcf7-response-output');
    if (!output) return;

    const observer = new MutationObserver(() => {
        const texto = output.innerText.trim();
        if (!texto) return;

        /*  if (cf7Estado === 'invalid') {
             output.textContent = '';
             output.style.display = 'none';
             return;
         } */

        if (output.querySelector('.cf7-msg')) return;

        output.textContent = '';
        output.classList.remove('is-error', 'is-success', 'is-warning');

        let iconSvg = '';

        switch (cf7Estado) {
            case 'invalid':
                /* case 'mailfailed': */
                output.classList.add('is-error');
                iconSvg = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="red" fill-rule="evenodd" d="M256 42.667c117.803 0 213.334 95.53 213.334 213.333S373.803 469.334 256 469.334S42.667 373.803 42.667 256S138.197 42.667 256 42.667m0 42.667c-94.1 0-170.666 76.565-170.666 170.666c0 94.102 76.565 170.667 170.666 170.667c94.102 0 170.667-76.565 170.667-170.667c0-94.101-76.565-170.666-170.667-170.666m48.918 91.584l30.165 30.165L286.166 256l48.917 48.918l-30.165 30.165L256 286.166l-48.917 48.917l-30.165-30.165L225.835 256l-48.917-48.917l30.165-30.165L256 225.835z"/>
                    </svg>`;
                break;

            case 'spam':
                output.classList.add('is-warning');
                iconSvg = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="yellow" fill-rule="evenodd" d="M278.313 48.296a42.67 42.67 0 0 1 15.876 15.876l182.478 319.336c11.691 20.46 4.583 46.523-15.876 58.214a42.67 42.67 0 0 1-21.169 5.622H74.667C51.103 447.344 32 428.24 32 404.677a42.67 42.67 0 0 1 5.622-21.169L220.099 64.172c11.691-20.459 37.755-27.567 58.214-15.876M257.144 85.34L74.667 404.677h364.955zM256 314.667c15.238 0 26.667 11.264 26.667 26.624S271.238 367.915 256 367.915c-15.584 0-26.667-11.264-26.667-26.965c0-15.019 11.429-26.283 26.667-26.283m21.333-165.333v128h-42.666v-128z"/>
                    </svg>`;
                break;

            case 'success':
                output.classList.add('is-success');
                iconSvg = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="green" fill-rule="evenodd" d="M256 42.667C138.18 42.667 42.667 138.18 42.667 256S138.18 469.334 256 469.334S469.334 373.82 469.334 256S373.821 42.667 256 42.667m0 384c-94.105 0-170.666-76.561-170.666-170.667S161.894 85.334 256 85.334S426.667 161.894 426.667 256S350.106 426.667 256 426.667m80.336-246.886l30.167 30.167l-131.836 132.388l-79.083-79.083l30.166-30.167l48.917 48.917z"/>
                    </svg>`;
                break;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'cf7-msg';

        const icon = document.createElement('span');
        icon.className = 'special-icon';
        icon.innerHTML = iconSvg;

        const p = document.createElement('p');
        p.textContent = texto;

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'special-icon';
        close.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="var(--primario-azul)" d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12L5.293 6.707a1 1 0 0 1 0-1.414"/>
                </svg>`;
        close.onclick = () => {
            output.style.display = 'none';
            document.body.classList.remove('no-scroll');
        };

        wrapper.append(icon, p, close);
        output.appendChild(wrapper);
        output.style.display = 'flex';
        document.body.classList.add('no-scroll');
    });

    observer.observe(output, {
        childList: true,
        characterData: true,
        subtree: true
    });
});