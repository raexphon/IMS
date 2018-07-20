// Add active class to the current button (highlight it)
/*var header = document.getElementById("rootmenu");

var btns = header.getElementsByClassName("fabio");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}*/

var pathloader='images/loader/sprites3.png'; //Gloabl Variable with loader path
//******************************************************************************************************************************************
//***************************** ITEM.PHP****************************************************************************************************
//******************************************************************************************************************************************
function showhidesearch(){		
	$('#fa_stock_itemslistsrch_SearchPanel').addClass("collapse");	  
}
//Check what key you press filtering letter.
function numval(e){
	// Allow: backspace, delete, tab, escape, enter and .
	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		 // Allow: Ctrl+A, Command+A
		(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
		 // Allow: home, end, left, right, down, up
		(e.keyCode >= 35 && e.keyCode <= 40)) {
			 // let it happen, don't do anything
			 return;
	}
	// Ensure that it is a number and stop the keypress
	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		e.preventDefault();
	}
} 

/* Formatting function for row details - modify as you need */
//************************************************************
function format ( d ) {
    var div = $('<div>')
        .addClass( 'loading' )
        .text( 'Loading...' );
	
	var product_id = d['DT_RowId'];//This is the ItemId
	var product_code = d[1];//This is the ItemCode
	var btn_action = 'item_detail';	
	
    $.ajax( {
		
        url: 'item_action.php', //Calling the function
		type:"POST",
        data: {			
            product_id:product_id, 
			product_code:product_code,
			btn_action:btn_action //Set item_id and variable se
        },        
        success: function ( detail ) {
            div
                .html( detail )
                .removeClass( 'loading' );			
        },
		error: function () {
            $('<div>').html('Find an error!');
		}
    } );
 
    return div;
}

