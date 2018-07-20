var pathloader='images/loader/sprites3.png'; //Gloabl Variable with loader path
//***************************************************************************************************************************************************************
//***************************** PLINE DATA TABLE *****************************************************************************************************************
//***************************************************************************************************************************************************************
function pline_set(){	
	//****************************************************
	//********** DATA TABLE PLINE DATA ********************
	//****************************************************
	var dt = $('#pline_data').DataTable( {
		"processing": true,
		"language": {
			"processing": "Loading Data ... Please wait"
		},
		"serverSide": true,
		"pageLength": 50,
		"stateSave" : true,			
		"order":[],			
		"ajax":{
			url:"prodline_fetch.php",
			type:"POST"
		},		
		"columnDefs":[			
			{				
				"targets":[0, 4,5,6],
				"orderable":false
			},
			{				
				"targets":['_all'],				
				"orderable":true
			}
		],
		"pageLength": 10 
	});
	
	$('#button').click( function () {
		table.row('.selected').remove().draw( false );
	} );
}	
//**********************************************************************************************************************************************
//********** END DATA TABLE pline DATA ********************************************************************************************************** 
//**********************************************************************************************************************************************

//**********************************************************************************************************************************************
	/* Custom filtering function which will search data in column four between two values */		
	//**********************************************************************************************************************************************		
	/*function filterColumn ( i, valsearch ) {
		$('#pline_data').DataTable().column( i ).search(
			valsearch,
			false,
			true
		).draw();		
	}
	
	var tadvsearch = $('#pline_data').DataTable();*/
	
	// Event listener to inactive item filtering to redraw on input
	$('#searchinactive').on("keyup change", function() {
		if ($('#searchinactive').prop('checked')==true){
			i=7;				
			filterColumn(i,'<span class="label label-danger">Inactive</span>');				
		}		
	});
	
	// Event listener to the two range filtering inputs to redraw on input
	$('#psearch').on("keyup click", function() {
		if (this.value !=''){
			if ($('#searchcode').prop('checked')==true){
				i=1;				
			}
			if ($('#searchdesc').prop('checked')==true){
				i=2;								
			}
			if ($('#searchpline').prop('checked')==true){
				i=3;				
			}			
			filterColumn(i,this.value);			
		}else{
			tadvsearch
				.search('')
				.columns(1).search('')
				.columns(2).search('')
				.columns(3).search('')
				.draw();
		}
	});
	//**********************************************************************************************************************************************
	/* End Custom filtering function which will search data in column four between two values */		
	//**********************************************************************************************************************************************
	
	//OPEN DETAIL PRODUCT LINE WINDOW IN VIEW MODE
	//************************************
	$(document).on('click', '.view', function(){
		$.preloader.start({
			modal: true,		
			src : pathloader
		});
		var prodline_id = $(this).attr("id");		
        var btn_action = 'pline_view';		
		$.ajax({
			url:"prodline_action.php",
            method:"POST",
            data:{
				prodline_id:prodline_id, 
				btn_action:btn_action
			},            
            success: function(data){
				$('#productModalView').modal('show');
                $('#productlineview').html(data);                
            },
			error : function () {
				$('<div>').html('Found an error in item viewing!');
			}
		});
		$.preloader.stop();
	});
	
	//************************************
	//OPEN DETAIL PRODUCT LINE WINDOW IN UPDATE MODE
	//************************************
	$(document).on('click', '.update', function(e){
		e.preventDefault();
		//Start loader
		$.preloader.start({
			modal: true,		
			src : pathloader
		});
		var prodline_id = $(this).attr("id");		
        var btn_action = 'pline_update';		
		$.ajax({
			url:"prodline_action.php",
            method:"POST",
			dataType:"json",
            data:{
				prodline_id:prodline_id, 
				btn_action:btn_action
			},            
            success: function(data){
				$('#productModalDetail').modal('show');
				$('#product_form')[0].reset();				
				$('.modal-title').html("<i class='fa fa-plus'></i>Update Product Line");
				$('#pline_code').val(data.data.prodline_cod); //Code
				$('#pline_desc').val(data.data.prodline_desc); //Description				
				$('#inputpicker-1').val(data.data.pt_pricecode); //Description
				$('#pline_pricecode').val(data.data.pt_pricecode); //Description	
				$('#product_id').val(data.data.prodline_id);
				$('#product_code').val(data.data.prodline_cod);
				$('#add_item').val("Save");
				$('#btn_action').val("pline_update");														
            },
			error : function () {				
				$('<div>').html('Found an error in item updating!');
			}			
		});
		$.preloader.stop();
	});
	
	//****************************************************
	//********** FILL PRICE CODE PLINE WINDOW ************
	//****************************************************		
	$('#pline_pricecode').inputpicker({		
		url:'inputfunc/fill_input.php?pck=pricecode',
		fields:[
			{name:'code',text:'Code'},
			{name:'desc',text:'Description'}
		],
		fieldText : 'code',
		fieldValue : 'code',		
		headShow: true,
		autoOpen: true //Selected automatically when focus		
	});
		
	//****************************************************
	//********** END FILL PRICE CODE PLINE WINDOW  *******
	//****************************************************	
	
	$(document).on('submit', '#product_form', function(event){
		event.preventDefault();		
		var valdata = $(this).serialize();	//Array with field value			
		if ($('#add_item').val() == "Add"){
			btn_action="add_pline"; //Set variable to call the add new item 						
		}else if ($('#add_item').val() == "Save"){
			var prodline_id = $('#product_id').val(); //Send the ID to the PHP File
			var prodline_code = $('#product_code').val(); //Send the ID to the PHP File
			btn_action="updsv_pline"; //Set variable to call update 						
		}	
		//call ajax function and send variable to php file. 
		$.ajax({			
			url:'prodline_action.php',
            method:"POST",
            data:{
				btn_action:btn_action,
				prodline_id:prodline_id,
				prodline_code:prodline_code,
				valdata:valdata,				
				},			
            success : function(data)
            {					
				$('#product_form')[0].reset();
                $('#productModalDetail').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#alert_action').fadeIn().html(data);
				$('#action').attr('disabled', false);
                $('#pline_data').DataTable().ajax.reload();                
            },
			error : function () {
				$('<div>').html('Found an error!');
			}
		})
	});
	//********************************************