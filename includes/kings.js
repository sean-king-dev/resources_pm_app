$(document).ready(function() {
	
	
setTimeout(function(){
          $('#logerror').css('color','white');
    }, 2000);	


$("#sorter").tablesorter({dateFormat: "uk"});

	// validate signup form on keyup and submit
	$("#profile").validate({
		rules: {
			frm_password: {
				required: true,
				minlength: 5
			},
			frm_confirm_password: {
				required: true,
				minlength: 5,
				equalTo: "#frm_password"
			},

		},
		messages: {

			frm_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			frm_confirm_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Your passwords do not match"
			},
		}
	});

  
$('#frm_product').bind('change', function() {
    var product = $("#frm_product").val();
       $.post(  "/select-chain.php?frm_type=product_select&product="+product,
                function(data){
                    if (data != null){
                         $('#frm_quantity').empty().append(data.options);
                    }else{
                    alert('Not sure what to do with this response')
                    }
                },
                "json"
        );
});

$('#searchdrop').bind('click', function() {
    $('#search').slideToggle();
});

//Auto Search form checkbox
$("#search input").bind("change",function(event){
   doSearch();
 });
$("#search select").bind("change",function(event){
   doSearch();
 });
    
$('#users input').bind('click', function() {
  updateProject('users', $(this).val());
	if($('.saved').css('display')!='block'){
		$('#users').prepend('<div class="saved">SAVED</div>');
		setTimeout(function(){
			  $('.saved').fadeOut();
		}, 1000);
	}
});

$('#keywordstable input').bind('click', function() {
  updateProject('keywords', $(this).val());
  if($('tr td.saved').css('display')!='block'){
    $('#keywordstable').prepend('<tr><td colspan="6" class="saved">SAVED</td></tr>');
    setTimeout(function(){
          $('.saved').fadeOut();
    }, 1000);
  }
});

$('#alerts input.in').live('change', function() {
          if ($(this).val()==5 && $('.customdate').val()=='') {
                   $(this).prop('checked',false)
                   alert('please select a custom date');
                   return false;
          }
          if (!$(this).parent().hasClass('task')) {
                    //code
                              updateProject('alerts', $(this).val());
                             if($('.saved').css('display')!='block'){
                              $('#alerts').prepend('<div class="saved">SAVED</div>');
                              setTimeout(function(){
                                 $('.saved').fadeOut();
                              }, 1000);
                             }
          }
});


$('input#calPrint').blur('change', function() {
  updateProject('duedate', $(this).val());
 if($('.saved').css('display')!='block'){
    $('#calendar').prepend('<div class="saved">SAVED</div>');
    setTimeout(function(){
          $('.saved').fadeOut();
    }, 1000);
 }
});

$('#frm_client').bind('change', function() {
  updateProject('client', $(this).val());
if($('.saved').css('display')!='block'){
    $('#client').prepend('<div class="saved">SAVED</div>');
    setTimeout(function(){
          $('.saved').fadeOut();
    }, 1000);
}
});


// initialize custom alert date
$(".customdate").dateinput( {
	format:'dd/mm/yy',
	// when date changes update the day display
// set initial value and show dateinput when page loads

}).data("dateinput");

$(".customdate").bind('change', function() {
          if ($('[name="frm_alerts[]"]:last').prop('checked')) {
                    updateProject('alerts', 6);
          }
});


// initialize dateinput
$("#calendar .date").dateinput( {
	format:'dd/mm/yy',
	// when date changes update the day display
	change: function(e, date)  {
		$("#theday").html(this.getValue("dd<span>mmmm yyyy</span>"));
	}
// set initial value and show dateinput when page loads
}).data("dateinput");
});



function hideNav(){
  $("#navigation ul li").css('display','none');
}

function submitProject(){// also when editing or adding a update
	var formID=$('form.content').attr('id');
	if ($("#"+formID).validate().form()){
	  $('#loading').fadeTo("slow", 1);
	  $('form').fadeTo("slow", 0.1);
		$.post(  "/select-chain.php?"+$('form').serialize(),
		function(data){
			if (data != null){
			//widow.location("/projects");
			 $('form').delay(500).fadeTo("slow", 1);
			 $('#loading').delay(500).fadeTo("slow", 0);
			 setTimeout(function(){
			  $('form').prepend('<div class="saved">SAVED</div>');
		   }, 1000);
			 
			 setTimeout(function(){
			  $('.saved').remove();
		   }, 3000);
			 
			 $("#project-request").html('Thank you, your project has been submitted')
			 
			}else{
			alert('Not sure what to do with this response')
			}
			
			if (data.goback ==true){
				parent.history.back();
				return false;
			 }
		},
			 "json"
				
		);
		$('.saved').fadeTo("slow", 0);
		$('.saved').remove();
	}
}
///// 
$(function(){
    // text area
  $('.editable-area').inlineEdit({
        buttonText: 'Add',
        control: 'textarea',
        save: function(e, data) {
            updateProject(this.id, $('#frm_'+this.id).val())
        }
    });
  $('.edit').inlineEdit({
        buttonText: 'Add',
        save: function(e, data) {
            
            updateProject(this.id, $('#frm_'+this.id).val())
        }
    });
});

