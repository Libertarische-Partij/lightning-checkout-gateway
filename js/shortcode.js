jQuery(document).ready(($) => {
    const $invoiceText = $('#invoice_text');

    const checkPayment = () => {
        $.ajax({
            url: paymentData.check_payment_url,
            data: { 'invoice_id': paymentData.order_id },
            success: (response) => {
                if (response['paid'] && injected_data['returnurl']) {
                    window.location.replace(injected_data['returnurl']);
                } else {
                    setTimeout(checkPayment, 5000);
                }
            },
            error: () => {
                setTimeout(checkPayment, 5000);
            },
        });
    }

    checkPayment();

    $('#qr_invoice').click(() => {
        $invoiceText.select();
        document.execCommand('copy');
    });
});
