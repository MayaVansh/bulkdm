<?php
	
include_once dirname(__FILE__).'/inc/config.php'; 
 
$q1 = app_db()->select('select * from users');

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css"> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script type="text/javascript">

$(document).ready(function($)
{ 	 
	function create_html_table (tbl_data)
	{
		//--->create data table > start
		var tbl = '';
		tbl +='<table class="table table-hover tbl_bdm">'

			//--->create table header > start
			tbl +='<thead>';
				tbl +='<tr>';
				tbl +='<th><input type="checkbox" class="select_all_items"></th>'
				tbl +='<th> Name </th>';
			//	tbl +='<th>Last Name</th>';
				tbl +='<th>Email</th>';
				tbl +='<th>Phone</th>';
				tbl +='<th>Subject</th>';
				tbl +='<th>Status</th>';
				tbl +='<th>Type</th>';
				tbl +='<th>Description		Internal<input class="checkbox" id="privateCommentAll" type="checkbox" /></th>';
				tbl +='<th>Assets</th>';
				tbl +='<th>Tags</th>';
				tbl +='<th>Options</th>';
				tbl +='</tr>';
			tbl +='</thead>';
			//--->create table header > end
			
			//--->create table body > start
			tbl +='<tbody>';

				//--->create table body rows > start
				$.each(tbl_data, function(index, val) 
				{
					//you can replace with your database row id
					var row_id = val['row_id'];

					//loop through ajax row data
					tbl +='<tr row_id="'+row_id+'">';
					
						tbl +='<td ><input type="checkbox" class="item_id" option_id="i"></td>';
						tbl +='<td ><div class="row_data" edit_type="click" col_name="fname">'+val['fname']+'</div></td>';
						tbl +='<td ><div class="row_data" edit_type="click" col_name="email">'+val['email']+'</div></td>';
						tbl +='<td ><div class="row_data" edit_type="click" col_name="phone">'+val['phone']+'</div></td>';
						tbl +='<td ><div class="row_data" edit_type="click" col_name="subject">'+val['subject']+'</div></td>';
						tbl +='<td ><div class="row_data" edit_type="click" col_name="status">'+val['status']+'</div></td>';
					//	tbl +='<td ><div class="row_data" edit_type="click" col_name="status">'+val['']+'</div></td>';
						tbl +='<td ><div class="row_data" edit_type="click" col_name="type">'+val['type']+'</div></td>';
						tbl +='<td ><div class="row_data" edit_type="click" col_name="description">'+val['description']+'</div></td>';
						tbl +='<td ><div class="row_data" edit_type="click" col_name="assets">'+val['assets']+'</div></td>';
						tbl +='<td ><div class="row_data" edit_type="click" col_name="tags">'+val['tags']+'</div></td>';

						//--->edit options > start
						tbl +='<td>';						 
							tbl +='<span class="btn_edit" > <a href="#" class="btn btn-link " row_id="'+row_id+'" > Edit</a> </span>';
							//only show this button if edit button is clicked
							tbl +='<a href="#" class="btn_save btn btn-link"  row_id="'+row_id+'"> Save </a>';
							tbl +='<a href="#" class="btn_cancel btn btn-link" row_id="'+row_id+'"> Cancel </a>';
							tbl +='<a href="#" class="btn_delete btn btn-link1 text-danger" row_id="'+row_id+'"> Delete</a>';
						tbl +='</td>';
						//--->edit options > end						
					tbl +='</tr>';
				});
				//--->create table body rows > end
			tbl +='</tbody>';
			//--->create table body > end

		tbl +='</table>';
		//--->create data table > end

		//add new table row
		tbl +='<div class="text-center">';
			tbl +='<span class="btn btn-primary btn_new_row">Add New Row</span>';
		tbl +='<div>';

		//out put table data
		$(document).find('.tbl_user_data').html(tbl);

		$(document).find('.btn_save').hide();
		$(document).find('.btn_cancel').hide(); 	
		$(document).find('.btn_delete').hide(); 
			
	}


	var ajax_url = "<?php echo APPURL;?>/ajax.php" ;
	var ajax_data = <?php echo json_encode($q1);?>;

	//create table on page load
	//create_html_table(ajax_data);

	//--->create table via ajax call > start
	$.getJSON(ajax_url,{call_type:'get'},function(data) 
	{
		create_html_table(data);
	});
	//--->create table via ajax call > end
	
//	<select id="list" onchange="getSelectValue();">
//		<option value="js">JavaScript</option>
//		<option value="php">PHP</option>
//		<option value="c#">Csharp</option>
//		<option value="java">Java</option>
//		<option value="node">Node.js</option>
//	</select>



	//--->make div editable > start
	$(document).on('click', '.row_data', function(event) 
	{
		event.preventDefault(); 

		if($(this).attr('edit_type') == 'button')
		{
			return false; 
		}

		//make div editable
		$(this).closest('div').attr('contenteditable', 'true');
		//add bg css
		$(this).addClass('bg-warning').css('padding','5px');

		$(this).focus();

		$(this).attr('original_entry', $(this).html());

	})	
	//--->make div editable > end

	//--->save single field data > start
	$(document).on('focusout', '.row_data', function(event) 
	{
		event.preventDefault();

		if($(this).attr('edit_type') == 'button')
		{
			return false; 
		}

		//get the original entry
		var original_entry = $(this).attr('original_entry');

		var row_id = $(this).closest('tr').attr('row_id'); 
		
		var row_div = $(this)				
		.removeClass('bg-warning') //add bg css
		.css('padding','')

		var col_name = row_div.attr('col_name'); 
		var col_val = row_div.html(); 
		
		var arr = {};
		//get the col name and value
		arr[col_name] = col_val; 
		//get row id value
		arr['row_id'] = row_id;

		if(original_entry != col_val)
		{ 
			//remove the attr so that new entry can take place
			$(this).removeAttr('original_entry');

			//ajax api json data			 
			var data_obj = 
			{
				row_id: row_id,
				col_name: col_name,
				col_val:col_val,
				call_type: 'single_entry',				
			};
			
			//call ajax api to update the database record
			$.post(ajax_url, data_obj, function(data) 
			{
				var d1 = JSON.parse(data);
				if(d1.status == "error")
				{
					var msg = ''
					+ '<h3>There was an error while trying to update your entry</h3>'
					+'<pre class="bg-danger">'+JSON.stringify(arr, null, 2) +'</pre>'
					+'';

					$('.post_msg').html(msg);
				}
				else if(d1.status == "success")
				{
					var msg = ''
					+ '<h3>Successfully updated your entry</h3>'
					+'<pre class="bg-success">'+JSON.stringify(arr, null, 2) +'</pre>'
					+'';

					$('.post_msg').html(msg);
				}
			});
		}
		else
		{
			$('.post_msg').html('');
		}
		
	})	
	//--->save single field data > end

	//--->button > edit > start	
	$(document).on('click', '.btn_edit', function(event) 
	{
		event.preventDefault();
		var tbl_row = $(this).closest('tr');

		var row_id = tbl_row.attr('row_id');

		tbl_row.find('.btn_save').show();
		tbl_row.find('.btn_cancel').show();
		tbl_row.find('.btn_delete').show();

		//hide edit button
		tbl_row.find('.btn_edit').hide(); 

		//make the whole row editable
		tbl_row.find('.row_data')
		.attr('contenteditable', 'true')
		.attr('edit_type', 'button')
		.addClass('bg-warning')
		.css('padding','3px')

		//--->add the original entry > start
		tbl_row.find('.row_data').each(function(index, val) 
		{  
			//this will help in case user decided to click on cancel button
			$(this).attr('original_entry', $(this).html());
		}); 		
		//--->add the original entry > end

	});
	//--->button > edit > end


	//--->button > cancel > start	
	$(document).on('click', '.btn_cancel', function(event) 
	{
		event.preventDefault();

		var tbl_row = $(this).closest('tr');

		var row_id = tbl_row.attr('row_id');

		//hide save and cacel buttons
		tbl_row.find('.btn_save').hide();
		tbl_row.find('.btn_cancel').hide();
		tbl_row.find('.btn_delete').hide();

		//show edit button
		tbl_row.find('.btn_edit').show();

		//make the whole row editable
		tbl_row.find('.row_data')
		.attr('edit_type', 'click')
		.removeClass('bg-warning')
		.css('padding','') 

		tbl_row.find('.row_data').each(function(index, val) 
		{   
			$(this).html( $(this).attr('original_entry') ); 
		});  
	});
	//--->button > cancel > end

	
	//--->save whole row entery > start	
	$(document).on('click', '.btn_save', function(event) 
	{
		event.preventDefault();
		var tbl_row = $(this).closest('tr');

		var row_id = tbl_row.attr('row_id');

		
		//hide save and cacel buttons
		tbl_row.find('.btn_save').hide();
		tbl_row.find('.btn_cancel').hide();
		tbl_row.find('.btn_delete').hide();

		//show edit button
		tbl_row.find('.btn_edit').show();


		//make the whole row editable
		tbl_row.find('.row_data')
		.attr('edit_type', 'click')
		.removeClass('bg-warning')
		.css('padding','') 

		//--->get row data > start
		var arr = {}; 
		tbl_row.find('.row_data').each(function(index, val) 
		{   
			var col_name = $(this).attr('col_name');  
			var col_val  =  $(this).html();
			arr[col_name] = col_val;
		});
		//--->get row data > end

		//get row id value
		arr['row_id'] = row_id;

		//out put to show
		$('.post_msg').html( '<pre class="bg-success">'+JSON.stringify(arr, null, 2) +'</pre>');

		//add call type for ajax call
		arr['call_type'] = 'row_entry';

		//call ajax api to update the database record
		$.post(ajax_url, arr, function(data) 
		{
			var d1 = JSON.parse(data);
			if(d1.status == "error")
			{
				var msg = ''
				+ '<h3>There was an error while trying to update your entry</h3>'
				+'<pre class="bg-danger">'+JSON.stringify(arr, null, 2) +'</pre>'
				+'';

				$('.post_msg').html(msg);
			}
			else if(d1.status == "success")
			{
				var msg = ''
				+ '<h3>Successfully updated your entry</h3>'
				+'<pre class="bg-success">'+JSON.stringify(arr, null, 2) +'</pre>'
				+'';

				$('.post_msg').html(msg);
			}			
		});
	});
	//--->save whole row entery > end



	$(document).on('click', '.btn_new_row', function(event) 
	{
		event.preventDefault();
		//create a random id
		var row_id = Math.random().toString(36).substr(2);

		//get table rows
		var tbl_row = $(document).find('.tbl_bdm').find('tr');	 
		var tbl = '';
		tbl +='<tr row_id="'+row_id+'">';
			tbl +='<td ><input type="checkbox" class="item_id" option_id="i"></td>';
			tbl +='<td ><div class="new_row_data fname bg-warning" contenteditable="true" edit_type="click" col_name="fname"></div></td>';
		//	tbl +='<td ><div class="new_row_data lname bg-warning" contenteditable="true" edit_type="click" col_name="lname"></div></td>';
			tbl +='<td ><div class="new_row_data email bg-warning" contenteditable="true" edit_type="click" col_name="email"></div></td>';
			tbl +='<td ><div class="new_row_data phone bg-warning" contenteditable="true" edit_type="click" col_name="phone"></div></td>';
			tbl +='<td ><div class="new_row_data subject bg-warning" contenteditable="true" edit_type="click" col_name="subject"></div></td>';
			tbl +='<td ><div class="new_row_data status bg-warning" contenteditable="true" edit_type="click" col_name="status"></div></td>';
			tbl +='<td ><div class="new_row_data type bg-warning" contenteditable="true" edit_type="click" col_name="type"></div></td>';
			tbl +='<td ><div class="new_row_data description bg-warning" contenteditable="true" edit_type="click" col_name="description"></div></td>';
			tbl +='<td ><div class="new_row_data assets bg-warning" contenteditable="true" edit_type="click" col_name="assets"></div></td>';
			tbl +='<td ><div class="new_row_data tags bg-warning" contenteditable="true" edit_type="click" col_name="tags"></div></td>';

			//--->edit options > start
			tbl +='<td>';			 
				tbl +='  <a href="#" class="btn btn-link btn_new" row_id="'+row_id+'" > Add Entry</a>   | ';
				tbl +='  <a href="#" class="btn btn-link btn_remove_new_entry" row_id="'+row_id+'"> Remove</a> ';
			tbl +='</td>';
			//--->edit options > end	

		tbl +='</tr>';
		tbl_row.last().after(tbl);

		$(document).find('.tbl_bdm').find('tr').last().find('.fname').focus();
	});

	
	$(document).on('click', '.btn_remove_new_entry', function(event) 
	{
		event.preventDefault();

		$(this).closest('tr').remove();
	});

	function alert_msg (msg)
	{
		return '<span class="alert_msg text-danger">'+msg+'</span>';
	}

	$(document).on('click', '.btn_new', function(event) 
	{
		event.preventDefault();
		
		var ele_this = $(this);
		var ele = ele_this.closest('tr');
		
		//remove all old alerts
		ele.find('.alert_msg').remove();

		//get row id
		var row_id = $(this).attr('row_id');

		//get field names
		var fname = ele.find('.fname');
		var lname = ele.find('.lname');
		var email = ele.find('.email');


		if(fname.html() == "")
		{
			fname.focus();
			fname.after(alert_msg('Enter First Name'));
		}
		else if(lname.html() == "")
		{
			lname.focus();
			lname.after(alert_msg('Enter Last Name'));
		}
		else if(email.html() == "")
		{
			email.focus();
			email.after(alert_msg('Enter Email'));
		}
		else
		{
			var data_obj=
			{
				call_type:'new_row_entry',
				row_id:row_id,
				fname:fname.html(),
				lname:lname.html(),
				email:email.html(),
			};	
			
			ele_this.html('<p class="bg-warning">Please wait....adding your new row</p>');

			$.post(ajax_url, data_obj, function(data) 
			{
				var d1 = JSON.parse(data);

				var tbl = '';
				tbl +='<a href="#" class="btn btn-link btn_edit" row_id="'+row_id+'" > Edit</a>';
				tbl +='<a href="#" class="btn btn-link btn_save"  row_id="'+row_id+'" style="display:none;"> Save</a>';
				tbl +='<a href="#" class="btn btn-link btn_cancel" row_id="'+row_id+'" style="display:none;"> Cancel</a>';
				tbl +='<a href="#" class="btn btn-link text-warning btn_delete" row_id="'+row_id+'" style="display:none;" > Delete</a>';

				if(d1.status == "error")
				{
					var msg = ''
					+ '<h3>There was an error while trying to add your entry</h3>'
					+'<pre class="bg-danger">'+JSON.stringify(data_obj, null, 2) +'</pre>'
					+'';

					$('.post_msg').html(msg);
				}
				else if(d1.status == "success")
				{
					ele_this.closest('td').html(tbl);
					ele.find('.new_row_data').removeClass('bg-warning');
					ele.find('.new_row_data').toggleClass('new_row_data row_data');
				}
			});
		}
	});



	$(document).on('click', '.btn_delete', function(event) 
	{
		event.preventDefault();

		var ele_this = $(this);
		var row_id = ele_this.attr('row_id');
		var data_obj=
		{
			call_type:'delete_row_entry',
			row_id:row_id,
		};	
		 		 
		ele_this.html('<p class="bg-warning">Please wait....deleting your entry</p>')
		$.post(ajax_url, data_obj, function(data) 
		{ 
			var d1 = JSON.parse(data); 
			if(d1.status == "error")
			{
				var msg = ''
				+ '<h3>There was an error while trying to add your entry</h3>'
				+'<pre class="bg-danger">'+JSON.stringify(data_obj, null, 2) +'</pre>'
				+'';

				$('.post_msg').html(msg);
			}
			else if(d1.status == "success")
			{
				ele_this.closest('tr').css('background','red').slideUp('slow');				 
			}
		});
	});
 
	
});
</script>