function updateProject(changed, val){
    itemchanged=  'frm_'+changed;
        var project_id = $('#project_id').val();
        $.post(  "/select-chain.php?frm_type=project&id="+project_id+"&changed="+itemchanged+"&val="+val+"&custom_alert_date="+$('.customdate').val(),
                function(data){
                    if (data != null){
                    }else{
                    alert('Not sure what to do with this response')
                    }
                },
                "json"
        );
        return false;
}
$('.close').live('click',function(){
           $('#inSystemAlerts').fadeOut();
           return false;
})


//to allow adim to switch users
$('.user-login').live('change',function(){
        var selectedUser =$('.user-login option:selected')
           $.ajax({
                    type: "POST",
                    url: '/',
                    data: {log:selectedUser.attr('email'),pwd:selectedUser.attr('password'),'wp-submit':'Log In'},
                    dataType: 'json'
          });
           var url = "/";    
          $(location).attr('href',url);
})

function seenit(id){
           $thing = $('#'+id);
           $.ajax({
                    type: "POST",
                    url: '/select-chain.php',
                    data: {frm_type:$thing.attr('name'), val:$thing.val()},
              
          dataType: 'json'
          });
           
            $thing.parent().fadeOut();
           
           return false;
}

//call every xxx seconds
/*
window.setInterval(function(){
          pollInSystemAlerts('normal')
}, 2000)
*/


function pollInSystemAlerts($type){
        $.ajax({
                    type: "POST",
                    url: '/insystemAlerts.php',
                    data: {type:$type},
                    success: function (alerts) {
                              if (alerts) {
                              //code
                              $('#inSystemAlerts .content').html(alerts);
                              $('#inSystemAlerts').fadeIn();
                              }else{
                                         $('#inSystemAlerts').fadeOut();
                              }
                              
                    },
          dataType: 'json'
          });
        
   
}

function back(){
      parent.history.back();
      return false;
    }
	
function reset(){
     ('form')[0].reset();
      return false;
    }	
	
function doPage(id){
          $('.pages').removeClass('active');
           $('#pages'+id).addClass('active');
           doSearch();
}
 
function doFilter(value,id){
 $('div#fullCol span').css('background-color','#CCCCCC');
 $('#'+id).css('background-color','#8DBDD8');
 $('.filter').removeClass('active');
 $('#'+id).addClass('active');
 $('#show').val(value);
 doSearch();
}
    
 //Auto Search form text field
function doSearchKey(){		
	var timer;
	clearTimeout(timer);
	var ms = 200;
    if($('#searchString').val().length > 1 || $('#searchString').val().length == 0){
            timer = setTimeout(function() {doSearch(),200});
			 $("#sorter").tablesorter({dateFormat: "uk"}); 
    }else{
        $("#results").html('<p>Please type at least 2 characters</p>');
    }
    return false;    
 }
 
function doSearch(){
        var search = $('form').serialize();
        var type= $('#type').val();
        $.post(  "/search.php?search="+type+"&"+search+'&page='+$('.pages.active').attr('page'),
                function(data){
                    $('#results').html(data.projects);
                    if($('#searchString').val().length > 2){
                     $('#results').highlight($('#searchString').val(), "highlight");
                    }
					
					 $("#sorter").tablesorter({dateFormat: "uk"}); 
                },
                   "json"
    );
        return false;    
    
}


$.fn.highlight = function (str, className) {
    var regex = new RegExp(str, "gi");
    return this.each(function () {
        this.innerHTML = this.innerHTML.replace(regex, function(matched) {
          if(!$.isNumeric(matched)){
            return "<span class=\"" + className + "\">" + matched + "</span>";
          }else{
                    return matched;
          }
        });
    });
};
    
