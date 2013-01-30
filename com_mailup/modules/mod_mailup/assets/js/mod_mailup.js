mod_mailup_helper = {
	
	isEmail: function(strEmail){
		validRegExp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	    if (strEmail.search(validRegExp) == -1) {
	      //alert('Inserire un indirizzo email valido');
	      return false;
	    }
	    return true;
	},
	
	checkEmail: function(strEmail, strName){
		
		var self = this;
		
		if(!self.ctrlPrivacy()){
			return false;
		}
		
		if(!strEmail || !strName){
			alert(self.lang['MOD_MAILUP MISSING FIELDS ALERT']);
			return false;
		} else {
			if (mod_mailup_helper.isEmail(strEmail)) {
				return true;
			}
			alert(self.lang['MOD_MAILUP INVALID EMAIL ALERT']);
			return false;
		}
	},
								
	ctrlPrivacy: function(){
		var bConsenso;
		var self = this;
		bConsenso = document.adminFormMailup.chkPrivacy.checked;
		if (bConsenso){
			return true;
		} else {
			alert(self.lang['MOD_MAILUP CONTROL PRIVACY ALERT']);
			return false;
		}
	},
	
	setToNo: function(classe){
		$$("."+classe+'[value="0"]').each(function(e,i){
			e.setProperty("checked",true);
		});
		$$("."+classe+'[value="1"]').each(function(e,i){
			e.setProperty("checked",false);
		});
	},
	
	setToYes: function (classe){
		$$("."+classe+'[value="0"]').each(function(e,i){
			e.setProperty("checked",false);
		});
		$$("."+classe+'[value="1"]').each(function(e,i){
			e.setProperty("checked",true);
		});
	}
	
	
}

