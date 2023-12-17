document.addEventListener("DOMContentLoaded", function (event) {

  // Functions

  /**
   * Copy the invoice_text to the clipboard.
   */
  const copyToClipboard = () => {
    let textToCopy = document.getElementById('invoice_text').innerText;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(textToCopy).then(() => {
        console.log('Copied to clipboard')
      })
    } else {
      console.log('Browser Not compatible')
    }
  }

  /**
   * Perform get request to the payment provider and see if
   * the status is paid. If not, wait and try again
   */
  const checkPayment = () => {
    fetch(paymentData.check_payment_url, {
      method: "GET",
      data: { 'invoice_id': paymentData.order_id },
      dataType: 'json',
      ContentType: 'application/json'
    }).then((response) => {
      return response.json();
    }).then((responseData) => {
      if (responseData['paid'] && injected_data['returnurl']) {
        window.location.replace(injected_data['returnurl']);
      } else {
        setTimeout(checkPayment, 5000);
      }
    }).catch((err) => {
      setTimeout(checkPayment, 5000);
    })
  }

  // Perform first check

  checkPayment();

  // Event listeners

  document.getElementById('qr_invoice').addEventListener("click", () => {
    copyToClipboard();
  })
});