function doComment(id){
    $('#comment'+id).fadeToggle();
    $('#text'+id).focus();
    $("#comment"+id).bind("submit",function(event){
           $.post(  "/select-chain.php?"+$('form').serialize()+'&frm_type=comment&updateId='+id,
	function(data){
	    if (data != null){
		//widow.location("/projects");
         $('form').delay(500).fadeTo("slow", 1);
         $('#loading').delay(500).fadeTo("slow", 0);
         
         setTimeout(function(){
          $('form').prepend('<div class="saved">SAVED</div>');
       }, 1000);
         
         setTimeout(function(){
          $('.saved').remove();
          
          $('#commenttext'+id).append('<div class="comment"><strong>'+data.user+'</strong> ('+data.date+') <br>'+data.text+'</div>');
          $('#text'+id).val('');
          
       }, 3000);
         
         
	    }else{
		alert('Not sure what to do with this response')
	    }
	},
	     "json"
			
    );
        return false;
    });
    
    return false;
}

function doProjectDelete(itemid){
var r=confirm("Are you sure you wish to delete this Project?")
		if (r==true){
     $.post(  "/select-chain.php?frm_type=delete_project&itemid="+itemid,
                function(data){
                    if (data != null){
						$('#navigation').html('<a target="_self" href="/projects"><div class="button">+Back to all projects</div></a>');
                        $('#fullCol').html('The project has been deleted');
                    }else{
                    alert('Not sure what to do with this response');
                    }
                },
                "json"
        );
	 }else{
		  return false;
	}
}

function doComplete(itemid,comp,type){
           $('.'+itemid+'-0').removeClass('active')
           $('.'+itemid+'-1').removeClass('active')
           $('.'+itemid+'-2').removeClass('active')
           
           
          $('.'+itemid+'-'+comp).addClass('active')
     $.post(  "/select-chain.php?frm_type=complete&itemid="+itemid+"&type="+type+"&val="+comp,
                function(data){
                    if (data != null){
                        if (comp==1){
                               $('#status').html('Complete')
                        }else if(comp==2){
                               $('#status').html('Ongoing')
                        }else{
                               $('#status').html('live')
                        }
                        
                    }else{
                    alert('Not sure what to do with this response')
                    }
                },
                "json"
        );
     return false;
}