<!--check box scrpt > start -->
<script>
$(document).ready(function($) 
	{

		//--->deletel single row > start
	/*	function remove_curr_tbl_row(ele) 
		{	 
			ele.closest('tr').css('background-color', 'red');
			
			ele.closest('tr').fadeOut('slow', function()
			{
				$(this).remove();
			}); 	
		};

		$(document).on('click', '.btn-delete', function(event) 
		{
			event.preventDefault();

			remove_curr_tbl_row($(this));
		}); 	 */	
		//--->deletel single row > start
 		

		//--->select/unselect all > start
 		function select_unselect_checkbox (this_el, select_el) 
 		{

			if(this_el.prop("checked"))
			{
				select_el.prop('checked', true);
			}
			else
			{ 
				select_el.prop('checked', false);				 
			}
 		};

		$(document).on('change', '.select_all_items', function(event) 
		{
			event.preventDefault();

			var ele = $(document).find('.item_id'); 

			select_unselect_checkbox($(this), ele); 
		});
		//--->select/unselect all > end



		//--->deletel selected rows > start
		function remove_all_checked_val(ele) 
		{	 
			ele.each(function(index, v1)
			{   
				if($(this).prop("checked")) 
		 		{
					$(this).closest('tr').css('background-color', 'red');
					
					$(this).closest('tr').fadeOut('slow', function()
					{
						$(this).remove();
					}); 
				} 
			});
		}; 
		$(document).on('click', '.btn_delete_val', function(event) 
		{
			event.preventDefault();

			var ele = $(document).find('.item_id'); 
			var v1 = remove_all_checked_val(ele);
		});
		//--->deletel selected rows > end



		//--->get selected rows values > start

	/*	function get_all_checked_val(ele, attr_lookup) 
		{  
			var get_obj = [];
			ele.each(function(index, v1)
			{   
				if($(this).prop("checked")) 
		 		{
					get_obj.push($(this).attr(attr_lookup));
				} 
			});			
			return get_obj;
		};


		$(document).on('click', '.btn_get_val', function(event) 
		{
			event.preventDefault();

			var ele = $(document).find('.item_id'); 

			var v1 = get_all_checked_val(ele, 'option_id');

			var v2 = ''
			+'<pre class="bg-secondary">' 
			+JSON.stringify(v1, null, 5)
			+'</pre>';

			$(document).find('.post_msg').html(v2);

		});		*/
		//--->get selected rows values > end
		

 
	}); 
	</script>

