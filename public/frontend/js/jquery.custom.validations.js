//jQuery.metadata.setType("attr", "validate");

jQuery().ready(function() {
    
// Custom validation function for Venue text with placeholder
jQuery.validator.addMethod("validateMemberWithPlaceholderText", function(value, element) {
if(value == "Enter member name (or) company" && $('input[name="member"]:checked').val() == '1'){
return false
}else if(value == "") {
return false
}else {
return true
}
}, "required");

// Custom validation function for Venue text with placeholder
jQuery.validator.addMethod("validateVenueWithPlaceholderText", function(value, element) {
if(value == "Enter venue name" && $('input[name="venue"]:checked').val() == '1'){
return false
}else if(value == "") {
return false
}else {
return true
}
}, "required");		
	
// Custome validation function for Contact text with placeholder
jQuery.validator.addMethod("validateContactWithPlaceholderText", function(value, element) {
if(value == "Enter contact name" && $('input[name="contact"]:checked').val() == '1'){
return false
}else if(value == "") {
return false
}else {
return true
}
}, "required");

// Custom validation function for Group of Checkboxes
jQuery.validator.addMethod("validateGroupOfCheckboxes", function(value, element) {
 var selected_fields = $('input[name="'+element.id+'"]:checked');
 var elementid = element.id;
 var checkbox_group_container_id = elementid.replace('[]','') + '_container';
 if(selected_fields.length==0){
 $('#'+checkbox_group_container_id).addClass('error');
 return false
 }else{
 $('#'+checkbox_group_container_id).removeClass('error');
 return true     
 }
}, "required");		
						
jQuery.validator.addMethod("checkAlphanum", function(value, element) {
var thisRegExp = /^[a-zA-Z0-9]+$/;
if(!thisRegExp.test(value)) {
return false;
} else if (value.match(/^s+$/)) {
return true;
} else {
return true;
}
}, "This field must contain only letters or numbers.");
						
jQuery.validator.addMethod("checkClient", function(value, element) {
if(value == "member name (or) company")
{
return false
}  else if(value != "") {
if(jQuery('#smemberid').val() == "")
return false
else
return true
}
else {
return true
}
}, "name is not valid.");

jQuery.validator.addMethod("checkSubcat", function(value, element) {
var totalCategory = jQuery('#totalCategory').val();												   
var str ='';
var elements_length = totalCategory;
var obj = element
var field;
for(i=0;i<elements_length;i++){
element_name = "subcatid"+i;
field = document.getElementById(element_name);
if(field.value == obj.value && element_name!=obj.id){
str = str+field.options[field.selectedIndex].text+',';
}
}
if(str!=''){
//alert(str.substring(0,str.length-1)+" already selected");
return false;
}else{
return true;
} 
}, "please select another business sub-category.");



$("#addpagecategory").validate({
rules: {
imagefile: {
required :true,
accept: "png|jpg|gif"
},
flashfile: {
accept: "swf"
}
},
messages: {
imagefile: {
required :"image file is empty.",
accept:"only jpg/png/gif filetypes allowed"
},
flashfile: {
accept:"only swf filetype allowed"
}
}
});


$("#editpagecategory").validate({
rules: {
imagefile: {
required :true,
accept: "png|jpg|gif"
},
flashfile: {
accept: "swf"
}
},
messages: {
imagefile: {
required :"image file is empty.",
accept:"only jpg/png/gif filetypes allowed"
},
flashfile: {
accept:"only swf filetype allowed"
}
}
});
});



// start of login validation

function jqueryLogin() {
jQuery("#frmlogin").validate({
submitHandler: function(form) {
jQuery.ajax({
type: "POST",
url:baseurl+'/auth/login',
data: "opt=checkmanage&login="+jQuery("#login").val()+"&pass="+jQuery("#pass").val(),
success: function(msg){
if(msg == "no") {
document.getElementById('userinvalid').style.display = 'block';
}
else {
document.location.href = baseurl;
}
tb_init('a.thickbox, area.thickbox, input.thickbox');
}
});
}
});
}


function addadminvalidate(){
var container = $('div.validationmssg');
jQuery("#addadministrator").validate({
errorContainer: container,
meta: "validate",
rules:{
username:{
required: true,
remote: {
url: baseurl+"/administrators/checkusername/type/"+jQuery("#type").val()+"/adminid/"+jQuery("#administratorid").val(),
type: "post"
}
},
password: {
required: true,
checkAlphanum: true,
rangelength: [8, 12]
},
password1: {
required: true,
checkAlphanum: true,
rangelength: [8, 12],
equalTo: "#password"
},
firstname: {
required: true
},
lastname: {
required: true
},
email: {
required: true,
email:true,
remote: {
url: baseurl+"/administrators/checkemail/type/"+jQuery("#type").val()+"/adminid/"+jQuery("#administratorid").val(),
type: "post"
}
}
},
messages: {
username:{
required: "login is empty",
remote:"chosen login already exists"
},
password: {
required: "password is empty",
checkAlphanum: "field must contain only letters or numbers",
rangelength: jQuery.format("password must be {0} to {1} characters")
},
password1: {
required: "confirm password is empty",
checkAlphanum: "field must contain only letters or numbers",
minlength: jQuery.format("password must be {0} to {1} characters"),
equalTo: "please enter the same password twice"
},
firstname: {
required: "first name is empty"
},
lastname: {
required: "last name is empty"
},
email: {
required: "email is empty or invalid",
email:"email is empty or invalid",
remote:"chosen email already exists"
}
}
}); 
}
// end of add member validation
// end of login validation
