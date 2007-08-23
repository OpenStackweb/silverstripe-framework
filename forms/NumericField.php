<?php
/**
 * A Single Numeric field extending a typical 
 * TextField but with validation.
 */
class NumericField extends TextField{
	
	function jsValidation() {
		$formID = $this->form->FormName();
		
		$jsFunc =<<<JS
Behaviour.register({
	"#$formID": {
		validateNumericField: function(fieldName) {	
				el = _CURRENT_FORM.elements[fieldName];
				if(!el || !el.value) return true;
				
			 	if(el.value.match(/^([0-9]+(\.[0-9]+)?$)/)) { 
			 		return true;
			 	} else {
					validationError(el, "'" + el.value + "' is not a number, only numbers can be accepted for this field","validation");
			 		return false;
			 	}
			}
	}
});
JS;

		Requirements::customScript($jsFunc, 'func_validateNumericField');

		//return "\$('$formID').validateNumericField('$this->name');";
		return <<<JS
if(typeof fromAnOnBlur != 'undefined'){
	if(fromAnOnBlur.name == '$this->name')
		$('$formID').validateNumericField('$this->name');
}else{
	$('$formID').validateNumericField('$this->name');
}
JS;
	}
	
	/** PHP Validation **/
	function validate($validator){
		if($this->value && !is_numeric($this->value)){
 			$validator->validationError($this->name,"'$this->value' is not a number, only numbers can be accepted for this field","validation");
			return false;
		} else{
			return true;
		}
	}
	
	function dataValue() {
		return (is_numeric($this->value)) ? $this->value : 0;
	}
}
?>
