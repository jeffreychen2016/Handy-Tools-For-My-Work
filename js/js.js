$( document ).ready(function() {

    // $('.open-pdf-iframe').click(function () {
    // 	$(".side-bar-container").css('display','unset');		 
    // });

    function remove_iframe(){
    	$('#iframe-container').css('display','none');
    }

    function remove_SQL_convertor(){
    	$('#SQL_convertor_container').css('display','none');
    }

    function remove_upload_window(){
    	$('#upload_window').css('display','none');
    }

    //-- Make sibe bar appear after win --//
    $( "iframe" ).after(function() {
  		$('.side-bar-container').css('display','unset');
	});

    //-- Toggle side bar --//
	$('.btn-open-side-bar').click(function(){
		$('.sidebar').toggleClass('side');
	});

	//-- Pull up textarea --//
	$('#SQL_convertor_btn').click(function(){
		remove_iframe();
		remove_upload_window()
		$('#SQL_convertor_container').css('display','unset');
	});
	//-- convert sql --//
	$('#convert-btn').click(function(){
		var strings_to_be_tenant_id = /tenant_id\s{0,5}=\s{0,5}\d{0,5}/ig;
		var strings_to_be_batch_number = /\(\^APExportBatchID\^\)/ig;
		var strings_to_be_found_from = /FROM /ig;
		var strings_to_be_found_join = /JOIN /ig;
		var strings_to_be_found_base_left_parenthesis = /BASE_\(/ig;
		var strings_to_be_found_dot = /\./ig;
		var strings_to_be_found_on = / ON /i;
		var strings_to_be_found_equal = /\s{0,5}=\s{0,5}/i;
		var strings_to_be_found_first_table = / ON \w{1,10}/i;
		var strings_to_be_found_second_table = / = \w{1,10}/i;
		var strings_to_be_found_systemsettings = /systemsettings/i; // this one does not need "g" because it is used for boolean in indivisual line base
		var strings_to_be_found_order_by = /order by/i; // this one does not need "g" because it is used for boolean in indivisual line base
		var strings_to_be_found_base_systemsettings = /base_\s{0,5}systemsettings/ig;
		var strings_to_be_found_colon_equal_sign = /\:\s{0,5}=/ig;
		var strings_to_be_found_greater_equal_sign = /> =/ig;
		var strings_to_be_found_less_equal_sign = /< =/ig;

		var batch_number = $('#input_batch_number').val();
		var tenant_id = $('#input_tenant_id').val();

		//replace all tenant_id and batch_number before process each single line
		var input_string_after_1st_conversion = $('#SQL_convertor_textarea').val().replace(strings_to_be_tenant_id,'tenant_id = ' + tenant_id)
		.replace(strings_to_be_batch_number,'(' + batch_number + ')');

		// break textarea into indivisual line
		var lines = input_string_after_1st_conversion.split('\n');
		for (var i = 0; i < lines.length; i++) 
		{
			// This fix is for when '=' has no space,it reports error, it needs to be here to standardize the format so that following codes can execute
			lines[i] = lines[i].replace(/\s{0,5}=\s{0,5}/,' = '); 
			// execute when there is " ON " and " = " sign at the same time in the line and the line does not include "systemsetting"  and "order by"
			if (strings_to_be_found_on.test(lines[i]) 
				&& strings_to_be_found_equal.test(lines[i])
				&& strings_to_be_found_systemsettings.test(lines[i]) == false
				&& strings_to_be_found_order_by.test(lines[i]) == false)
			{
				// Find the specific text in each line then convert the text to string then replace
				// Append second join statement to the end of each line
				var first_table = lines[i].match(strings_to_be_found_first_table).join(' ').replace(' ON ','');
				var second_table = lines[i].match(strings_to_be_found_second_table).join(' ').replace(' = ','');
				var join_and_statement = ' and ' + first_table + '.tenant_id' + ' = ' + second_table + '.tenant_id'
				lines[i] = lines[i] + join_and_statement;
			}
		}
		// join each inivisual line back to paragragh
		var SQL_after_add_tenant_join = lines.join('\n');
		// alert(SQL_after_add_tenant_join);

		var converted_string = SQL_after_add_tenant_join.replace(strings_to_be_found_from,'FROM base_')
		.replace(strings_to_be_found_join,'JOIN base_').replace(strings_to_be_found_base_left_parenthesis,'(').replace(strings_to_be_found_base_systemsettings,'systemsettings')
		.replace(strings_to_be_found_colon_equal_sign,':=').replace(strings_to_be_found_greater_equal_sign,'>=').replace(strings_to_be_found_less_equal_sign,'<=');

		$('#SQL_convertor_textarea').val(converted_string);
	});	

	// copy the content in the textarea
	$('#copy-btn').click(function(){
		$('#SQL_convertor_textarea').select();
		document.execCommand('copy');
	});

	// reverse query back to ET readable format
	$('#reverse-btn').click(function(){
		var strings_to_be_found_base_underscore = /base_/ig;
		var strings_to_be_found_batch_id = /where\s{0,5}ap.apexportbatch_id\s{0,5}in\s{0,5}\(\s{0,5}\d{0,10}\s{0,5}\)/ig;
		var strings_to_be_found_batch_id_equal = /where\s{0,5}ap.apexportbatch_id\s{0,5}=\s{0,5}\(\s{0,5}\d{0,10}\s{0,5}\)/ig;
		var strings_to_be_found_second_join_key = /and\s{0,5}\w{0,10}\.tenant_id\s{0,5}=\s{0,5}\w{0,5}\.tenant_id/ig;
		var converted_string = $('#SQL_convertor_textarea').val().replace(strings_to_be_found_base_underscore,'')
		.replace(strings_to_be_found_second_join_key,'').replace(strings_to_be_found_batch_id,'WHERE ap.apexportbatch_id IN (^APExportBatchID^)').replace(strings_to_be_found_batch_id_equal,'WHERE ap.apexportbatch_id IN (^APExportBatchID^)');
		$('#SQL_convertor_textarea').val(converted_string);
	});

	//pops up the upload window
	$('#choose_file_btn').bind('change', function() { 
		var fileName = ''; 
		fileName = $(this).val(); 
		if (fileName === '') {
			$('#file_selected').html('No file selected'); 
		} else {
			$('#file_selected').html(fileName); 
		};
	});

	$(document).on('click','.single_direcotry',function(e){
		var fileName = $(e.target).html();
		var domString = 'Upload the file to this directory: ';
		$('#directory_selected').html(domString + fileName); 
	});

	$('#upload_btn').click(function(){
		remove_iframe();
		remove_SQL_convertor();
		$('#upload_window').css('display','block');
		console.error('test');
	});
	
});

