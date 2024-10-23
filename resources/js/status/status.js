
import { Notyf } from "notyf";

window.Echo.channel('notifications')

    .listen('.payment_received', (event) => {

        const statusContainer = document.getElementById('status-container');
        statusContainer.innerHTML = '';

        statusContainer.innerHTML = `
            <div class="icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Compra Realizada!</h1>
            <p>Sua compra foi realizada com sucesso!</p>
            <button class="back-button" onclick="window.location.href='/checkout'">Obrigado, voltar ao inicio</button>
        `;

        const backButton = document.querySelector('.back-button');
        if (backButton) backButton.style.display = 'inline-block';
    }).listen('.payment_not_received', (event) => {

        const statusContainer = document.getElementById('status-container');
        statusContainer.innerHTML = '';

        statusContainer.innerHTML = `
            <div class="icon failure">
                <i class="fas fa-times-circle"></i>
            </div>
            <h1>Compra Falhou!</h1>
            <p>Houve um problema ao processar sua compra.</p>
            <button class="back-button" onclick="window.location.href='/checkout'">Voltar ao início</button>
        `;

        const backButton = document.querySelector('.back-button');
        if (backButton) backButton.style.display = 'inline-block';

        displayWarning(event);
    });


function displayWarning(event) {

    let message = event?.message;

    let data = event?.data;
    let duration = 5000;

    if (data?.bankSlipUrl) {
        duration = 7000;
        message += `No entanto, voce pode acessar o boleto de teste gerado aqui: 
        <a href="${data?.bankSlipUrl}" target="_blank" rel="noopener noreferrer">Acessar PDF</a>`;
    }

    const notyf = new Notyf({
        duration: duration,
        position: {
            x: 'right',
            y: 'top',
        },
        types: [
            {
                type: 'warning',
                background: 'orange',
                icon: {
                    className: 'material-icons',
                    tagName: 'i',
                    text: 'warning'
                }
            },
            {
                type: 'error',
                background: 'indianred',
                duration: 2000,
                dismissible: true
            }
        ]
    });

    notyf.open({
        type: 'warning',
        message: message
    });
}