<div class="wp-lnp-wrapper">
	<p class="wp-lnp-description">Betaal €<?php echo $amount ?>.</p>
	<a class="payment-link" href="lightning:<?php echo $payment_request ?>">
		<img alt="QR Code" src="<?php echo esc_url("https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".$payment_request."&choe=UTF-8") ?>"/>
	</a>
	<div class="wp-lnp-qrcode">
		<a class="payment-link" href="lightning:<?php echo $payment_request ?>">
			<?php echo substr($payment_request, 0, 10) ?>...
		</a>
		<button onclick="copyToClipboard('<?php echo $payment_request ?>');" class="wp-lnp-copy">
			<svg xmlns="http://www.w3.org/2000/svg" class="LNP_copy" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path class="LNP_path" stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"></path></svg>
		</button>
	</div>
	<div class="logo-section">
		<small>Bitcoin Payment Processor</small><br>
		<a href="https://lightningcheckout.eu">
			<img alt="Logo Lightning Checkout" src="/wp-content/plugins/lnc-gateway/assets/img/logo-lightningcheckout.png" height="25">
		</a>
    </div>
</div>

<style>
.wp-lnp-wrapper {
	text-align:center;
}

.wp-lnp-description {
	margin-bottom: 1.5em;
}

.wp-lnp-qrcode {
	text-align: center;
}

.wp-lnp-copy svg {
	cursor: pointer;
	width: 20px;
	height: 20px;
}

.logo-section {
	text-align: center; 
	padding-top: 10px
}
</style>
