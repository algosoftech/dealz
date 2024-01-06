<button id="rzp-button1">Pay <?=$order['amount']?></button>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
	var options = {
		"key": "<?=$key_id?>",    
		"amount": <?=(int)$order['amount']?>,    
		"currency": "<?=$order['currency']?>",    
		"name": "DealzArabia",    
		"description": "Test Transaction",    
		"image": "<?=base_url('assets/fav-icon.png')?>",    
		"order_id": "<?=$order['id']?>", 
		"callback_url": "<?=base_url('order/paymentStatus')?>",    
		"prefill": {        
			"name": "<?=$name?>",        
			"email": "<?=$email?>",        
			"contact": "<?=$mobile?>"    
		},    
		"notes": {        
			"address": "Box Park, Al Wasl Rd, P.O. Box 122555 Dubai, United Arab Emirates"
		},    
		"theme": {        
			"color": "#3399cc"    
		}
	};
var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){    
rzp1.open();    
e.preventDefault();
}
document.getElementById('rzp-button1').onclick();
</script>

