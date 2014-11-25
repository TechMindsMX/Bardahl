function updateCaptcha(){
	var captchas = document.getElementsByName('captcha'); 
	var imgURL	=icaptchaURI+'plugins/system/icaptcha/captcha_systems/securImage2/show.php?'+ Math.random();
	var captchasFields = $$('input.sicaptcha');
	for (var i=0; i<captchas.length; i++){
		captchas[i].src	= imgURL;
		captchasFields[i].set('value','');
	}
}
function validateSICaptcha(captcha){
	var urlScript	= icaptchaURI+'plugins/system/icaptcha/captcha_systems/securImage2/verify_json.php';
	var jSonRequest = new Request.JSON({url:urlScript, onSuccess: function(response){
			icaptchaValidator	= document.id((captcha.get('id')+'-validation'));
			if(response.action == 'success'){
	    		captcha.removeClass('invalid');
	    		captcha.addClass('success');
	    		captcha.set('aria-invalid', 'false');
	    		icaptchaValidator.set('value',1);
	    		parentForm	= captcha.getParent('form');
	    		if(parentForm){
	    			var parentFormValidator = new Form.Validator.Inline(parentForm);
	    			parentFormValidator.validateField(captcha);  
	    		}
	    	}else{
	    		icaptchaValid = false;
	    		captcha.removeClass('success');
	    		captcha.addClass('invalid');
	    		captcha.set('aria-invalid', 'true');
	    		icaptchaValidator.set('value',0); 		
	    	}
	    }
		}).get(({'captcha_code':captcha.get('value')})); 
}

window.addEvent('domready', function(){
	var icaptchaUseAjax = document.id(('icaptchaUseAjax'));
	if(icaptchaUseAjax.get('value') == 1){	
		Array.each($$('input.sicaptcha'), function(captcha, index){
			captcha.addEvent('blur',function(){
				validateSICaptcha(captcha);
			});
			captcha.addEvent('keydown',function(event){
				if(event.key == 'enter'){validateSICaptcha(captcha);}
			});
		});
	}
	(function(){updateCaptcha()}).delay(500);
});