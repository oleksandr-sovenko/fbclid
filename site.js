<script>
	function isMobileDevice() {
		return window.matchMedia('(max-width: 768px)').matches; // Adjust the breakpoint as needed
	}
  
  	const mobile = isMobileDevice();
	const url = new URL(window.location.href);
	const fbclid = url.searchParams.get('fbclid');
	const qOne = $('.qOne');
	const qTwo = $('.qTwo');
  	const claimForm = $('.claimForm');
  	let valid = false;
  	let fail = true;

   	let disableCloakingForAll = false; // this can be true/false
    let simulateFail = false; // this can be true/false
  
	$.get(`https://api.stimiinc.com/is-valid-fbclid/?fbclid=${fbclid}`, (response) => {
	    if (!simulateFail)
			fail = false;
      
		if (response.result)
			valid = true;
	}).always(() => {
		if (!valid) {
			claimForm.removeClass('hidden');
		} else {
          
	   	}
      
      	if (!mobile) {
			claimForm.removeClass('hidden');
        }
      
		if (fail) {
	      	if (mobile) {
				claimForm.addClass('hidden');
        	} else {
				claimForm.removeClass('hidden');
            }
        }
      
      	if (disableCloakingForAll) {
			claimForm.addClass('hidden');
        }
      
       	console.log(`valid = ${valid}`);
      	console.log(`fail = ${fail}`);
      	console.log(`disableCloakingForAll = ${disableCloakingForAll}`);
      	console.log(`simulateFail = ${simulateFail}`);
	});
  
  	/*
	let valid = false;
  
	if (mobile) {
		$.get(`https://api.stimiinc.com/is-valid-fbclid/?fbclid=${fbclid}`, (response) => {
			if (response.result)
				valid = true;
	    }).always(() => {
			if (!valid) {
				claimForm.removeClass('hidden');
		    } else {
              
	    	}
		});
	} else {
		claimForm.removeClass('hidden');
    }
    */
</script>