function reveal(id,clicked){
    if ($(id+'Print').css('display') == 'none'){
        $(id+'Print').css('display', 'block');
        $(id).fadeOut();
    }else{
        $(id).fadeIn();
        $(id+'Print').css('display', 'none');
    }
        
}


   function add(){
    var product = $('#frm_product option:selected').text();
    if(product!=='Please select'){
          var quantity = $('#frm_quantity option:selected').text();
         
          $('#prod').append('<tr><td class="nopad" width="400px"><strong>Product: </strong>'+product
                            +'<input type="hidden" id="prod" name="prod[]" value="'+$('#frm_product option:selected').val()+','+$('#frm_quantity option:selected').text()+'">'+'</td>'
                            +'<td class="nopad"><strong>Quantity: </strong>'+quantity
                            +'</td>'
                            +'<td class="nopad"><input type="button" value="+ Delete" class="button delete" style="float: none;"></td></tr>');
           $('#frm_product').val('0');
          $('.delete').click(function(){
              $(this).parent().parent().fadeOut();
          });
    }else{
          alert('You have not selected anything');
    }

}

  function submitrequest(){
         $.post(  "/select-chain.php?frm_type=staionary&"+$('form').serialize(),
                function(data){
                    if (data != null){
                     $('form').html('<h1>Thank you</h1> <p>Your request has been submitted</p><p>Stationery generally takes between 1 &#8722; 2 weeks to print and deliver. If you have any problems or queries with this order please email <a href="mailto:design@kingseducation.com">design@kingseducation.com</a></p>');
                    }else{
                    alert('Not sure what to do with this response')
                    }
                },
                "json"
        );
    
   }
   
   
    function deleteTask(id){
		
		var r=confirm("Are you sure you wish to delete this task?")
		if (r==true){
         $.post(  "/select-chain.php?frm_type=delete&"+'id='+id,
                function(data){
                    if (data != null){
                     $('form').html('<h1>Thank you</h1> <p>The task has been deleted</p>');
                    }else{
                    alert('Not sure what to do with this response')
                    }
                },
                "json"
        );
	}else{
		  return false;
	}
    
   }
   
    function deleteUser(id){
		var r=confirm("Are you sure you wish to delete this user?")
		if (r==true){
         $.post(  "/select-chain.php?frm_type=deleteUser&"+'id='+id,
                function(data){
                    if (data != null){
					$('#'+id).fadeOut();
                    }else{
                    alert('Not sure what to do with this response')
                    }
                },
                "json"
        );
	}else{
		  return false;
	}
    
   }
   
   
   
    function deleteRequest(id){
		var r=confirm("Are you sure you wish to delete this request?");
		if (r==true){
         $.post(  "/select-chain.php?frm_type=deleteRequest&"+'id='+id,
                function(data){
                    if (data != null){
                     $('#req'+id).fadeOut();
                    }else{
                    alert('Not sure what to do with this response')
                    }
                },
                "json"
        );
	}else{
		  return false;
	}
    
   }
   
   
     function submitProfile(){
        if ($("#profile").valid()){
            $('#loading').fadeTo("slow", 1);
            $('form').fadeTo("slow", 0.1);
             $.post(  "/select-chain.php?frm_type=profile&"+$('form').serialize(),
                    function(data){
                        if (data != null){
                            		//widow.location("/projects");
                        $('form').delay(500).fadeTo("slow", 1);
                        $('#loading').delay(500).fadeTo("slow", 0);
                        
                        setTimeout(function(){
                         $('form').prepend('<div class="saved">SAVED</div>');
                      }, 1000);
                        
                        setTimeout(function(){
                         $('.saved').remove();
                      }, 3000);
                        
                        $('#bil').html(data.billing);
                        $('#del').html(data.delivery);
         
                        }else{
                        alert('Not sure what to do with this response')
                        }
                    },
                    "json"
            );
        }else{
            return false;
        }
    
   }
   
   function submitSupplier(){
            if ($("#suppliers").valid()){
            $('#loading').fadeTo("slow", 1);
            $('form').fadeTo("slow", 0.1);
             $.post(  "/select-chain.php?frm_type=suppliers&"+$('form').serialize(),
                    function(data){
                        if (data){

                        $('form').delay(500).fadeTo("slow", 1);
                        $('#loading').delay(500).fadeTo("slow", 0);
                        setTimeout(function(){
                         $('form').prepend('<div class="saved">SAVED</div>');
                      }, 1000);
                        setTimeout(function(){
                         $('.saved').remove();
                      }, 3000);

                               $('#frm_supplier_id').val(data.sup_id);
                        }else{
                        alert('Not sure what to do with this response')
                        }
                    },
                    "json"
            );
              
        }else{
            return false;
        }
          
   }
   
   
   
        function submitProduct(){
        if ($("#product").valid()){
            $('#loading').fadeTo("slow", 1);
            $('form').fadeTo("slow", 0.1);
             $.post(  "/select-chain.php?frm_type=product&"+$('form').serialize(),
                    function(data){
                        if (data){

                        $('form').delay(500).fadeTo("slow", 1);
                        $('#loading').delay(500).fadeTo("slow", 0);
                        setTimeout(function(){
                         $('form').prepend('<div class="saved">SAVED</div>');
                      }, 1000);
                        setTimeout(function(){
                         $('.saved').remove();
                      }, 3000);

                               $('#frm_product_id').val(data.prod_id);
                        }else{
                        alert('Not sure what to do with this response')
                        }
                    },
                    "json"
            );
              
        }else{
            return false;
        }
    
   }
   
   
    function deleteSupplier(id){
          var r=confirm("Are you sure you wish to delete this Supplier?")
                    if (r==true){
                             $.post(  "/select-chain.php?frm_type=deleteSupplier&"+'id='+id);
                              $('#sup_'+id).fadeOut();
                            }else{
                                      return false;
                            }
          }
                    

   
       function deleteProduct(id){
		var r=confirm("Are you sure you wish to delete this Product?")
		if (r==true){
         $.post(  "/select-chain.php?frm_type=deleteProduct&"+'id='+id,
                function(data){
                    if (data != null){
					$('#prod_'+id).fadeOut();
                    }else{
                    alert('Not sure what to do with this response')
                    }
                },
                "json"
        );
	}else{
		  return false;
	}
    
   }
   
   
 (function() {
            var bar = $('.bar');
            var percent = $('.percent');

            $('#fileForm').ajaxForm({
                beforeSend: function() {
                     $('#file-status').html('');
                },
                complete: function(xhr) {
                    $('#file-status').html(xhr.responseText)
                    $('#fileForm').fadeOut();
                }
            });
        })();
 
 
function deleteFile(fileID){
         
var r=confirm("Are you sure you wish to delete this file?")
		if (r==true){
                    $('#file'+fileID).fadeOut();
     $.post(  "/select-chain.php?frm_type=delete_file&fileid="+fileID,
                function(data){
                      $('#file'+fileID).fadeOut();
                }  
        );
	 }else{

		  return false;
	}
}
