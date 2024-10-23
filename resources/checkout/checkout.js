import { Notyf } from "notyf";

export const clickForm = () => {
    const form = document.getElementById('formCheckout');

    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        try {

            const formData = new FormData(form);

            const response = await fetch('checkout', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const result = await response.json();

            if (result.status === 'success') {
                window.location.href = '/checkout/status';
            } else {
                if (result?.error?.code === 'purchase_fields_failed') {
                    const notyf = new Notyf({
                        duration: 3000,
                        position: {
                            x: 'right',
                            y: 'top',
                        },
                        types: [
                            {
                                type: 'warning',
                                background: 'orange',
                                icon: {
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
                        message: "O formulário não foi devidamente preenchido"
                    });
                }

            }
        } catch (error) {
            console.error(error);
        }
    });
};
