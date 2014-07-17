function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function pay(){
   

    var username=window.localStorage.getItem("userId")+":mt";  
    var accountCode=window.localStorage.getItem("accountCode");  
    var subdomain=window.localStorage.getItem("subdomain"); 
   
    var first_name=$("#first_name").val();
    var last_name=$("#last_name").val();
    var email=$("#email").val();
    var card_number=$("#card_number").val();
    var address1=$("#address1").val();   
    var address2=$("#address2").val(); 
    var city=$("#city").val();   
    var state_zip=$("#state_zip").val();   
    var zip=$("#zip").val();   
    var vat_number=$("#vat_number").val();   
    var cvv= $("#cvv").val(); 
    var country=$(".country").find("select").val();
    var month=$(".month").find("select").val();
    var year=$(".year").find("select").val();
    var company=$("#company").val();
    var phone_number=$("#phone_number").val();
    var amount=$("#amount").val();
    
    
    if(  !($.isNumeric( amount ))){ alert("You have to enter amount correctly"); return; }
    if( !validateEmail( email ) ){ alert("You have to enter the email correctly"); return; }
    
    var url="https://"+subdomain+".recurly.com/jsonp/"+subdomain+"/transactions/create";


    $.ajax({
        url: "ajax-pay.php",
        dataType : "json",
        type : "POST",
        data : { amount : amount , 
                 accountCode : accountCode,
                 first_name:first_name,
                 last_name:last_name,
                 email:email,
                 username:username
                 
                 },
        success : function(data){
            if(data.result == "success"){
                    var signature=data.signature; 
                    $.ajax({
                          dataType: 'jsonp',
                          url: url,
                          data: {
                            signature: signature,
                            billing_info: {
                                  first_name: first_name,
                                  last_name: last_name,
                                  address1:address1,
                                  state: state_zip,
                                  username:username,
                                  city: city,
                                  zip: zip,
                                  country: country,
                                  number: card_number,
                                  year: year,
                                  month: month,
                                  phone:phone_number ,
                                  verification_value: cvv,
                            },

                          },
                          success: function (data) {
                            if(!(data.errors)==""){
                                var error="";
                                for(i=0;i<data.errors.base.length;i++){
                                   error=error+data.errors.base[i];
                                   alert(error);
                               } 
                            }else{
                                
                                    
                                  alert("The payment is successful");
                                  parent.onAfterPayment(amount); 
                        
                                } 
                          },
                        });
                return;
            }
        }
    });
   
 
 }