//***************************************************************************************************************************************************************
//***************************** ITEM DATA TABLE *****************************************************************************************************************
//***************************************************************************************************************************************************************
function item_set(){
	//$(document).ready(function() {
		//****************************************************
		//********** DATA TABLE ITEM DATA ********************
		//****************************************************
		var dt = $('#item_data').DataTable( {
			"processing": true,
			"language": {
				"processing": "Loading Data ... Please wait"
			},
			"serverSide": true,
			"pageLength": 50,
			"stateSave" : true,			
			"order":[],			
			"ajax":{
				url:"item_fetch.php",
				type:"POST"
			},		
			"columnDefs":[
				{
					"class":"details-control",
					"targets":[0]
				},
				{				
					"targets":[0, 6, 7, 8, 9],
					"orderable":false
				},
				{				
					"targets":['_all'],				
					"orderable":true
				}
			],
			"pageLength": 10 
		});
		// Array to track the ids of the details displayed rows
		var detailRows = [];
		// Add event listener for opening and closing details
		$('#item_data tbody').on('click', 'td.details-control', function () {		
			var tr = $(this).closest('tr');
			var row = dt.row( tr );
			var idx = $.inArray( tr.attr('id'), detailRows );
	 
			if ( row.child.isShown() ) {
				row.child.hide();
				tr.removeClass( 'details' );            
	 
				// Remove from the 'open' array
				detailRows.splice( idx, 1 );
			}
			else {            
				row.child( format( row.data() ) ).show();
				tr.addClass( 'details' );
	 
				// Add to the 'open' array
				if ( idx === -1 ) {
					detailRows.push( tr.attr('id') );
				}
			}
		} );
	 
		// On each draw, loop over the `detailRows` array and show any child rows
		dt.on( 'draw', function () {
			$.each( detailRows, function ( i, id ) {
				$('#'+id+' td.details-control').trigger( 'click' );
			} );
		} );
		
		//I have to disable it because of error when I hit the view or update or delete button
		/*$('#item_data tbody').on( 'click', 'tr', function () {
			if ( $(this).hasClass('selected') ) {
				$(this).removeClass('selected');
			}
			else {
				dt.$('tr.selected').removeClass('selected');
				$(this).addClass('selected');			
			}
		} );*/
		$('#button').click( function () {
			table.row('.selected').remove().draw( false );
		} );			
	//});
	//**********************************************************************************************************************************************
	//********** END DATA TABLE ITEM DATA ********************************************************************************************************** 
	//**********************************************************************************************************************************************
	
	//**********************************************************************************************************************************************
	/* Custom filtering function which will search data in column four between two values */		
	//**********************************************************************************************************************************************		
	function filterColumn ( i, valsearch ) {
		$('#item_data').DataTable().column( i ).search(
			valsearch,
			false,
			true
		).draw();		
	}
	
	var tadvsearch = $('#item_data').DataTable();
	
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
	
	//**********************************************************************************************************************************************
	//********** DATA TABLE ITEM PRICE LVL *********************************************************************************************************
	//**********************************************************************************************************************************************
	$('#item_pricelvl').DataTable({		
		"ordering"	: false,		
		"columnDefs":[
			{
				"class":"details-control",				
			},
			{				
				"targets":['_all'],
				"orderable":false
			},
			{ 
				"type": "num", 
				"targets": [2, 3]					
			},
			{ 
				"type": "num-fmt", 
				"targets": [4, 5]					
			},			
			"Column"
		],		
	}); 
	AfterSetTable();
	
	var ct_row = 1;//Counter to increase check index of Item Detail Table
	//These Variables are the indexes of the cell selected!
	var rowidx=0;
	var colidx=0;
	
	function AfterSetTable(){
		var it_det=$('#item_pricelvl').DataTable();
					
		it_det.MakeCellsEditable({
			"onUpdate": myCallbackFunction,
			"inputCss": "itdet",
			"columns": [0,2,3,4,5],
			"allowNulls": {"columns": [1], "errorClass":""}				
		});	
	}
		
	$('#item_pricelvl').on('click', 'td', function(e){				
		//e.preventDefault(); I MUST disable this row code because when enabled, don't trigger click on checkbox.
		this.firstElementChild.select();
		//Declaring datatable var.
		var oTablepl = $('#item_pricelvl').DataTable();
		//Putting in the variables the actual row and column index
		rowidx = $('#item_pricelvl').DataTable().cell(this).index().row;
		colidx = $('#item_pricelvl').DataTable().cell(this).index().column;		
	});
			
	function myCallbackFunction(updatedCell, updatedRow, oldValue) {		
		var oTablepl = $('#item_pricelvl').DataTable();	
		var actval=updatedCell.data();		
		switch(colidx){
			case 2: //FROM QUANTITY
				if (actval != parseInt(actval, 10)){					
					oTablepl.cell(rowidx, colidx).data('1').draw();			
				};
				break;
			case 3: //TO QUANTITY
				if (actval != parseInt(actval, 10)){					
					oTablepl.cell(rowidx, colidx).data('999999999').draw();			
				};
				break;
			case 4: //MARKUP OR UNITPRICE
				if (!$.isNumeric(actval)){					
					oTablepl.cell(rowidx, colidx).data('0.000').draw();
					break;
				}else{
					oTablepl.cell(rowidx, colidx).data(parseFloat(actval).toFixed(3)).draw();
				};
				if ($('#price_method').val()=="CM"){
					//var markup = parseFloat(updatedCell.data()).toFixed(3).replace(/(\d)(?=(\d{4})+\.)/g, '$1,');					
					var stcost = parseFloat($("#item_cost").val());	//Standard Cost				
					var val2=(parseFloat(actval)*stcost/100).toFixed(3); //Calculating the percentage					
					var val2=parseFloat(parseFloat(val2) + parseFloat(stcost)).toFixed(3);//Sum among st cost and perc.
					//console.log("cost: "+stcost);			
					//console.log("val2: "+val2);???
					oTablepl.cell(rowidx, colidx+1).data(val2).draw();
				}
				break;
		}	
		/*console.log("value is: "+oTablepl.cell(this).data()); 
		console.log("Row is: "+oTablepl.cell(this).index().row);
		console.log("Column is: "+oTablepl.cell(this).index().column);
		console.log("The new value for the cell is: " + updatedCell.data());
		console.log("The old value for that cell was: " + oldValue);
		console.log("The values for each cell in that row are: " + updatedRow.data());*/
	}
	
	//************************************************
	//INSERT NEW PRICE LEVEL INTO THE ITEM PRICE TABLE
	//************************************************
	$('#insertplvl').click(function(){
		var pLvl=$('#item_pricelvl').DataTable();
		if ($('#price_method').val()=="CM"){
			pLvl.row.add([			
			"D",
			"COST MARKUP",
			"1",
			"999999999",
			"0.00",
			"0.00",
			"<input type='checkbox' name='chkdet' class='chk_plvl' id='"+ct_row+"'/><input type='hidden' name='newfb'>"
			]).draw(false);
		}else{
			pLvl.row.add([			
			"D",
			"PRICE OVERRIDE",
			"1",
			"999999999",
			"0.00",
			"0.00",
			"<input type='checkbox' name='chkdet' class='chk_plvl' id='"+ct_row+"'/><input type='hidden' name='newfb'>"
			]).draw(false);
		}
		
		ct_row++;
	})
		
	//CHECK ALL ROWS INTO THE ITEM MAIN TABLE
	//*************************************************
	$('.checkall').on("click", function(event){			
		var checked = !$(this).data('checked');
		$('input:checkbox').prop('checked', checked);		
		$(this).data('checked', checked);		
	});
	
	//Changing TYpe of Price level will change datatable
	//**************************************************
	$('#price_method').on("change", function(){
		var oTable=$('#item_pricelvl').DataTable();//Declare the variable
				
		var column = oTable.column(4); 
		// Toggle the visibility		
		if ($(this).val() == "CM"){
			//column.visible( ! column.visible() );			
			column.visible( true );		
		}else{
			column.visible( false );		
		}		
	});
	
	//**********************************************************************************************************************************************
	//********** END DATA TABLE PRICE LVL **********************************************************************************************************
	//**********************************************************************************************************************************************

	//**********************************************************************************************************************************************
	//********** DATA TABLE ITEM PRICE CODE *********************************************************************************************************
	//**********************************************************************************************************************************************
	$('#pcode_lvl').DataTable({		
		"columnDefs":[
			{
				"class":"details-control",				
			},
			{				
				"targets":['_all'],
				"orderable":false
			},		
			"Column"
		],		
	}); 
	AfterSetTable1();
	
	var ct_row1 = 1;
	
	function AfterSetTable1(){
		var it_det1=$('#pcode_lvl').DataTable();
					
		it_det1.MakeCellsEditable({
			"onUpdate": myCallbackFunction,
			"inputCss": "itdet",
			"columns": [0,2,3,4,5],
			"allowNulls": {"columns": [1], "errorClass":""}		
		});	
	}
	
	$('#pcode_lvl tbody').on('click', 'td', function(){
		this.firstElementChild.select();		
	});
			
	//************************************************
	//INSERT NEW PRICE CODE INTO THE ITEM PRICE TABLE
	//************************************************
	$('#inspcode').click(function(){
		var pLvl=$('#pcode_lvl').DataTable();
		if ($('#price_method1').val()=="CM"){			
			pLvl.row.add([			
			"D",
			"COST MARKUP",
			"1",
			"999999999",
			"0.00",
			"0.00",
			"<input type='checkbox' name='chkdet1' class='chk_plvl' id='"+ct_row1+"'/>"
			]).draw(false);
		}else{			
			pLvl.row.add([			
			"D",
			"PRICE OVERRIDE",
			"1",
			"999999999",
			"0.00",
			"0.00",
			"<input type='checkbox' name='chkdet1' class='chk_plvl' id='"+ct_row1+"'/>"
			]).draw(false);
		}
		
		ct_row1++;
	})

	//*******************************************
	//********* REMOVE PRICE CODE TABLE ROW *****
	//*******************************************
	$("#delpcode").click(function(){
		//CODE TO DELETE ROWS 
		var oTable  = $('#pcode_lvl').DataTable(); //Read the detail table
		//$('input:checkbox').prop('checked', checked);
		$('input:checked').each(function() {	
			//the parent is the , the parent's parent is the 
			var tempRow1 = $(this).parent().parent();	
			//Remove rows from the datatable
			oTable
				.row(tempRow1)
				.remove()
				.draw();
		});
	}); 
	//*******************************************
	//****** END REMOVE PRICE CODE TABLE ROW ****
	//*******************************************
	
	//CHECK ALL ROWS INTO THE ITEM MAIN TABLE
	//*************************************************
	$('chkpcode').on("click", function(event){			
		var checked = !$(this).data('checked');
		$('input:checkbox').prop('checked', checked);		
		$(this).data('checked', checked);		
	});
	
	//Changing TYpe of Price level will change datatable
	//**************************************************
	$('#price_method1').on("change", function(){
		var oTable=$('#pcode_lvl').DataTable();//Declare the variable		
		var column = oTable.column(4); 
		// Toggle the visibility 
		if ($(this).val() == "CM"){
			//column.visible( ! column.visible() );		
			column.visible( true );		
		}else{
			column.visible( false );		
		}	
		//column.visible( ! column.visible() );
	});
	
	//**********************************************************************************************************************************************
	//********** END DATA TABLE PRICE CODE **********************************************************************************************************
	//**********************************************************************************************************************************************
	
	//****************************************************
	//********** FILL PROD LINE SELECT BOX *************** 
	//****************************************************
		
	$('#src_prodline').inputpicker({
		url:'inputfunc/fill_input.php?pck=pline',
		fields:[
			{name:'code',text:'Code'},
			{name:'desc',text:'Description'}
		],		
		fieldText : 'code',		
		fieldValue : 'code',		
		autoOpen:true, //Selected automatically when focus
		headShow: true,
		//responsive: true,
		filterOpen: true //true=filter rows when changing input content					
	});
		
	//****************************************************
	//********** END FILL PROD LINE SELECT BOX ***********
	//****************************************************
	
	//****************************************************
	//********** FILL PROD LINE SELECT BOX *************** 
	//****************************************************
		//UPDATE WINDOW
	$('#src_prodline_upd').inputpicker({		
		url:'inputfunc/fill_input.php?pck=pline',
		fields:[
			{name:'code',text:'Code'},
			{name:'desc',text:'Description'}
		],		
		fieldText : 'code',		
		fieldValue : 'code',
		autoOpen:true, //Selected automatically when focus
		headShow: true,
		filterOpen: true //true=filter rows when changing input content		
		//pagination: false,		
		//tabToSelect:true, //Press tab to select automatically
		//creatable:false, // Allow user creates new value when true
		/*limit: 5,
		pageMode: '',  // '' or 'scroll'		
		pageCurrent: 1,
		pageField: 'p',
		pageLimitField: 'per_page',*/		
		//responsive: true,		
	});
		
	//****************************************************
	//********** END FILL PROD LINE SELECT BOX ***********
	//****************************************************
	
	//****************************************************
	//********** FILL UOM SELECT BOX ********************* 
	//****************************************************
		
	$('#uom').inputpicker({		
		url:'inputfunc/fill_input.php?pck=uom',
		fields:[			
			{name:'code',text:'Code'}
		],
		fieldText : 'code',
		fieldValue : 'code',
		//pagination: false,
		headShow: true,
		autoOpen:true, //Selected automatically when focus
		filterOpen: true
		/*tabToSelect:true, //Press tab to select automatically
		creatable:false, // Allow user creates new value when true							
		responsive: true,*/		
	});
		
	//****************************************************
	//********** END FILL UOM SELECT BOX *****************
	//****************************************************	
	
	//****************************************************
	//********** FILL PRICING METHOD SELECT BOX **********
	//****************************************************
		
	$('#price_method').inputpicker({				
		data:[ 
			{code:"CM",desc:"COST MARKUP %"},
			{code:"PO",desc:"PRICE OVERRIDE"}
			],
		fields:[
			{name:'code',text:'Code'},
			{name:'desc',text:'Description'}
		],		
		autoOpen:true, //Selected automatically when focus
		headShow: true,
		fieldText : 'desc',
		fieldValue: 'code'					
	});
	//****************************************************
	//********** END FILL PRICING METHOD SELECT BOX ******
	//****************************************************	
	
	//****************************************************
	//********** FILL PRICING CODE METHOD SELECT BOX *****
	//****************************************************
		
	$('#price_method1').inputpicker({				
		data:[ 
			{value:"CM",description:"COST MARKUP %"},
			{value:"PO",description:"PRICE OVERRIDE"}
			],
		fields:[
			{name:'value',text:'Code'},
			{name:'description',text:'Description'}
		],		
		autoOpen:true, //Selected automatically when focus
		headShow: true,
		fieldText : 'description',
		fieldValue: 'value'
		//tabToSelect:true, //Press tab to select automatically
		//creatable:false, // Allow user creates new value when true				
	});
	//****************************************************
	//********** END FILL PRICE CODE METHOD SELECT BOX ***
	//****************************************************	
	
	//****************************************************
	//********** FILL PRICE CODE SELECT BOX **************
	//****************************************************
		
	$('#src_pricecode').inputpicker({		
		url:'inputfunc/fill_input.php?pck=pricecode',
		fields:[
			{name:'code',text:'Code'},
			{name:'desc',text:'Description'}
		],
		fieldText : 'code',
		fieldValue : 'code',
		//pagination: false,
		headShow: true,
		autoOpen:true //Selected automatically when focus
		/*tabToSelect:true, //Press tab to select automatically
		creatable:false, // Allow user creates new value when true					
		filterOpen: true,
		responsive: true,	*/	
	});
		
	//****************************************************
	//********** END FILL PRICE CODE SELECT BOX **********
	//****************************************************	
	
	//****************************************************
	//********** FILL PRICE CODE PLINE WINDOW ************
	//****************************************************
		
	$('#src_pricecode1').inputpicker({		
		url:'inputfunc/fill_input.php?pck=pricecode',
		fields:[
			{name:'code',text:'Code'},
			{name:'desc',text:'Description'}
		],
		fieldText : 'code',
		fieldValue : 'code',		
		headShow: true,
		autoOpen:true //Selected automatically when focus
		/*pagination: false,
		tabToSelect:true, //Press tab to select automatically
		creatable:false, // Allow user creates new value when true					
		filterOpen: true,
		responsive: true,*/		
	});
		
	//****************************************************
	//********** END FILL PRICE CODE PLINE WINDOW  *******
	//****************************************************	
	
	//OPEN DETAIL ITEM WINDOW
	//***********************
	$('#add_button').click(function(){
		$('#productModal').modal('show');
		$('#product_form')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> Add New Item Product");
		$('#add_item').val("Add");
		$('#btn_action').val("add_pricelvl");
		//Reset the datatable and clear the rows.
		$('#item_pricelvl').DataTable().clear();		
		$('#item_pricelvl').DataTable().draw();
	});
	
	//OPEN DETAIL ITEM WINDOW IN VIEW MODE
	//************************************
	$(document).on('click', '.view', function(){
		$.preloader.start({
			modal: true,		
			src : pathloader
		});
		var item_id = $(this).attr("id");		
        var btn_action = 'item_view';		
		$.ajax({
			url:"item_action.php",
            method:"POST",
            data:{
				item_id:item_id, 
				btn_action:btn_action
			},            
            success: function(data){
				$('#productModalView').modal('show');
                $('#itemview').html(data);                
            },
			error : function () {
				$('<div>').html('Found an error in item viewing!');
			}
		});
		$.preloader.stop();
	});
	
	//************************************
	//OPEN DETAIL ITEM WINDOW IN UPDATE MODE
	//************************************
	$(document).on('click', '.update', function(e){
		e.preventDefault();
		$.preloader.start({
			modal: true,		
			src : pathloader
		});
		var item_id = $(this).attr("id");		
        var btn_action = 'item_update';		
		$.ajax({
			url:"item_action.php",
            method:"POST",
			dataType:"json",
            data:{
				item_id:item_id, 
				btn_action:btn_action
			},            
            success: function(data){
				$('#productModal').modal('show');
				$('#product_form')[0].reset();
				//Reset the datatable and clear the rows.
				$('#item_pricelvl').DataTable().clear();						
				$('#item_pricelvl').DataTable().draw(); //Construction Table
				$('.modal-title').html("<i class='fa fa-plus'></i>Update Item Product");
				$('#item_code').val(data.data.item_code); //Code
				$('#item_desc').val(data.data.item_desc); //Description
				if (data.data.item_inactiveitem == "Y"){ //Setting Check or not the Item Status
					$('#item_active').prop('checked', true);
				}
				$('#qty').val(data.data.qty); //Description
				//NEED TO FIX INPUTPICKER BECAUSE NO MAKE SENSE I GOTTA PUT VALUE IN BOTH INPUT BOXES
				$('#inputpicker-1').val(data.data.src_prodline); //Product line				
				$('#src_prodline').val(data.data.src_prodline); //Product line				
				//NEED TO FIX INPUTPICKER BECAUSE NO MAKE SENSE I GOTTA PUT VALUE IN BOTH INPUT BOXES
				$('#inputpicker-5').val(data.data.src_pricecode); 
				$('#src_pricecode').val(data.data.src_pricecode); 
				if (data.data.item_tax == "TX"){ //Setting Check or not the Item TAX
					$('#item_tax').prop('checked', true);
				}
				$('#item_taxvalue').val(data.data.item_taxvalue); //Taxes Value
				//NEED TO FIX INPUTPICKER BECAUSE NO MAKE SENSE I GOTTA PUT VALUE IN BOTH INPUT BOXES
				$('#inputpicker-2').val(data.data.uom); //Unit of MEasurement
				$('#uom').val(data.data.uom); //Unit of MEasurement
				$('#item_cost').val(data.data.item_cost); //Standard Cost 
				$('#item_standardprice').val(data.data.item_standardprice); //Standard Price
				$('#item_retailprice').val(data.data.item_retailprice); //Standard Price								
				if (data.data.item_lrec){
					$('#item_lrec').val(data.data.item_lrec);//Data Receipt				
					$('#item_lrec').datepicker('setDate', new Date(data.data.item_lrec)); //Data Receipt
				}
				$('#product_id').val(data.data.item_id);//Data Receipt
				$('#product_code').val(data.data.item_code);//Data Receipt
				$('#add_item').val("Save");
				$('#btn_action').val("item_update");				
				if (data.rowcount > 0){//Count how many records I got in the table details	
					var oTable=$('#item_pricelvl').DataTable();//Declare the variable				
					var column = oTable.column(4); 
					
									
					//START FOR CYCLE TO FILL THE ROWS INTO THE TABLE
					var pLvl=$('#item_pricelvl').DataTable();
					for (i=0; i < data.rowcount; i++){
						if (i == 0){
							if (data.dtable[i].pricelvl_method == "COST MARKUP"){//Check what type of method I have
								// Toggle the visibility
								column.visible(true);
							}else{
								column.visible(false);
							}
						}
						if (data.dtable[i].pricelvl_method == "COST MARKUP"){//Check what type of method I have
							$('#inputpicker-3').val("CM");
							$('#price_method').val("CM");							
						}else{
							$('#inputpicker-3').val("PO");
							$('#price_method').val("PO");							
						}
						if (data.dtable[i].pricelvl_method == "COST MARKUP"){//Check what type of method I have
							pLvl.row.add([ //MARKUP
							data.dtable[i].pricelvl_code,
							data.dtable[i].pricelvl_method,
							data.dtable[i].pricelvl_qty_from,
							data.dtable[i].pricelvl_qty_to,
							data.dtable[i].pricelvl_markup,
							data.dtable[i].pricelvl_unitprice,
							"<input type='checkbox' name='chkdet' class='chk_plvl' id='"+data.dtable[i].pricelvl_id+"'/>"
							]).draw(false);
						}else{
							pLvl.row.add([//OVERRIDE
							data.dtable[i].pricelvl_code,
							data.dtable[i].pricelvl_method,
							data.dtable[i].pricelvl_qty_from,
							data.dtable[i].pricelvl_qty_to,
							"0.00",
							data.dtable[i].pricelvl_unitprice,
							"<input type='checkbox' name='chkdet' class='chk_plvl' id='"+data.dtable[i].pricelvl_id+"'/>"
							]).draw(false);
						}
					}
				}else{
					//Reset the datatable and clear the rows.
					$('#item_pricelvl').DataTable().clear();						
					$('#item_pricelvl').DataTable().draw(); //Construction Table
				}							
            },
			error : function () {
				$('<div>').html('Found an error in item updating!');
			}
		});
		$.preloader.stop();
	});
	
	$( "#item_lrec" ).datepicker();
	
	//*****Check Numeric value in this field*****
	$("#item_cost").keydown(function (e) {	
		numval(e);
	});
	
	$("#item_taxvalue").keydown(function (e) {	
		numval(e);
	});	
	
	//****************************************************
	//DELETE ITEM INTO THE ITEM TABLE
	//****************************************************
	$(document).on('click', '.delete', function(e){		
		e.preventDefault();
		$.preloader.start({
			modal: true,		
			src : pathloader
		});
		btn_action 	=  "chk_rel_item";
		var item_id	=	 $(this).attr("id");
		var invoice_link = 0;//0: No invoice linked to it
		var code_item = "";
		$.ajax({
			url:'item_action.php',
            method:"POST",
			dataType:"json",
			data:{
				btn_action	:btn_action,
				item_id		:item_id				
			},
			success:function(data){
				//CODE CHECKING RELATIONSHIP WITH INVOICE BEFORE DELETE THE ITEM				
				if (data.invoice_link == 0) {
					//Delete Item
					code_item = data.item_code;				
					$('#msgboxModal').modal('show');
					$('#msgbox_form')[0].reset();		
					$('.modal-title').html("<i class='fa fa-plus'>IMS - Confirm Delete</i>");
					$('.form-msglbl').html("<label>Do you want to delete "+code_item+"?</label>");					
					$('#product_code').val(code_item);
					$('#product_id').val(item_id);
					$('#btn_action').val("delete_item");					
				} 				
			},
			error : function () {
				$('<div>').html('Found an error on checking correspondence between this item and the invoices!');
			}
		});
		$.preloader.stop();			
	});
	//****************************************************
	//DELETE ITEM INTO THE ITEM TABLE
	//****************************************************
	
	//****************************************************
	//DELETING ITEM INTO THE ITEM TABLE
	//****************************************************
	$(document).on('submit', '#msgbox_form', function(event){
		event.preventDefault();
		btn_action 		=  	$('#btn_action').val();
		var item_id		=	$('#product_id').val();
		var item_code	=	$('#product_code').val();
		$.ajax({
			url:'item_action.php',
            method:"POST",			
			data:{
				btn_action	:btn_action,
				item_id		:item_id,
				item_code	:item_code
				},
			success: function(data){
				$('#msgbox_form')[0].reset();
				$('#msgboxModal').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#alert_action').fadeIn().html(data);
				$('#action').attr('disabled', false);
				$('#item_data').DataTable().ajax.reload(); 
			},
			error : function () {
				$('<div>').html('Found an error deleting item');
			}			
		})
	});
	//****************************************************
	//DELETING ITEM INTO THE ITEM TABLE
	//****************************************************
	
	//****************************************************
	//ADDING OR SAVING NEW ITEM INTO THE ITEM TABLE
	//****************************************************
	$(document).on('submit', '#product_form', function(event){
		event.preventDefault();	
		$.preloader.start({
			modal: true,		
			src : pathloader
		});
		var valdata = $(this).serialize();	//Array with field value	
		var item_inactiveitem = $('#item_active').val(); //checkbox inactive or active 
		var tax = $('#item_tax').val(); //checkbox tax 
		var taxvalue = $('#item_taxvalue').val(); //inputbox tax
		var prodline = $('#src_prodline').val(); //inputbox Prodline
		var pricecode = $('#src_pricecode').val(); //inputbox Price Code
		var qty = $('#qty').val(); //inputbox quantity
		var uom = $('#uom').val(); //inputbox tax
		var tabledets = $('#item_pricelvl').DataTable(); //Read the detail table
		var datadets = tabledets
    			.rows()
    			.data();
				
		var arr1=[];
		var i=0;
		//Put the datatable rows in the array
		for (i=0; i<datadets.length; i++){
			arr1[i] = datadets.row(i).data();	
		}
		var plvl_count = datadets.length;//Send to the function how many record we have into the price level datatable
		if ($('#add_item').val() == "Add"){
			btn_action="add_item"; //Set variable to call the add new item 						
		}else if ($('#add_item').val() == "Save"){
			var item_id = $('#product_id').val(); //Send the ID to the PHP File
			var item_code = $('#product_code').val(); //Send the ID to the PHP File
			btn_action="updsv_item"; //Set variable to call update 						
		}	
		//call ajax function and send variable to php file. 
		$.ajax({			
			url:'item_action.php',
            method:"POST",
            data:{
				btn_action:btn_action,
				item_id:item_id,
				item_code:item_code,
				valdata:valdata,
				item_inactiveitem:item_inactiveitem, 
				tax:tax,
				taxvalue:taxvalue,
				prodline:prodline,
				pricecode:pricecode,
				qty:qty,
				uom:uom,
				plvl_count:plvl_count,
				arr1:arr1
				},			
            success : function(data)
            {					
				$('#product_form')[0].reset();
                $('#productModal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#alert_action').fadeIn().html(data);
				$('#action').attr('disabled', false);
                $('#item_data').DataTable().ajax.reload();                
            },
			error : function () {
				$('<div>').html('Found an error!');
			}
		});
		$.preloader.stop();
	});
	//********************************************
	
	//*******************************************
	//********* REMOVE PRICE LVL TABLE ROW*******
	//*******************************************
	$("#removeplvl").click(function(){
		//CODE TO DELETE ROWS 
		var oTable  = $('#item_pricelvl').DataTable(); //Read the detail table
		//$('input:checkbox').prop('checked', checked);
		$('input:checked').each(function() {	
			//the parent is the , the parent's parent is the 
			var tempRow = $(this).parent().parent();	
			//Remove rows from the datatable
			oTable
				.row(tempRow)
				.remove()
				.draw();
		});
	});
	//*******************************************
	//****** END REMOVE PRICE LVL TABLE ROW******
	//*******************************************
	
	//*******************************************
	//**** Enable or disable input tax field ****
	//*******************************************
	$("#item_tax").click(function(){
		if ($("#item_tax").prop('checked')==true) {
			$("#item_taxvalue").prop('disabled' , false);
			$("#item_taxvalue").val("8.875");
			$("#item_tax").val("TX");
		}else{
			$("#item_taxvalue").prop('disabled' , true);
			$("#item_taxvalue").val("0.00");
			$("#item_tax").val("NT");
		}
	});
	
	//*******************************************
	//**** Enable or disable other checkbox ****
	//*******************************************
	//*********** Search Code *******************
	$("#searchcode").click(function(){
		if ($("#searchcode").prop('checked')==true) {
			$("#searchdesc").prop('checked' , false);
			$("#searchpline").prop('checked' , false);		
		}
	});
	
	//*********** Search Description *******************
	$("#searchdesc").click(function(){
		if ($("#searchdesc").prop('checked')==true) {
			$("#searchcode").prop('checked' , false);
			$("#searchpline").prop('checked' , false);		
		}
	});
	
	//*********** Search Description *******************
	$("#searchpline").click(function(){
		if ($("#searchpline").prop('checked')==true) {
			$("#searchcode").prop('checked' , false);
			$("#searchdesc").prop('checked' , false);		
		}
	});
	//*********** STATUS FILTER *******************
	$("#searchinactive").click(function(){
		if ($("#searchinactive").prop('checked')==true) {
			$("#searchinactive").val('Y');			
		}else{
			$("#searchinactive").val('N');
		}
	});
	//*******************************************
	//**** END Enable or disable other checkbox ****
	//*******************************************
	
	//Formatting inputbox value in currency
	//*************************************
	$("#item_cost").on("blur", function(){
		var $input = $(this),
            value = $input.val(),
            num = parseFloat(value).toFixed(3).replace(/(\d)(?=(\d{4})+\.)/g, '$1,');
		if (!isNaN(num)){
			$("#item_cost").val(num);        
		}else{
			$("#item_cost").val("0.00");
		}
	});
	
	//Check Numeric value in this field
	//*********************************
	$("#item_taxvalue").keydown(function (e) {	
		numval(e);
	});
	
	//Check Numeric value in this field
	//*********************************
	$("#qty").keydown(function (e) {	
		numval(e);
	});
	
	//PUT Active or Inactive the item
	//************************************
	$("#item_active").on("click", function(){
		if ($(this).prop('checked')==true){
			$(this).val("Y");			
		}else{
			$(this).val("N");	
		}
	});
	
	//OPEN WINDOW New Product LINE
	//************************************
	$("#addpline").on("click", function(){
		//Add nwe product line from new/edit window
		$('#productModalPLine').modal('show');
        $('#pline_form')[0].reset();
        $('#productModalPLine .modal-title').html("<i class='fa fa-plus'></i> Add Product Line");
        $('#action').val("Add");        
	});
	
	//OPEN WINDOW New Product LINE FROM UPDATE WINDOW
	//************************************	
	$("#addpline_upd").on("click", function(){
		//Add nwe product line from new/edit window
		$('#productModalPLine').modal('show');
        $('#pline_form')[0].reset();
        $('#productModalPLine .modal-title').html("<i class='fa fa-plus'></i> Add Product Line");
        $('#action').val("Add");        
	});
	
	//OPEN WINDOW New PRICE CODE
	//************************************
	$("#addpricecode").on("click", function(){
		//Add nwe product line from new/edit window
		$('#ModalPCode').modal('show');
        $('#pcode_form')[0].reset();
        $('#ModalPCode .modal-title').html("<i class='fa fa-plus'></i> Add Price Code");
        $('#action').val("Add");
		//Reset the datatable and clear the rows.
		$('#pcode_lvl').DataTable().clear();		
		$('#pcode_lvl').DataTable().draw();
	});

	//ADDING NEW PRODUCT LINE ************************
	//****************************************************
	$(document).on('submit', '#pline_form', function(event){
		event.preventDefault();				
		var valdata = $(this).serialize();	//Array with field value	
		btn_action="new_pline"; //Set variable to call the add new item 						
		//call ajax function and send variable to php file.
		$.ajax({			
			url:'item_action.php',
            method:"POST",
            data:{
				btn_action:btn_action, 
				valdata:valdata,				
				},			
            success : function(data)
            {					
				$('#pline_form')[0].reset();
                $('#productModalPLine').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#alert_action').fadeIn().html(data);
				$('#action').attr('disabled', false);
				//***********************************************************
				$('#src_prodline').inputpicker({//Reinitialize 
					url:'inputfunc/fill_input.php?pck=pline',					
					fields:[
						{name:'code',text:'Code'},
						{name:'desc',text:'Description'}
					],	
					fieldText : 'code',		
					fieldValue : 'code',
					headShow: true,
					autoOpen:true, //Selected automatically when focus
					filterOpen: true //true=filter rows when changing input content					
					/*pagination: true,
					pageMode: '',
					pageField: 'p',
					pageLimitField: 'per_page',
					limit: 10,
					pageCurrent: 1,
					//autoOpen:true, //Selected automatically when focus
					//tabToSelect:true, //Press tab to select automatically
					//creatable:false, // Allow user creates new value when true					
					
					//responsive: true,*/					
				});				
            },
			error : function () {
				$('<div>').html('Found an error!');
			}
		})
	});
	//********************************************
	
	//ADDING NEW PRICE CODE ************************
	//****************************************************
	$(document).on('submit', '#pcode_form', function(event){
		event.preventDefault();				
		var valdata = $(this).serialize();	//Array with field value	
		var tabledets = $('#pcode_lvl').DataTable(); //Read the detail table
		var datadets = tabledets
    			.rows()
    			.data();
				
		var arr_pcode=[];
		var i=0;
		//Put the datatable rows in the array
		for (i=0; i<datadets.length; i++){
			arr_pcode[i] = datadets.row(i).data();	
		}		
		btn_action="new_pcode"; //Set variable to call the add new item 						
		//call ajax function and send variable to php file.
		$.ajax({			
			url:'item_action.php',
            method:"POST",
            data:{
				btn_action:btn_action, 
				valdata:valdata,
				arr_pcode:arr_pcode
				},			
            success : function(data)
            {					
				$('#pline_form')[0].reset();
                $('#ModalPCode').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#alert_action').fadeIn().html(data);
				$('#action').attr('disabled', false);
				//***********************************************************
				$('#src_pricecode').inputpicker({		
					url:'inputfunc/fill_input.php?pck=pricecode',
					fields:[
						{name:'code',text:'Code'},
						{name:'desc',text:'Description'}
					],	
					fieldText : 'code',
					fieldValue : 'code',					
					headShow: true,
					filterOpen: true,
					autoOpen:true //Selected automatically when focus
					//pagination: false,
					/*tabToSelect:true, //Press tab to select automatically
					creatable:false, // Allow user creates new value when true										
					responsive: true,					*/
				});
				$('#src_pricecode1').inputpicker({		
					url:'inputfunc/fill_input.php?pck=pricecode',
					fields:[
						{name:'code',text:'Code'},
						{name:'desc',text:'Description'}
					],	
					fieldText : 'code',
					fieldValue : 'code',					
					headShow: true,
					autoOpen:true //Selected automatically when focus
					/*pagination: false,
					tabToSelect:true, //Press tab to select automatically
					creatable:false, // Allow user creates new value when true					
					filterOpen: true,
					responsive: true,					*/
				});
            },
			error : function () {
				$('<div>').html('Found an error!');
			}
		})
	});
	//********************************************	
}
//******************************************************************************************************************************************
//***************************** END ITEM.PHP************************************************************************************************
//******************************************************************************************************************************************