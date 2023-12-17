document.addEventListener("DOMContentLoaded", function (event) {

  // Functions

  /**
   * Copy the invoice_text to the clipboard.
   */
  const copyToClipboard = (value) => {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(value).catch((err) => {
        console.warn("Copy to clipboard failed:", err);
        alert("Copy to clipboard failed. Please try again.");
      });
    } else {
      // Fallback for browsers that don't support navigator.clipboard.
      let textarea = document.createElement("textarea");
      textarea.textContent = value;
      document.body.appendChild(textarea);
      textarea.select();
      try {
        document.execCommand('copy');
      } catch (ex) {
        console.warn("Copy to clipboard failed:", ex);
        alert("Copy to clipboard failed. Please try again.");
      } finally {
        document.body.removeChild(textarea);
      }
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
    copyToClipboard(document.getElementById('invoice_text').innerText);
  })
});