<!--check box scrpt > end -->





<!-- sorting data -->
<script>

/*	th = document.getElementsByTagName('th');

for(let c=0; c < th.length; c++){

    th[c].addEventListener('click',item(c))
}


function item(c){

    return function(){
      console.log(c)
      sortTable(c)
    }
}


function sortTable(c) {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("tbl_user_data");
  switching = true; */

  /*Make a loop that will continue until
  no switching has been done:*/

/*  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows; */

    /*Loop through all table rows (except the
    first, which contains table headers):*/

/*    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false; */

      /*Get the two elements you want to compare,
      one from current row and one from the next:*/

/*      x = rows[i].getElementsByTagName("TD")[c];
      y = rows[i + 1].getElementsByTagName("TD")[c];
      //check if the two rows should switch place:
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) { */

      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/

   /*   rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
} */

</script>
<!-- sorting scriprt > end -->


<!-- script for icons > start-->
<script>
	//shifting coloumns
			
</script>
<!-- script for icons > end -->





<!--<div style="padding:10px;"></div>
 
<div class="container">
	<h1 class="text-center">Easily Add, Edit, and Delete HTML Table Rows Or Cells With jQuery</h1>

	<div style="padding:20px;"></div>

	<div class="panel panel-default">
	  <div class="panel-heading text-center"><h3> Editable HTML Table </h3> </div>

	  <div class="panel-body">
		
		<div class="tbl_user_data"></div>

	  </div>

	</div>

	 

	<div class="panel panel-default">
	  <div class="panel-heading"><b>HTML Table Edits/Upates</b> </div>

	  <div class="panel-body">
		
		<p>All the changes will be displayed below</p>
		<div class="post_msg"> </div>

	  </div>
	</div>
</div> -->
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<head>
	<title>bdm</title>
</head>

<div style="padding:10px;"></div>
 
<!--<div class="container-fluid"> -->
	<h1 class="text-center">Bulk Data Manager</h1> <div class="text-center"><div class="btn btn-primary btn_delete_val">Delete</div> </div>
<!--	<a href="#" class="btn_delete btn btn-link1 text-danger"> Delete</a> -->

	<div style="padding:20px;"></div>

	<div class="panel-body">
<div class="tbl_user_data"></div>
<!--	</div> -->


</html>